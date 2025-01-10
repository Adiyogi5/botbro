<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CouponHistory;
use Illuminate\Support\Facades\DB;
use DateTime;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    public function getOrders()
    {
        return $this->belongsTo(self::class,'id');
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class,'order_status_id','id');
    }

    public function getProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function get_order_products($order_id){ 
        $orderData = Order::select('orders.*','order_products.product_id','order_products.unit_price','order_products.total_price', 'order_products.is_replace_apply', 'order_products.is_return_apply','product_name', 'model', 'quantity', 'tax_amount', 'subtotal', 'total')
        ->leftJoin('order_products', 'order_products.order_id', '=', 'orders.id')
        ->where('orders.id', $order_id)->get()->toArray();
        // prd($orderData);
        $product_data = [];
        foreach ($orderData as $order) {
           /// echo $order['product_id'];
            $product_query = Product::select('products.*')->with('getProductImage:product_id,attachment')
            ->where('products.id','=', $order['product_id'])->withTrashed()->get()->toArray();  
            $option_data = [];    

            foreach ($product_query as $pkey => $product) {
                $option_price = 0; 
                $master = [];
                $proimage = isset($product['get_product_image'])?$product['get_product_image']['attachment']:'';
                if(!empty($order['attdetails'])){
                  $master['sapid'] = $order['attsapid'];
                  $master['price'] = $order['attprice'];
                  $attdata = json_decode($order['attdetails'], true);                    
                  foreach ($attdata as $akey => $avalue) {
                    $master['attr'][] = array('attribute_master_id'=>$avalue['master_attr_id'], 'attribute_master_name'=>$avalue['master_attr_name'],'attribute_name'=>$avalue['attr_name'],'attribute_id'=>$avalue['attr_id']);
                    //$master['attr'] = array('mid'=>$avalue['master_attr_id'],'aid'=>$avalue['attr_id']);
                  }  
                }
               
               $proreturn = new Returns();               
               $return_detail =  $proreturn->get_product_return_detail($order['id'], $product['id'], $order['user_id']);
               
               $price = $order['subtotal'];               
               $product_data[] = array( 
                  'product_id'      => $product['id'],
                  'slug'            => $product['slug'],
                  'name'            => $order['product_name'],
                  'model'           => $order['model'],
                  'image'           => imageexist($proimage),
                  'rating'          => isset($order['rating'])?true:false,
                  'quantity'        => $order['quantity'],  
                  'unit_price'      => $order['unit_price'],
                  'total_price'     => $order['total_price'],
                  'tax_price'       => $order['tax_amount'],
                  'price'           => $order['subtotal'],
                  'total'           => $order['total'],
                  'return_detail'   => $return_detail,
                  'is_return_apply'   => $order['is_return_apply'],
                  'is_replace_apply'   => $order['is_replace_apply'],
               );  
            } 
         }
      return $product_data; 
    }

    public function get_order_history($order_id){ 
        $orderData = Order::select('order_histories.order_status_id as order_status_id','order_histories.comment as comment','order_status.name as order_status','order_histories.created_at')
        ->leftJoin('order_histories', 'order_histories.order_id', '=', 'orders.id')
        ->leftJoin('order_status', 'order_status.id', '=', 'order_histories.order_status_id')
        ->where('orders.id', $order_id)
        ->orderBy('order_histories.created_at', 'desc')
        ->get()->toArray(); 
        $product_data = [];
        foreach ($orderData as $key => $value) {
            $product_data[]=$value;
        }
        return $product_data; 
    }
}
