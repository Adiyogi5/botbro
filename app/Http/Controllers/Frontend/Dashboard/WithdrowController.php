<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\UserWithdrawRequest;
use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use DB;

class WithdrowController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Withdrow Request';

        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        $my_balance = User::Where('id', $user->id)->whereNull('deleted_at')
            ->first();

        $requestAmount = UserWithdrawRequest::selectRaw('SUM(amount) as total_amount')
        ->where('user_id', $user->id)
        ->where('status', 0)
        ->first();

        $rejectAmount = UserWithdrawRequest::selectRaw('SUM(amount) as total_amount')
        ->where('user_id', $user->id)
        ->where('status', 2)
        ->first();

        $my_withdrow_request = UserWithdrawRequest::select('user_withdraw_requests.*', 'user_withdraw_requests.status as request_status', 'users.name', 'users.user_balance', 'users.status')
            ->join('users', 'user_withdraw_requests.user_id', '=', 'users.id')
            ->Where('user_id', $user->id)
            ->Where('users.status', '1')
            ->get();

        return view('frontend.dashboard.withdrow_request', compact('title', 'my_balance', 'my_withdrow_request','rejectAmount','requestAmount'));
    }


    public function withdrow(Request $request)
    {
        $user = auth('web')->user();
        $balanceData = User::where('id', $user->id)->whereNull('deleted_at')->first();
        $transferFee = TRANSFER_FEE;
        
        $requestData = UserWithdrawRequest::where('status', '0')->whereNull('deleted_at')
            ->where('user_id', $user->id)
            ->get();

        DB::beginTransaction();    
        try {
            $validate = $request->validate([
                'amount' => 'required|numeric',
            ]);
            if(empty($user->bank_account_number)){            
                $errorMessage = "Your bank detail is not update, Please update your bank detail then try to withdraw Request!!";
                return redirect()->back()->with('error', $errorMessage);
            }else if ($request->amount <= ($balanceData->user_balance - $requestData->sum('amount'))) {
                $wdata = new UserWithdrawRequest();
                $reference_id = RandcardStr(15);
                $wdata->user_id = $user->id;
                $wdata->reference_id = $reference_id;
                $wdata->amount = $request->amount;
                $wdata->request_date = now()->format('Y-m-d H:i:s');
                $wdata->status = 0;
                $wdata->save();

                /// Auto Payout to razarpay api    
                /*$charges =  $transferFee; ///($request->amount*$transferFee)/100; 
                $transferAmout = $request->amount-$charges;
                $payment_setting = GeneralSetting::where('setting_type',6)->get()->toArray();
                $payment_setting = array_combine(array_column($payment_setting, 'setting_name'), array_column($payment_setting, 'filed_value'));
                $payment_key = $payment_setting['razorpay_keyid'];
                $payment_secret = $payment_setting['razorpay_secretkey'];
                // Data for the request
                $data = array(
                      "account_number"=>$user->bank_account_number,
                      "fund_account_id"=>$user->rzfund_account_id,
                      "amount"=> $transferAmout*100,
                      "currency"=> "INR",
                      "mode"=> "IMPS",
                      "purpose"=> "payout",
                      "queue_if_low_balance"=> true,
                      "reference_id"=> $reference_id,
                      "narration"=> "Upayliving Withdraw Request",
                ); 
                $bankDetail = array(
                      "bank_account_name"=>$user->bank_account_name,
                      "bank_account_number"=>$user->bank_account_number,
                      "bank_name"=>$user->bank_name,
                      "ifsc"=>$user->ifsc,
                      "fund_account_id"=>$user->rzfund_account_id,
                      "amount"=> $transferAmout*100,
                      "currency"=> "INR",
                      "mode"=> "IMPS",
                      "purpose"=> "payout",
                      "narration"=> "Amt:".$request->amount.",Charges:".$charges."",
                );

                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://api.razorpay.com/v1/payouts',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>json_encode($data),
                  CURLOPT_USERPWD => $payment_key . ':' . $payment_secret,
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                  ),
                ));
                $rppayout = curl_exec($curl);
                curl_close($curl); 
                $rppayout = json_decode($rppayout);
                if(!empty($rppayout->error->description)){
                   $error =  $rppayout->error->description;
                   DB::rollback();
                   return back()->with('error', $error); 
                }
                $rzpayoutId = $rppayout->id;
                UserWithdrawRequest::where('id', $wdata->id)->update(['rzpayout_id' => $rzpayoutId, 'status' => 1,'payment_method'=>'Bank Transfer','payment_detail'=>json_encode($bankDetail)]); 

                //// Update wallet records
                $wallet_data = User::select('user_balance')->where('id',$wdata->user_id)->get()->first();        
                $user_wallet = new UserWallet;
                $user_wallet->amount = $wdata->amount;
                $user_wallet->date = date('Y-m-d h:i');
                $user_wallet->user_id = $wdata->user_id;
                $user_wallet->particulars = 'Withdraw Request Approved';
                $user_wallet->payment_type = 2;
                $user_wallet->current_balance = $wallet_data->user_balance;
                $user_wallet->updated_balance = ($wallet_data->user_balance - $wdata->amount);
                $user_wallet->created_at = date('Y-m-d H:i:s');
                $user_wallet->updated_at = date('Y-m-d H:i:s');
                $user_wallet->save();
                User::where('id',$wdata->user_id)->update(['user_balance' => ($wallet_data->user_balance - $wdata->amount)]);*/

                /// Send Notification to Admin
                $adnoti = new AdminNotification();                
                $adnoti->title = "Withdraw Request";
                $adnoti->message = "Get new withdraw request from ".$user->name."!!";
                $adnoti->notification_type = 1;
                $adnoti->is_read = 0;
                $adnoti->created_at = now()->format('Y-m-d H:i:s');
                $adnoti->updated_at = now()->format('Y-m-d H:i:s');
                $adnoti->save();

                DB::commit();
                $request->session()->flash('success', 'Your Withdraw Request Submitted Successfully');
                return redirect()->back();
            } else {
                $availableBalance = $balanceData->user_balance - $requestData->sum('amount');
                $errorMessage = "Your requested amount is not more than your available balance. Your Available Balance is: {$availableBalance}";
                return redirect()->back()->with('error', $errorMessage);
            }
         
        } catch (\Exception $e) {
            $errorMessage = 'Failed to Submit Withdraw Request: ' . $e->getMessage();
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha' => captcha_img('flat')]);
    }
}
