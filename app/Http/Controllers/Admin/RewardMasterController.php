<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RewardMaster;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RewardMasterController extends Controller
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
        checkPermission($this, 122);
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $records = RewardMaster::select('id', 'name', 'min_product' , 'status');

            return DataTables::of($records)
                ->addColumn('status', function ($row) {
                    $status = ($row->status == 1) ? 'checked' : '';
                    return '<input class="tgl_checkbox tgl-ios" data-id="' . $row->id . '" id="cb_' . $row->id . '" type="checkbox" ' . $status . '><label for="cb_' . $row->id . '"></label>';
                })
                ->addColumn('action', function ($row) {
                    $action_btn = '';
                    $action_btn .= '<a href="' . route('admin.reward_masters.edit', [$row->id]) . '" class="btn btn-sm btn-primary m-1" title="Edit"><i class="fa fa-edit"></i></a>';
                    $action_btn .=   '<button data-id="' . $row->id . '" class="btn btn-sm btn-danger delete_record" title="Delete"><i class="fa fa-trash"></i></button>';
                    return $action_btn;
                })
                ->removeColumn('id')
                ->rawColumns(['status', 'action'])->make(true);
        }
        $title = "Reward Master";
        return view('admin.reward_masters.index', compact('title'));
    }

    public function create()
    {
        $title = "Add Reward Master";
        return view('admin.reward_masters.add', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:250|unique:reward_masters,name',
            'min_products' => 'required|integer',
        ]);

        $data = new RewardMaster;
        $data->name = $request->name;
        $data->min_product = $request->min_products;
        $data->status = 1;
        $data->save();
        
        $request->session()->flash('success', 'Reward Master Added Successfully!!');
        return redirect(route('admin.reward_masters.index'));
    }

    public function edit(Request $request, $id)
    {
        $title = "Edit Reward Master";
        $data = RewardMaster::where('id', $id)->first();
        if (!empty($data)) {
            return view('admin.reward_masters.edit', compact('title', 'data'));
        } else {
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title', 'message'));
        }
    }

    public function update(Request $request, $id)
    {
        $data = RewardMaster::where('id', $id)->first();
        if ($data) {
            $request->validate([
                'name' => 'required|max:250|unique:reward_masters,name,'.$data->id,
                'min_products' => 'required|integer',
            ]);

            $data->name = $request->name;
            $data->min_product = $request->min_products;
            $data->save();
            
            $request->session()->flash('success', 'Reward Master Update Successfully!!');
            return redirect(route('admin.reward_masters.index'));
        } else {
            $request->session()->flash('error', 'Reward Master Does Not Exist!!');
            return redirect(route('admin.reward_masters.index'));
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = RewardMaster::where('id', $id)->delete();
        } else {
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = RewardMaster::where('id', $request->id)->first();
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
        }
    }
}
