<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Validator; 
use Datatables;
use App\Models\Role;
use App\Models\Module;
use App\Models\RolePermission;

class RolesController extends Controller
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
        checkPermission($this, 107);          
    }


    public function index(Request $request)
    {
        if($request->ajax()) {

            $records = Role::select('id','name', 'status')->where([['deleted_at', NULL]])->get();

            return Datatables::of($records)
            ->addColumn('status', function($row) {
                $status = ($row->status == 1)? 'checked': '';
                return $statusBtn = '<input class="tgl_checkbox tgl-ios" 
                data-id="'.$row->id.'" 
                id="cb_'.$row->id.'"
                type="checkbox" '.$status.'><label for="cb_'.$row->id.'"></label>';  
            })
            ->addColumn('action', function($row) {
                return $action_btn = '<a href="'.url('admin/role/'.$row->id.'/edit').'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>

                    <a href="'.url('admin/role_permission/'.$row->id.'/edit').'" class="btn btn-sm btn-dark"><i class="fa fa-lock"></i></a>

                    <button data-id="'.$row->id.'" class="btn btn-sm btn-danger delete_record"><i class="fa fa-trash"></i> Delete</button>'; 
            }) 
            ->removeColumn('id') 
            ->rawColumns(['status', 'action'])->make(true);
            
        }
        $title = "Roles";
        return view('admin.role.index', compact('title'));
    }

    public function create()
    {
        $title = "Add Roles";    
        return view('admin.role.add', compact('title'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|max:100|unique:roles,name'
        ]); 
        $data = new Role;
        $data->name = $request->name;
        $data->status = 1;
        $data->save(); 
        
         /* update role permission*/
        $role_permisions = Module::where([])->get();
        $role_id = $data->id;

        foreach ($role_permisions as $val) {
            $role_per = new RolePermission();
            $role_per->role_id = $role_id;
            $role_per->module_id = $val['module_id'];
            $role_per->can_add = 0;
            $role_per->can_edit = 0;
            $role_per->can_delete = 0;
            $role_per->can_view = 0;
            $role_per->allow_all = 0;
            $role_per->save();
        }

        $request->session()->flash('success','Role Added Successfully!!'); 
        return redirect( url('admin/role'));
    }

    public function edit(Request $request, $id)
    {
        $title = "Edit Roles";   
        $data = Role::where('id', $id)->first();
        if(!empty($data)){
            return view('admin.role.edit', compact('title','data'));
        }else{
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title','message'));
        }
    }

    public function update(Request $request, $id)
    { 
        $validate = $request->validate([
         'name' => "required|max:100|unique:roles,name,$id",
        ]);
        $data = Role::where('id', $id)->first();
        if($data) {
            $data->name = $request->name;
            $data->save();

            $request->session()->flash('success','Role Update Successfully!!');
            return redirect(url('admin/role'));
        }else {
            $request->session()->flash('error','Role Does Not Exist!!');
            return redirect(url('admin/role'));
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Role::where('id', $id)->delete();              
        }else{
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::where('id', $request->id)->first();
            $data->status = $data->status==1?0:1;
            $data->save();
        }
    }


}
