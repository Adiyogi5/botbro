<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserMylist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyListController extends Controller
{
    public function index(Request $request)
    {

        $title = 'My List';
        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        $mylist_data = UserMylist::select('user_mylists.*', 'products.name', 'products.sort_description', 'product_images.image', 'product_prices.special_price')
            ->join('products', 'products.id', '=', 'user_mylists.product_id')
            ->join('product_images', 'product_images.product_id', '=', 'products.id')
            ->join('product_prices', 'product_prices.product_id', '=', 'products.id')
            ->Where('user_id', $user->id)
            ->groupBy('products.id')
            ->paginate(10);

        return view('frontend.dashboard.my_list', compact('title', 'mylist_data'));
    }


    public function edit(Request $request)
    {
        if($request->ajax())
        {
            $product_id = $request->product_id;
            $user_id = auth('web')->user()->id;

            $favorite_product = UserMylist::where('product_id',$product_id)->where('user_id',$user_id)->first();

            if(empty($favorite_product))
            {
                $fv_product = new UserMylist();
                $fv_product->product_id = $product_id;
                $fv_product->user_id = $user_id;
                $fv_product->save();
            }
            else
            {
                $favorite_product->delete();
            }
            
            return 1;
        }
    }


}

