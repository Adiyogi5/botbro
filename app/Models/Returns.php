<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;

    public function get_return_history($return_id){ 
        $returnData = Returns::select('returns.return_status_id as return_status_id','returns.comment as comment','returns.created_at')
        ->where('returns.id', $return_id)->get()->toArray(); 
        $product_data = [];
        foreach ($returnData as $key => $value) {
            $product_data[]=$value;
        }
        return $product_data; 
    }


    public function get_product_return_detail($order_id, $product_id, $user_id){ 
        $returnData = Returns::select('returns.*') 
        ->where('returns.order_id', $order_id)->where('returns.product_id', $product_id)->where('returns.customer_id', $user_id)->first(); 
        /*$product_data = [];
        foreach ($returnData as $key => $value) {
            $product_data[]=$value;
        }*/
        return $returnData; 
    }
}
