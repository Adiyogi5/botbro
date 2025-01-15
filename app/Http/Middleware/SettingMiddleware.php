<?php

namespace App\Http\Middleware;

use App\Models\AdminNotification;
use App\Models\Cart;
use App\Models\GeneralSetting;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;


class SettingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $data = array();
        $settings = array();
        $app_data = GeneralSetting::all();

        foreach ($app_data as $row) {
            $settings[$row['setting_name']] = $row['filed_value'];
        }

        $header_notifications = AdminNotification::select('id', 'title', 'message' ,'created_at', 'is_read')->where('is_read', 0)->orderBy('created_at','desc');
        
        $total_notifications    = $header_notifications->get()->count();
        $admin_notifications    = $header_notifications->limit(7)->get();

        $data = array(
            'admin_notifications'   => $admin_notifications,
            'total_notifications'   => $total_notifications,
        );

        # Cart Counter
        $user = auth('web')->user();
        $user_id = !empty($user) ? $user->id : '';

        $cart_count = Cart::where('customer_id',$user_id)->count('id');

        $user_approved = null;
        if ($user) {
            $user_approved = User::where('id', $user->id)->first();
        }

        View::share(['site_settings' => $settings, 'header_info' => $data,'cart_count'=>$cart_count, 'user_approved'=>$user_approved]); 

        return $next($request);
    }
}
