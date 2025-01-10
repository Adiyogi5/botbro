<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FaqTypeController extends Controller
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
        checkPermission($this, 113);
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $records = FaqType::select('id', 'name', 'status');

            return DataTables::of($records)
                ->addColumn('status', function ($row) {
                    $status = ($row->status == 1) ? 'checked' : '';
                    return '<input class="tgl_checkbox tgl-ios"
                data-id="' . $row->id . '"
                id="cb_' . $row->id . '"
                type="checkbox" ' . $status . '><label for="cb_' . $row->id . '"></label>';
                })
                ->addColumn('action', function ($row) {
                    $action_btn = '';

                    $action_btn .= '<a href="' . route('admin.faq_types.edit', [$row->id]) . '" class="btn btn-sm btn-primary m-1" title="Edit"><i class="fa fa-edit"></i></a>';


                    $action_btn .=   '<button data-id="' . $row->id . '" class="btn btn-sm btn-danger delete_record" title="Delete"><i class="fa fa-trash"></i></button>';

                    return $action_btn;
                })
                ->removeColumn('id')
                ->rawColumns(['status', 'action'])->make(true);
        }
        $title = "FAQ Type";
        return view('admin.faq_types.index', compact('title'));
    }

    public function create()
    {
        $title = "Add FAQ Type";
        return view('admin.faq_types.add', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $data = new FaqType;
        $data->name = $request->name;
        $data->status = 1;
        $data->save();
        
        $request->session()->flash('success', 'FAQ Type Added Successfully!!');
        return redirect(route('admin.faq_types.index'));
    }

    public function edit(Request $request, $id)
    {
        $title = "Edit FAQ Type";
        $data = FaqType::where('id', $id)->first();
        if (!empty($data)) {
            return view('admin.faq_types.edit', compact('title', 'data'));
        } else {
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title', 'message'));
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => "required",
        ]);

        $data = FaqType::where('id', $id)->first();
        if ($data) {
            $data->name = $request->name;
            $data->save();
            
            $request->session()->flash('success', 'FAQ Type Update Successfully!!');
            return redirect(route('admin.faq_types.index'));
        } else {
            $request->session()->flash('error', 'FAQ Type Does Not Exist!!');
            return redirect(route('admin.faq_types.index'));
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = FaqType::where('id', $id)->delete();
        } else {
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = FaqType::where('id', $request->id)->first();
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
        }
    }
}
