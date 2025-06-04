<?php
namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Ledger;
use App\Models\RefferEarnsLedger;
use App\Models\User;
use App\Models\UserReferral;
use App\Models\UserReferralWithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefferController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Reffer History';

        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        $my_reffer = UserReferral::select('user_referrals.*', 'users.id as user_id', 'users.name', 'users.mobile', 'users.is_approved', 'users.status')
            ->join('users', 'user_referrals.refer_id', '=', 'users.id')
            ->Where('referral_id', $user->id)
            ->Where('users.status', '1')
            ->get();

        $my_balance = RefferEarnsLedger::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $requestAmount = UserReferralWithdrawRequest::selectRaw('SUM(request_amount) as total_amount')
            ->where('user_id', $user->id)
            ->where('status', 0)
            ->first();

        $rejectAmount = UserReferralWithdrawRequest::selectRaw('SUM(request_amount) as total_amount')
            ->where('user_id', $user->id)
            ->where('status', 2)
            ->first();

        $my_withdrow_request = UserReferralWithdrawRequest::select('user_referral_withdraw_requests.*', 'user_referral_withdraw_requests.status as request_status', 'users.name', 'users.user_balance', 'users.status')
            ->join('users', 'user_referral_withdraw_requests.user_id', '=', 'users.id')
            ->Where('user_id', $user->id)
            ->Where('users.status', '1')
            ->get();
       
        $ledgerData = RefferEarnsLedger::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->whereNull('deleted_at')
            ->get();

        $approvedMemberships = UserReferral::select('user_referrals.*', 'users.is_approved')
            ->leftjoin('users', 'users.id', '=', 'user_referrals.refer_id')
            ->where('user_referrals.referral_id', $user->id)
            ->where('users.is_approved', 1)
            ->count();
           
        $totalMembers = UserReferral::select('user_referrals.*', 'users.is_approved')
            ->leftjoin('users', 'users.id', '=', 'user_referrals.refer_id')
            ->where('user_referrals.referral_id', $user->id)
            ->count();
         
        return view('frontend.dashboard.reffer_history', compact('title', 'user', 'my_reffer', 'my_balance', 'requestAmount', 'rejectAmount', 'my_withdrow_request','ledgerData','approvedMemberships','totalMembers'));
    }

    public function withdrowrefferrequest(Request $request)
    {
        $user        = auth('web')->user();
        $balanceData = RefferEarnsLedger::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->whereNull('deleted_at')
            ->first();

        $requestData = UserReferralWithdrawRequest::where('status', '0')->whereNull('deleted_at')
            ->where('user_id', $user->id)
            ->get();

        $approvedMemberships = UserReferral::select('user_referrals.*', 'users.is_approved')
            ->leftjoin('users', 'users.id', '=', 'user_referrals.refer_id')
            ->where('user_referrals.referral_id', $user->id)
            ->where('users.is_approved', 1)
            ->count();

        $totalMembers = UserReferral::select('user_referrals.*', 'users.is_approved')
            ->leftjoin('users', 'users.id', '=', 'user_referrals.refer_id')
            ->where('user_referrals.referral_id', $user->id)
            ->count();

        DB::beginTransaction();
        try {
            $validate = $request->validate([
                'amount' => 'required|numeric',
            ], [
                'amount.required' => 'The withdrawal amount is required.',
                'amount.numeric'  => 'Please enter a valid numeric amount.',
            ]);
    
            if (!now()->between(now()->startOfMonth(), now()->startOfMonth()->addDays(5))) {
                $errorMessage = "Withdrawal requests are only allowed between the 1st and 5th of each month. Please try again during this period.";
                return redirect()->back()->with('error', $errorMessage);
            // }
            // if ($approvedMemberships < 5 || $totalMembers < 6) {
            //     $errorMessage = "To request a withdrawal, you must have at least 5 approved memberships and 6 On Board. Please try again after meeting this requirement.";
            //     return redirect()->back()->with('error', $errorMessage);
            } else if ($request->amount <= ($balanceData->balance - $requestData->sum('request_amount'))) {
                $wdata                 = new UserReferralWithdrawRequest();
                $reference_id          = RandcardStr(15);
                $wdata->user_id        = $user->id;
                $wdata->reference_id   = $reference_id;
                $wdata->request_amount = $request->amount;
                $wdata->request_date   = now()->format('Y-m-d H:i:s');
                $wdata->status         = 0;
                $wdata->save();

                /// Send Notification to Admin
                $adnoti                    = new AdminNotification();
                $adnoti->title             = "Referral Withdrawal Request Amount";
                $adnoti->message           = "Get new withdraw request from " . $user->name . "!!";
                $adnoti->notification_type = 1;
                $adnoti->is_read           = 0;
                $adnoti->created_at        = now()->format('Y-m-d H:i:s');
                $adnoti->updated_at        = now()->format('Y-m-d H:i:s');
                $adnoti->save();

                DB::commit();
                $request->session()->flash('success', 'Your Referral Withdraw Request Submitted Successfully');
                return redirect()->back();
            } else {
                $availableBalance = $balanceData->balance - $requestData->sum('amount');
                $errorMessage     = "Your requested amount is not more than your available balance. Your Available Balance is: {$availableBalance}";
                return redirect()->back()->with('error', $errorMessage);
            }

        } catch (\Exception $e) {
            $errorMessage = 'Failed to Submit Withdraw Request: ' . $e->getMessage();
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }
}
