<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BadgeMaster;
use App\Models\Cms;
use App\Models\User;
use App\Models\UserBadgeLog;
use App\Models\UserOtp;
use App\Models\GeneralSetting;
use App\Models\UserReferral;
use App\Rules\CheckRefer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use App\Rules\ReCaptcha;
use Illuminate\Support\Facades\Auth;

class JoinUsController extends Controller
{
    
    /**
     * Only guests for "user" guard are allowed except
     * for logout.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['guest:web']);
    }

    public function index(Request $request)
    {
        $title = 'Join Us';

        $terms_cms = Cms::where('cms.name', 'Terms Of Use')
            ->where('status', '1')
            ->first();

        return view('frontend.joinus', compact('title', 'terms_cms'));
    }

    public function register_user(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|max:30',
            'email' => 'nullable|email|unique:users,email',
            'mobile' => 'required|numeric|min:10|unique:users,mobile',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'reffer_code' => ['nullable', 'max:100', new CheckRefer('users')],
            'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);
        
        DB::beginTransaction();
        $reference_id = RandcardStr(15);
        try {
            $userData = [
                'reference_id' => $reference_id,
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                //'alternate_mobile' => $request->alternate_mobile,
                'password' => Hash::make($request->password),
                'reffer_code' => RandcardStr(8),
                'status' => 1,
                'badge_status' => 1,
            ];
            $user = User::create($userData);

            /*/// Create a contacts on razorpay
            $payment_setting = GeneralSetting::where('setting_type',6)->get()->toArray();
            $payment_setting = array_combine(array_column($payment_setting, 'setting_name'), array_column($payment_setting, 'filed_value'));
            $payment_key = $payment_setting['razorpay_keyid'];
            $payment_secret = $payment_setting['razorpay_secretkey'];
            // Data for the request
            $data = array(
                "name" => $request->name,
                "email" => $request->email,
                "contact" => $request->mobile,
                "type" => "customer",
                "reference_id" => $reference_id
            ); 
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.razorpay.com/v1/contacts',
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
            $rpaycustmer = curl_exec($curl);
            curl_close($curl); 
            $rpaycustmer = json_decode($rpaycustmer);
            if(!empty($rpaycustmer->error)){
               $error =  $rpaycustmer->error->description;
               DB::rollback();
               return back()->with('error', $error); 
            }
            $rzcontactId = $rpaycustmer->id;
            User::where('id', $user->id)->update(['rzcontact_id' => $rzcontactId]); */
             
            // Update Badge Master details
            $badgeMaster = BadgeMaster::find(1);
            if (!empty($badgeMaster)) {                
                UserBadgeLog::updateOrCreate(
                    ['user_id' => $user->id, 'badge_id' => 0],
                    [
                        'user_id' => $user->id,
                        'badge_id' => $badgeMaster->id,
                        'date' => now()->toDateString(),
                        'purchase_count' => 0,
                        'particulars' => $badgeMaster->name . ' allotted to ' . $user->name . ' on registration.',
                    ]
                );
            }

            if (!empty($request->reffer_code)) {
                $referUser = User::where(['reffer_code' => $request->reffer_code])->first();
                if (!empty($referUser)) {
                    UserReferral::updateOrCreate(
                        ['refer_id' => $user->id],
                        ['refer_id' => $user->id, 'referral_id' => $referUser->id]
                    );
                }
            }
            DB::commit();
            
            $SendSMS = sendSms(['+91' . $request->mobile], "Dear User your account at Upayliving has been successfully created! You can login from your registered mobile no. Regards, Upayliving ( https://upayliving.com/login)", $this->general_settings);

            $request->session()->flash('success', 'Thank you for register with upayliving, Login with the register details');
            // $request->session()->flash('success', 'Thank you for register with upayliving, Login with the register details');
            // return redirect()->route('frontend.login');
            Auth::guard('web')->loginUsingId($user->id, true);
            return to_route('frontend.dashboard')->withSuccess("Successfully Registered!!");
        } catch (\Exception $e) {
            DB::rollback();
            $errorMessage = 'Failed to Register: ' . $e->getMessage();
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    public function checkReferralCode(Request $request)
    {
        $referralCode = $request->input('refers');
        $additionalFieldValue = User::where('reffer_code', $referralCode)->value('name');
        
        if ($additionalFieldValue !== null) {
            $res = json_encode(array('status'=>true,'msg'=>'Referral code is valid and referred by '.$additionalFieldValue));
            // Do something with $additionalFieldValue
        } else {
            $res = json_encode(array('status'=>false,'msg'=>'Invalid referral code. Please try again'));
        }
        
        return $res;
    }


    public function reloadCaptcha()
    {
        return response()->json(['captcha' => captcha_img('flat')]);
    }


    public function sendOTP(Request $request)
    {
        if ($request->mobile == '') {
            $response = [
                'status' => false,
                'message' => 'Please provide a valid phone number.',
                'otp' => "",
            ];
            return response()->json($response, 200); // Use 400 for Bad Request
        }

        try {
            $user = User::where('mobile', $request->mobile)->first();

            if ($user) {
                $response = [
                    'status' => false,
                    'message' => 'User Already Exist. Please check your credentials.',
                    'otp' => "",
                ];
                return response()->json($response, 200); // Use 404 for Not Found
            }

            // Delete previous OTPs
            UserOtp::where('mobile_no', $request->mobile)->delete();

            // Generate and store new OTP
            $otp = rand(100000, 999999);
            UserOtp::create([
                'mobile_no' => $request->mobile,
                'otp' => $otp,
                'created_at' => Carbon::now()->addMinutes(10),
                'updated_at' => Carbon::now()->addMinutes(10),
            ]);
            
            $SendSMS = sendSms(['+91' . $request->mobile], "Dear Customer, Use this one time password ".$otp." to log in Upayliving account. This OTP will be valid for the next 10 mins. Regards, Upayliving.",
            $this->general_settings);

            if ($SendSMS) {
                $response = [
                    'status' => true,
                    'message' => 'We have send OTP on your mobile.',
                    'otp' => '',
                ];
                return response()->json($response, 200); // Use 200 for OK
            }else{
                $response = [
                    'status' => false,
                    'message' => "SMS can not be send, Please  retrying after some time.",
                    'otp' => '',
                ];
                return response()->json($response, 500); // Use 500 for Internal Server Error
            }            
        } catch (\Exception $e) {
            // Handle exceptions
            $response = [
                'status' => false,
                'message' => 'An unexpected server error occurred. Please try again later.',
                'otp' => "",
            ];
            return response()->json($response, 500); // Use 500 for Internal Server Error
        }
    }

}
