<?php
namespace App\Http\Controllers\Admin\Auth;

use Auth;
use Password;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /**
     * This will do all the heavy lifting
     * for resetting the password.
     */
    use ResetsPasswords;

     /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Only guests for "admin" guard are allowed except
     * for logout.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest:admin');
    }

    /**
     * Show the reset password form.
     * 
     * @param  \Illuminate\Http\Request $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token = null){
        return view('admin.auth.passwords.reset',[
            'title' => 'Reset Admin Password',
            'passwordUpdateRoute' => 'admin.password.update',
            'token' => $token,
        ]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    protected function broker(){
        return Password::broker('admins');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard(){
        return Auth::guard('admin');
    }

    public function reset(Request $request){
 
       $request->validate([
            'mobile' => 'required|numeric',
            'otp' => 'required',
            'password' => 'required|confirmed',
        ]);

        $user = Admin::where('mobile', $request->mobile)->where('deleted_at', null)->first();
        if (is_null($user)) { 

            Session::flash('message', "We can't find a user with that mobile address.");
                return redirect()->back();
        }
        if (!is_null($user)) {
            if ($user->token == $request->otp) {
                $user->password = Hash::make($request->password);
                $user->token = '';
                $user->save();
                
                session()->flash('status', 'Password has been reset successfully. You can now log in with your new password.');
                return redirect()->route('frontend.login');

            } else {  
                Session::flash('message', "OTP did not match!!");
                return redirect()->back();
            }
        }

    }
}
