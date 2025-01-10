<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Returns;
use App\Models\ReturnStatus;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category; 
use App\Models\UserWallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
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
        checkPermission($this, 102);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $created_at = $request->created_at ?? null;
            $return_status = $request->return_status ?? null;
            $return_type = $request->return_type ?? null;
            
            $records = Returns::select('returns.*') 
                ->join('products', 'products.id', 'returns.product_id');
            
            if (!empty($created_at) && strtotime($created_at) === false) {
                $records = $records->whereDate('returns.created_at', '=', date('Y-m-d', strtotime($created_at)));
            }
            if (!empty($return_type)) {
                $records = $records->where('returns.return_type', '=', $return_type);
            }
            if (!empty($return_status)) {
                $records = $records->where('returns.return_status_id', '=', $return_status);
            }
            
            return Datatables::of($records)
                
                ->editColumn('order_no', function ($row) {
                    if($row->return_type==1){ $returntype= '<span class="btn-sm btn-danger">Return</span>'; }else{  $returntype= '<span class="btn-sm btn-info">Replace</span>'; }
                    return $row->order_no.'<br>'.$returntype;
                }) 
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y h:i a', strtotime($row->created_at));
                }) 
                ->editColumn('return_status_id', function ($row) {
                    return isset(RETURNSTATUS[$row->return_status_id]) ? RETURNSTATUS[$row->return_status_id] : '';
                })
                ->editColumn('return_action_id', function ($row) {
                    return isset(RETURNACTIONS[$row->return_action_id]) ? RETURNACTIONS[$row->return_action_id] : '';
                })
                ->addColumn('action', function ($row) {
                    return $action_btn = '<a href="' . url('admin/returns/' . $row->id) . '" class="btn btn-sm btn-secondary" title="View"><i class="fa fa-eye"></i></a>';
                })
                ->rawColumns(['order_no','product_name','action'])->make(true);
        };

        $title = "Return/Replace Products";
        $returnstatus = RETURNSTATUS;
        return view('admin.return.index', compact('title', 'returnstatus'));
    }


    public function show(Request $request, $id)
    {
        $data = Returns::select('returns.*')
        ->join('products', 'products.id', 'returns.product_id')->where(['returns.id' => $id])->first();
        $nproduct = new Returns(); 
        

        $returnstatus = RETURNSTATUS;
        $returnaction = RETURNACTIONS;
        $returntype =  ($data->return_type==1)?RETURNTYPE:REPLACETYPE;
        $title = ($data->return_type==1)?"Return Product":"Replace Product";
        return view('admin.return.view', compact('title', 'data', 'returnaction', 'returnstatus','returntype'));
    }

 
    public function updReturnStatus(Request $request, $return_id)
    {        
        if ($request->ajax()) {
            $return = Returns::where(['id' => $return_id])->first();
            if ($return->return_status_id < $request->status_id) {
                $return->return_status_id = $request->status_id;
                $return->return_action_id = $request->return_action;
                $return->admin_comment = $request->return_comment;
                $return->save();
 
                // if product refund then wallet transfer amount of product
                if($request->return_action==1){
                   $returnproduct = OrderProduct::where(['order_id' => $return->order_id])->where(['product_id' => $return->product_id])->first();
                   
                   if(!empty($returnproduct)){
                        $user_balance = UserWallet::where('user_id', $return->customer_id)->orderByDesc('id')->first()->updated_balance ?? 0.00;
                        
                        $amount = $returnproduct->total_price; 
                        $walletAmt = [
                                    'voucher_no'        => RandcardStr(8),
                                    'user_id'           => $return->customer_id, 
                                    'date'              => Carbon::now()->toDateTimeString(),
                                    'particulars'       => "Refund Product (".$returnproduct->product_name.") amount of order no. ".$return->order_no." has been credit in your Upayliving wallet ₹ ".$amount,
                                    'payment_type'      => 1,
                                    'order_id'          => $return->order_id,
                                    'amount'            => $amount,     
                                    'current_balance'   => $user_balance,
                                    'updated_balance'   => ($user_balance + intval($amount)),
                                    'created_at'        => date('Y-m-d H:i:s'),
                                    'updated_at'        => date('Y-m-d H:i:s'),
                                ];
                        UserWallet::insert($walletAmt);
                        /// update user balance in user table
                        User::where('id', $return->customer_id)->update(['user_balance' => $user_balance + intval($amount)]);   
                   }
                }                 
                
                $return['error'] = 0;
                $msg = "Return status has been updated successfully!!";
                $return['msg'] = $msg;
                return json_encode($return);
            } else {
                $ostatus = RETURNSTATUS[$request->status_id];
                $return['error'] = 1;
                $return['msg'] = " Allready assign " . $ostatus . " status to this Return!!";
                return json_encode($return);
            }
        }
    }


}
