<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BadgeMaster;
use App\Models\Cart;
use App\Models\City;
use App\Models\Country;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\RewardMaster;
use App\Models\UserAddress;
use App\Models\UserBadgeLog;
use App\Models\UserRewardLog;
use App\Models\UserReferral;
use App\Models\UserTempWallet;
use App\Models\AdminNotification;
use Carbon\Carbon;
use Error;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use App\Mail\NewOrderMail;
use Barryvdh\DomPDF\Facade;
use PDF;

class CartController extends Controller
{   
    private $razor_pay_key = '';
    private $razor_pay_secret = '';
    private $CURRENCY_SYMBOL;
    //private $general_settings;


    public function __construct(Request $request)
    {   
        parent::__construct();
        $this->middleware(['mail']);
        $settings = GeneralSetting::all()->toArray();
        $this->general_settings = array_combine(array_column($settings,'setting_name'),array_column($settings,'filed_value'));
       
        $this->razor_pay_key = $this->general_settings['razorpay_keyid'];
        $this->razor_pay_secret = $this->general_settings['razorpay_secretkey'];

    }

    public function index(Request $request)
    {   
        $title = "Cart";

        $user = Auth::guard('web')->user();

        $cartData = new Cart();

        $data['warning'] = "";
        $data['products'] = [];
        $data['totals'] = [];

        $products = $cartData->getCartProducts($user->id, $request);
       
        $totals = $cartData->cartTotal($user->id,$request);
        
        if(!empty($products))
        {
            
            foreach ($products as $product)
            {

                $image = $product['image'];

                $data['products'][] = array(
                    'cart_id'     => $product['cart_id'],
                    'image'       => $image,
                    'slug'        => $product['slug'],
                    'name'        => stripslashes($product['name']),
                    'product_id'  => $product['product_id'],
                    'quantity'    => $product['quantity'],
                    'price'       => round($product['price'], 2),
                    'total'       => round($product['total'], 2),
                );
            }

            $errorcart = false;
           

            $data['totals'][] = array(
                'title' => "Sub-Total",
                'value' => ($this->CURRENCY_SYMBOL).'₹ '.round(($totals['subtotal'] != '' ? $totals['subtotal']  : 0), 2),
            );
            
            $netTotal = round(($totals['total'] != '' ? ($totals['total']): 0), 2);

            $roundoff = round($netTotal) - $netTotal;
            
            
            $data['totals'][] = array(
                'title' => "Round Off",
                'value' => ($this->CURRENCY_SYMBOL).'₹ '.round($roundoff, 2),
            );
            
            $data['totals'][] = array(
                'title' => "Total",
                'value' => ($this->CURRENCY_SYMBOL).'₹ '.round(($totals['total'] != '' ? ($totals['total']) : 0)),
            );
            $data['cart_total'] = round(($totals['total'] != '' ? ($totals['total']) : 0), 2);
        }
        
        return view('frontend.cart.cart', compact('title','products'),$data);
    }

    public function addtocart(Request $request)
    {   
        $user = Auth::guard('web')->user();        
        $validator = Validator::make(
            $request->all(),
            [
                'product_id' => 'required',
                'quantity'  => 'required'
            ],
            $messages = [
                'product_id.required'   => 'Product is required',
                'quantity.required'     => 'Quantity is required'
            ]
        );

        $user_id = "";
        if (!empty($user->id)) {
            $user_id = $user->id;
        }
        
        if($validator->fails()) {           
            $arr = [];
            $valid = json_decode($validator->errors());
            foreach ($valid as $key => $value) {
                $arr[$key] = $value[0];
            }
            $response = array(
                'status'   => false,
                'message'   => 'Bad request',
                'data'      => $arr
            );
            return response()->json($response, 400);
        }
        
        $cartData = new Cart();
        $products = $cartData->getCartProducts($user_id, $request);
       
        $product_total = 0;
        foreach ($products as $product_2) {
            if ($product_2['product_id'] == $request->product_id) {
                $product_total += $product_2['quantity'];
            }           
        }

        $product_info = Product::select('products.id','products.name')
        ->where('products.status', 1)
        ->where('products.deleted_at', NULL)
        ->where('products.id', $request->product_id)->first();

        if ($product_info) {

            $product_total = $product_total+$request->quantity;            
            if(empty($json['warning'])){                    
                $cartData = new Cart();
                $cartid_api = $cartData->addcart($user_id, $request);             
                $json['cartid'] = (int)$cartid_api;
                $json['total'] = $cartData->countCart($user_id, $request);
                $json['total_item'] = $cartData->countProducts($user_id, $request);
   
                $response = array(
                    'status' => true,
                    'message' => 'Cart Save Successfully!!',
                    'data' => $json
                );
                return response()->json($response, 200);
            }
            else
            {
                $response = array(
                    'status'   => false,
                    'message'   => $json['warning'],
                    'data'      => []
                );
                return response()->json($response, 400);
            }
        }
        else
        {
            $response = array(
                'status'   => false,
                'message'   => 'Product Not Found',
                'data'      => []
            );
            return response()->json($response, 404);
        }
    }

    public function removecart(Request $request)
    {
        
        $user = Auth::guard('web')->user();
        $validator = Validator::make(
            $request->all(),
            [
                'cart_id' => 'required',
            ],
            $messages = [
                'cart_id.required'   => 'Cart Id is required',
            ]
        );
        $user_id = "";
        if (!empty($user->id)) {
            $user_id = $user->id;
        }
        if ($validator->fails()) {
            $arr = [];
            $valid = json_decode($validator->errors());
            foreach ($valid as $key => $value) {
                $arr[$key] = $value[0];
            }
            $response = array(
                'status'   => false,
                'message'   => 'Bad request',
                'data'      => $arr
            );
            return response()->json($response, 422);
        }

        $cartData = new Cart();
        $ucart = $cartData->removeCart($user_id, $request);
        if ($ucart) {
            
            $response = array(
                'status' => true,
                'message' => 'Cart Remove Successfully!!',
                'data' => []
            );
            return response()->json($response, 200);
        } else {
            $response = array(
                'status'   => false,
                'message'   => 'Invalid cart detail',
                'data'      => []
            );
            return response()->json($response, 422);
        }
    }
    
    public function updatecartitem(Request $request)
    { 
       
        $user = Auth::guard('web')->user();
        $validator = Validator::make(
            $request->all(),
            [
                'cart_id' => 'required',
                'quantity' => 'required',
            ],
            $messages = [
                'cart_id.required'   => 'Cart Id is required',
                'quantity.required'   => 'Quantity is required',
            ]
        );
        $user_id = "";
        if (!empty($user->id)) {
            $user_id = $user->id;
        }
        if ($validator->fails()) {
            $arr = [];
            $valid = json_decode($validator->errors());
            foreach ($valid as $key => $value) {
                $arr[$key] = $value[0];
            }
            $response = array(
                'status'   => false,
                'message'   => 'Bad request',
                'data'      => $arr
            );
            return response()->json($response, 422);
        }

        $cartData = new Cart();
       
        $cart_product = Cart::where('id',$request->cart_id)->first();
       
        $product_info = Product::select('products.id','products.name')
        ->where('products.status', 1)
        ->where('products.deleted_at', NULL)
        ->where('products.id', $cart_product->product_id)->first();

       
            
        if ($product_info) { 

            
            if(empty($json['warning'])){   
                $ucart = $cartData->updateCart($user_id, $request);
                if ($ucart) {
                    $response = array(
                        'status' => true,
                        'message' => 'Your cart has been updated successfully!',
                        'data' => []
                    );
                    return response()->json($response, 200);
                } else {
                    $response = array(
                        'status'   => false,
                        'message'   => 'The cart details provided are invalid.',
                        'data'      => []
                    );
                    return response()->json($response, 422);
                }
            }else{
                $response = array(
                    'status'   => false,
                    'message'   => $json['warning'],
                    'data'      => []
                );
                return response()->json($response, 422);
            }
        } else {
            $response = array(
                'status'   => false,
                'message'   => 'The product could not be found.',
                'data'      => []
            );
            return response()->json($response, 404);
        }    
    }

    public function getCartCount(Request $request)
    {
        $user = Auth::guard('web')->user();
        
        if($request->ajax()){

            $user_id = "";

            if (!empty($user->id)) {
                $user_id = $user->id;
            }
        
            $cartData = new Cart();
            $cart_total =  Cart::where('customer_id',$user_id)->count('id');
            
            $response = array(
                'success'   => true,
                'message'   => 'Count fetched successfully',
                'data'      => [
                    "cart_total" =>$cart_total
                ]
            );

            return response()->json($response, 200);
        }
        else
        {
            $response = array(
                'success'   => false,
                'message'   => 'Bad request',
                'data'      => []
            );
            return response()->json($response, 403);
        }

    }


    public function checkout(Request $request)
    {
        $title = "Checkout";

        $user = auth('web')->user();

        $cart = new Cart();
        $cart_count = (int) $cart->countCart($user->id,$request);

        if(!$cart_count)
        {
            $request->session()->flash('error','Your cart is currently empty.');
            return redirect('cart');
        }

        $cartData = new Cart();

        $data['warning'] = "";
        $data['products'] = [];
        $data['totals'] = [];

        $countries = Country::where('status', 1)->pluck('name', 'id')->toArray();

        $addresses = UserAddress::where('user_id',$user->id)->orderBy('default_id','DESC')->get()->toArray();

        $products = $cartData->getCartProducts($user->id, $request);
        $cart_totals = $cartData->cartTotal($user->id,$request);

        if (!empty($products)) {
            foreach ($products as $product) {
                $product_total = 0;
                foreach ($products as $product_2) {
                    if ($product_2['product_id'] == $product['product_id']) {
                        $product_total += $product_2['quantity'];
                    }
                }

                $image = $product['image'];

                $data['products'][] = array(
                    'cart_id'     => $product['cart_id'],
                    'image'       => $image,
                    'model'       => $product['model'],
                    'slug'        => $product['slug'],
                    'name'        => stripslashes($product['name']),
                    'product_id'  => $product['product_id'],
                    'quantity'    => $product['quantity'],
                    'price'       => round($product['price'],2),
                    'total'       => round($product['total'], 2),
                );
            }
           
            $data['totals'][] = array(
                'title' => "Sub-Total",
                'value' => ($this->CURRENCY_SYMBOL).'₹ '.round(($cart_totals['subtotal'] != '' ? $cart_totals['subtotal']  : 0), 2),
            );
            
            $netTotal = round(($cart_totals['total'] != '' ? ($cart_totals['total']): 0), 2);

            $roundoff = round($netTotal) - $netTotal;

            $data['totals'][] = array(
                'title' => "Round Off",
                'value' => ($this->CURRENCY_SYMBOL).'₹ '.(round($roundoff, 2)),
            );

            $data['totals'][] = array(
                'title' => "Total",
                'value' => ($this->CURRENCY_SYMBOL).'₹ '.(round(($cart_totals['total'] != '' ? ($cart_totals['total']) : 0))),
            );
        }
        
        $user_address_data = UserAddress::select('user_addresses.*', 'countries.name as country_name', 'states.name as state_name', 'cities.name as city_name')
            ->join('countries', 'countries.id', '=', 'user_addresses.country_id')
            ->join('states', 'states.id', '=', 'user_addresses.state_id')
            ->join('cities', 'cities.id', '=', 'user_addresses.city_id')
            ->Where('user_id', $user->id)
            ->get();
            
        
        return view('frontend.cart.checkout', compact('title', 'user_address_data', 'countries','addresses'),$data);
    }


    public function placeorder(Request $request)
    {
        
        $user = Auth::guard('web')->user();
        $user_id =  !empty($user->id) ? $user->id : '';

        $address_id = (int) $request->address_id;

        $user_balance = $user->user_balance;

        if(empty($address_id))
        {
            $request->session()->flash('error','Please choose an address.');
            return redirect()->back()->withInput();
        }

        $customer_address = UserAddress::where('id',$address_id)->first();
        
        if(empty($customer_address))
        {
            $request->session()->flash('error','The shipping address provided is invalid.');
            return redirect()->back()->withInput();
        }
        
        $payment_setting = GeneralSetting::whereIn('setting_type', [1, 6])->get()->toArray();
        $payment_setting = array_combine(array_column($payment_setting, 'setting_name'), array_column($payment_setting, 'filed_value'));
        $payment_key = $payment_setting['razorpay_keyid'];
        $payment_secret = $payment_setting['razorpay_secretkey'];
        $logo = $payment_setting['logo'];

        $cartData = new Cart(); 
        
        $city_name = $customer_address->get_Cities($customer_address->city_id);
        $state_name = $customer_address->get_States($customer_address->state_id);
        $country_name = $customer_address->get_Country($customer_address->country_id);

        $is_valid =  $cartData->validate_cart($user_id,$request);   
        if(empty($is_valid) || $is_valid['status']=='0')
        {
            $request->session()->flash('error',$is_valid['msg']);
            return redirect('cart');
        }
        $products = $cartData->getCartProducts($user_id,$request);
        $cartTotal = $cartData->cartTotal($user_id,$request);
           
        $subtotal              = $cartTotal['subtotal'];
        $shipping_amout        = 0;
        $tax_amount            = $cartTotal['total'];
        $total_amount          = $cartTotal['total'];
        $round_off             = round($total_amount) - $total_amount;
        
        try {
            // Order Data Save
            $data = new Order();
            $data->date                 =   date("Y-m-d");            
            # Customer details
            $data->user_id              =   Auth::guard('web')->id();
            $data->customer_email       =   Auth::guard('web')->user()->email;
            $data->customer_mobile      =   Auth::guard('web')->user()->mobile;
            $data->customer_name        =   Auth::guard('web')->user()->name;
            $data->shipping_address_1   =   $customer_address->address_1;
            $data->shipping_address_2   =   $customer_address->address_2;
            $data->shipping_city        =   $city_name;
            $data->shipping_state       =   $state_name;
            $data->shipping_country     =   $country_name;
            $data->shipping_postcode    =   $customer_address->postcode;
            $data->user_comment         =   $request->user_comment;

            # Payment
            $data->payment_status       =   0;
            $data->payment_type         =   1;
            $data->order_status_id      =   1;
            # Discount
            $data->discount             =   0;
            $data->tax_amount           =   0;
            # Shipping
            $data->shipping_amount      =   $shipping_amout;
            #total
            $data->subtotal             =   $subtotal;
            $data->wallet_amount        =   0;
            $data->round_off            =   $round_off;
            $data->total                =   round($total_amount);
            $data->order_ipaddresss     =   $request->ip();

            $MaxOrderNo = Order::selectRaw('MAX(SUBSTRING(order_no,5)) as max_order_no')->first();
            $max_no = (int) (@$MaxOrderNo->max_order_no);
            $orderNo = 'UPL-' . sprintf("%08d",$max_no + 1);
            $data->order_no = $orderNo;            
            $data->save(); 
                        
            // Order History Save
            $order_history = new OrderHistory();
            $order_history->order_id         =      $data->id;
            $order_history->order_status_id  =      1;
            $order_history->comment          =      "Order Placed";
            $order_history->save();

            $proQuantity = 0;            
            foreach ($products as $key => $value)
            {
                $OrderProduct = OrderProduct::create([
                    'order_id'           => $data->id,
                    'product_id'         => $value['product_id'],
                    'product_name'       => stripslashes($value['name']),
                    'model'              => $value['model'],
                    'quantity'           => $value['quantity'],
                    'unit_price'         => $value['price'],
                    'total_price'        => $value['total'],
                    'referral_price'     => $value['referral_price'], 
                    'refer_price'        => $value['refer_price'],
                ]);
                $proQuantity= $proQuantity+$value['quantity'];
            }
             
            /// remove customer cart
            Cart::where('customer_id', Auth::guard('web')->id())->delete();
             
            $api = new Api($payment_key, $payment_secret);
            $curtype['currency'] = 'INR';
            $curtype['conversion_value'] = '1';
            $orderData = [
                'receipt'         =>    $orderNo,
                'amount'          =>    round($total_amount) * 100,
                'currency'        =>    'INR',
                'payment_capture' =>    1 // auto capture
            ]; 
            $razorpayOrder      = $api->order->create($orderData);
            $razorpayOrderId    = $razorpayOrder['id'];

            Order::where('id', $data->id)->update(['order_no' => $orderNo, 'transaction_payment_id' => $razorpayOrderId]);            
            Session::forget('razorpayOrderId');
            Session::put('razorpayOrderId',$razorpayOrderId);
            Session::forget('order_id');
            Session::put('order_id',$data->id);
           // $request->session()->flash('success','Please complete your payment');
            return redirect('payment');
                
        } catch (Error  $err) {           
            $request->session()->flash('error','Failed to place order.');
            return redirect('checkout');
        }      

    }

  
    public function pay(Request $request)
    {   
        $data['title'] = "Complete Payment";
        $order_id = (int) session('order_id');
        $order_data = Order::where('id',$order_id)->first();
        $razorpayOrderId = session('razorpayOrderId');
        if(empty($order_data) && !empty($razorpayOrderId))
        {
            $request->session()->flash('error','Invalid request');
            return redirect('cart');
        } 
        $payment_data = [
            "key"               => $this->razor_pay_key,
            "amount"            => $order_data->total * 100,
            "name"              => $this->general_settings['application_name'],
            "description"       => mb_strimwidth($this->general_settings['site_description'],0,200,'..'),
            "image"             => get_image($this->general_settings['logo'],'user'),
            "prefill"           => [
            "name"              => $order_data->customer_name,
            "email"             => $order_data->customer_email,
            "contact"           => $order_data->customer_mobile,
            ],
            "notes"             => [
            "address"           => $order_data->shipping_address_1,
            "merchant_order_id" => $order_data->id,
            ],
            "theme"             => [
            "color"             => "#32cd32"
            ],
            "order_id"          => $razorpayOrderId,
            "display_currency"  =>"INR",
            "display_amount"    => $order_data->total
        ];
        $data['razorpayOrderId'] = $razorpayOrderId;
        $data['payment_json'] = json_encode($payment_data);
        $data['display_amount'] = $order_data->total;
        $data['order_no'] = $order_data->order_no;
        
        return view('frontend.cart.pay', $data);
    }


    public function verify_payment(Request $request)
    {        
        $success = true;
        $error = "Payment Failed";        
        if(!empty($request->razorpay_payment_id))
        {
            $api = new Api($this->razor_pay_key, $this->razor_pay_secret);
            try
            {
                $attributes = array(
                    'razorpay_order_id' => session('razorpayOrderId'),
                    'razorpay_payment_id' => $_POST['razorpay_payment_id'],
                    'razorpay_signature' => $_POST['razorpay_signature']
                );

                $api->utility->verifyPaymentSignature($attributes);
            }
            catch(SignatureVerificationError $e)
            {
                $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }
        }

        $order_id = session('order_id');
        $orderNo = Order::where('id',$order_id)->first();

        if ($success === true)
        {            
            $razorpay_order_id = session('razorpayOrderId');
            $payment_id = $request->razorpay_payment_id;
            $json = ['payment_id'=>$payment_id,'order_id'=>$razorpay_order_id];

            $order_data = Order::where('id',$order_id)->first();
            $order_data->razorpay_payment_Id = $payment_id;
            $order_data->payment_json = json_encode($json);
            $order_data->payment_status = 1;
            $order_data->save();
            $orderno = $order_data->order_no;

            //// Update products cout and Badge & reward
            $proQuantity = 0;
            $OrderProduct = OrderProduct::where('order_id', $order_id)->get();

            // Update Reward wallet
            $referralData = UserReferral::where('refer_id', '=', Auth::guard('web')->id())->first();
            
            if(!empty($referralData)){
                // if have referralData then allow referral amount
                foreach ($OrderProduct as $opkey => $opvalue) {                     
                    UserTempWallet::create([
                        'user_id'           => Auth::guard('web')->id(), 
                        'is_refer'          => 0,
                        'date'              => date('Y-m-d H:i:s'),
                        'particulars'       => 'Congratulations, You are getting referral reward in your wallet on purchase product on Order No: '.$orderno.'',
                        'payment_type'      => 1,
                        'order_id'          => $order_id,
                        'order_product_id'  => $opvalue->id,
                        'amount'            => $opvalue['refer_price'],  // to rate
                        'created_at'        => date('Y-m-d H:i:s'),
                        'updated_at'        => date('Y-m-d H:i:s'),
                    ]);
                
                    UserTempWallet::create([
                        'user_id'           => $referralData->referral_id, 
                        'is_refer'          => Auth::guard('web')->id(),
                        'date'              => date('Y-m-d H:i:s'),
                        'particulars'       => 'Congratulations, You are getting referral reward in your wallet on purchasing by '.@Auth::guard('web')->user()->name.' on Order No: '.$orderno.'',
                        'payment_type'      => 1,
                        'order_id'          => $order_id,
                        'order_product_id'  => $opvalue->id,
                        'amount'            => $opvalue['referral_price'],  // from rate
                        'created_at'        => date('Y-m-d H:i:s'),
                        'updated_at'        => date('Y-m-d H:i:s'),
                    ]);
                }
            } 
            
            /// Send Notification to Admin
            $adnoti = new AdminNotification();                
            $adnoti->title = "Get New Order";
            $adnoti->message = "Get new order from ".Auth::guard('web')->user()->name.". Order no: ".$orderno."";
            $adnoti->notification_type = 2;
            $adnoti->is_read = 0;
            $adnoti->created_at = now()->format('Y-m-d H:i:s');
            $adnoti->updated_at = now()->format('Y-m-d H:i:s');
            $adnoti->save();

            /// Mail to users 
            try {
                $user = Auth::guard('web')->user();
                $invoiceData['data'] = Order::select('orders.*', 'order_status.name as order_status_name', 'order_status.color as order_status_bg')->where('orders.id', $order_id)
                ->where('orders.user_id', $user->id)
                ->leftJoin('order_status', 'order_status.id', '=', 'orders.order_status_id')
                ->first();
                
                DB::statement("SET SQL_MODE = ''");
                $order_products = OrderProduct::select("order_products.*", 'product_images.attachment as product_image',DB::raw('CASE WHEN returns.return_type=1 and returns.id IS NOT NULL THEN 1 ELSE 0 END as is_product_return'),DB::raw('CASE WHEN returns.return_type=2 and returns.id IS NOT NULL THEN 1 ELSE 0 END as is_product_replace') )
                    ->where('order_products.order_id', $order_id)
                    ->leftJoin('product_images', 'product_images.product_id', '=', 'order_products.product_id')
                    ->leftJoin('returns', function ($join) use ($order_id) {
                        $join->on('returns.product_id', '=', 'order_products.product_id')
                            ->where('returns.order_id', '=', $order_id);
                    })
                    ->groupBy('order_products.id')
                    ->get()->toArray();

                $invoiceData['order_products'] = $order_products;
                $pdf = PDF::loadView('frontend.cart.invoice', $invoiceData);

                $attachment['subject'] = 'Your order no. '.$orderno.' placed on Upayliving is confirmed!!'; 
                $attachment['mailmessage'] = 'Dear '.$order_data->customer_name.', 

                Your order no. '.$orderno.' placed on Upayliving is confirmed.
                You will receive shipping confirmation soon. 

                Thank you for your purchase!! 
                 
                Thanks, 
                Upayliving'; 

                $attachment['attachment'] = $pdf->output();
                Mail::to($user->email)->send(new NewOrderMail($attachment));

            } catch (\Exception $e) {
                Session::forget('order_id');
                Session::forget('razorpayOrderId');
                $request->session()->flash('success','Payment procced successfully');
                return redirect('paymentconfirm')->with(['orderno'=>$orderno,'mode'=>'Success']);
            }
            
            Session::forget('order_id');
            Session::forget('razorpayOrderId');
            $request->session()->flash('success','Payment procced successfully');
            return redirect('paymentconfirm')->with(['orderno'=>$orderno,'mode'=>'Success']);
        }
        else
        {
            $request->session()->flash('error','Payment failed error : '.$error);
            return redirect('paymentconfirm')->with(['orderno'=>$orderNo,'mode'=>'Failed']);
        }
    }

    public function paymentconfirm(Request $request)
    {
        $order_no = session('orderno');
        $mode = session('mode');
      
        if(!empty($order_no)){
            return view('frontend.cart.paymentconfirm',compact('order_no','mode'));
        }else{
           return redirect('/dashboard');
        }
    } 

  
}
        