<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class BannerController extends Controller
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
        checkPermission($this, 102);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = Banner::where('banner_type', 1)->get();

            return DataTables::of($records)
                ->editColumn('image', function ($row) {
                    return '<img src="' . imageexist($row->image, 'product_detail') . '" class="image logosmallimg">';
                })
                ->addColumn('status', function ($row) {
                    $status = ($row->status == 1) ? 'checked' : '';
                    return '<input class="tgl_checkbox tgl-ios" 
                    data-id="' . $row->id . '" 
                    id="cb_' . $row->id . '"
                    type="checkbox" ' . $status . '><label for="cb_' . $row->id . '"></label>';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.banner.edit', [$row->id]) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                    <button data-id="' . $row->id . '" class="btn btn-sm btn-danger delete_record"><i class="fa fa-trash"></i> Delete</button>';
                })
                ->removeColumn('id')
                ->rawColumns(['status', 'image', 'action'])->make(true);
        }
        $title = "Banners";
        return view('admin.banner.index', compact('title'));
    }

    public function create()
    {
        $title = "Add Banners";
        return view('admin.banner.add', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:banners,name',
            'title'         => 'required',
            'content'         => 'required',
            'url' => 'required',
            'sort_order' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = new Banner;
        $data->banner_type = 1;
        $data->name = $request->name;
        $data->title = $request->title;
        $data->content = $request->content;
        $data->url = $request->url;
        $data->sort_order = $request->sort_order;
        $data->status = 1;
        $banner_image = Null;
        $path = 'uploads/banners/';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if (!empty($ivalue['old_image'])) {
                delete_file($ivalue['old_image']);
            }
            $destinationPath    = 'public\\' . $path;
            $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
            Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
            $banner_image = $path . '/' . $uploadImage;
        }
        $data->image = $banner_image;
        $data->save();

        $request->session()->flash('success', 'Banner Added Successfully!!');
        return redirect(route('admin.banner.index'));
    }

    public function edit(Request $request, $id)
    {
        $title = "Edit Banners";
        $data = Banner::where('id', $id)->first();
        if (!empty($data)) {
            return view('admin.banner.edit', compact('title', 'data'));
        } else {
            $title = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title', 'message'));
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => "required|max:100|unique:banners,name,$id",
            'title'         => 'required',
            'content'         => 'required',
            'url'           => 'required',
            'sort_order'    => 'required',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = Banner::where('id', $id)->first();
        if ($data) {
            $data->banner_type = 1;
            $data->name         = $request->name;
            $data->title        = $request->title;
            $data->content      = $request->content;
            $data->url          = $request->url;
            $data->sort_order   = $request->sort_order;

            $banner_image       = $data->image;
            $path = 'uploads/banners/';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if (!empty($ivalue['old_image'])) {
                    delete_file($ivalue['old_image']);
                }
                $destinationPath    = 'public\\' . $path;
                $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
                $banner_image = $path . '/' . $uploadImage;
            }
            $data->image = $banner_image;
            $data->save();

            $request->session()->flash('success', 'Banner Update Successfully!!');
            return redirect(route('admin.banner.index'));
        } else {
            $request->session()->flash('error', 'Banner Does Not Exist!!');
            return redirect(route('admin.banner.index'));
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            Banner::where('id', $id)->delete();
        } else {
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = Banner::where('id', $request->id)->first();
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
        }
    }
}
