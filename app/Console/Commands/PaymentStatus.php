<?php

namespace App\Console\Commands;

use App\Models\GeneralSetting;
use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use App\Models\OrderProduct;
use App\Models\BadgeMaster;
use App\Models\RewardMaster;
use App\Models\UserBadgeLog;
use App\Models\UserRewardLog;
use App\Models\UserReferral;
use App\Models\UserTempWallet;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\NewOrderMail;
use Barryvdh\DomPDF\Facade;
use PDF;

class PaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purchase:payment_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purchase Payment Status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(); 
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $GeneralSetting     = new GeneralSetting();
        $razor_key_data     = $GeneralSetting->get_general_settings('razorpay_keyid')->first();
        $secret_key_data    = $GeneralSetting->get_general_settings('razorpay_secretkey')->first();
        
        $razor_key  = $razor_key_data->filed_value??Null;
        $secret_key = $secret_key_data->filed_value??Null;

        $setting = GeneralSetting::whereIn('setting_type', [1, 2])->pluck('filed_value', 'setting_name')->toArray();

        Config::set("mail.mailers.smtp", [
            'transport'     => 'smtp',
            'host'          => $setting['smtp_host'],
            'port'          => $setting['smtp_port'],
            'encryption'    => $setting['smtp_encryption'],
            'username'      => $setting['smtp_user'],
            'password'      => $setting['smtp_pass'],
            'timeout'       =>  null,
            'auth_mode'     =>  null,
        ]);
        Config::set("mail.from", [
            'address'       =>  $setting['email_from'],
            'name'          =>  config('app.name'),
        ]);
         
        // Update package payment status if not clear
        $OrderList = Order::where('payment_status', 0)->whereBetween('date', [Carbon::now()->subDay()->toDateString(), Carbon::now()->toDateString()])->get();

        if (count($OrderList) > 0) {
            foreach ($OrderList as $key => $value) {
                $api = new Api($razor_key, $secret_key);
                 
                $payment = $api->order->fetch($value->transaction_payment_id)->payments(); 
                $iscaptured = '';
                if(!empty($payment)){
                    foreach ($payment->items as $pkey => $pvalue) {
                        if($pvalue['status'] =='captured'){
                            $iscaptured = 'captured';
                            $finalpaymentId = $pvalue['id'];
                            break;
                        }
                    }
                }

                if (!empty($payment) && $payment->count > 0 && $iscaptured=='captured') {
                    Order::where(['id' => $value->id,])->update([
                        'razorpay_payment_Id'    => $finalpaymentId,
                        'payment_status'    => 1,
                        'payment_json'   => json_encode($payment->toArray())
                    ]);

                    //// Update products cout and Badge & reward
                    $orderno = $value->order_no;
                    $order_id = $value->id;
                    $customer_id = $value->user_id;
                    $customer_name = $value->customer_name;
                    $customer_email = $value->customer_email;
                    $proQuantity = 0;                        
                    $OrderProduct = OrderProduct::where('order_id', $order_id)->get();

                    // Update Reward wallet
                    $referralData = UserReferral::where('refer_id', '=',$value->user_id)->first();
                    if(!empty($referralData)){
                        // if have referralData then allow referral amount
                        foreach ($OrderProduct as $opkey => $opvalue) {  
                            UserTempWallet::create([
                                'user_id'           => $customer_id,
                                'is_refer'          => 0, 
                                'date'              => date('Y-m-d H:i:s'),
                                'particulars'       => 'Congratulations, You are getting referral reward in your wallet on purchase product on Order No: '.$orderno.'',
                                'payment_type'      => 1,
                                'order_id'          => $order_id,
                                'order_product_id'  => $opvalue->id,
                                'amount'            => $opvalue['refer_price'],   // to rate
                                'created_at'        => date('Y-m-d H:i:s'),
                                'updated_at'        => date('Y-m-d H:i:s'),
                            ]); 
                        
                            UserTempWallet::create([
                                'user_id'           => $referralData->referral_id, 
                                'is_refer'          => $customer_id, 
                                'date'              => date('Y-m-d H:i:s'),
                                'particulars'       => 'Congratulations, You are getting referral reward in your wallet on purchasing by '.@$customer_name.' on Order No: '.$orderno.'',
                                'payment_type'      => 1,
                                'order_id'          => $order_id,
                                'order_product_id'  => $opvalue->id,
                                'amount'            => $opvalue['referral_price'],   // from rate
                                'created_at'        => date('Y-m-d H:i:s'),
                                'updated_at'        => date('Y-m-d H:i:s'),
                            ]);
                        }
                    }                     
                }

                try {
                     
                    $invoiceData['data'] = Order::select('orders.*', 'order_status.name as order_status_name', 'order_status.color as order_status_bg')->where('orders.id', $order_id)
                    ->where('orders.user_id', $value->user_id)
                    ->leftJoin('order_status', 'order_status.id', '=', 'orders.order_status_id')
                    ->first();
                    
                    DB::statement("SET SQL_MODE = ''");
                    $order_products = OrderProduct::select("order_products.*", 'product_images.attachment as product_image',DB::raw('CASE WHEN returns.return_type=1 and returns.id IS NOT NULL THEN 1 ELSE 0 END as is_product_return'),DB::raw('CASE WHEN returns.return_type=2 and returns.id IS NOT NULL THEN 1 ELSE 0 END as is_product_replace'))
                        ->where('order_products.order_id', $order_id)
                        ->leftJoin('product_images', 'product_images.product_id', '=', 'order_products.product_id')
                        ->leftJoin('returns', function ($join) use ($order_id) {
                            $join->on('returns.product_id', '=', 'order_products.product_id')
                                ->where('returns.order_id', '=', $order_id);
                        })
                        ->groupBy('order_products.id')
                        ->get()->toArray();

                    $invoiceData['order_products'] = $order_products;
                    $pdf = PDF::loadView('frontend.cart.invoice', $invoiceData);
                    
                    $attachment['subject'] = 'Your order no. '.$orderno.' placed on Upayliving is confirmed!!'; 
                    $attachment['mailmessage'] = 'Dear '.$customer_name.', 

                    Your order no. '.$orderno.' placed on Upayliving is confirmed.
                    You will receive shipping confirmation soon. 

                    Thank you for your purchase!! 
                     
                    Thanks, 
                    Upayliving'; 

                    $attachment['attachment'] = $pdf->output();
                    Mail::to($customer_email)->send(new NewOrderMail($attachment));

                } catch (\Exception $e) {
                    
                }


            }
        }
        
        return Command::SUCCESS;
    }
}
