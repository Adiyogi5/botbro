<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\FaqType;
use Yajra\DataTables\DataTables;

class FaqController extends Controller
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
        checkPermission($this, 106);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = Faq::select('faqs.id', 'faq_types.name as faqtype_name', 'faqs.question', 'faqs.status')
                ->leftJoin('faq_types', 'faq_types.id', '=', 'faqs.faq_type_id')
                ->where([['faqs.deleted_at', NULL]])->get();

            return DataTables::of($records)
                ->addColumn('status', function ($row) {
                    $status = ($row->status == 1) ? 'checked' : '';
                    return '<input class="tgl_checkbox tgl-ios" 
                data-id="' . $row->id . '" 
                id="cb_' . $row->id . '"
                type="checkbox" ' . $status . '><label for="cb_' . $row->id . '"></label>';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.faqs.edit', [$row->id]) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                    <button data-id="' . $row->id . '" class="btn btn-sm btn-danger delete_record"><i class="fa fa-trash"></i> Delete</button>';
                })
                ->removeColumn('id')
                ->rawColumns(['status', 'action'])->make(true);
        }
        $title = "Faq";
        return view('admin.faqs.index', compact('title'));
    }

    public function create()
    {
        $title = "Add Faq";
        $faqtype = FaqType::where([['deleted_at', NULL], ['status', 1]])->get();
        return view('admin.faqs.add', compact('title', 'faqtype'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'question' => 'required|',
            'answer' => 'required|',
            'sort_order' => 'required|numeric',
            'faq_type_id' => 'required|',
        ]);
        $data = new Faq;
        $data->sort_order = $request->sort_order;
        $data->question = $request->question;
        $data->answer = $request->answer;
        $data->faq_type_id = $request->faq_type_id;
        $data->status = 1;
        $data->save();

        $request->session()->flash('success', 'Faq Added Successfully!!');
        return redirect(route('admin.faqs.index'));
    }

    public function edit(Request $request, $id)
    {
        $title = "Edit Faq";
        $faqtype = FaqType::where([['deleted_at', NULL], ['status', 1]])->get();
        $data = Faq::where('id', $id)->first();
        if (!empty($data)) {
            return view('admin.faqs.edit', compact('title', 'data', 'faqtype'));
        } else {
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title', 'message'));
        }
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'question' => 'required|',
            'answer' => 'required|',
            'sort_order' => 'required|numeric',
            'faq_type_id' => 'required|',

        ]);
        $data = Faq::where('id', $id)->first();
        if ($data) {
            $data->sort_order = $request->sort_order;
            $data->question = $request->question;
            $data->answer = $request->answer;
            $data->faq_type_id = $request->faq_type_id;
            $data->save();

            $request->session()->flash('success', 'Faq Update Successfully!!');
            return redirect(route('admin.faqs.index'));
        } else {
            $request->session()->flash('error', 'Faq Does Not Exist!!');
            return redirect(route('admin.faqs.index'));
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Faq::where('id', $id)->delete();
        } else {
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = Faq::where('id', $request->id)->first();
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
        }
    }
}
