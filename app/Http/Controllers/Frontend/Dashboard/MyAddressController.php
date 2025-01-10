<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MyAddressController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Address';
        $user = auth('web')->user();
        $user_address_data = UserAddress::select('user_addresses.*', 'countries.name as country_name', 'states.name as state_name', 'cities.name as city_name')
            ->join('countries', 'countries.id', '=', 'user_addresses.country_id')
            ->join('states', 'states.id', '=', 'user_addresses.state_id')
            ->join('cities', 'cities.id', '=', 'user_addresses.city_id')
            ->Where('user_id', $user->id)
            ->get();
            
        $countries = Country::where('status', 1)->pluck('name', 'id')->toArray();

        return view('frontend.dashboard.my_address', compact('title', 'user_address_data', 'countries'));
    }

    public function add()
    {
        return view('frontend.dashboard.my_address.add', compact('countries'));
    }

    public function save(Request $request)
    {
        $user = auth('web')->user();
        
        $validation = Validator::make($request->all(), [
            'address_1' => ['required', 'string'],
            'address_2' => ['required', 'string'],
            'country_id' => ['required', 'integer'],
            'state_id' => ['required', 'integer'],
            'city_id' => ['required', 'integer'],
            'postcode' => ['required', 'integer'],
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                'success' => false,
                'message' => $errors->first('name'),
                'data' => '',
            ]);
        } else {

            UserAddress::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'postcode' => $request->postcode,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Address Added Successfully',
                'data' => '',
            ]);
        }
    }

    public function update(Request $request)
    {
        $user = auth('web')->user();
        if ($user == null) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Authenticated!!',
            ]);
        }

        $user_data = UserAddress::firstWhere('id', $request->id);
        if ($user_data == null) {
            return response()->json([
                'success' => false,
                'message' => 'Address Not Found!!',
            ]);
        }

        $validation = Validator::make($request->all(), [
            'address_1' => ['required', 'string'],
            'address_2' => ['required', 'string'],
            'country_id' => ['required', 'integer'],
            'state_id' => ['required', 'integer'],
            'city_id' => ['required', 'integer'],
            'postcode' => ['required', 'integer'],
        ]);

        if ($validation->fails()) {
            $err = array();
            foreach ($validation->errors()->toArray() as $key => $value) {
                $err[$key] = $value[0];
            }
            return response()->json([
                'status' => false,
                'message' => "Invalid Input values.",
                "data" => $err,
            ]);
        } else {

            $user_data->update([
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'postcode' => $request->postcode,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Address Updated Successfully',
            ]);
        }
    }

    public function delete(Request $request)
    {
        if ($request->id) {
            $city = UserAddress::where('id', $request->id)->first();
            if ($city == null) {
                return response()->json([
                    'success' => true,
                    'message' => 'Address Not Found.',
                ]);
            }
            $city->delete();
            return response()->json([
                'success' => true,
                'message' => 'Address deleted Successfully',
            ]);
        }
    }

    public function switch_address(Request $request){
        $user = auth('web')->user();
        
        UserAddress::where('user_id', $user->id)->update(['default_id'=>0]);
        $data = UserAddress::where('user_id', $user->id)->where('id', $request->address_id)->update(['default_id'=>1]);
    
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
