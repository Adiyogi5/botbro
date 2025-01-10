<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefferController extends Controller
{
    public function index(Request $request) {
        $title = 'Reffer History';

        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        $my_reffer = UserReferral::select('user_referrals.*', 'users.name','users.mobile','users.status')
                        ->join('users','user_referrals.refer_id' , '=' ,'users.id')
                        ->Where('referral_id', $user->id)
                        ->Where('users.status', '1')
                        ->get();

        return view('frontend.dashboard.reffer_history',compact('title','my_reffer'));
    }
}
