<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Validator; 
use Datatables;
use App\Models\Country;

class CountryController extends Controller
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
        checkPermission($this, 117);
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            $records = Country::select('id','name', 'status')->where([['deleted_at', NULL]])->get();

            return Datatables::of($records)
            ->addColumn('status', function($row) {
                $status = ($row->status == 1)? 'checked': '';
                return $statusBtn = '<input class="tgl_checkbox tgl-ios" 
                data-id="'.$row->id.'" 
                id="cb_'.$row->id.'"
                type="checkbox" '.$status.'><label for="cb_'.$row->id.'"></label>';  
            })
            ->addColumn('action', function($row) {
                return $action_btn = '<a href="'.url('admin/countries/'.$row->id.'/edit').'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                    <button data-id="'.$row->id.'" class="btn btn-sm btn-danger delete_record"><i class="fa fa-trash"></i> Delete</button>'; 
            }) 
            ->removeColumn('id') 
            ->rawColumns(['status', 'action'])->make(true);
            
        }
        $title = "Country";
        return view('admin.countries.index', compact('title'));
    }

    public function create()
    {
        $title = "Add Country";    
        return view('admin.countries.add', compact('title'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|max:100|unique:countries,name',
            'country_code'=>'required|numeric|',
        ]); 
        $data = new Country;
        $data->name = $request->name;
        $data->country_code = $request->country_code;
        $data->status = 1;
        $data->save(); 

        $request->session()->flash('success','Country Added Successfully!!'); 
        return redirect( url('admin/countries'));
    }

    public function edit(Request $request, $id)
    {
        $title = "Edit Country";   
        $data = Country::where('id', $id)->first();
        if(!empty($data)){
            return view('admin.countries.edit', compact('title','data'));
        }else{
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title','message'));
        }
    }

    public function update(Request $request, $id)
    { 
        $validate = $request->validate([
         'name' => "required|max:100|unique:countries,name,$id",
         'country_code'=>'required|numeric|',

        ]);
        $data = Country::where('id', $id)->first();
        if($data) {
            $data->name = $request->name;
            $data->country_code = $request->country_code;
            $data->save();

            $request->session()->flash('success','Country Update Successfully!!');
            return redirect(url('admin/countries'));
        }else {
            $request->session()->flash('error','Country Does Not Exist!!');
            return redirect(url('admin/countries'));
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Country::where('id', $id)->delete();              
        }else{
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = Country::where('id', $request->id)->first();
            $data->status = $data->status==1?0:1;
            $data->save();
        }
    }
}
