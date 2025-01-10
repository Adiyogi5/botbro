<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderProduct;
use App\Models\Returns;
use App\Models\AdminNotification;
use App\Traits\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Auth;

class OrderViewController extends Controller
{
    use Invoice;
    public function index(Request $request, $id)
    {
        $title = 'Order Detail';

        $user = auth('web')->user();

        $order_data = Order::select('orders.*', 'order_status.name as order_status_name', 'order_status.color as order_status_bg')->where('orders.id', $id)
            ->where('orders.user_id', $user->id)
            ->leftJoin('order_status', 'order_status.id', '=', 'orders.order_status_id')
            ->first();

        if (empty($order_data)) {
            return redirect('my-order')->with('error', "Order not found");
        }
        DB::statement("SET SQL_MODE = ''");
        $order_products = OrderProduct::select("order_products.*", 'product_images.attachment as product_image',DB::raw('CASE WHEN returns.return_type=1 and returns.id IS NOT NULL THEN 1 ELSE 0 END as is_product_return'),DB::raw('CASE WHEN returns.return_type=2 and returns.id IS NOT NULL THEN 1 ELSE 0 END as is_product_replace'))
            ->where('order_products.order_id', $id)
            ->leftJoin('product_images', 'product_images.product_id', '=', 'order_products.product_id')
            ->leftJoin('returns', function ($join) use ($id) {
                $join->on('returns.product_id', '=', 'order_products.product_id')
                    ->where('returns.order_id', '=', $id);
            })
            ->groupBy('order_products.id')
            ->get()->toArray();

        $orderHistory = OrderHistory::select('order_histories.*', 'order_status.name as status_name', 'order_status.color as status_color')
            ->where('order_id', $id)
            ->join('order_status', 'order_status.id', 'order_histories.order_status_id')
            ->get()->toArray();

        $forOrderReturn = OrderHistory::where('order_id', $id)
        ->where('order_status_id', 5)
        ->first();
        
        return view('frontend.dashboard.order_view', compact('title', 'order_data', 'order_products', 'orderHistory','forOrderReturn'));
    }

    public function return_product(Request $request){
        if($request->ajax()){
            $product_id = $request->product_id;
            $order_id = $request->order_id;
            $type = $request->type;

            $orderProduct = OrderProduct::join('orders','orders.id','=','order_products.order_id')->where([['order_id',$order_id],['product_id',$product_id]])->first();

            $msg = [];
            if($orderProduct){
                $last_return_date = date('Y-m-d',strtotime($orderProduct->order_return_expiredate));
                $today = date('Y-m-d');
                $protype = ($type==1)?'Return':'Replace';

                if(strtotime($today) <= strtotime($last_return_date)){
                    $data = new Returns;
                    $data->order_id = $orderProduct->order_id;
                    $data->order_no = $orderProduct->order_no;
                    $data->product_id = $orderProduct->product_id;
                    $data->product_name = $orderProduct->product_name;
                    $data->product_model = $orderProduct->model;
                    $data->customer_id = $orderProduct->user_id;
                    $data->customer_name = $orderProduct->customer_name;
                    $data->customer_email = $orderProduct->customer_email;
                    $data->customer_mobile = $orderProduct->customer_mobile;
                    $data->return_type = $type;
                    $data->return_status_id = 1;
                    $data->comment = 'Product '.$protype.' Request Submited By '.Auth::guard('web')->user()->name.'.';
                    $data->date_ordered = $orderProduct->date;
                    $data->created_at = date('Y-m-d H:i:s');
                    $data->updated_at = date('Y-m-d H:i:s');
                    $data->save();

                    
                   if($type==1){
                        OrderProduct::where([['product_id',$orderProduct->product_id],['order_id',$orderProduct->order_id]])->update(['is_return_apply' => 1]);
                    }else{
                        OrderProduct::where([['product_id',$orderProduct->product_id],['order_id',$orderProduct->order_id]])->update(['is_replace_apply' => 1]);
                    }

                    /// Send Notification to Admin
                    $adnoti = new AdminNotification();  
                    if($type==1){              
                        $adnoti->title = "Get New Return Request";
                        $adnoti->message = "Get new return request from ".Auth::guard('web')->user()->name.". on Product ".$orderProduct->product_name." of Order no: ".$orderProduct->order_no."";
                        $adnoti->notification_type = 3;
                    }else{
                        $adnoti->title = "Get New Replace Request";
                        $adnoti->message = "Get new replace request from ".Auth::guard('web')->user()->name.". on Product ".$orderProduct->product_name." of Order no: ".$orderProduct->order_no."";
                        $adnoti->notification_type = 4;
                    }                    
                    $adnoti->is_read = 0;
                    $adnoti->created_at = now()->format('Y-m-d H:i:s');
                    $adnoti->updated_at = now()->format('Y-m-d H:i:s');
                    $adnoti->save();

                    $msg = array('status'=>true,'message'=>'Product return request submited!!');
                    return json_encode($msg);
                }else{

                    $msg = array('status'=>false,'message'=>'Return Date Expired!!');
                    return json_encode($msg);
                }
            }else{
                $msg = array('status'=>false,'message'=>'Selected product not mached!!');
                return json_encode($msg);
            }
        }

    }
}
