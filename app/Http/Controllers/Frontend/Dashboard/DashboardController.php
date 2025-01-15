<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\MembershipDetail;
use App\Models\Order;
use App\Models\User;
use App\Models\UserReferral;
use App\Models\UserWallet;
use App\Models\UserWithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $title = 'Dashboard';
        $user = auth('web')->user();

        $user_membership = MembershipDetail::where('user_id', $user->id)->first();

        DB::statement("SET SQL_MODE = ''");
        $my_balance = User::select('users.*','badge_masters.name as badge_level')->Where('users.id', $user->id)->whereNull('users.deleted_at')
        ->leftjoin('badge_masters','badge_masters.id','=','users.badge_status')
        ->first();
        
        $refer_by = UserReferral::select('users.name as referred_by')->where('user_referrals.refer_id',$user->id)
        ->leftjoin('users','users.id','=','user_referrals.referral_id')
        ->first();
        
        $requestAmount = UserWithdrawRequest::selectRaw('SUM(amount) as total_amount')
        ->where('user_id', $user->id)
        ->where('status', 0)
        ->first();

        $rejectAmount = UserWithdrawRequest::selectRaw('SUM(amount) as total_amount')
        ->where('user_id', $user->id)
        ->where('status', 2)
        ->first();

        $total_order_count = Order::Where('user_id', $user->id) 
                                    ->whereNotIn('order_status_id', [0])
                                    ->whereNull('deleted_at')->count();
        $delivered_order_count = Order::Where('user_id', $user->id)
                                    ->where('order_status_id', 5)
                                    ->whereNull('deleted_at')->count();
        $cancel_order_count = Order::Where('user_id', $user->id) 
                                    ->where('order_status_id', 6)
                                    ->whereNull('deleted_at')->count();
        $pending_order_count = Order::Where('user_id', $user->id) 
                                    ->whereNotIn('order_status_id', [5,6])
                                    ->whereNull('deleted_at')->count();
       
        return view('frontend.dashboard.dashboard',compact('title', 'user_membership', 'total_order_count','delivered_order_count','cancel_order_count','pending_order_count','my_balance','requestAmount','rejectAmount','refer_by'));
    }

    public function qrcodepayment(Request $request)
    {
        // dd($request);
        $validate = $request->validate([
            'reference_id' => 'required',
            'transaction_id' => 'required',
            'date' => 'required',
        ]);

        try {
            $data = new MembershipDetail();
            $data->user_id = $request->user_id;
            $data->membership_fee = $request->membership_fee;
            $data->reference_id = $request->reference_id;
            $data->transaction_id = $request->transaction_id;
            $data->date = $request->date;
            $data->save();

            $request->session()->flash('success', 'Thanks For Join Our Membership.. Please wait for Approval !');
            return redirect()->back();
        } catch (\Exception $e) {
            $errorMessage = 'Failed to save Payment Details: ' . $e->getMessage();
            return redirect()->back()->with('error', $errorMessage);
        }
    }
}
