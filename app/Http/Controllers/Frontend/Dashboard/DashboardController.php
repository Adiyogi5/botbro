<?php
namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Investment;
use App\Models\Ledger;
use App\Models\MembershipDetail;
use App\Models\Order;
use App\Models\RefferEarnsLedger;
use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Dashboard';
        $user  = auth('web')->user();

        $user_membership = MembershipDetail::where('user_id', $user->id)->where('deleted_at', null)->first();

        DB::statement("SET SQL_MODE = ''");
        $my_balance = User::select('users.*', 'badge_masters.name as badge_level')->Where('users.id', $user->id)->whereNull('users.deleted_at')
            ->leftjoin('badge_masters', 'badge_masters.id', '=', 'users.badge_status')
            ->first();

        $refer_by = UserReferral::select('users.name as referred_by')->where('user_referrals.refer_id', $user->id)
            ->leftjoin('users', 'users.id', '=', 'user_referrals.referral_id')
            ->first();

        $investmentData = Ledger::select('balance')
            ->where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->whereIn('id', function ($query) use ($user) {
                $query->selectRaw('MAX(id)')
                    ->from('ledgers')
                    ->whereColumn('ledgers.invest_id', 'invest_id') 
                    ->where('user_id', $user->id)
                    ->whereNull('deleted_at')
                    ->groupBy('invest_id'); 
            })
            ->sum('balance'); 
        

        $user_refferBalance = RefferEarnsLedger::Where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->whereNull('deleted_at')
            ->first();

        $user_commissionBalance = User::select('users.id', 'users.user_commission_balance')
            ->Where('id', $user->id)->whereNull('deleted_at')
            ->first();

        $approvedMemberships = UserReferral::select('user_referrals.*', 'users.is_approved')
            ->leftjoin('users', 'users.id', '=', 'user_referrals.refer_id')
            ->where('user_referrals.referral_id', $user->id)
            ->where('users.is_approved', 1)
            ->count();
           
        $totalMembers = UserReferral::select('user_referrals.*', 'users.is_approved')
            ->leftjoin('users', 'users.id', '=', 'user_referrals.refer_id')
            ->where('user_referrals.referral_id', $user->id)
            ->count();
            
        $investmentCounts = Investment::where('user_id', $user->id)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN is_approved = 1 AND deleted_at IS NULL THEN 1 ELSE 0 END) as approved')
            ->selectRaw('SUM(CASE WHEN is_approved = 0 AND deleted_at IS NULL THEN 1 ELSE 0 END) as pending')
            ->selectRaw('SUM(CASE WHEN deleted_at IS NOT NULL THEN 1 ELSE 0 END) as rejected')
            ->first();
        
        $total_investment_count = $investmentCounts->total;
        $approved_investment_count = $investmentCounts->approved;
        $pending_investment_count = $investmentCounts->pending;
        $rejected_investment_count = $investmentCounts->rejected;
        
        return view('frontend.dashboard.dashboard', compact('title', 'user_membership', 'approvedMemberships', 'totalMembers',  'investmentData', 'user_refferBalance', 'user_commissionBalance', 'refer_by','total_investment_count','approved_investment_count','pending_investment_count','rejected_investment_count'));
    }

    public function qrcodepayment(Request $request)
    {
        $user = auth('web')->user();
        
        $validate = $request->validate([
            'reference_id'   => 'required',
            'transaction_id' => 'required',
            // 'payment_date' => 'required',
        ]);

        try {
            $data                 = new MembershipDetail();
            $data->user_id        = $request->user_id;
            $data->membership_fee = $request->membership_fee;
            $data->reference_id   = $request->reference_id;
            $data->transaction_id = $request->transaction_id;
            $data->payment_date   = now()->toDateString();
            $data->save();

             /// Send Notification to Admin
             $adnoti                    = new AdminNotification();
             $adnoti->title             = "New Customer Payment Approval Request";
             $adnoti->message           = "Get New Customer Payment Approval Request from " . $user->name . "!!";
             $adnoti->notification_type = 1;
             $adnoti->is_read           = 0;
             $adnoti->created_at        = now()->format('Y-m-d H:i:s');
             $adnoti->updated_at        = now()->format('Y-m-d H:i:s');
             $adnoti->save();

            $request->session()->flash('success', 'Thanks For Join Our Membership.. Please wait for Approval !');
            return redirect()->back();
        } catch (\Exception $e) {
            $errorMessage = 'Failed to save Payment Details: ' . $e->getMessage();
            return redirect()->back()->with('error', $errorMessage);
        }
    }
}
