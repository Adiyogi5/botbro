<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Validator;
use App\Models\Gift;
use Datatables;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class GiftController extends Controller
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
        checkPermission($this, 104);
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $records = Gift::select('id', 'name', 'status')->where('deleted_at', NULL)->get();

            return Datatables::of($records)
                ->addColumn('status', function ($row) {
                    $status = ($row->status == 1) ? 'checked' : '';
                    return $statusBtn = '<input class="tgl_checkbox tgl-ios" 
                data-id="' . $row->id . '" 
                id="cb_' . $row->id . '"
                type="checkbox" ' . $status . '><label for="cb_' . $row->id . '"></label>';
                })
                ->addColumn('action', function ($row) {
                    return $action_btn = '<a href="' . url('admin/gift/' . $row->id . '/edit') . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                })
                ->removeColumn('id')
                ->rawColumns(['status', 'action'])->make(true);
        }
        $title = "Gift";
        return view('admin.gift.index', compact('title'));
    }


    public function edit(Request $request, $id)
    {
        $title = "Edit Gift";
        $data = Gift::where('id', $id)->first();
        if (!empty($data)) {
            return view('admin.gift.edit', compact('title', 'data'));
        } else {
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title', 'message'));
        }
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'name' => "required|max:250",
        ]);

        $data = Gift::where('id', $id)->first();
        if ($data) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));
            $data->name = $request->name;
            $data->save();

            $request->session()->flash('success', 'Gift Update Successfully!!');
            return redirect(url('admin/gift'));
        } else {
            $request->session()->flash('error', 'Gift Does Not Exist!!');
            return redirect(url('admin/gift'));
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Gift::where('id', $id)->delete();
        } else {
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = Gift::where('id', $request->id)->first();
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
        }
    }
}
