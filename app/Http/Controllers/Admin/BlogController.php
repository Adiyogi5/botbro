<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class BlogController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        checkPermission($this, 110);
    }

    public function index(Request $request)
    {
        $blog = Blog::first();

        if ($request->ajax()) {
            $data = Blog::select('id', 'title', 'image', 'post_by', 'status')->where('deleted_at', null)->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('image', function ($data) {
                    return '<a href="' . $data->image . '" target="_blank"><img src="' . imageexist($data->image) . '" alt="Image Not Found" class="rounded-circle" style="width: 40px; height: 40px" /></a>';
                })
                ->addColumn('status', function ($data) {
                    $status = ($data->status == 1) ? 'checked' : '';
                    return '<input class="tgl_checkbox tgl-ios" 
                data-id="' . $data->id . '" 
                id="cb_' . $data->id . '"
                type="checkbox" ' . $status . '><label for="cb_' . $data->id . '"></label>';
                })
                ->addColumn('action', function ($data) {
                    return '<a href="' . url('admin/blog/' . $data->id . '/edit') . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                    <button data-id="' . $data->id . '" class="btn btn-sm btn-danger delete_record"><i class="fa fa-trash"></i></button>';
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }

        $title = "Blogs";
        return view('admin.blogs.index', compact('title'));
    }

    public function create(Request $request)
    {
        $title = "Add Blog";
        $data['level1_categories'] = BlogCategory::where(['status' => 1, 'deleted_at' => null])->get();
        return view('admin.blogs.add', compact('title'), $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'             => 'required|unique:blogs,title',
            'post_by'           => 'required',
            'sort_order'        => 'required',
            'image'             => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
            'sort_description'  => 'required',
            'description'       => 'required',
            'meta_title'  => 'required|max:100|',
            'meta_keyword'  => 'required|max:100|',
            'meta_description'  => 'required',
        ]);

        $blog_image = Null;
        $path = 'uploads/blogs/';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if (!empty($ivalue['old_image'])) {
                delete_file($ivalue['old_image']);
            }
            $destinationPath    = 'public\\' . $path;
            $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
            Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
            $blog_image = $path . '/' . $uploadImage;
        }

        $data = new Blog;
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->title)));
        $data->category_id      = $request->category_1;
        $data->title            = $request->title;
        $data->meta_title       = $request->meta_title;
        $data->meta_keyword     = $request->meta_keyword;
        $data->meta_description = $request->meta_description;
        $data->slug             = $slug;
        $data->image            = $blog_image;
        $data->sort_description = $request->sort_description;
        $data->description      = $request->description;
        $data->post_by          = $request->post_by;
        $data->sort_order       = $request->sort_order;
        $data->save();

        $request->session()->flash('success', 'Blog Added Successfully!!');
        return redirect(url('admin/blog'));
    }

    public function edit(Request $request, $id)
    {
        $title = 'Edit Blog';
        // $data['level1_categories'] = BlogCategory::where(['status' => 1, 'deleted_at' => null])->get();
        $data = Blog::where('id', $id)->first();

        $parcat = BlogCategory::where(['status' => 1, 'deleted_at' => null])->get();



        if (!empty($data)) {
            return view('admin.blogs.edit', compact('title', 'data', 'parcat'));
        } else {
            $title = '404 Error Page';
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i>Oops! Page Not Found!!';
            return view('admin.error', compact('title', 'message'));
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'             => "required|unique:blogs,title," . $id,
            'post_by'           => 'required',
            'sort_order'        => 'required',
            'sort_description'  => 'required',
            'description'       => 'required',
            'meta_title'  => 'required|max:100|',
            'meta_keyword'  => 'required|max:100|',
            'meta_description'  => 'required',
        ]);

        $data = Blog::where('id', $id)->first();
        if ($data) {
            // $blog_image = '';
            $blog_image = $data->image;
            $path = 'uploads/blogs/';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if (!empty($ivalue['old_image'])) {
                    delete_file($ivalue['old_image']);
                }
                $destinationPath    = 'public\\' . $path;
                $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
                $blog_image = $path . '/' . $uploadImage;
            }
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', trim($request->title))));
            $data->category_id      = $request->category_1;
            $data->title            = $request->title;
            $data->meta_title       = $request->meta_title;
            $data->meta_keyword     = $request->meta_keyword;
            $data->meta_description = $request->meta_description;
            $data->slug             = $slug;
            $data->sort_description = $request->sort_description;
            $data->description      = $request->description;
            $data->post_by          = $request->post_by;
            $data->sort_order       = $request->sort_order;
            $data->image = $blog_image;
            $data->save();

            $request->session()->flash('success', 'Blog Updated Successfully!!');
            return redirect(url('admin/blog'));
        } else {
            $request->session()->flash('error', 'Blog Does Not Exist!!');
            return redirect(url('admin/blog'));
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = Blog::where('id', $request->id)->first();
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Blog::where('id', $id)->delete();
        } else {
            return 0;
        }
    }
}
