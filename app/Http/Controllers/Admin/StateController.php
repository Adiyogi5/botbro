<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;
use Datatables;

class StateController extends Controller
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
        checkPermission($this, 118);
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            $records = State::select('states.id','countries.name as country_name','states.name', 'states.status')
            ->leftJoin('countries','countries.id','=','states.country_id')
            ->where([['states.deleted_at', NULL]])->get();

            return Datatables::of($records)
            ->addColumn('status', function($row) {
                $status = ($row->status == 1)? 'checked': '';
                return $statusBtn = '<input class="tgl_checkbox tgl-ios" 
                data-id="'.$row->id.'" 
                id="cb_'.$row->id.'"
                type="checkbox" '.$status.'><label for="cb_'.$row->id.'"></label>';  
            })
            ->addColumn('action', function($row) {
                return $action_btn = '<a href="'.url('admin/states/'.$row->id.'/edit').'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                    <button data-id="'.$row->id.'" class="btn btn-sm btn-danger delete_record"><i class="fa fa-trash"></i> Delete</button>'; 
            }) 
            ->removeColumn('id') 
            ->rawColumns(['status', 'action'])->make(true);
            
        }
        $title = "State";
        return view('admin.states.index', compact('title'));
    }

    public function create()
    {
        $title = "Add State";  
        $country = Country::where([['deleted_at', NULL],['status',1]])->get();  
        return view('admin.states.add', compact('title','country'));
    }

    public function store(Request $request)
    {
        
        $validate = $request->validate([
            'name' => 'required|max:100|unique:states,name',
            'country_id'=>'required|',
            'code'=>'required|',
        ]); 
        $data = new State;
        $data->name = $request->name;
        $data->country_id = $request->country_id;
        $data->code = $request->code;
        $data->status = 1;
        $data->save(); 

        $request->session()->flash('success','State Added Successfully!!'); 
        return redirect( url('admin/states'));
    }

    public function edit(Request $request, $id)
    {
        $title = "Edit State"; 
        $country = Country::where([['deleted_at', NULL],['status',1]])->get();    
        $data = State::where('id', $id)->first();
        if(!empty($data)){
            return view('admin.states.edit', compact('title','data','country'));
        }else{
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title','message'));
        }
    }

    public function update(Request $request, $id)
    { 
        $validate = $request->validate([
         'name' => "required|max:100|unique:states,name,$id",
         'country_id'=>'required|',
         'code'=>'required|',

        ]);
        $data = State::where('id', $id)->first();
        if($data) {
            $data->name = $request->name;
            $data->country_id = $request->country_id;
            $data->code = $request->code;
            $data->save();

            $request->session()->flash('success','State Update Successfully!!');
            return redirect(url('admin/states'));
        }else {
            $request->session()->flash('error','State Does Not Exist!!');
            return redirect(url('admin/states'));
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = State::where('id', $id)->delete();              
        }else{
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = State::where('id', $request->id)->first();
            $data->status = $data->status==1?0:1;
            $data->save();
        }
    }

    public function get_state(Request $request)
    {
         $country_id = $request->country_id;
         $states = State::where([['status', '1'],['country_id' , $country_id]])->orderBy('name', 'asc')->get();

        $data = '<option value="">Select State</option>' ;
        foreach ($states as $key => $value) {
            $data .= '<option value="'.$value->id.'">'. $value->name.'</option>';
        }         
        $res = array('status' => TRUE, 'data' => $data);
        return response()->json($res);
    }

}
