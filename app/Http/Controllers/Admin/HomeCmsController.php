<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Validator;
use App\Models\HomeCms;
use Datatables;
use Illuminate\Support\Facades\Storage;

class HomeCmsController extends Controller
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
            $records = HomeCms::select('id', 'name', 'cms_title', 'meta_title', 'status')->get();

            return Datatables::of($records)
                ->addColumn('status', function ($row) {
                    $status = ($row->status == 1) ? 'checked' : '';
                    return $statusBtn = '<input class="tgl_checkbox tgl-ios" 
                data-id="' . $row->id . '" 
                id="cb_' . $row->id . '"
                type="checkbox" ' . $status . '><label for="cb_' . $row->id . '"></label>';
                })
                ->addColumn('action', function ($row) {
                    return $action_btn = '<a href="' . url('admin/homecms/' . $row->id . '/edit') . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                })
                ->removeColumn('id')
                ->rawColumns(['status', 'action'])->make(true);
        }
        $title = "Home Cms";
        return view('admin.homecms.index', compact('title'));
    }


    public function edit(Request $request, $id)
    {
        $title = "Edit Home Cms";
        $data = HomeCms::where('id', $id)->first();
        if (!empty($data)) {
            return view('admin.homecms.edit', compact('title', 'data'));
        } else {
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title', 'message'));
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $validate = $request->validate([
            'name' => "required|max:100|unique:home_cms,name,$id",
            'cms_title' => 'required',
            'meta_title'  => 'required|max:100|',
            'meta_keyword'  => 'required|max:100|',
            'meta_description'  => 'required',
            'cms_contant'  => 'required',
            'url'  => "required",
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = HomeCms::where('id', $id)->first();
        if ($data) {
            $data->name = $request->name;
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', trim($request->name))));
            $data->cms_title = $request->cms_title;
            $data->meta_title = $request->meta_title;
            $data->meta_keyword = $request->meta_keyword;
            $data->meta_description = $request->meta_description;
            $data->cms_contant = $request->cms_contant;
            $data->slug = $slug;
            $data->url = $request->url;
            $cms_image = $data->image;
            $path = 'uploads/homecms/';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if (!empty($ivalue['old_image'])) {
                    delete_file($ivalue['old_image']);
                }
                $destinationPath    = 'public\\' . $path;
                $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
                $cms_image = $path . '/' . $uploadImage;
            }
            $data->image = $cms_image;
            $data->save();

            $request->session()->flash('success', 'Home Cms Update Successfully!!');
            return redirect(url('admin/homecms'));
        } else {
            $request->session()->flash('error', 'Home Cms Does Not Exist!!');
            return redirect(url('admin/homecms'));
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = HomeCms::where('id', $id)->delete();
        } else {
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = HomeCms::where('id', $request->id)->first();
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
        }
    }
}
