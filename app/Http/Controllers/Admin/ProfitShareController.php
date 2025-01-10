<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfitShareLog;
use App\Models\User;
use App\Models\UserWallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProfitShareController extends Controller
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
        checkPermission($this, 116);
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = ProfitShareLog::select('user_profit_sharing_logs.id', 'user_profit_sharing_logs.user_id', 'user_profit_sharing_logs.date', 'user_profit_sharing_logs.amount', 'user_profit_sharing_logs.create_by', 'users.name as user_name', 'admins.name as admin_name')
                ->leftJoin('users', 'users.id', 'user_profit_sharing_logs.user_id')
                ->leftJoin('admins', 'admins.id', 'user_profit_sharing_logs.create_by');

            return DataTables::of($records)
                ->editColumn('date', function ($row) {
                    return date('d-M-Y', strtotime($row->date));
                })
                ->filterColumn('date', function($query, $keyword) {
                    $query->whereDate('date', date('Y-m-d', strtotime("{$keyword}")));
                })
                ->orderColumn('date', function ($row, $order) {
                    return $row->orderBy('date', $order);
                })
                ->removeColumn('id')
                ->rawColumns(['action'])->make(true);
        }
        $title = "Profit Shares";
        return view('admin.profit_shares.index', compact('title'));
    }

    public function create(Request $request)
    {
        $title = "Share Profit";
        return view('admin.profit_shares.add', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount'        => ['required', 'numeric'],
        ]);

        $users = User::select('id', 'name')->where(['purchase_status' => 1, 'status' => 1])->get();

        if (count($users) > 0) {
            $wallet_info = [];
            $profit_info = [];
            foreach ($users as $key => $value) {
                $user_balance = UserWallet::where('user_id', $value->id)->orderByDesc('id')->first()->updated_balance ?? 0.00;

                $wallet_info[] = [
                    'voucher_no'        => RandcardStr(8),
                    'user_id'           => $value->id,
                    'date'              => Carbon::now()->toDateTimeString(),
                    'particulars'       => $value->name . ' has been credited ' . $request->amount . ' Rs. amount by ' . auth()->user()->name . '. (profit share)',
                    'payment_type'      => 1,
                    'order_id'          => Null,
                    'amount'            => $request->amount,
                    'current_balance'   => $user_balance,
                    'updated_balance'   => ($user_balance + intval($request->amount)),
                    'created_at'        => Carbon::now()->toDateTimeString(),
                    'updated_at'        => Carbon::now()->toDateTimeString()
                ];

                $profit_info[] = array(
                    'user_id'       => $value->id,
                    'date'          => Carbon::now()->toDateTimeString(),
                    'amount'        => $request->amount,
                    'create_by'     => auth()->id(),
                    'particulars'   => $value->name . ' has been credited ' . $request->amount . ' Rs. amount by ' . auth()->user()->name,
                    'created_at'        => Carbon::now()->toDateTimeString(),
                    'updated_at'        => Carbon::now()->toDateTimeString()
                );
            
                /// update user balance in user table
                User::where('id', $value->id)->update(['user_balance' => $user_balance + intval($request->amount)]);
            }

            UserWallet::insert($wallet_info);
            ProfitShareLog::insert($profit_info);

            $request->session()->flash('success', 'Profit shared successfully!!');
            return redirect(route('admin.profit_shares.index'));
        } else {
            $request->session()->flash('error', 'Product purchased users not found!!');
            return redirect(route('admin.profit_shares.index'));
        }
    }

    public function get_users(Request $request)
    {
        $user = $request->data_id;
        $users = '';
        if ($user == 2) {
            $users = User::select('id', 'mobile', 'name')->where(['purchase_status' => 1, 'status' => 1])->get();
        }
        $data = '<option value="">Select Users</option>';
        foreach ($users as $key => $value) {
            $data .= '<option value="' . $value->id . '">' . $value->mobile . ' (' . $value->name . ')</option>';
        }
        $res = array('status' => TRUE, 'data' => $data);
        return response()->json($res);
    }
}
