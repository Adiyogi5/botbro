<?php
namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Order;
use App\Models\Returns;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MyInvestmentController extends Controller
{

    public function index(Request $request)
    {
        $title = 'Investments';

        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        $my_order = Order::select('orders.*', 'order_status.name as status_text', 'order_status.color as status_class', 'payment_status')
            ->join('order_status', 'order_status.id', '=', 'orders.order_status_id')
            ->Where('orders.user_id', $user->id)
            ->whereNull('orders.deleted_at')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('frontend.dashboard.investment', compact('title', 'my_order'));
    }

    public function investmoney(Request $request)
    {
        $invest_amount = $request->site_settings['invest_amount'];

        $request->validate([
            'invest_amount'  => 'required|numeric|min:' . $invest_amount,
            'payment_type'   => 'required|in:0,1',
            'transaction_id' => 'nullable|required_if:payment_type,1',
            'date'           => 'required|date',
            'screenshot' => 'required|mimes:jpg,jpeg,png|max:2048', 
        ]);

        try {
            $data                 = new Investment();
            $data->user_id        = auth()->id();
            $data->invest_amount  = $request->invest_amount;
            $data->payment_type   = $request->payment_type;
            $data->transaction_id = $request->payment_type == 1 ? $request->transaction_id : null;
            $data->date           = $request->date;
            if ($file = $request->file('screenshot')) {
                $path = 'investment';
                $destinationPath    = 'public\\' . $path;
                $uploadImage        = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
                $data->screenshot = $path . '/' . $uploadImage;
            }

            $data->save();

            return redirect()->back()->with('success', 'Thanks for investing with us. Please wait for approval!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to save investment details: ' . $e->getMessage());
        }
    }

    public function get_filter_data(Request $request)
    {
        $user = auth('web')->user();

        $my_order = Order::select('orders.*', 'order_status.name as status_text', 'order_status.color as status_class')
            ->join('order_status', 'order_status.id', '=', 'orders.order_status_id')
            ->where('orders.user_id', $user->id);

        // Add search filter
        if ($request->orderno_search) {
            $my_order->where('orders.order_no', 'LIKE', '%' . $request->orderno_search . '%');
        }

        $my_order = $my_order->orderBy('id', 'DESC')
            ->get()->toArray();

        return response()->json(['data' => $my_order]);
    }

    public function my_return(Request $request)
    {
        $title = 'Order List';

        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        $my_return = Returns::select('returns.*')
            ->Where('customer_id', $user->id)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('frontend.dashboard.my_return', compact('title', 'my_return'));
    }

    public function get_filter_return_data(Request $request)
    {
        $user = auth('web')->user();

        $my_return = Returns::select('returns.*')
            ->Where('customer_id', $user->id);

        // Add search filter
        if ($request->proname_search) {
            $my_return->where('returns.product_name', 'LIKE', '%' . $request->proname_search . '%');
        }

        $my_return = $my_return->orderBy('id', 'DESC')
            ->get()->toArray();

        return response()->json(['data' => $my_return]);
    }

}
