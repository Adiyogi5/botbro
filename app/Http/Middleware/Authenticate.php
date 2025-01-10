<?php

namespace App\Http\Middleware;

use Route;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
     protected function redirectTo($request)
    {   
        if (! $request->expectsJson()) {
            if(Route::is('admin.*')){
                return url('admin');
            }
            return url('/');
        }
    }

    protected function unauthenticated($request, array $guards)
    {   
        if($request->is('api/*')){
            $data = [
                'status'    => false,
                'message'   => 'Unauthenticated Access..!!',
                'data'      => []
            ];
            return abort(response()->json($data, 401));
        }else{
            throw new AuthenticationException(
                'Unauthenticated.', $guards, $this->redirectTo($request)
            );
        }
    }
    
}
