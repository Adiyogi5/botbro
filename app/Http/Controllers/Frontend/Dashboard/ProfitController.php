<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfitSharingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitController extends Controller
{
    public function index(Request $request) {
        $title = 'Profit History';
        
        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        $my_profit = UserProfitSharingLog::select('user_profit_sharing_logs.*', 'users.name','users.status')
                        ->join('users','user_profit_sharing_logs.user_id' , '=' ,'users.id')
                        ->Where('user_id', $user->id)
                        ->Where('users.status', '1')
                        ->get();


        return view('frontend.dashboard.profit_history',compact('title','my_profit'));
    }
}
