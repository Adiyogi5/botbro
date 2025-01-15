<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Validator;
use App\Models\Testimonial;
use Datatables;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
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
        checkPermission($this, 114);
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = Testimonial::select('id', 'name', 'designation', 'image', 'status')->where([['deleted_at', NULL]])->get();

            return Datatables::of($records)
                ->addColumn('status', function ($row) {
                    $status = ($row->status == 1) ? 'checked' : '';
                    return $statusBtn = '<input class="tgl_checkbox tgl-ios" 
                data-id="' . $row->id . '" 
                id="cb_' . $row->id . '"
                type="checkbox" ' . $status . '><label for="cb_' . $row->id . '"></label>';
                })
                ->addColumn('action', function ($row) {
                    return $action_btn = '<a href="' . url('admin/testimonials/' . $row->id . '/edit') . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                <button data-id="' . $row->id . '" class="btn btn-sm btn-danger delete_record"><i class="fa fa-trash"></i> Delete</button>';
                })
                ->editColumn('image', function ($row) {
                    return '<img src="' . imageexist($row->image) . '" class="image" style="height:60px;width:60px">';
                })
                ->removeColumn('id')
                ->rawColumns(['status', 'image', 'action'])->make(true);
        }
        $title = "Testimonials";
        return view('admin.testimonials.index', compact('title'));
    }


    public function create()
    {
        $title = "Add Testimonial";
        return view('admin.testimonials.add', compact('title'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|max:150|unique:testimonials,name',
            'designation' => 'required',
            'message'  => 'required|',
            'sort_order'  => 'required|numeric|',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = new Testimonial;
        $data->name = $request->name;
        $data->designation = $request->designation;
        $data->message = $request->message;
        $data->sort_order = $request->sort_order;
        $Testimonial_image = $data->image;
        $path = 'uploads/testimonials/';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if (!empty($ivalue['old_image'])) {
                delete_file($ivalue['old_image']);
            }
            $destinationPath    = 'public\\' . $path;
            $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
            Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
            $Testimonial_image = $path . '/' . $uploadImage;
        }
        $data->image = $Testimonial_image;
        $data->save();


        $request->session()->flash('success', 'Testimonial Added Successfully!!');
        return redirect(url('admin/testimonials'));
    }



    public function edit(Request $request, $id)
    {
        $title = "Edit Testimonial";
        $data = Testimonial::where('id', $id)->first();
        if (!empty($data)) {
            return view('admin.testimonials.edit', compact('title', 'data'));
        } else {
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title', 'message'));
        }
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'name' => "required|max:150|unique:testimonials,name,$id",
            'designation' => 'required',
            'message'  => 'required|',
            'sort_order'  => 'required|numeric|',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = Testimonial::where('id', $id)->first();
        if ($data) {
           
            $Testimonial_image = $data->image;
            $path = 'uploads/testimonials/';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if (!empty($ivalue['old_image'])) {
                    delete_file($ivalue['old_image']);
                }
                $destinationPath    = 'public\\' . $path;
                $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
                $Testimonial_image = $path . '/' . $uploadImage;
            }
            $data->image = $Testimonial_image;
            $data->name = $request->name;
            $data->designation = $request->designation;
            $data->message = $request->message;
            $data->sort_order = $request->sort_order;

            $data->save();

            $request->session()->flash('success', 'Testimonial Update Successfully!!');
            return redirect(url('admin/testimonials'));
        } else {
            $request->session()->flash('error', 'Testimonial Does Not Exist!!');
            return redirect(url('admin/testimonials'));
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Testimonial::where('id', $id)->delete();
        } else {
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = Testimonial::where('id', $request->id)->first();
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
        }
    }
}
