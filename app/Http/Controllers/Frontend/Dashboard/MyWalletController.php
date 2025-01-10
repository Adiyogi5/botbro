<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyWalletController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Wallet';
        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        $my_balance = User::select('users.user_balance','user_wallets.user_id')
            ->join('user_wallets', 'users.id', '=', 'user_wallets.user_id')
            ->Where('user_wallets.user_id', $user->id)
            ->get();
        
        $my_balance_data = UserWallet::select('user_wallets.*', 'users.name')
            ->join('users', 'users.id', '=', 'user_wallets.user_id')
            ->Where('user_wallets.user_id', $user->id)
            ->orderBy('user_wallets.id', 'desc')
            ->paginate(10);
            
        return view('frontend.dashboard.my_wallet', compact('title', 'my_balance', 'my_balance_data'));
    }
}
