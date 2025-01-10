<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BadgeMaster;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BadgeMasterController extends Controller
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
        checkPermission($this, 121);
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $records = BadgeMaster::select('id', 'name', 'min_product', 'status');

            return DataTables::of($records)
                ->addColumn('status', function ($row) {
                    $status = ($row->status == 1) ? 'checked' : '';
                    $status_btn =  '<input class="tgl_checkbox tgl-ios" data-id="' . $row->id . '" id="cb_' . $row->id . '" type="checkbox" ' . $status . '><label for="cb_' . $row->id . '"></label>';
                    if ($row->id !== 1) {
                        return $status_btn;
                    }
                })
                ->addColumn('action', function ($row) {
                    $action_btn = '';
                    $action_btn .= '<a href="' . route('admin.badge_masters.edit', [$row->id]) . '" class="btn btn-sm btn-primary m-1" title="Edit"><i class="fa fa-edit"></i></a>';
                    $action_btn .=   '<button data-id="' . $row->id . '" class="btn btn-sm btn-danger delete_record" title="Delete"><i class="fa fa-trash"></i></button>';
                    if ($row->id !== 1) {
                        return $action_btn;
                    }
                })
                ->removeColumn('id')
                ->rawColumns(['status', 'action'])->make(true);
        }
        $title = "Badge Masters";
        return view('admin.badge_masters.index', compact('title'));
    }

    public function create()
    {
        $title = "Add Badge Master";
        return view('admin.badge_masters.add', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:250|unique:badge_masters,name',
            'min_products' => 'required|integer',
        ]);

        $data = new BadgeMaster;
        $data->name = $request->name;
        $data->min_product = $request->min_products;
        $data->status = 1;
        $data->save();
        
        $request->session()->flash('success', 'Badge Master Added Successfully!!');
        return redirect(route('admin.badge_masters.index'));
    }

    public function edit(Request $request, $id)
    {
        $title = "Edit Badge Master";
        $data = BadgeMaster::where('id', $id)->where('id', '!=', 1)->first();
        
        if (!empty($data)) {
            return view('admin.badge_masters.edit', compact('title', 'data'));
        } else {
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title', 'message'));
        }
    }

    public function update(Request $request, $id)
    {
        $data = BadgeMaster::where('id', $id)->where('id', '!=', 1)->first();
        if ($data) {
            $request->validate([
                'name' => 'required|max:250|unique:badge_masters,name,'.$data->id,
                'min_products' => 'required|integer',
            ]);

            $data->name = $request->name;
            $data->min_product = $request->min_products;
            $data->save();
            
            $request->session()->flash('success', 'Badge Master Update Successfully!!');
            return redirect(route('admin.badge_masters.index'));
        } else {
            $request->session()->flash('error', 'Badge Master Does Not Exist!!');
            return redirect(route('admin.badge_masters.index'));
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = BadgeMaster::where('id', $id)->where('id', '!=', 1)->delete();
        } else {
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = BadgeMaster::where('id', $request->id)->where('id', '!=', 1)->first();
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
        }
    }
}
