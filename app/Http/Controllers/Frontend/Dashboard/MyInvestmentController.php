<?php
namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Investment;
use App\Models\Order;
use App\Models\Returns;
use App\Models\User;
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
        $my_order = Investment::select('investments.*')
            ->Where('investments.user_id', $user->id)
            ->whereNull('investments.deleted_at')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('frontend.dashboard.investment', compact('title', 'my_order'));
    }

    public function investmoney(Request $request)
    {
        $user = auth('web')->user();

        // Load site settings
        $app_data = GeneralSetting::all();
        foreach ($app_data as $row) {
            $site_settings[$row['setting_name']] = $row['filed_value'];
        }
        $invest_amount = $site_settings['invest_amount'];

        // Validate input
        $request->validate([
            'invest_amount'  => 'required|numeric|min:' . $invest_amount,
            'payment_type'   => 'required|in:0,1',
            'transaction_id' => 'nullable|required_if:payment_type,1',
            'date'           => 'required|date',
            'screenshot'     => 'required|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            // Generate unique invest_no
            $invest_no = $this->generateUniqueInvestNo();

            // Create new investment record
            $data                  = new Investment();
            $data->invest_no       = $invest_no;
            $data->user_id         = $user->id;
            $data->customer_name   = $user->name;
            $data->customer_email  = $user->email;
            $data->customer_mobile = $user->mobile;
            $data->invest_amount   = $request->invest_amount;
            $data->payment_type    = $request->payment_type;
            $data->transaction_id  = $request->payment_type == 1 ? $request->transaction_id : null;
            $data->date            = $request->date;

            // Handle screenshot upload
            if ($file = $request->file('screenshot')) {
                $path            = 'investment';
                $destinationPath = 'public\\' . $path;
                $uploadImage     = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
                $data->screenshot = $path . '/' . $uploadImage;
            }

            $data->save();

            return redirect()->back()->with('success', 'Thanks for investing with us. Please wait for approval!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to save investment details: ' . $e->getMessage());
        }
    }

    /**
     * Generate a unique invest_no.
     *
     * @return string
     */
    private function generateUniqueInvestNo()
    {
        do {
            $number    = str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT); // Generate a number like 00001
            $invest_no = 'IN-' . $number;
        } while (Investment::where('invest_no', $invest_no)->exists());

        return $invest_no;
    }

    public function get_filter_data(Request $request)
    {
        $user = auth('web')->user();

        $my_order = Investment::select('investments.*')
            ->whereNull('investments.deleted_at')
            ->where('investments.user_id', $user->id);

        if ($request->investno_search) {
            $my_order->where('investments.invest_no', 'LIKE', '%' . $request->investno_search . '%');
        }

        $my_order = $my_order->orderBy('id', 'DESC')
            ->get()->toArray();

        return response()->json(['data' => $my_order]);
    }

    public function investmentDetails(Request $request, $id=''){
        $title = 'Order Detail';

        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        $investment_data = Investment::select('investments.*')
            ->Where('investments.user_id', $user->id)
            ->whereNull('investments.deleted_at')
            ->first();
            
        if (empty($investment_data)) {
            return redirect('investment')->with('error', "Investment not found");
        }
        
        return view('frontend.dashboard.investmentdetails', compact('title', 'investment_data'));
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
