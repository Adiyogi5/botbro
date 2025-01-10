<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserBadgeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BadgeController extends Controller
{
    public function index(Request $request) {
        $title = 'Badge History';
        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        $my_badge = UserBadgeLog::select('user_badge_logs.*', 'badge_masters.name','badge_masters.min_product','badge_masters.status')
                        ->join('badge_masters','user_badge_logs.badge_id' , '=' ,'badge_masters.id')
                        ->Where('user_id', $user->id)
                        ->get();

        return view('frontend.dashboard.badge_history',compact('title','my_badge'));
    }
}
