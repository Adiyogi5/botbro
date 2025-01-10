<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserAddressController extends Controller
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

    public function index(Request $request, $user_id)
    {
        if ($request->ajax()) {
            $records = UserAddress::select('user_addresses.*', 'countries.name as country_name', 'states.name as state_name', 'cities.name as city_name')
            ->leftJoin('countries', 'countries.id', 'user_addresses.country_id')
            ->leftJoin('states', 'states.id', 'user_addresses.state_id')
            ->leftJoin('cities', 'cities.id', 'user_addresses.city_id')
            ->where(['user_id' => $request->user_id]);

            return DataTables::of($records)
                ->addColumn('default_id', function ($row) {
                    $status = ($row->default_id == 1) ? 'checked' : '';
                    return '<input class="tgl_checkbox tgl-ios" data-id="' . $row->id . '"
                id="cb_' . $row->id . '"  type="checkbox" ' . $status . '><label for="cb_' . $row->id . '"></label>';
                })
                ->addColumn('action', function ($row) use($request) {
                    return '<a href="' . route('admin.address.edit', [$row->id, 'user_id' => $request->user_id]) . '" class="btn btn-sm btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
                    <button data-id="' . $row->id . '" class="btn btn-sm btn-danger delete_record" title="Delete"><i class="fa fa-trash"></i></button>';
                })
                ->removeColumn('id')
                ->rawColumns(['default_id', 'action'])->make(true);
        }

        $user = User::where('id', $user_id)->first();
        if (!empty($user)) {
            $title = "Add Customer Address";
            $countries = Country::where(['status' => 1])->get();
            return view('admin.user_address.index', compact('title', 'user', 'countries'));
        } else {
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title', 'message'));
        }
    }

    public function create(Request $request, $user_id)
    {
        $user = User::where('id', $user_id)->first();
        if (!empty($user)) {
            $title = "Add Customer Address";
            $countries = Country::where([['deleted_at', null], ['status', 1]])->get();
            return view('admin.user_address.add', compact('title', 'user', 'countries'));
        } else {
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title', 'message'));
        }
    }

    public function store(Request $request, $user_id)
    {
        $user = User::where('id', $user_id)->first();

        if ($user) {
            $request->validate([
                'country'   => 'required|integer',
                'state'     => 'required|integer',
                'city'      => 'nullable|integer',
                'postcode'  => 'required|digits_between:2,12|numeric',
                'address_1' => 'required|max:250',
                'address_2' => 'nullable|max:250',
            ]);

            $data = [
                'user_id'       => $user->id,
                'address_1'     => $request->address_1,
                'address_2'     => $request->address_2,
                'postcode'      => $request->postcode,
                'country_id'    => $request->country,
                'state_id'      => $request->state,
                'city_id'       => $request->city,
                'default_id'    => 0,
            ];

            UserAddress::create($data);

            $request->session()->flash('success', 'Customer Address Created Successfully!!');
            return redirect(route('admin.address.index', ['user_id' => $user_id]));
        } else {
            $request->session()->flash('error', 'Customer Does Not Exist!!');
            return redirect(route('admin.address.index', ['user_id' => $user_id]));
        }
    }

    public function edit(Request $request, $user_id, $id)
    {
        $user = User::where('id', $user_id)->first();
        if (!empty($user)) {
            $title = "Edit Customer";
            $data = UserAddress::where('id', $id)->first();
            $countries = Country::where([['deleted_at', null], ['status', 1]])->get();
            return view('admin.user_address.edit', compact('title', 'data', 'user', 'countries'));
        } else {
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title', 'message'));
        }
    }

    public function update(Request $request, $user_id, $id)
    {
        $user = User::where('id', $user_id)->first();

        if ($user) {
            $request->validate([
                'country'   => 'required|integer',
                'state'     => 'required|integer',
                'city'      => 'nullable|integer',
                'postcode'  => 'required|digits_between:2,12|numeric',
                'address_1' => 'required|max:250|',
                'address_2' => 'nullable|max:250|',
            ]);

            $data = [
                'user_id'       => $request->user_id,
                'address_1'     => $request->address_1,
                'address_2'     => $request->address_2,
                'postcode'      => $request->postcode,
                'country_id'    => $request->country,
                'state_id'      => $request->state,
                'city_id'       => $request->city,
            ];
            
            $user_addresses = UserAddress::where(['id' => $id])->first();
            $user_addresses->update($data);

            $request->session()->flash('success', 'Customer Address Update Successfully!!');
            return redirect(route('admin.address.index', ['user_id' => $user_id]));
        } else {
            $request->session()->flash('error', 'Customer Does Not Exist!!');
            return redirect(route('admin.address.index', ['user_id' => $user_id]));
        }
    }

    public function destroy(Request $request, $user_id, $id)
    {
        if ($request->ajax()) {
            UserAddress::where('id', $id)->delete();
        } else {
            return 0;
        }
    }

    public function change_status(Request $request, $user_id)
    {
        if ($request->ajax()) {
            $data = UserAddress::where('user_id', $request->user_id)->first();
            $data->default_id = $data->default_id == 1 ? 0 : 1;
            $data->save();
        }
    }
}
