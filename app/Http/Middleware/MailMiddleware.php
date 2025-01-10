<?php

namespace App\Http\Middleware;

use App\Models\GeneralSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;

class MailMiddleware
{
    public function handle(Request $request, Closure $next)
    {   
        
        $data = array();
        $app_data = GeneralSetting::where('setting_type','2')->get();
        foreach ($app_data as $row) {
            $data[$row['setting_name']] = $row['filed_value'];
        }

        Config::set("mail.mailers.smtp", [
            'transport'     => 'smtp',
            'host'          => $data['smtp_host'],
            'port'          => $data['smtp_port'],
            'encryption'    => in_array((int) $data['smtp_port'], [587, 2525]) ? 'tls' : 'ssl',
            'username'      => $data['smtp_user'],
            'password'      => $data['smtp_pass'],
            'timeout'       =>  null,
            'auth_mode'     =>  null,
        ]);

        Config::set("mail.from", [
            'address'       =>  $data['email_from'],
            'name'          =>  config('app.name'),
        ]);

        return $next($request);
    }

}
