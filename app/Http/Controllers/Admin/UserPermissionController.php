<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Validator;
use Datatables;
use App\Models\Admin;
use App\Models\Module;
use App\Models\AdminPermission;


class UserPermissionController extends Controller
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
        $title = "Edit User Permission";
        $roledata = Admin::where('id', $id)->first();
        $moduledata = Module::get();
        $data = AdminPermission::select('admin_permissions.id','admin_permissions.admin_id','modules.name as module_name', 'modules.module_id as module_id', 'admin_permissions.can_delete','admin_permissions.can_add','admin_permissions.can_edit', 'admin_permissions.can_view')
        ->leftJoin('modules','modules.module_id','=','admin_permissions.module_id')
        ->where([['admin_permissions.admin_id',$id]])
        ->get();
        
        $mdata = [];
        if($data){
            foreach($data as $key => $value){
                
                $mdata[$value['module_id']] = $value;
                
            }
        }
        
        if(!empty($data)){
            return view('admin.user_permission.index', compact('title','roledata', 'data', 'mdata' ,'moduledata'));
        }else{
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title','message'));
        }
    }

    public function update_user_permission(Request $request)
    {   
        
        if ($request->ajax()) {
            $data = AdminPermission::where([['module_id',(int) $request->model_id],['admin_id',(int) $request->role_id]])->first();
            
            if($data){
                $data[$request->type] = $request->status;
                $data->save();
            }else{
                
                $module = new AdminPermission();
                $module->admin_id = $request->role_id;
                $module->module_id = $request->model_id;
                $module[$request->type] = $request->status;
                $module->save();
            }
        }
    }
}