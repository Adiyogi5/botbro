<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductToCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;


class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        checkPermission($this, 112);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $records = Product::all();

            return DataTables::of($records)
                ->addColumn('status', function ($row) {
                    $status = ($row->status == 1) ? 'checked' : '';
                    return $statusBtn = '<input class="tgl_checkbox tgl-ios" data-id="' . $row->id . '" id="cb_' . $row->id . '" type="checkbox" ' . $status . '><label for="cb_' . $row->id . '"></label>';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.products.edit', [$row->id]) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                    <button data-id="' . $row->id . '" class="btn btn-sm btn-danger delete_record"><i class="fa fa-trash"></i> Delete</button>';
                })
                ->removeColumn('id')
                ->rawColumns(['status', 'action'])->make(true);
        }
        $title = 'Products';
        return view('admin.products.index', compact('title'));
    }

    public function create()
    {
        $title = "Add Product";
        $product_categories = Category::where(['status' => 1, 'deleted_at' => null])->get();

        return view('admin.products.add', compact('title', 'product_categories'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name'              => 'required|max:250|unique:products,name',
            'model'             => 'required|max:250',
            'price'             => 'required|numeric',
            'stock_quantity'    => 'required|numeric', 
            'sort_order'        => 'required|numeric',
            'product_image'     => 'required|image|mimes:jpg,png,jpeg|max:500',
            'description'       => 'required|max:5000',
            'product_category'  => 'required',
            'referral_price'    => 'required|numeric',
            'refer_price'       => 'required|numeric',
            'image.*.media'     => 'required_if: old_media, Null|image|mimes:jpg,png,jpeg|max:500',
        ], [
            'image'                     => 'The image must be a file of type: JPG, JPEG, PNG.',
            'image.*.media.required_if' => 'The image is required',
            'image.*.media.mimes'       => 'The image must be a file of type: JPG, JPEG, PNG.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please check all errors carefully!!');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));
        $product_data = [
            'name'              => $request->name,
            'slug'              => $slug,
            'model'             => $request->model,
            'price'             => $request->price,
            'stock'             => $request->stock_quantity,
            'referral_price'    => $request->referral_price,
            'refer_price'       => $request->refer_price,
            'sort_order'        => $request->sort_order,
            'description'       => $request->description,
            'status'            => 1,
        ];

        $path = 'uploads/products/';
        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            if (!empty($ivalue['old_product_image'])) {
                delete_file($ivalue['old_product_image']);
            }
            $destinationPath    = 'public\\' . $path;
            $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
            Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
            $product_data['image'] = $path . $uploadImage;
        }

        $product = Product::create($product_data);

        if (!empty($request->image) || !empty($request->video)) {
            $i = 0;
            foreach (config('constant.media_types') as $m_key => $m_value) {
                $request[$m_key] = !empty($request[$m_key]) ? $request[$m_key] : [];
                foreach ($request[$m_key] as $ikey => $ivalue) {
                    $media[$ikey] = [
                        'product_id'    => $product->id,
                        'sort_order'    => $ivalue['sort_order'],
                    ];
                    $path = 'uploads/products/' . $m_key;
                    if ($request->hasFile($m_key . '.' . $ikey . '.media')) {
                        $file = $request->file($m_key . '.' . $ikey . '.media');
                        if (!empty($ivalue['old_media'])) {
                            delete_file($ivalue['old_media']);
                        }
                        $destinationPath    = 'public\\' . $path;
                        $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
                        Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
                        $media[$ikey]['attachment'] = $path . '/' . $uploadImage;
                    } else {
                        $media[$ikey]['attachment'] = $ivalue['old_media'];
                    }
                    $i++;
                }
            }
            ProductImage::where('product_id', $product->id)->delete();
            ProductImage::insert($media);
        }

        if (!empty($request->product_category)) {
            ProductToCategory::where('product_id', $product->id)->delete();
            foreach (explode(',', $request->product_category) as $key => $value) {
                ProductToCategory::create([
                    'product_id'    => $product->id,
                    'category_id'   => $value,
                ]);
            }
        }

        $request->session()->flash('success', 'Product Added Successfully!!');
        return redirect(route('admin.products.index'));
    }

    public function edit(Request $request, $id)
    {
        $product = Product::with(['product_image', 'productCategory'])->where('id', $id)->first();
        
        if (empty($product)) {
            $request->session()->flash('error', 'Product Does Not Exist!!');
            return redirect(route('admin.products.index'));
        }

        $title = "Edit products";
        $product_categories = Category::where(['status' => 1, 'deleted_at' => null])->get();
        return view('admin.products.edit', compact('title', 'product', 'product_categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('id', $id)->first();
        if (empty($product)) {
            $request->session()->flash('error', 'Product Does Not Exist!!');
            return redirect(route('admin.products.index'));
        }

        $validator = Validator::make($request->all(),[
            'name'              => 'required|max:250|unique:products,name,'.$product->id,
            'model'             => 'required|max:250',
            'price'             => 'required|numeric',
            'stock_quantity'    => 'required|numeric', 
            'sort_order'        => 'required|numeric',
            'product_image'     => 'nullable|image|mimes:jpg,png,jpeg|max:500',
            'description'       => 'required|max:5000',
            'product_category'  => 'required',
            'referral_price'    => 'required|numeric',
            'refer_price'       => 'required|numeric',
            'image.*.media'     => 'required_if: old_media, Null|image|mimes:jpg,png,jpeg|max:500',
        ], [
            'image'                     => 'The image must be a file of type: JPG, JPEG, PNG.',
            'image.*.media.required_if' => 'The image is required',
            'image.*.media.mimes'       => 'The image must be a file of type: JPG, JPEG, PNG.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please check all errors carefully!!');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));
        $product_data = [
            'name'              => $request->name,
            'slug'              => $slug,
            'model'             => $request->model,
            'price'             => $request->price,
            'stock'             => $request->stock_quantity, 
            'sort_order'        => $request->sort_order,
            'referral_price'    => $request->referral_price, 
            'refer_price'       => $request->refer_price, 
            'description'       => $request->description,
            'status'            => 1,
        ];

        $path = 'uploads/products/';
        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            if (!empty($ivalue['old_product_image'])) {
                delete_file($ivalue['old_product_image']);
            }
            $destinationPath    = 'public\\' . $path;
            $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
            Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
            $product_data['image'] = $path . '/' . $uploadImage;
        }

        $product->update($product_data);

        if (!empty($request->image) || !empty($request->video)) {
            $i = 0;
            foreach (config('constant.media_types') as $m_key => $m_value) {
                $request[$m_key] = !empty($request[$m_key]) ? $request[$m_key] : [];
                foreach ($request[$m_key] as $ikey => $ivalue) {
                    $media[$ikey] = [
                        'product_id'    => $product->id,
                        'sort_order'    => $ivalue['sort_order'],
                    ];
                    
                    $path = 'uploads/products/' . $m_key;
                    if ($request->hasFile($m_key . '.' . $ikey . '.media')) {
                        $file = $request->file($m_key . '.' . $ikey . '.media');
                        if (!empty($ivalue['old_media'])) {
                            delete_file($ivalue['old_media']);
                        }
                        $destinationPath    = 'public\\' . $path;
                        $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
                        Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
                        $media[$ikey]['attachment'] = $path . '/' . $uploadImage;
                    } else {
                        $media[$ikey]['attachment'] = $ivalue['old_media'] ?? '';
                    }
                    $i++;
                }
            }
            ProductImage::where('product_id', $product->id)->delete();
            ProductImage::insert($media);
        }

        if (!empty($request->product_category)) {
            ProductToCategory::where('product_id', $product->id)->delete();
            foreach (explode(',', $request->product_category) as $key => $value) {
                ProductToCategory::create([
                    'product_id'    => $product->id,
                    'category_id'   => $value,
                ]);
            }
        }

        $request->session()->flash('success', 'Product Updated Successfully!!');
        return redirect(route('admin.products.index'));
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Product::where('id', $id)->delete();
        } else {
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::where('id', $request->id)->first();
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
        }
    }

    public function get_sub_categories(Request $request)
    {
        $cate1_id = $request->cat1_id;
        $cat2_id = $request->cat2_id;

        $level2_categories = Category::select('*')->where(['parent_id' => $cate1_id, 'status' => 1, 'deleted_at' => null])->get();

        $data = '<option value="">Select Category</option>';
        foreach ($level2_categories as $key => $value) {

            $selected = $value->id == $cat2_id ? 'selected' : '';

            $data .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->name . '</option>';
        }

        $res = ['status' => true, 'data' => $data];
        return response($res);
    }
}
