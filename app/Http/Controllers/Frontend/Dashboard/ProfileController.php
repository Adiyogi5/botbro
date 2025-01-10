<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\GeneralSetting;
use Razorpay\Api\Api;
use DB;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Profile';
        $user_log = auth('web')->user();
        $user = User::where('id', $user_log->id)->first();

        $refferal = UserReferral::where('refer_id', $user_log->id)
                                ->leftjoin('users', 'users.id', '=', 'user_referrals.referral_id')
                                ->first();
        if ($user == null) {
            return redirect()->route('/')->with('error', 'Path not Valid.');
        }

        return view('frontend.dashboard.profile', compact('title', 'user','refferal'));
    }

    public function update(Request $request)
    {
        $user = auth('web')->user();
        $bank_account = array(
                        'name' => $user->bank_account_name,
                        'ifsc' => $user->ifsc,
                        'account_number' => $user->bank_account_number,
                        'bank_name' => $user->bank_name, 
                    );

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:250'],
            'email' => ['required', 'unique:users,email,' . $user->id], 
            'mobile' => ['required', 'digits:10', 'unique:users,mobile,' . $user->id], 
            'bank_account_name' => ['required', 'string', 'max:250'],
            'bank_account_number' => ['required', 'string', 'max:50'],
            'ifsc' => ['required', 'string', 'max:50'],
            'bank_name' => ['required', 'string', 'max:250'],
            'image' => ['image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ]);

        DB::beginTransaction();
        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->bank_account_name = $request->bank_account_name;
            $user->bank_account_number = $request->bank_account_number;
            $user->ifsc = $request->ifsc;
            $user->bank_name = $request->bank_name;

            if ($file = $request->file('image')) {
                $path = 'user';
                $destinationPath    = 'public\\' . $path;
                $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
                $user->image = $path . '/' . $uploadImage;
            }
            $user->save();

            /*/// Get General Setting Data
            $payment_setting = GeneralSetting::where('setting_type',6)->get()->toArray();
            $payment_setting = array_combine(array_column($payment_setting, 'setting_name'), array_column($payment_setting, 'filed_value'));
            $payment_key = $payment_setting['razorpay_keyid'];
            $payment_secret = $payment_setting['razorpay_secretkey'];*/

            /*if(empty($user->rzcontact_id)){
                /// Create a contacts on razorpay                
               $data = array(
                    "name" => $request->name,
                    "email" => $request->email,
                    "contact" => $request->mobile,
                    "type" => "customer",
                    "reference_id" => $user->reference_id
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
                User::where('id', $user->id)->update(['rzcontact_id' => $rzcontactId]);
            }else{
                /// Edit a contacts on razorpay
                $rzcontact_id = $user->rzcontact_id; 
                $data = array(
                    "name" => $request->name,
                    "email" => $request->email,
                    "contact" => $request->mobile,
                ); 
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://api.razorpay.com/v1/contacts/'.$rzcontact_id.'',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'PATCH',
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
            }*/

            /// Create a fund Account on razorpay
            /*if(empty($bank_account['account_number'])){
               $data = array(
                        'contact_id' => $rzcontact_id,
                        'account_type' => 'bank_account', 
                        'bank_account' => [
                            'name' => $user->bank_account_name,
                            'ifsc' => $user->ifsc,
                            'account_number' => $user->bank_account_number,
                        ],                      
                    );           
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://api.razorpay.com/v1/fund_accounts',
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
                $fundAccount = curl_exec($curl);
                curl_close($curl);  
                $fundAccount = json_decode($fundAccount);
                if(!empty($fundAccount->error)){
                   $error =  $fundAccount->error->description;
                   DB::rollback();
                   return back()->with('error', $error); 
                }
                $rzfundAccountId = $fundAccount->id;
                User::where('id', $user->id)->update(['rzfund_account_id' => $rzfundAccountId]); 
            }else{
                if($bank_account['account_number']!=$user->bank_account_name ||
                   $bank_account['bank_account_number']!=$user->bank_account_number ||
                   $bank_account['ifsc']!=$user->ifsc){

                    //// Deactivate old bank account    
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => 'https://api.razorpay.com/v1//fund_accounts/'.$user->rzfund_account_id.'',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'PATCH',
                      CURLOPT_POSTFIELDS =>'{
                            "active": false
                        }',
                      CURLOPT_USERPWD => $payment_key . ':' . $payment_secret,
                      CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                      ),
                    ));
                    $fundAccount = curl_exec($curl);
                    curl_close($curl);

                    ///create new bank detail     
                    $data = array(
                            'contact_id' => $rzcontact_id,
                            'account_type' => 'bank_account', 
                            'bank_account' => [
                                'name' => $user->bank_account_name,
                                'ifsc' => $user->ifsc,
                                'account_number' => $user->bank_account_number,
                            ],                      
                        );
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => 'https://api.razorpay.com/v1/fund_accounts',
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
                    $fundAccount = curl_exec($curl);
                    curl_close($curl);  
                    $fundAccount = json_decode($fundAccount);
                    if(!empty($fundAccount->error)){
                       $error =  $fundAccount->error->description;
                       DB::rollback();
                       return back()->with('error', $error); 
                    }
                    $rzfundAccountId = $fundAccount->id;
                    User::where('id', $user->id)->update(['rzfund_account_id' => $rzfundAccountId]);   
                }
            }*/
            
            DB::commit();
            return back()->with('success', 'Profile Updated Successfully!!');
        } catch (\Exception $e) {
            DB::rollback();
            $errorMessage = 'Failed to Register: ' . $e->getMessage();
            return redirect()->back()->with('error', $errorMessage);
        }
    }

}
