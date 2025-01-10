<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use App\Exports\OrderExport;
use App\Models\Category;
use App\Models\Country;
use App\Models\GeneralSetting;
use App\Models\OrderStatus;
use App\Models\User;
use App\Models\UserWallet;
use App\Mail\OrderstatusMail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
use File;
use Illuminate\Support\Facades\Auth;
use Spatie\FlareClient\Api;
use Mail;

class OrderController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth:admin','mail']);
        checkPermission($this, 102);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $order_date     = $request->order_date ?? null;
            $order_status   = $request->order_status ?? null;
            $order_paystatus   = $request->order_paystatus ?? null;

            $records = Order::select('orders.id', 'orders.order_no', 'orders.customer_name', 'discount', 'subtotal', 'shipping_amount', 'order_status.name as order_status_name', 'orders.total', 'orders.date', 'orders.created_at', 'orders.updated_at','payment_type','payment_status','order_status_id')
                ->join('order_status', 'order_status.id', 'orders.order_status_id')
                ->where('orders.deleted_at', null);

            if (!empty($order_date)) {
                $records = $records->whereDate('orders.date', '=', date('Y-m-d', strtotime($order_date)));
            }
            if (!empty($order_status)) {
                $records = $records->where('orders.order_status_id', '=', $order_status);
            }

            if (!empty($order_paystatus)) {
                if($order_paystatus==1){
                    $records = $records->where('orders.payment_type', '=', 0);
                }elseif($order_paystatus==2){
                    $records = $records->where('orders.payment_type', '=', 1)->where('orders.payment_status', '=', 1);
                }elseif($order_paystatus==3){
                    $records = $records->where('orders.payment_type', '=', 1)->where('orders.payment_status', '=', 0);
                }
            }

            return Datatables::of($records)
                ->addColumn('ordercheck', function ($row) {
                    return "<input type='checkbox' class='multicheck' value='" . $row->id . "'>";
                })
                ->editColumn('total', function ($row) {
                    return " " . $row->total;
                })
                ->editColumn('order_no', function ($row) {
                    $order_no = $row->order_no;
                    if($row->order_status_id == 6){
                        $order_no = "<b style='color:#FF0000'>".$row->order_no."</b>";
                    }else if($row->payment_type ==0){
                        $order_no = "<b style='color:#0066CC'>".$row->order_no."</b>";
                    }else{
                        if($row->payment_status ==0){
                            $order_no = "<b style='color:#FF6600'>".$row->order_no."</b>";
                        }else{
                           $order_no = "<b style='color:#009900'>".$row->order_no."</b>";
                        } 
                    }
                    return $order_no;
                })
                ->editColumn('created_at', function ($row) { 
                    return [
                        'display' => Carbon::parse($row->created_at)->format('d/m/Y h:i a'),
                        'timestamp' => $row->created_at->timestamp
                    ];
                })
                ->editColumn('updated_at', function ($row) {
                    return date('d-m-Y', strtotime($row->updated_at));
                })
                ->editColumn('order_status_name', function ($row) {
                    if($row->payment_type ==0){
                        $sname = 'COD';
                    }else{ 
                        $sname = '<b>Online Payment</b>:';
                        if($row->payment_status ==0){
                            $sname.='Unpaid';
                        }else{
                            $sname .='Paid';
                        } 
                    }
                    return $row->order_status_name.'<br>'.$sname;
                })                
                ->addColumn('action', function ($row) {
                    return $action_btn = '<a href="' . url('admin/orders/' . $row->id) . '" class="btn btn-sm btn-secondary" title="View"><i class="fa fa-eye"></i></a> &nbsp <a href="' . url('admin/orders/invoice/' . $row->id) . '" class="btn btn-sm btn-warning" target="_blank" title="Invoice"><i class="fa fa-print"></i></a>';
                })
                ->rawColumns(['order_no','ordercheck','order_status_name', 'action'])->make(true);
        };

        $title = "Orders";
        $order_status = OrderStatus::all();
        return view('admin.order.index', compact('title', 'order_status'));
    }

    public function show(Request $request, $id)
    {
        $data = Order::select('orders.*', 'order_status.name as order_status_name')->join('order_status', 'order_status.id', 'order_status_id')->where(['orders.id' => $id, 'orders.deleted_at' => null])->first();
        $nproduct = new Order();
        $order_products = $nproduct->get_order_products($id);
        $order_histories = $nproduct->get_order_history($id);
        
        $orderstatus = OrderStatus::select('id', 'name')->whereNotIn('id', [1, 6])->get();

        $title = "Orders";
        return view('admin.order.view', compact('title', 'data', 'order_products', 'order_histories', 'orderstatus'));
    }

    public function create(Request $request)
    {
        $countries = Country::where([['status', 1], ['deleted_at', null]])->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $category = new Category();
        $categories = $category->getparentCat(1);
        $customers = User::where(['status' => 1, 'deleted_at' => null])->get();
        $order_status = OrderStatus::all();
        $title = 'Add Order';
        $promocode = [];
        // $promocode = Coupon::select('id', 'name', 'code', 'type', 'discount', 'date_start', 'date_end', 'uses_total')->where(['status' => 1, 'deleted_at' => null])->orderBy('created_at', 'desc')->get();
        /// delete admin cart if page refresh 
        //DB::table('admin_carts')->delete();
        //DB::table('admin_cart_coupons')->delete();

        foreach ($promocode as $key => $value) {
            if ($value->type == 0) {
                $promocode[$key]['type'] = 'flat';
            } else if ($value->type == 1) {
                $promocode[$key]['type'] = '%';
            }
        }
        $curdate = date('Y-m-d');
        $Coupon = [];
        foreach ($promocode as $key => $value) {
            $Coupon[$key] = $value;
            $Coupon[$key]['isexpire'] = false;
            if (strtotime($curdate) > strtotime($value['date_end'])) {
                $Coupon[$key]['isexpire'] = true;
            }
        }

        return view('admin.order.add', compact('title', 'customers', 'order_status', 'categories', 'countries', 'Coupon'));
    }


    public function updOrderStatus(Request $request, $order_id)
    {
        if ($request->ajax()) {
            
            $order = Order::where(['id' => $order_id, 'deleted_at' => null])->first();
            if ($order->order_status_id < $request->status_id) { 
                $order->order_status_id = $request->status_id;
                $order->save();

                $order_history = new OrderHistory;
                $order_history->order_id = $order->id;
                $order_history->order_status_id = $request->status_id;
                $order_history->comment = $request->order_comment;
                $order_history->save();
                $pakmessage = [];
                
                if ($request->status_id == 5) {
                    $date = date('Y-m-d');
                    $expiredate = date('Y-m-d', strtotime('+'.$this->general_settings['return_days'].' day', strtotime($date)));
                    Order::where('id', $order->id)->update(['order_return_expiredate' => $expiredate]);    
                }                
                if ($request->status_id == 6) {
                    if ($order->payment_type == 1 && $order->payment_status == 1) { 

                        $user_data = User::where('id',$order->user_id)->first();
                        if ($user_data) {
                            $newBalance = $user_data->user_balance + $order->total;
                            User::where('id', $order->user_id)->update(['user_balance' => $newBalance]);
                            UserWallet::create([
                                'user_id' => $order->user_id,
                                'date' => date('Y-m-d H:i:s'),
                                'particulars' => 'The order no of '.$order->order_no.' is canceled by admin',
                                'payment_type' => 1,
                                'order_id' => $order->id,
                                'amount' => $order->total,
                                'current_balance' => $user_data->user_balance,
                                'updated_balance' => $newBalance,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);
                        }
                    }                   
                }
                
                $orderstatus = OrderStatus::where(['id' => $request->status_id])->first();
                // Send Order status mail to client
                if ($request->status_id == '2') {
                    $eid = 'Confirm Successfully. Thank you for your purchase!!';
                } elseif ($request->status_id == '3') {
                    $eid = 'Confirm Successfully. Thank you for your purchase!!';
                } elseif ($request->status_id == '4') {
                    $eid = 'Dispatch successfully and will be delivered soon!!';
                } elseif ($request->status_id == '5') {
                    $eid = 'Delivered Successfully. Thanks for your purchase!!';
                } elseif ($request->status_id == '6') {
                    $eid = 'Canceled and your order amount will be transfer to your Upayliving wallet!!';
                }
                
                $attachment['subject'] = 'Order status has been updated on Upayliving'; 
                $attachment['mailmessage'] = 'Dear '.$order->customer_name.', 
                
                    Your order no. '.$order->order_no.' has been '.$eid.'.
                 
                Thanks, 
                Upayliving';   

                Mail::to($order->customer_email)->send(new OrderstatusMail($attachment));
 
                $msg = "Order status has been updated successfully!!";
               
                $return['msg'] = $msg;
                return json_encode($return);
            } else {
                $ostatus = OrderStatus::where(['id' => $request->status_id])->first();
                $return['error'] = 1;
                $return['msg'] = " Allready assign " . $ostatus->name . " status to this Order!!";
                return json_encode($return);
            }
        }
    }

    public function addcartinsession(Request $request)
    {
        $user = $request->uid;
        $productId = $request->pid;
        $masterId = $request->mattrid;
        $attrId = $request->attrid;
        $cartid = isset($request->cartid) ? $request->cartid : '0';
        $quantity = isset($request->quantity) ? $request->quantity : 1;
        $mode = $request->mode;
        $attributesData = "";
        $adminCart = new AdminCart();
        if (!empty($masterId)) {
            $masterId = explode(',', $masterId);
            $attrId = explode(',', $attrId);
            $attributes = [];
            foreach ($masterId as $key => $value) {
                $attributes[$value] = $attrId[$key];
            }
            $attributesData = json_encode($attributes);
        }

        $country_id = 0;
        $cust_address = UserAddress::where(['deleted_at' => null, 'id' => $request->address_id])->first();
        if ($cust_address) {
            $country_id = $cust_address->country_id;
        }

        $cartqty = 0;
        if ($mode == 1) {
            $carttotal = AdminCart::where('product_id', '=', $productId)
                ->where(function ($query) use ($attributesData) {
                    if (!empty($attributesData) && $attributesData != Null) {
                        $query->where('attr_value', '=', ($attributesData));
                    } else {
                        $query->whereNull('attr_value');
                    }
                })
                ->where(function ($query) use ($user) {
                    $query->where('customer_id', $user);
                })->count();
            if ($carttotal == 0) {
                $ucart = new AdminCart;
                $ucart->customer_id = $user;
                $ucart->product_id  = $productId;
                $ucart->quantity    = $quantity;
                if (!empty($attributesData) && $attributesData != Null) {
                    $ucart->attr_value = ($attributesData);
                }
                $ucart->save();
                $cartId = $ucart->id;
            } else {
                AdminCart::where('product_id', '=', $productId)
                    ->where(function ($query) use ($attributesData) {
                        if (!empty($attributesData) && $attributesData != Null) {
                            $query->where('attr_value', '=', ($attributesData));
                        } else {
                            $query->whereNull('attr_value');
                        }
                    })
                    ->where(function ($query) use ($user) {
                        $query->where('customer_id', $user);
                    })
                    ->update([
                        'quantity' => DB::raw('quantity + ' . $quantity)
                    ]);
                $ucart = AdminCart::where('product_id', '=', $productId)
                    ->where(function ($query) use ($attributesData) {
                        if (!empty($attributesData) && $attributesData != Null) {
                            $query->where('attr_value', '=', ($attributesData));
                        } else {
                            $query->whereNull('attr_value');
                        }
                    })
                    ->where(function ($query) use ($user) {
                        $query->where('customer_id', $user);
                    })->first();
                $cartId =  $ucart->id;
            }
        } elseif ($mode == 2) {
            $adminCart->subtractCart($user, $cartid);
        } elseif ($mode == 3) {
            $adminCart->removeCart($user, $cartid);
        } elseif ($mode == 4) {
            $adminCart->updateCart($user, $cartid);
        }
        $countryName = '';
        if(!empty($cust_address)){
            $countryName = $cust_address->get_Country($cust_address->country_id);    
        }
        

        $UserCart = '';
        $CartTotal = '';
        $result = $adminCart->getcartDetail($user, strtolower($countryName) != 'india');
        $finalCart = json_decode($result, true);

        $cartqty = isset($finalCart['data']['products']) ? count($finalCart['data']['products']) : 0;
        if ($finalCart['success'] == 1) {
            foreach ($finalCart['data']['products'] as $key => $value) {
                //$Price = ($value['price']-$value['tax_price']);
                $Price = ($value['price']);
                $UserCart .= '<div class="col-sm-12 " style="position: relative;" id="cartrow' . $key . '">';
                $UserCart .= '<div class="card" style="margin-bottom: .5rem !important;">';
                $UserCart .= '<div class="row" style="padding: 5px;">';
                $UserCart .= '<div class="col-sm-3">';
                $UserCart .= '<img id="user-img-nav1" alt="' . $value['name'] . '" src="' . $value['image'] . '" width="50" height="50" />';
                $UserCart .= '</div>';
                $UserCart .= '<div class="col-sm-6" style="font-size:12px; text-align:left;"><div>' . $value['name'] . '</div>';
                $UserCart .= '<div>Price(₹): ' . $Price . '</div>';
                $UserCart .= '<div>Tax: ' . $value['tax_name'] . '</div>';
                if (!empty($value['attributes'])) {
                    $attrData = "";
                    foreach ($value['attributes']['attr'] as $atkey => $atvalue) {
                        $attrData .= $atvalue['attribute_master_name'] . ':' . $atvalue['attribute_name'] . '<br>';
                    }
                    $UserCart .= '<div>Attributes: ' . $attrData . '</div>';
                }
                $UserCart .= '<div>Total(₹):' . $value['price'] * $value['quantity'] . '</div>';
                $UserCart .= '</div>';
                $UserCart .= '<div class="col-sm-3" style="padding: 0px;"><span class="form-control text-center" style="width:50%; margin-top: 20px;padding: 8px 2px;">' . $value['quantity'] . '</span>
                     <i aria-hidden="true" style="position: absolute; top: 20px; right: 25px;color: #018601!important;cursor:pointer;"  class="fa fa-plus float-right inc-btn updateCart" cartid="' . $value['cart_id'] . '" ></i> <br> <i aria-hidden="true" style="position: absolute; top: 50px; right: 25px; color: #f90202!important;cursor:pointer;" class="fa fa-minus float-right inc-btn minusCart" cartid="' . $value['cart_id'] . '" ></i>';
                $UserCart .= '<div style="position: absolute; top: -8px; right: 8px; cursor:pointer;" cartid="' . $value['cart_id'] . '" class="removeCart"><i class="fa fa-times"></i></div></div>';
                $UserCart .= '</div></div></div>';
            }
            foreach ($finalCart['data']['total'] as $key => $value) {
                $CartTotal .= '<div class="col-sm-12"><b>' . $value['title'] . ':</b>&nbsp;<span id="ctotal">' . $value['value'] . '</span></div>';
            }
        }
        $records['status'] = '1';
        $records['msg'] = 'Product add successfully in cart!!!';
        $records['data']['UserCart'] = $UserCart;
        $records['data']['CartTotal'] = $CartTotal;
        $records['data']['totalcart'] = $cartqty;
        echo json_encode($records);
        exit;
    }

    public function addcouponcode(Request $request)
    {
        $user = $request->uid;
        $coupon = $request->coupon;
        $adminCart = new AdminCart();
        $response = $adminCart->checkCoupon($user, $coupon);
        $result = json_decode($response, true);
        $CartTotal = '';
        if ($result['status'] == true) {
            $response = $adminCart->cartTotal($user);
            $finalCart = $response;
            foreach ($finalCart['totals'] as $key => $value) {
                $CartTotal .= '<div class="col-sm-12"><b>' . $value['title'] . ':</b>&nbsp;<span id="ctotal">' . $value['value'] . '</span></div>';
            }
            $records['status'] = '1';
            $records['msg'] = 'Selected coupon code apply successfully!!!';
            $records['data']['CartTotal'] = $CartTotal;
            echo json_encode($records);
            exit;
        } else {
            echo $response;
            exit;
        }
    }

    public function invoice(Request $request, $order_id)
    {
        $data = Order::select('orders.*', 'order_status.name as order_status_name')->join('order_status', 'order_status.id', 'order_status_id')->where(['orders.id' => $order_id, 'orders.deleted_at' => null])->first()->toArray();
        $nproduct = new Order();
        $order_products = $nproduct->get_order_products($order_id);
        $order_histories = $nproduct->get_order_history($order_id);
        $general_settings  = $this->general_settings;

        $title = "Orders";
        return view('admin.order.invoice', compact('title', 'data', 'order_products', 'order_histories', 'general_settings'));
    }

    public function print(Request $request, $order_id)
    {
        $data = Order::select('orders.*', 'order_status.name as order_status_name')->join('order_status', 'order_status.id', 'order_status_id')->where(['orders.id' => $order_id, 'orders.deleted_at' => null])->first()->toArray();
        $nproduct = new Order();
        $order_products = $nproduct->get_order_products($order_id);
        $order_histories = $nproduct->get_order_history($order_id);
        $general_settings  = $this->general_settings;
        $shipdate = '';
        foreach ($order_histories as $key => $value) {
            if ($value['order_status_id'] == 4) {
                $shipdate = $value['created_at'];
            }
        }

        $title = "Orders";
        return view('admin.order.dispatch', compact('title', 'data', 'order_products', 'general_settings', 'shipdate'));
    }

    
    
}
