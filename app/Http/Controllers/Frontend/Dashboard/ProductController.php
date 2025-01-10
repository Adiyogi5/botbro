<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request, $slug = '')
    {
        $title = 'Products';
        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        if ($slug) {
            $query = Product::select('products.*', 'categories.slug', 'products.slug')
                ->leftjoin('product_to_categories', 'products.id', '=', 'product_to_categories.product_id')
                ->leftjoin('categories', 'categories.id', '=', 'product_to_categories.category_id')
                ->where(array('products.status' => 1, 'categories.slug' => $slug));
        } else {
            $query = Product::select('products.*')->where('status', 1);
        }

        $CategoriesData = [];

        $catdata = Category::select('id', 'name', 'slug', 'image')->where(['status' => 1, 'deleted_at' => null, 'parent_id' => 0])->orderBy('sort_order', 'ASC')->get()->toArray();

        foreach ($catdata as $category) {
            /* 1 Level Sub Categories START */
            $children_data = array();
            $children = Category::where(['status' => 1, 'deleted_at' => null, 'parent_id' => $category['id']])->orderBy('sort_order', 'ASC')->get()->toArray();

            foreach ($children as $child) {
                /* 2 Level Sub Categories START */
                $childs_data = array();
                $child_2 = Category::where(['status' => 1, 'deleted_at' => null, 'parent_id' => $child['id']])->orderBy('sort_order', 'ASC')->get()->toArray();

                foreach ($child_2 as $childs) {
                    $childs_data[] = array(
                        'id' => $childs['id'],
                        'slug' => $childs['slug'],
                        'name' => $childs['name'],
                        'image' => imageexist($childs['image']),
                    );
                }
                /* 2 Level Sub Categories END */
                $children_data_2 = array();
                $children_2 = Category::where(['status' => 1, 'deleted_at' => null, 'parent_id' => $child['id']])->orderBy('sort_order', 'ASC')->get()->toArray();
                foreach ($children_2 as $child_2) {
                    $children_data_2[] = array(
                        'id' => $child_2['id'],
                        'slug' => $child_2['slug'],
                        'name' => $child_2['name'],
                        'image' => imageexist($child_2['image']),
                    );
                }
                $children_data[] = array(
                    'id' => $child['id'],
                    'slug' => $child['slug'],
                    'name' => $child['name'],
                    'image' => imageexist($child['image']),
                    'children' => $children_data_2,
                );
            }

            $CategoriesData[] = array(
                'id' => $category['id'],
                'slug' => $category['slug'],
                'name' => $category['name'],
                'image' => imageexist($category['image']),
                'children' => $children_data,

            );
        }

        if ($request->filled('sort')) {
            $query->orderBy('price', $request->input('sort'));
        }

        $productsData = $query->paginate(12);

        return view('frontend.dashboard.products', compact('title', 'productsData', 'slug', 'CategoriesData'));
    }

    public function productdetail($slug = '')
    {
        $title = "Product Details";

        $productdetails = Product::select('products.*')
            ->with('product_image')
            ->where('products.slug', $slug)
            ->where('products.status', '1')
            ->get();

        $data = Product::where('slug', $slug)->first();

        $catdata = Category::select('categories.*', 'categories.slug as catslug', 'products.slug')
            ->leftjoin('product_to_categories', 'categories.id', '=', 'product_to_categories.category_id')
            ->leftjoin('products', 'products.id', '=', 'product_to_categories.product_id')
            ->where(array('products.status' => 1))
            ->get();

        $firstCategorySlug = $catdata->isEmpty() ? null : $catdata->first()->catslug;

        if ($slug && $firstCategorySlug) {
            $query = Product::select('products.*', 'categories.slug as catslug', 'products.slug')
                ->leftJoin('product_to_categories', 'products.id', '=', 'product_to_categories.product_id')
                ->leftJoin('categories', 'categories.id', '=', 'product_to_categories.category_id')
                ->where('products.slug', '!=',$slug)
                ->where('categories.slug', $firstCategorySlug);
        } else {
            $query = Product::select('products.*')->where('status', 1);
        }

        $relatedproductsData = $query->get();

        return view('frontend.dashboard.productdetails', compact('title', 'productdetails', 'slug', 'data', 'relatedproductsData'));
    }
}
