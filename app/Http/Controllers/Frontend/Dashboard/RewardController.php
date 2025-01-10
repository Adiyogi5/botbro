<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRewardLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    public function index(Request $request) {
        $title = 'Reward History';
        
        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        $my_reward = UserRewardLog::select('user_reward_logs.*', 'reward_masters.name','reward_masters.min_product','reward_masters.status')
                        ->join('reward_masters','user_reward_logs.reward_id' , '=' ,'reward_masters.id')
                        ->Where('user_id', $user->id)
                        ->Where('reward_status', '1')
                        ->get();


        return view('frontend.dashboard.reward_history',compact('title','my_reward'));
    }
}
