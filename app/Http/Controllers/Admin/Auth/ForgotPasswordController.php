<?php

namespace App\Http\Controllers\Admin\Auth;

use Auth;
use Password;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\Admin;
use Mail;
use Session;


class ForgotPasswordController extends Controller
{
    
    /**
     * Only guests for "admin" guard are allowed except
     * for logout.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['guest:admin']);
    }

    /**
     * Show the reset email form.
     * 
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm(){
        return view('admin.auth.passwords.mobile',[
            'title' => 'Admin Password Reset',
            'passwordEmailRoute' => 'admin.password.mobile'
        ]);
    }

    /**
     * password broker for admin guard.
     * 
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker(){
        return Password::broker('admins');
    }

    /**
     * Get the guard to be used during authentication
     * after password reset.
     * 
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    public function guard(){
        return Auth::guard('admin');
    }


    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['mobile' => 'required']);
        $user = Admin::where('mobile', request()->input('mobile'))->first();
        if (is_null($user)) {
            return back()
                    ->withInput($request->only('mobile'))
                    ->withErrors(['mobile' => trans("We can't find a user with that mobile number.")]);
        }
        if(!is_null($user)) {
            $token = random_int(10000, 99999);
            $data = array('token' => $token);
            $user->token = $token;
            $user->save();

            $SendSMS = sendSms(['+91' . $request->mobile], "Dear Customer, the one time password ".$token." to reset your password at Upaylving . This OTP will expire in 5 minutes. Regards, Upaylving.", $this->general_settings); 
    

            if ($SendSMS) {
                return redirect()->route('admin.password.reset', $request->mobile)->with('success', "Enter OTP recived on your mobile!!");              
            } else {

                Session::flash('status', "SMS can't be send, Please  retrying after some time.");
                return back()
                    ->withInput($request->only('mobile'))
                    ->with(['status' => "SMS can't be send, Please  retrying after some time."]);
            } 
             
        }
    }
 

}
