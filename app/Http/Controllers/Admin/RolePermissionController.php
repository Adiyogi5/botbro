<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Validator;
use Datatables;
use App\Models\Role;
use App\Models\Module;
use App\Models\RolePermission;


class RolePermissionController extends Controller
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
        checkPermission($this, 120);
    }

    public function edit(Request $request, $id)
    {
        $title = "Edit Role Permission";
        $roledata = Role::where('id', $id)->first();
        $moduledata = Module::get();
        $data = RolePermission::select('role_permissions.id','role_permissions.role_id','modules.name as module_name', 'modules.module_id as module_id', 'role_permissions.can_delete','role_permissions.can_add','role_permissions.can_edit', 'role_permissions.can_view')
        ->leftJoin('modules','modules.module_id','=','role_permissions.module_id')
        ->where([['role_permissions.role_id',$id]])
        ->get();
        
        $mdata = [];
        if($data){
            foreach($data as $key => $value){
                
                $mdata[$value['module_id']] = $value;
                
            }
        }
        
        if(!empty($data)){
            return view('admin.role_permission.index', compact('title','roledata', 'data', 'mdata' ,'moduledata'));
        }else{
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title','message'));
        }
    }

    public function update_role_permission(Request $request)
    {   
        
        if ($request->ajax()) {
            $data = RolePermission::where([['module_id',(int) $request->model_id],['role_id',(int) $request->role_id]])->first();
            
            if($data){
                $data[$request->type] = $request->status;
                $data->save();
            }else{
                
                $module = new RolePermission();
                $module->role_id = $request->role_id;
                $module->module_id = $request->model_id;
                $module[$request->type] = $request->status;
                $module->save();
            }
        }
    }
}