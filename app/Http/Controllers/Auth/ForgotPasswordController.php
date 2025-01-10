<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Password;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Mail;


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
        $this->middleware(['guest:web']);
    }

    /**
     * Show the reset mobile form.
     * 
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm(){
        return view('auth.passwords.mobile',[
            'title' => 'Customer Password Reset',
            'passwordMobileRoute' => 'password.mobile'
        ]);
    }

    /**
     * password broker for admin guard.
     * 
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker(){
        return Password::broker('users');
    }

    /**
     * Get the guard to be used during authentication
     * after password reset.
     * 
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    public function guard(){
        return Auth::guard('web');
    }


    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['mobile' => 'required']);
        $user = User::where('mobile', request()->input('mobile'))->first();
        if (is_null($user)) {
            return back()
                    ->withInput($request->only('mobile'))
                    ->withErrors(['mobile' => trans("We can't find a user with that mobile number.")]);
        }
        if(!is_null($user)) {
            $token = Password::getRepository()->create($user);
            $data = array('token' => $token);

            $mobile = $user->mobile; 
            $actionUrl = route('password.reset', $token).'?mobile='.$mobile.'';  
            $offer = [
                'user' => $user->name,
                'title' => config('app.name') . ' Password Reset Link',
                'subject' => config('app.name') . ' Password Reset Link',
                'actionText' => 'Reset Password',
                'color'=>'#2d3748',
                'actionUrl' => $actionUrl,
                'introLines' => 'You are receiving this email because we received a password reset request for your account.',
                'outroLines' => 'This password reset link will expire in 60 minutes.<br><br>If you did not request a password reset, no further action is required.', 
            ];
      
            Mail::to($email)->send(new ResetPasswordMail($offer)); 
            if( count(Mail::failures()) > 0 ) { 
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans("Email can't be send, Please  retrying after some time.")]);
            } else { 
                return back()->with('status', trans("We have emailed your password reset link!"));
            } 
        }
    }
 

}
