<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
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
        checkPermission($this, 111);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = Category::select('categories.id', 'pcat.name as parent_name', 'categories.name', 'categories.status')
                ->leftJoin('categories as pcat', 'pcat.id', '=', 'categories.parent_id')
                ->where([['categories.deleted_at', null]])->get();

            return DataTables::of($records)
                ->addColumn('status', function ($row) {
                    $status = ($row->status == 1) ? 'checked' : '';
                    return $statusBtn = '<input class="tgl_checkbox tgl-ios"
                data-id="' . $row->id . '"
                id="cb_' . $row->id . '"
                type="checkbox" ' . $status . '><label for="cb_' . $row->id . '"></label>';
                })
                ->addColumn('action', function ($row) {
                    return $action_btn = '<a href="' . url('admin/categories/' . $row->id . '/edit') . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                    <button data-id="' . $row->id . '" class="btn btn-sm btn-danger delete_record"><i class="fa fa-trash"></i> Delete</button>';
                })
                ->editColumn('parent_name', function ($row) {
                    if ($row->parent_name == '') {
                        $par = 'Parent';
                    } else {
                        $par = $row->parent_name;
                    }
                    return $par;
                })

                ->removeColumn('id')
                ->rawColumns(['status', 'image', 'action'])->make(true);
        }
        $title = "Category";
        $parent_categories = Category::select('*')->where(['parent_id' => 0, 'status' => 1, 'deleted_at' => null])->get()->toArray();
        return view('admin.category.index', compact('title', 'parent_categories'));
    }

    public function create()
    {
        $title = "Add Category";
        $parcat = Category::where(['parent_id' => 0, 'status' => 1])->pluck('name', 'id')->toArray();
        return view('admin.category.add', compact('title', 'parcat'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            //'name'            => 'required|max:100|unique:categories,name,NULL,id,deleted_at,NULL',
            'name'              => 'required|max:200',
            'parent_id'         => 'required',
            'sort_order'        => 'required|numeric|',
            'sort_description'  => 'required|max:5000',
            'image'             => 'mimes:png,jpg,jpeg,JPG,JPEG,png|max:2048',
        ]);

        $data                   = new Category;
        $data->slug             = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', trim(str_replace(' - ', '-', $request->name)))));
        $data->parent_id        = $request->parent_id;
        $data->name             = $request->name;
        $data->sort_order       = $request->sort_order ?? 0;
        $data->sort_description = $request->sort_description;
        $data->status           = 1;
        $path = 'uploads/category/';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if (!empty($ivalue['old_image'])) {
                delete_file($ivalue['old_image']);
            }
            $destinationPath    = 'public\\' . $path;
            $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
            Storage::disk('local')->put($destinationPath . $uploadImage, file_get_contents($file));
            $data->image = $path . $uploadImage;
        }
        $data->save();

        $request->session()->flash('success', 'Category Added Successfully!!');
        return redirect(url('admin/categories'));
    }

    public function edit(Request $request, $id)
    {
        $title = "Edit Category";
        $data = Category::where('id', $id)->first();
        $parcat = Category::where(['parent_id' => 0, 'status' => 1])->pluck('name', 'id')->toArray();
        if (!empty($data)) {
            return view('admin.category.edit', compact('title', 'data', 'parcat'));
        } else {
            $request->session()->flash('error', 'Category Not Found!!');
            return route('admin.categories');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            //'slug' => "max:30|unique:categories,slug,$id",
            //'name' => "required|max:100|unique:categories,name,".$id.",id,deleted_at,NULL",
            'name'              => 'required|max:200',
            'parent_id'         => 'required',
            'sort_order'        => 'required|numeric',
            'sort_description'  => 'required|max:5000',
            'image'             => 'mimes:png,jpg,jpeg,JPG,JPEG,png|max:2048',
        ]);

        $data = Category::where('id', $id)->first();

        if ($data) {
            $data->parent_id        = $request->parent_id;
            $data->name             = $request->name;
            $data->sort_order       = $request->sort_order ?? 0;
            $data->sort_description = $request->sort_description;
            $data->status           = 1;
            $path = 'uploads/category/';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if (!empty($ivalue['old_image'])) {
                    delete_file($ivalue['old_image']);
                }
                $destinationPath    = 'public\\' . $path;
                $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->put($destinationPath . $uploadImage, file_get_contents($file));
                $data->image = $path . $uploadImage;
            }
            $res = $data->save();

            $request->session()->flash('success', 'Category Update Successfully!!');
            return redirect(url('admin/categories'));
        } else {
            $request->session()->flash('error', 'Category Does Not Exist!!');
            return redirect(url('admin/categories'));
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Category::where('id', $id)->delete();
        } else {
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::where('id', $request->id)->first();
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
        }
    }
}
