<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Only Authenticated users for "admin" guard 
     * are allowed.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        checkPermission($this, 115);
    }

    public function profile()
    {
        ////Auth::guard('admin')->check();
        $adminid = Auth::guard('admin')->user()->id;
        $record = Admin::where('id', $adminid)->get()->first();
        if ($record === null) {
            return redirect('admin/dashboard')->with('error', 'Record Not Found!!');
        }
        $data['record'] = $record;
        $data['title']  = 'Profile';
        $view_name = 'admin.profile.profile';
        return view($view_name, $data);
    }

    public function updateprofile(Request $request)
    {
        $adminid = Auth::guard('admin')->user()->id;
        $record = Admin::where('id', $adminid)->get()->first();

        $this->validate($request, [
            'name' => 'required|max:100',
            'mobile' => 'required|min:10|numeric',
            'email'     => 'bail|required|unique:admins,email,' . $record->id,
            'image'     => 'bail|mimes:png,jpg,jpeg,JPG,JPEG,PNG|max:2048',
        ]);

        if ($record) {
            // Update Profile Record
            $record->name =  $request->name;
            $record->email =  $request->email;
            $record->mobile =  $request->mobile;
            $path = 'uploads/admin/';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if (!empty($ivalue['old_image'])) {
                    delete_file($ivalue['old_image']);
                }
                $destinationPath    = 'public\\' . $path;
                $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
                $record->image = $path . '/' . $uploadImage;
            }
            $record->save();
            $request->session()->flash('success', 'Profile Update Successfully!!');
            return redirect('admin/profile');
        } else {
            $request->session()->flash('error', 'Profile Record Not Found!!');
            return redirect(route('admin/profile'));
        }
    }

    public function changePassword()
    {
        $adminid = Auth::guard('admin')->user()->id;
        $record = Admin::where('id', $adminid)->get()->first();
        if ($record === null) {
            return redirect('admin/dashboard')->with('error', 'Record Not Found!!');
        }
        $data['record']             = $record;
        $data['title']              = 'Change Password';
        $view_name = 'admin.profile.change_password';
        return view($view_name, $data);
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password'     => 'required|confirmed',
        ]);
        $credentials = $request->only(
            'old_password',
            'password',
            'password_confirmation'
        );
        $admin = Auth::guard('admin')->user();
        if (Hash::check($credentials['old_password'], $admin->password)) {
            $admin->password = Hash::make($credentials['password']);
            $admin->save();

            $request->session()->flash('success', 'Password Updated Successfully!!');
            return redirect('admin/change-password');
        } else {
            $request->session()->flash('error', 'Incorrect Old Password!!');
            return redirect()->back();
        }
    }
}
