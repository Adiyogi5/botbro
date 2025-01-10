<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Returns;
use App\Models\OrderProduct;
use App\Models\User;
use App\Models\UserTempWallet;
use App\Models\UserWallet;
use App\Models\GeneralSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateWalletPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:walletpayment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage wallet amount after delivery days, once the return time completed.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    { 
        // Update package payment status if not clear
        $purchased_packages = Order::where('payment_status', 1)->where('order_status_id','5')->whereRaw('order_return_expiredate = "'.Carbon::now()->subDay(1)->toDateString().'"')->get();

        if (count($purchased_packages) > 0) {
            foreach ($purchased_packages as $key => $value) {
                try {                    
                    $walletAmt = []; 
                    $TmpwalletAmt = UserTempWallet::select('user_temp_wallets.*', 'order_products.product_id' ,'order_products.product_name' , 'order_products.quantity', 'order_products.is_return_apply')->leftJoin('order_products', 'order_products.id', '=', 'user_temp_wallets.order_product_id')->where('user_temp_wallets.order_id',$value->id)->where('is_return_apply','0')->get(); 

                    foreach ($TmpwalletAmt as $twkey => $twvalue) { 
                        $user_balance = UserWallet::where('user_id', $twvalue->user_id)->orderByDesc('id')->first()->updated_balance ?? 0.00;
                        
                        $amount = $twvalue->amount*$twvalue->quantity;
                        $walletAmt = [
                                    'voucher_no'        => RandcardStr(8),
                                    'user_id'           => $twvalue->user_id, 
                                    'date'              => Carbon::now()->toDateTimeString(),
                                    'particulars'       => $twvalue->particulars,
                                    'payment_type'      => $twvalue->payment_type,
                                    'order_id'          => $twvalue->order_id,
                                    'amount'            => $amount,     
                                    'current_balance'   => $user_balance,
                                    'updated_balance'   => ($user_balance + intval($amount)),
                                    'created_at'        => date('Y-m-d H:i:s'),
                                    'updated_at'        => date('Y-m-d H:i:s'),
                                ];
                        UserWallet::insert($walletAmt);
                        /// update user balance in user table
                        User::where('id', $twvalue->user_id)->update(['user_balance' => $user_balance + intval($amount)]);                          
                        /// delete temp record     
                        UserTempWallet::where('id', $twvalue->id)->delete();                                

                        /// ***********update amount on agent reward if user have agent********
                        $userslist = User::where('status', 1)->where('is_agent_allow',1)->where('id',$twvalue->user_id)->first();

                        if(!empty($userslist)){ 
                            $setting = GeneralSetting::where('setting_type', 1)->pluck('filed_value', 'setting_name')->toArray();
                            $user_balance = UserWallet::where('user_id', $userslist->id)->orderByDesc('id')->first()->updated_balance ?? 0.00;                        

                            $particulars  =  str_replace('referral','agent', $twvalue->particulars);
                            $agamount = $setting['agent_reward']; 
                            $walletAmt = [
                                        'voucher_no'        => RandcardStr(8),
                                        'user_id'           => $userslist->id, 
                                        'date'              => Carbon::now()->toDateTimeString(),
                                        'particulars'       => $particulars,
                                        'payment_type'      => 1,
                                        'order_id'          => $twvalue->order_id,
                                        'amount'            => $agamount,     
                                        'current_balance'   => $user_balance,
                                        'updated_balance'   => ($user_balance + intval($agamount)),
                                        'created_at'        => date('Y-m-d H:i:s'),
                                        'updated_at'        => date('Y-m-d H:i:s'),
                                    ];
                            UserWallet::insert($walletAmt);
                            /// update user balance in user table
                            User::where('id', $userslist->id)->update(['user_balance' => $user_balance + intval($agamount)]); 
                        }                              
                    }                     
                } catch (\Throwable $th) {
                    
                }
            }

        }
        return Command::SUCCESS;
    }
}
