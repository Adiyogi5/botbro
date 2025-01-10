<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ConfirmPassController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Change Password';

        return view('frontend.dashboard.confirm_password', compact('title'));
    }

    public function changePassword(Request $request)
    {
        # Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ]);

        #Match The Old Password
        if (!Hash::check($request->old_password, auth('web')->user()->password)) {
            return back()->with("error", "Old Password Doesn't match!");
        }

        #Update the new Password
        User::whereId(auth('web')->user()->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with("status", "Password changed successfully!");
    }

}
