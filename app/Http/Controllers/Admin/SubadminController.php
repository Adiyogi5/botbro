<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Validator;
use Illuminate\Support\Facades\Hash;
use Datatables;
use App\Models\Role;
use App\Models\Admin;
use App\Models\RolePermission;
use App\Models\AdminPermission;
use Illuminate\Support\Facades\Storage;

class SubadminController extends Controller
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
        checkPermission($this, 103);
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = Admin::select('id', 'name', 'email', 'mobile', 'status')->where([['id', '!=', 1]])->where([['deleted_at', NULL]])->get();

            return Datatables::of($records)
                ->addColumn('status', function ($row) {
                    $status = ($row->status == 1) ? 'checked' : '';
                    return $statusBtn = '<input class="tgl_checkbox tgl-ios" 
                data-id="' . $row->id . '" 
                id="cb_' . $row->id . '"
                type="checkbox" ' . $status . '><label for="cb_' . $row->id . '"></label>';
                })
                ->addColumn('action', function ($row) {
                    return $action_btn = '<a href="' . url('admin/subadmin/' . $row->id . '/edit') . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                
                    <a href="' . url('admin/user_permission/' . $row->id . '/edit') . '" class="btn btn-sm btn-dark"><i class="fa fa-lock"></i></a>

                    <button data-id="' . $row->id . '" class="btn btn-sm btn-danger delete_record"><i class="fa fa-trash"></i> Delete</button>';
                })
                ->removeColumn('id')
                ->rawColumns(['status', 'action'])->make(true);
        }
        $title = "Subadmin";
        return view('admin.subadmin.index', compact('title'));
    }

    public function create()
    {
        $title = "Add subadmin";
        $role = Role::where([['deleted_at', NULL], ['status', '1']])->get();
        return view('admin.subadmin.add', compact('title', 'role'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|max:100|',
            'role_id' => 'required',
            'email' => 'required|max:150|unique:admins,email',
            'mobile' => 'required|min:10|numeric|unique:admins,mobile',
            'image'     => 'mimes:png,jpg,jpeg,JPG,JPEG,png|max:2048',
            'password'     => 'required|confirmed',

        ]);

        $role_permision = RolePermission::where([['role_id', $request->role_id]])->get();

        $data = new Admin;
        $data->name = $request->name;
        $data->role_id = $request->role_id;
        $data->email = $request->email;
        $data->mobile = $request->mobile;
        $data->password = Hash::make($request->password);
        $data->status = 1;
        $path = 'uploads/admin/';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if (!empty($ivalue['old_image'])) {
                delete_file($ivalue['old_image']);
            }
            $destinationPath    = 'public\\' . $path;
            $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
            Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
            $data->image = $path . '/' . $uploadImage;
        }
        $data->save();
        $admin_id = $data->id;

        foreach ($role_permision as $val) {

            $admin_per = new AdminPermission;
            $admin_per->admin_id = $admin_id;
            $admin_per->module_id = $val['module_id'];
            $admin_per->can_add = $val['can_add'];
            $admin_per->can_edit = $val['can_edit'];
            $admin_per->can_delete = $val['can_delete'];
            $admin_per->can_view = $val['can_view'];
            $admin_per->allow_all = $val['allow_all'];
            $admin_per->save();
        }

        $request->session()->flash('success', 'Subadmin Added Successfully!!');
        return redirect(url('admin/subadmin'));
    }

    public function edit(Request $request, $id)
    {
        $title = "Edit Subadmin";
        $role = Role::where([['id', '!=', 1], ['deleted_at', NULL], ['status', '1']])->get();
        $data = Admin::where('id', $id)->where([['id', '!=', 1]])->first();
        if (!empty($data)) {
            return view('admin.subadmin.edit', compact('title', 'data', 'role'));
        } else {
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title', 'message'));
        }
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'name' => 'required|max:100|',
            'role_id' => 'required',
            'email' => "required|max:150|unique:admins,email,$id",
            'mobile' => "required|min:10|numeric|unique:admins,mobile,$id",
            'image'     => 'mimes:png,jpg,jpeg,JPG,JPEG,png|max:2048',
        ]);

        $role_permision = RolePermission::where([['role_id', $request->role_id]])->get();

        $data = Admin::where('id', $id)->first();
        if ($data) {
            $data->name = $request->name;
            $data->role_id = $request->role_id;
            $data->email = $request->email;
            $data->mobile = $request->mobile;
            $data->password = Hash::make($request->password);
            $data->status = 1;
            $path = 'uploads/admin/';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if (!empty($ivalue['old_image'])) {
                    delete_file($ivalue['old_image']);
                }
                $destinationPath    = 'public\\' . $path;
                $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
                $data->image = $path . '/' . $uploadImage;
            }
            $data->save();

            AdminPermission::where('admin_id', $id)->delete();

            foreach ($role_permision as $val) {

                $admin_per = new AdminPermission;
                $admin_per->admin_id = $id;
                $admin_per->module_id = $val['module_id'];
                $admin_per->can_add = $val['can_add'];
                $admin_per->can_edit = $val['can_edit'];
                $admin_per->can_delete = $val['can_delete'];
                $admin_per->can_view = $val['can_view'];
                $admin_per->allow_all = $val['allow_all'];
                $admin_per->save();
            }

            $request->session()->flash('success', 'Subadmin Update Successfully!!');
            return redirect(url('admin/subadmin'));
        } else {
            $request->session()->flash('error', 'Subadmin Does Not Exist!!');
            return redirect(url('admin/subadmin'));
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Admin::where('id', $id)->delete();
        } else {
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = Admin::where('id', $request->id)->first();
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
        }
    }
}
