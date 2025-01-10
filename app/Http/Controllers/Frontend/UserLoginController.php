<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    public function index(Request $request)
    {
        
        $title = 'Login';
        

        
        return view('frontend.login', compact('title'));
    }


}
