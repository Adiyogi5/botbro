<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Validator; 
use Datatables;


class CityController extends Controller
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
        checkPermission($this, 119);
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            $records = City::select('cities.id','countries.name as country_name','states.name as state_name','cities.name', 'cities.status')
            ->leftJoin('countries','countries.id','=','cities.country_id')
            ->leftJoin('states','states.id','=','cities.state_id')
            ->where([['cities.deleted_at', NULL]])->get();

            return Datatables::of($records)
            ->addColumn('status', function($row) {
                $status = ($row->status == 1)? 'checked': '';
                return $statusBtn = '<input class="tgl_checkbox tgl-ios" 
                data-id="'.$row->id.'" 
                id="cb_'.$row->id.'"
                type="checkbox" '.$status.'><label for="cb_'.$row->id.'"></label>';  
            })
            ->addColumn('action', function($row) {
                return $action_btn = '<a href="'.url('admin/cities/'.$row->id.'/edit').'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                    <button data-id="'.$row->id.'" class="btn btn-sm btn-danger delete_record"><i class="fa fa-trash"></i> Delete</button>'; 
            }) 
            ->removeColumn('id') 
            ->rawColumns(['status', 'action'])->make(true);
            
        }
        $title = "City";
        return view('admin.cities.index', compact('title'));
    }

    public function create()
    {
        $title = "Add City";  
        $country = Country::where([['deleted_at', NULL],['status',1]])->get(); 
        return view('admin.cities.add', compact('title','country'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|max:100|unique:cities,name',
            'country_id'=>'required|',
            'state_id'=>'required|',

        ]); 
        $data = new City;
        $data->name = $request->name;
        $data->country_id = $request->country_id;
        $data->state_id = $request->state_id;
        $data->status = 1;
        $data->save(); 

        $request->session()->flash('success','City Added Successfully!!'); 
        return redirect( url('admin/cities'));
    }

    public function edit(Request $request, $id)
    {
        $title = "Edit City"; 
        $country = Country::where([['deleted_at', NULL],['status',1]])->get();   
        $data = City::where('id', $id)->first();
        if(!empty($data)){
            return view('admin.cities.edit', compact('title','data','country'));
        }else{
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title','message'));
        }
    }

    public function update(Request $request, $id)
    { 
        $validate = $request->validate([
         'name' => "required|max:100|unique:cities,name,$id",
         'country_id'=>'required|',
         'state_id'=>'required|',

        ]);
        $data = City::where('id', $id)->first();
        if($data) {
            $data->name = $request->name;
            $data->country_id = $request->country_id;
            $data->state_id = $request->state_id;

            $data->save();

            $request->session()->flash('success','City Update Successfully!!');
            return redirect(url('admin/cities'));
        }else {
            $request->session()->flash('error','City Does Not Exist!!');
            return redirect(url('admin/cities'));
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = City::where('id', $id)->delete();              
        }else{
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = City::where('id', $request->id)->first();
            $data->status = $data->status==1?0:1;
            $data->save();
        }
    }

    public function get_city(Request $request)
    {
         $state_id = $request->state_id;
         $cities = City::where([['status', '1'],['state_id' , $state_id]])->get();

        $data = '<option value="">Select City</option>' ;
        foreach ($cities as $key => $value) {
            $data .= '<option value="'.$value->id.'">'. $value->name.'</option>';
        }         
        $res = array('status' => TRUE, 'data' => $data);
        return response()->json($res);
    }


}
