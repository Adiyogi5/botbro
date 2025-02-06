<?php
namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use App\Models\Investment;
use App\Models\Ledger;
use App\Models\Returns;
use App\Models\User;
use App\Models\UserWithdrawRequest;
use Carbon\Carbon;
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
        $invest_amount   = $site_settings['invest_amount'];
        $rate_of_intrest = $site_settings['rate_of_intrest'];

        // Validate input
        $request->validate([
            'invest_amount'  => 'required|numeric|min:' . $invest_amount,
            'payment_type'   => 'required|in:0,1',
            'transaction_id' => 'nullable|required_if:payment_type,1',
            'date'           => 'required|date',
            'screenshot'     => 'nullable|mimes:jpg,jpeg,png|max:2048',
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
            $data->rate_of_intrest = $rate_of_intrest;
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

    public function investmentDetails(Request $request, $id = '')
    {
        $title = 'Investment Detail';

        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        $investment_data = Investment::select('investments.*', 'user_addresses.default_id', 'user_addresses.address_1', 'user_addresses.address_2', 'user_addresses.postcode', 'user_addresses.country_id', 'user_addresses.state_id', 'user_addresses.city_id', 'countries.name as country_name', 'states.name as state_name', 'cities.name as city_name')
            ->leftJoin('user_addresses', 'user_addresses.user_id', '=', 'investments.user_id')
            ->leftJoin('countries', 'countries.id', '=', 'user_addresses.country_id')
            ->leftJoin('states', 'states.id', '=', 'user_addresses.state_id')
            ->leftJoin('cities', 'cities.id', '=', 'user_addresses.city_id')
            ->where(function ($query) use ($user) {
                $query->where('user_addresses.default_id', 1)
                    ->orWhereNull('user_addresses.default_id');
            })
            ->Where('investments.user_id', $user->id)
            ->where('investments.id', $request->id)
            ->whereNull('investments.deleted_at')
            ->first();

        if (empty($investment_data)) {
            return redirect('investment')->with('error', "Investment not found");
        }

        $ledgerData = Ledger::where('user_id', $user->id)
            ->where('invest_id', $investment_data->id)
            ->orderBy('id', 'desc')
            ->whereNull('deleted_at')
            ->get();

        $ledgermonthcheck = Ledger::where('user_id', $user->id)
            ->where('invest_id', $investment_data->id)
            ->whereNull('deleted_at')
            ->first();

        // ###### Widthdrow Request #####
        $my_balance = Ledger::where('user_id', $user->id)
            ->where('invest_id', $investment_data->id)
            ->orderBy('created_at', 'desc')
            ->first();
        // dd($my_balance);
        $requestAmount = UserWithdrawRequest::selectRaw('SUM(amount) as total_amount')
            ->where('user_id', $user->id)
            ->where('invest_id', $investment_data->id)
            ->where('status', 0)
            ->first();

        $rejectAmount = UserWithdrawRequest::selectRaw('SUM(amount) as total_amount')
            ->where('user_id', $user->id)
            ->where('invest_id', $investment_data->id)
            ->where('status', 2)
            ->first();

        $my_withdrow_request = UserWithdrawRequest::select('user_withdraw_requests.*', 'user_withdraw_requests.status as request_status', 'users.name', 'users.user_balance', 'users.status')
            ->join('users', 'user_withdraw_requests.user_id', '=', 'users.id')
            ->Where('user_id', $user->id)
            ->where('invest_id', $investment_data->id)
            ->Where('users.status', '1')
            ->get();

        return view('frontend.dashboard.investmentdetails', compact('title', 'investment_data', 'ledgerData', 'my_balance', 'requestAmount', 'rejectAmount', 'my_withdrow_request', 'ledgermonthcheck'));
    }

    //####### For Full Investment Withdrow Before 6 month calculation Start #######
    public function checkLedgerMonth(Request $request)
    {
        $user         = auth('web')->user();
        $investmentId = $request->input('invest_id');

        $app_data = GeneralSetting::all();
        foreach ($app_data as $row) {
            $site_settings[$row['setting_name']] = $row['filed_value'];
        }

        $ledger = Ledger::where('user_id', $user->id)
            ->where('invest_id', $investmentId)
            ->whereNull('deleted_at')
            ->first();

        $balanceData = Ledger::where('user_id', $user->id)
            ->where('invest_id', $investmentId)
            ->orderBy('created_at', 'desc')
            ->first();

        if (! $ledger) {
            return response()->json([
                'success' => false,
                'message' => 'No ledger record found for this investment.',
            ]);
        }

        $percentageCalculation = $site_settings['full_withdrow_commission_percentage'];
        $percentageValue       = $percentageCalculation / 100;

        if (Carbon::parse($ledger->date)->gt(Carbon::now()->subMonths($site_settings['withdrow_request_months']))) {
            if (! now()->between(now()->startOfMonth(), now()->startOfMonth()->addDays(5))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Withdrawals are only allowed within the Date 1 to 5 of the every month.',
                ]);
            }

            $calculatedAmount = $balanceData->balance * $percentageValue; // Apply commission percentage

            return response()->json([
                'success'           => true,
                'balance'           => $balanceData->balance,
                'percentage'        => ($calculatedAmount == $balanceData->balance) ? 0 : $percentageCalculation,
                'calculated_amount' => $calculatedAmount,
            ]);

        } elseif (Carbon::parse($ledger->date)->lte(Carbon::now()->subMonths($site_settings['withdrow_request_months']))) {
            if (! now()->between(now()->startOfMonth(), now()->startOfMonth()->addDays(5))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Withdrawals are only allowed within the Date 1 to 5 of the every month.',
                ]);
            }

            $calculatedAmount = $balanceData->balance; // Full balance for withdrawal
            return response()->json([
                'success'           => true,
                'balance'           => $balanceData->balance,
                'percentage'        => ($calculatedAmount == $balanceData->balance) ? 0 : $percentageCalculation,
                'calculated_amount' => $calculatedAmount,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'The ledger record is older than 6 months.',
        ]);
    }

    public function fullwithdrowInvestment(Request $request, $id = '')
    {
        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        $balanceData = Ledger::where('user_id', $user->id)
            ->where('invest_id', $request->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $requestData = UserWithdrawRequest::where('status', '0')->whereNull('deleted_at')
            ->where('user_id', $user->id)
            ->get();

        DB::beginTransaction();
        try {
            $validate = $request->validate([
                'amount' => 'required|numeric',
            ], [
                'amount.required' => 'The withdrawal amount is required.',
                'amount.numeric'  => 'Please enter a valid numeric amount.',
            ]);

            if (! now()->between(now()->startOfMonth(), now()->startOfMonth()->addDays(5))) {
                $errorMessage = "Withdrawal requests are only allowed between the 1st and 5th of each month. Please try again during this period.";
                return redirect()->back()->with('error', $errorMessage);

            } elseif ($request->amount <= ($balanceData->balance - $requestData->sum('amount'))) {

                $wdata                      = new UserWithdrawRequest();
                $reference_id               = RandcardStr(15);
                $wdata->user_id             = $user->id;
                $wdata->invest_id           = $balanceData->invest_id;
                $wdata->reference_id        = $reference_id;
                $wdata->is_full_withdraw    = 1;
                $wdata->amount              = $request->amount;
                $wdata->request_date        = now()->format('Y-m-d H:i:s');
                $wdata->status              = 0;
                $wdata->save();

                /// Send Notification to Admin
                $adnoti                    = new AdminNotification();
                $adnoti->title             = "Investment Full Withdrawal Request Amount";
                $adnoti->message           = "Get new withdraw request from " . $user->name . "!!";
                $adnoti->notification_type = 1;
                $adnoti->is_read           = 0;
                $adnoti->created_at        = now()->format('Y-m-d H:i:s');
                $adnoti->updated_at        = now()->format('Y-m-d H:i:s');
                $adnoti->save();

                DB::commit();
                $request->session()->flash('success', 'Your Full Withdraw Request Submitted Successfully');
                return redirect()->back();
            } else {
                $availableBalance = $balanceData->balance - $requestData->sum('amount');
                $errorMessage     = "Your requested amount is not more than your available balance. Your Available Balance is: {$availableBalance}";
                return redirect()->back()->with('error', $errorMessage);
            }

        } catch (\Exception $e) {
            $errorMessage = 'Failed to Submit Withdraw Request: ' . $e->getMessage();
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }
    //####### For Full Investment Withdrow Before 6 month calculation End #######
    

    public function withdrowInvestment(Request $request, $id = '')
    {
        $user     = auth('web')->user();
        $app_data = GeneralSetting::all();
        foreach ($app_data as $row) {
            $site_settings[$row['setting_name']] = $row['filed_value'];
        }

        DB::statement("SET SQL_MODE = ''");
        $balanceData = Ledger::where('user_id', $user->id)
            ->where('invest_id', $request->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $ledgermonthcheck = Ledger::where('user_id', $user->id)
            ->where('invest_id', $request->id)
            ->first();

        $requestData = UserWithdrawRequest::where('status', '0')->whereNull('deleted_at')
            ->where('user_id', $user->id)
            ->get();

        $firstInvestBalance = Ledger::where('user_id', $user->id)
            ->where('invest_id', $request->id)
            ->orderBy('created_at', 'asc')
            ->first();

        $totalIntrest = $balanceData->balance - $firstInvestBalance->balance; //Intrest Balance Calculation

        DB::beginTransaction();
        try {
            $validate = $request->validate([
                'amount' => 'required|numeric',
            ], [
                'amount.required' => 'The withdrawal amount is required.',
                'amount.numeric'  => 'Please enter a valid numeric amount.',
            ]);

            if (! now()->between(now()->startOfMonth(), now()->startOfMonth()->addDays(5))) {
                $errorMessage = "Withdrawal requests are only allowed between the 1st and 5th of each month. Please try again during this period.";
                return redirect()->back()->with('error', $errorMessage);
            }
            
            // Check if the investment is less than the required months old
            if (Carbon::parse($ledgermonthcheck->date)->gt(Carbon::now()->subMonths($site_settings['withdrow_request_months']))) {
                if ($request->amount <= ($totalIntrest - $requestData->sum('amount'))) {
                    $withdrawableAmount = $totalIntrest;
                } else {
                    $availableBalance = $totalIntrest - $requestData->sum('amount');
                    $errorMessage = "Your requested amount exceeds your available interest balance. Your Available Intrest Balance is: {$availableBalance}";
                    return redirect()->back()->with('error', $errorMessage);
                }
            } 
            // If investment is older than required months, allow full balance withdrawal
            else if (Carbon::parse($ledgermonthcheck->date)->lte(Carbon::now()->subMonths($site_settings['withdrow_request_months']))) {
                if ($request->amount <= ($balanceData->balance - $requestData->sum('amount'))) {
                    $withdrawableAmount = $balanceData->balance;
                } else {
                    $availableBalance = $balanceData->balance - $requestData->sum('amount');
                    $errorMessage = "Your requested amount exceeds your available balance. Your Available Balance is: {$availableBalance}";
                    return redirect()->back()->with('error', $errorMessage);
                }
            }
            
            // Process withdrawal if validation passes
            $wdata                      = new UserWithdrawRequest();
            $reference_id               = RandcardStr(15);
            $wdata->user_id             = $user->id;
            $wdata->invest_id           = $balanceData->invest_id;
            $wdata->reference_id        = $reference_id;
            $wdata->is_full_withdraw    = 2;
            $wdata->amount              = $request->amount;
            $wdata->request_date        = now()->format('Y-m-d H:i:s');
            $wdata->status              = 0;
            $wdata->save();
            
            /// Send Notification to Admin
            $adnoti                    = new AdminNotification();
            $adnoti->title             = "Investment Withdrawal Request Amount";
            $adnoti->message           = "Get new withdraw request from " . $user->name . "!!";
            $adnoti->notification_type = 1;
            $adnoti->is_read           = 0;
            $adnoti->created_at        = now()->format('Y-m-d H:i:s');
            $adnoti->updated_at        = now()->format('Y-m-d H:i:s');
            $adnoti->save();
            
            DB::commit();
            $request->session()->flash('success', 'Your Withdraw Request Submitted Successfully');
            return redirect()->back();
            
        } catch (\Exception $e) {
            $errorMessage = 'Failed to Submit Withdraw Request: ' . $e->getMessage();
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    // #########################################################################

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
