<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BadgeMaster;
use App\Models\GeneralSetting;
use App\Models\Investment;
use App\Models\Ledger;
use App\Models\MembershipDetail;
use App\Models\RefferEarnsLedger;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserBadgeLog;
use App\Models\UserProfitSharingLog;
use App\Models\UserReferral;
use App\Models\UserReferralWithdrawRequest;
use App\Models\UserRewardLog;
use App\Models\UserWallet;
use App\Models\UserWithdrawRequest;
use App\Rules\CheckRefer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class UserController extends Controller
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
        checkPermission($this, 115);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $is_agent_allow = $request->is_agent_allow ?? null;
            $is_approved    = $request->is_approved ?? null;

            $records = User::select(
                'users.*',
                DB::raw("
                        CASE
                            WHEN (SELECT COUNT(*) FROM user_referrals
                                  JOIN users ON users.id = user_referrals.refer_id
                                  WHERE user_referrals.referral_id = users.id
                                  AND users.is_approved = 1) > 5
                            OR (SELECT COUNT(*) FROM user_referrals
                                  WHERE user_referrals.referral_id = users.id) > 6
                            THEN 'Agent'
                            ELSE 'Member'
                        END AS badge_level
                    "),
                'users.created_at as date'
            )
                ->leftJoin('user_referrals', 'users.id', '=', 'user_referrals.refer_id')
                ->whereNull('users.deleted_at');

            // Filter by membership status
            if (! is_null($is_approved) && $is_approved !== '') {
                $records->where('users.is_approved', $is_approved);
            }

            // Filter by badge level (Member or Agent)
            if (! is_null($is_agent_allow) && $is_agent_allow !== '') {
                if ($is_agent_allow == 1) {
                    $records->having('badge_level', 'Agent');
                } else {
                    $records->having('badge_level', 'Member');
                }
            }

            return DataTables::of($records)
                ->addColumn('status', function ($row) {
                    $status = ($row->status == 1) ? 'checked' : '';
                    return '<input class="tgl_checkbox tgl-ios" data-id="' . $row->id . '"
                id="cb_' . $row->id . '"  type="checkbox" ' . $status . '><label for="cb_' . $row->id . '"></label>';
                })
                ->addColumn('name', function ($row) {
                    if ($row->is_agent_allow == 1) {$isagent = '<i class="fa fa-user-circle" style="font-size: 20px; color: #dc3545;"></i>';} else { $isagent = '';}
                    return $isagent . ' <a href="' . route('admin.customer.details', $row->id) . '" title="Details">' . $row->name . '</a> <br>' . $row->email;
                })
                ->editColumn('date', function ($row) {
                    return date('d-m-Y h:i a', strtotime($row['date']));
                })
                ->addColumn('is_approved', function ($row) {
                    // Fetch membership details
                    $membershipDetails = MembershipDetail::where('user_id', $row->id)
                        ->whereNull('deleted_at')
                        ->first();

                    // Case 1: No membership and Pending
                    // if (is_null($membershipDetails) && $row->is_approved == 0) {
                    //     return '<div class="d-flex align-items-center">
                    //                 <small class="btn btn-sm btn-warning rounded mx-auto px-3">Rejected</small>
                    //             </div>';
                    // }
                    // Case 2: Pending but membership exists
                    if ($row->is_approved == 0) {
                        $approveButton = '<button data-id="' . $row->id . '" class="btn btn-sm btn-success approve-membership" title="Approve Membership"><i class="fa-solid fa-check"></i></button>';
                        $rejectButton  = '<button data-id="' . $row->id . '" class="btn btn-sm btn-danger reject-membership" title="Reject Membership"><i class="fa-solid fa-xmark"></i></button>';

                        return '<div class="d-flex align-items-center gap-2">
                                    <small class="btn btn-sm btn-warning rounded text-nowrap">Pending</small>
                                    ' . $approveButton . '
                                    ' . $rejectButton . '
                                </div>';
                    }
                    // Case 3: Approved
                    if ($row->is_approved == 1) {
                        return '<div class="d-flex align-items-center">
                                    <small class="btn btn-sm btn-success rounded mx-auto px-3">Approved</small>
                                </div>';
                    }
                    // Default: Rejected (fallback case)
                    return '<div class="d-flex align-items-center">
                                <small class="btn btn-sm btn-danger rounded mx-auto px-3">Rejected</small>
                            </div>';
                })
                ->orderColumn('date', function ($row, $order) {
                    $row->orderBy('date', $order);
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.customer.edit', $row->id) . '" class="btn btn-sm btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
                    <a href="' . route('admin.address.index', ['user_id' => $row->id]) . '" class="btn btn-sm btn-primary" title="Edit Address"><i class="fa fa-map-marker"></i></a>
                    <button data-id="' . $row->id . '" class="btn btn-sm btn-danger delete_record" title="Delete"><i class="fa fa-trash"></i></button>';
                })
                ->removeColumn('id')
                ->rawColumns(['status', 'is_approved', 'name', 'action'])->make(true);
        }

        $title  = "Customer";
        $status = $request->status;
        return view('admin.users.index', compact(['title', 'status']));
    }

    public function approveMembership(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            // Update the `is_approved` field in `users` table
            $user              = User::findOrFail($userId);
            $user->is_approved = 1;
            $user->save();

            // Update the `membership_details` table
            $membership = MembershipDetail::where('user_id', $userId)->first();
            if (! $membership) {
                return response()->json(['success' => false, 'message' => 'Membership details not found.']);
            }

            $currentDate = now()->toDateString();
            $endDate     = now()->addDays(365)->toDateString();

            $membership->membership_start_date = $currentDate;
            $membership->membership_end_date   = $endDate;
            $membership->save();

            // Update user referral balance
            $userrefferBonus = User::select('users.*', 'user_referrals.refer_id', 'user_referrals.referral_id')
                ->join('user_referrals', 'user_referrals.referral_id', '=', 'users.id')
                ->where('user_referrals.refer_id', $userId)
                ->where('users.status', 1)
                ->first();

            if ($userrefferBonus) {
                $userLedgerBalance = RefferEarnsLedger::where('user_id', $userrefferBonus->referral_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $previousBalance = $userLedgerBalance ? $userLedgerBalance->balance : 0;
                $interestAmount  = 2000; // Consider making this dynamic if needed

                RefferEarnsLedger::create([
                    'user_id'         => $userrefferBonus->referral_id,
                    'refer_id'        => $userrefferBonus->refer_id,
                    'date'            => now()->format('Y-m-d'),
                    'description'     => 'Your Referral - ' . $user->name . ' Membership Approved.',
                    'rate_of_intrest' => 0,
                    'credit'          => $interestAmount,
                    'debit'           => 0,
                    'balance'         => $previousBalance + $interestAmount,
                ]);

                // Optional: Log success message
                Log::info("Referral balance updated and ledger entry created for user ID: {$userrefferBonus->referral_id}.");
            }

            return response()->json(['success' => true, 'message' => 'Membership approved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

//     $userledgerBalance = User::select('users.*', 'reffer_earns_ledgers.refer_id', 'reffer_earns_ledgers.user_id')
    //     ->join('reffer_earns_ledgers', 'reffer_earns_ledgers.user_id', '=', 'users.id')
    //     ->where('reffer_earns_ledgers.refer_id', $userId)
    //     ->where('users.status', 1)
    //     ->first();

// // If referral details are found, update balance
    // if ($userrefferBonus) {

//     RefferEarnsLedger::create([
    //         'user_id'         => $userrefferBonus->referral_id,
    //         'refer_id'        => $userrefferBonus->refer_id,
    //         'date'            => now()->format('Y-m-d'),
    //         'description'     => 'Your Refferal -'. $user->name .' Membership Approved.',
    //         'rate_of_intrest' => 0,
    //         'credit'          => 2000,
    //         'debit'           => 0,
    //         'balance'         => $userledgerBalance ? $userledgerBalance->balance + 2000 : 2000,
    //     ]);
    //     // Optional: Log success message
    //     Log::info("Referral balance updated and ledger entry created for user ID: {$userrefferBonus->referral_id}.");
    // }

    public function rejectMembership(Request $request)
    {
        $userId = $request->input('user_id');

        // Update the 'is_approved' field to 0
        $user = User::find($userId);
        if ($user) {
            $user->is_approved = 2;
            $user->save();

            // Soft delete the related membership details record
            $membership = MembershipDetail::where('user_id', $userId)->first();
            if ($membership) {
                $membership->delete(); // Soft delete
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function create()
    {
        $title = "Add Customer";
        return view('admin.users.add', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'max:100'],
            'email'       => ['required', 'max:150', 'unique:users,email'],
            'mobile'      => ['required', 'min:10', 'numeric', 'unique:users,mobile'],
            'image'       => ['mimes:png,jpg,jpeg,JPG,JPEG,png', 'max:2048'],
            'reffer_code' => ['nullable', 'max:100', new CheckRefer('users')],
            'password'    => ['required', 'confirmed'],
        ]);

        DB::beginTransaction();
        try {
            $data         = [];
            $reference_id = RandcardStr(15);
            $data         = [
                'reference_id' => $reference_id,
                'name'         => $request->name,
                'email'        => $request->email,
                'mobile'       => $request->mobile,
                'password'     => Hash::make($request->password),
                'reffer_code'  => RandcardStr(8),
                'status'       => 1,
            ];
            $path = 'uploads/users/';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if (! empty($ivalue['old_image'])) {
                    delete_file($ivalue['old_image']);
                }
                $destinationPath = 'public\\' . $path;
                $uploadImage     = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
                $data['image'] = $path . '/' . $uploadImage;
            }
            $user = User::create($data);

            /// Create a contacts on razorpay
            $payment_setting = GeneralSetting::where('setting_type', 6)->get()->toArray();
            $payment_setting = array_combine(array_column($payment_setting, 'setting_name'), array_column($payment_setting, 'filed_value'));
            $payment_key     = $payment_setting['razorpay_keyid'];
            $payment_secret  = $payment_setting['razorpay_secretkey'];
            // Data for the request
            $data = [
                "name"         => $request->name,
                "email"        => $request->email,
                "contact"      => $request->mobile,
                "type"         => "customer",
                "reference_id" => $reference_id,
            ];
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL            => 'https://api.razorpay.com/v1/contacts',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => '',
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => 'POST',
                CURLOPT_POSTFIELDS     => json_encode($data),
                CURLOPT_USERPWD        => $payment_key . ':' . $payment_secret,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                ],
            ]);
            $rpaycustmer = curl_exec($curl);
            curl_close($curl);
            $rpaycustmer = json_decode($rpaycustmer);
            if (! empty($rpaycustmer->error)) {
                $error = $rpaycustmer->error->description;
                DB::rollback();
                return back()->with('error', $error);
            }
            $rzcontactId = $rpaycustmer->id;
            User::where('id', $user->id)->update(['rzcontact_id' => $rzcontactId]);

            /// Update Badge Master
            $badge_master = BadgeMaster::where(['id' => 1])->first();
            if (! empty($badge_master)) {
                UserBadgeLog::updateOrCreate(['user_id' => $user->id, 'badge_id' => 0], [
                    'user_id'        => $user->id,
                    'badge_id'       => $badge_master->id,
                    'date'           => Carbon::now()->toDateString(),
                    'purchase_count' => 0,
                    'particulars'    => $badge_master->name . ' allotted to ' . $user->name . ' on registration.',
                ]);
            }
            if (! empty($request->reffer_code)) {
                $refer_user = User::where(['reffer_code' => $request->reffer_code])->first();
                if (! empty($refer_user)) {
                    UserReferral::updateOrCreate(['refer_id' => $user->id], ['refer_id' => $user->id, 'referral_id' => $refer_user->id]);
                }
            }
            DB::commit();

            $request->session()->flash('success', 'Customer Added Successfully!!');
            return redirect(route('admin.customer.index'));

        } catch (\Exception $e) {
            DB::rollback();
            $errorMessage = 'Failed to Register: ' . $e->getMessage();
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    public function edit(Request $request, $id)
    {
        $title = "Edit Customer";
        $data  = User::where('id', $id)->first();

        if (! empty($data)) {
            return view('admin.users.edit', compact('title', 'data'));
        } else {
            $title   = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title', 'message'));
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->first();

        if ($user) {
            $request->validate([
                'name'   => ['required', 'max:100'],
                'email'  => ['required', 'max:150', 'unique:users,email,' . $id . ',id,deleted_at,NULL'],
                'mobile' => ['required', 'min:10', 'numeric', 'unique:users,mobile,' . $id . ',id,deleted_at,NULL'],
                'image'  => ['mimes:png,jpg,jpeg,JPG,JPEG,png', 'max:2048'],
            ]);

            $data = [
                'name'     => $request->name,
                'email'    => $request->email,
                'mobile'   => $request->mobile,
                'password' => Hash::make($request->password),
                'status'   => 1,
            ];

            $path = 'uploads/users/';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if (! empty($ivalue['old_image'])) {
                    delete_file($ivalue['old_image']);
                }
                $destinationPath = 'public\\' . $path;
                $uploadImage     = time() . '_' . rand(99999, 1000000) . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->put($destinationPath . '/' . $uploadImage, file_get_contents($file));
                $data['image'] = $path . '/' . $uploadImage;
            }
            $user->update($data);

            $rzcontact_id = $user->rzcontact_id;
            /// Create a contacts on razorpay
            try {
                $payment_setting = GeneralSetting::where('setting_type', 6)->get()->toArray();
                $payment_setting = array_combine(array_column($payment_setting, 'setting_name'), array_column($payment_setting, 'filed_value'));
                $payment_key     = $payment_setting['razorpay_keyid'];
                $payment_secret  = $payment_setting['razorpay_secretkey'];
                // Data for the request
                $data = [
                    "name"    => $request->name,
                    "email"   => $request->email,
                    "contact" => $request->mobile,
                ];
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL            => 'https://api.razorpay.com/v1/contacts/' . $rzcontact_id . '',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING       => '',
                    CURLOPT_MAXREDIRS      => 10,
                    CURLOPT_TIMEOUT        => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST  => 'PATCH',
                    CURLOPT_POSTFIELDS     => json_encode($data),
                    CURLOPT_USERPWD        => $payment_key . ':' . $payment_secret,
                    CURLOPT_HTTPHEADER     => [
                        'Content-Type: application/json',
                    ],
                ]);
                $rpaycustmer = curl_exec($curl);
                curl_close($curl);
            } catch (\Exception $e) {

            }

            $request->session()->flash('success', 'Customer Update Successfully!!');
            return redirect(route('admin.customer.index'));
        } else {
            $request->session()->flash('error', 'Customer Does Not Exist!!');
            return redirect(route('admin.customer.index'));
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = User::where('id', $id)->delete();
        } else {
            return 0;
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $data         = User::where('id', $request->id)->first();
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
        }
    }

    public function details(Request $request, $id)
    {
        $title = "Customer Details";
        $data  = User::where('id', $id)->first();

        if (! empty($data)) {
            return view('admin.users.details', compact('title', 'data', 'id'));
        } else {
            $title   = "404 Error Page";
            $message = '<i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found!!';
            return view('admin.error', compact('title', 'message'));
        }
    }

    public function user_badges(Request $request, $id)
    {
        if ($request->ajax()) {
            $records = UserBadgeLog::select('user_badge_logs.*', 'users.name as user_name', 'badge_masters.name as badge_level')
                ->where([['user_id', $id]])
                ->leftjoin('badge_masters', 'badge_masters.id', '=', 'user_badge_logs.badge_id')
                ->leftjoin('users', 'users.id', '=', 'user_badge_logs.user_id')->get();

            return DataTables::of($records)

                ->editColumn('date', function ($row) {
                    return date('d-m-Y h:i a', strtotime($row['date']));
                })

                ->removeColumn('id')
                ->rawColumns(['date'])->make(true);
        }

    }
    public function user_refer(Request $request, $id)
    {
        if ($request->ajax()) {
            $records = UserReferral::select('user_referrals.*', 'refrer.name as refrer_name', 'refrral.reffer_code as ref_code', 'refrral.name as user_refrral')
                ->where([['user_referrals.referral_id', $id]])
                ->leftjoin('users as refrer', 'refrer.id', '=', 'user_referrals.refer_id')
                ->leftjoin('users as refrral', 'refrral.id', '=', 'user_referrals.referral_id')->get();

            return DataTables::of($records)
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y h:i a', strtotime($row['created_at']));
                })
                ->removeColumn('id')
                ->rawColumns(['created_at'])->make(true);
        }
    }

    public function user_investments(Request $request, $id)
    {
        if ($request->ajax()) {
            $records = Investment::select('investments.*')
                ->where([['investments.user_id', $id]])
                ->leftjoin('users', 'users.id', '=', 'investments.user_id')->get();

            return DataTables::of($records)
                ->editColumn('invest_no', function ($row) {
                    return '<a href="' . url('admin/investments/' . $row->id) . '" target="_blank">' . $row->invest_no . '</a>';
                })
                ->editColumn('payment_status', function ($row) {
                    $status_badge = $row->payment_status == 1 ? 'bg-success' : 'bg-danger';
                    $status_text  = $row->payment_status == 1 ? 'Paid' : 'Pending';

                    return '<span class="badge ' . $status_badge . '">' . $status_text . '</span>';
                })
                ->editColumn('date', function ($row) {
                    return date('d-m-Y', strtotime($row['date']));
                })
                ->removeColumn('id')
                ->rawColumns(['date', 'invest_no', 'payment_status'])->make(true);
        }
    }

    public function user_wallet(Request $request, $id)
    {

        if ($request->ajax()) {
            $records = UserWallet::select('user_wallets.*', 'users.name as customer_name')
                ->where([['user_wallets.user_id', $id]])
                ->leftjoin('users', 'users.id', '=', 'user_wallets.user_id')->get();

            return DataTables::of($records)
                ->editColumn('payment_type', function ($row) {
                    return (($row->payment_type == 1) ? 'Credit' : 'Debit');
                })
                ->editColumn('date', function ($row) {
                    return date('d-m-Y h:i a', strtotime($row['date']));
                })

                ->removeColumn('id')
                ->rawColumns(['date'])->make(true);
        }
    }

    public function user_withdraw(Request $request, $id)
    {
        if ($request->ajax()) {
            $records = UserWithdrawRequest::select('user_withdraw_requests.*', 'users.name as customer_name', 'investments.invest_no')
                ->where([['user_withdraw_requests.user_id', $id]])
                ->leftjoin('users', 'users.id', '=', 'user_withdraw_requests.user_id')
                ->leftjoin('investments', 'investments.id', '=', 'user_withdraw_requests.invest_id')->get();

            return DataTables::of($records)
                ->addColumn('balance', function ($row) {
                    $latestLedgerEntry = Ledger::where('user_id', $row->user_id)
                        ->where('invest_id', $row->invest_id)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    return $latestLedgerEntry ? $latestLedgerEntry->balance : 'N/A';
                })
                ->editColumn('reference_id', function ($row) {
                    $rzpayout = ! empty($row->rzpayout_id) ? $row->rzpayout_id : '--';
                    return "<b>RefId</b><br><small>" . $row->reference_id . '</small>';
                })
                ->editColumn('payment_type', function ($row) {
                    return (($row->payment_type == 1) ? 'Credit' : 'Debit');
                })
                ->editColumn('payment_method', function ($row) {
                    return 'Offline';
                })
                ->editColumn('request_date', function ($row) {
                    return date('d-m-Y h:i a', strtotime($row['request_date']));
                })
                ->editColumn('status', function ($row) {
                    $status = '';

                    $status = $row['status'] == 0 ? '<span class="d-flex"><a href="' . url('admin/customer/withdraw_approve/' . $row['id']) . '" class="btn btn-sm btn-success approve_btn mx-1">Approve</a><a href="' . url('admin/customer/withdraw_reject/' . $row['id']) . '" class="btn btn-sm btn-danger reject_btn">Reject</a></span>' : ($row['status'] == 1 ? '<span  class="badge btn-success">Approved</span>' : '<span class="badge btn-danger">Rejected</span>');
                    return $status;
                })
                ->removeColumn('id')
                ->rawColumns(['reference_id', 'request_date', 'payment_method', 'status'])->make(true);
        }

    }

    public function withdraw_approve(Request $request, $id)
    {
        try {
            $withdraw_request_data = UserWithdrawRequest::find($id);

            if (! $withdraw_request_data) {
                return Redirect::back()->with('error', 'Withdrawal request not found!');
            }

            DB::statement("SET SQL_MODE = ''");

            $userslist = User::select('users.*', 'investments.id as invest_id', 'investments.user_id', 'investments.rate_of_intrest', 'investments.date')
                ->leftJoin('investments', 'investments.user_id', '=', 'users.id')
                ->where('users.id', $withdraw_request_data->user_id)
                ->where('investments.id', $withdraw_request_data->invest_id)
                ->where('users.status', 1)
                ->whereNull('users.deleted_at')
                ->where('investments.payment_status', 1)
                ->where('investments.is_approved', 1)
                ->whereNull('investments.deleted_at')
                ->first();

            if (! $userslist) {
                return Redirect::back()->with('error', 'User investment details not found!');
            }

            $latestLedgerEntry = Ledger::where('user_id', $userslist->user_id)
                ->where('invest_id', $userslist->invest_id)
                ->orderBy('created_at', 'desc')
                ->first();

            // Check if a valid balance exists
            if ($latestLedgerEntry && $latestLedgerEntry->balance > 0) {
                $rateOfInterest         = $userslist->rate_of_intrest;
                $previousBalance        = $latestLedgerEntry->balance;
                $withdraw_requestAmount = $withdraw_request_data->amount;
                $newBalance             = $previousBalance - $withdraw_requestAmount;

                if ($withdraw_requestAmount > $previousBalance) {
                    return Redirect::back()->with('error', 'Insufficient balance for withdrawal!');
                }

                DB::beginTransaction();

                // Insert ledger entry for withdrawal
                Ledger::create([
                    'user_id'         => $userslist->user_id,
                    'invest_id'       => $userslist->invest_id,
                    'date'            => Carbon::now()->format('Y-m-d'),
                    'description'     => 'Withdrawal Request Approved',
                    'rate_of_intrest' => 0,
                    'credit'          => 0,
                    'debit'           => $withdraw_requestAmount,
                    'balance'         => $newBalance,
                ]);

                // Update withdrawal request status
                UserWithdrawRequest::where('id', $id)->update(['status' => 1]);

                DB::commit();

                return Redirect::back()->with('success', 'Amount Withdrawn Successfully!');
            } else {
                UserWithdrawRequest::where('id', $id)->update(['status' => 2]);
                return Redirect::back()->with('error', 'Insufficient balance for withdrawal.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function withdraw_reject(Request $request, $id)
    {
        UserWithdrawRequest::where('id', $id)->update(['status' => 2]);

        return Redirect::back()->with('error', 'Fund Request Rejected Successfully !!');
    }

    public function user_referral_withdraw(Request $request, $id)
    {
        if ($request->ajax()) {
            $records = UserReferralWithdrawRequest::select('user_referral_withdraw_requests.*', 'users.name as customer_name', 'users.user_reffer_balance')
                ->where([['user_referral_withdraw_requests.user_id', $id]])
                ->leftjoin('users', 'users.id', '=', 'user_referral_withdraw_requests.user_id')->get();

            return DataTables::of($records)
                ->editColumn('reference_id', function ($row) {
                    $rzpayout = ! empty($row->rzpayout_id) ? $row->rzpayout_id : '--';
                    return "<b>RefId</b><br><small>" . $row->reference_id . '</small>';
                })
                ->editColumn('payment_type', function ($row) {
                    return (($row->payment_type == 1) ? 'Credit' : 'Debit');
                })
                ->editColumn('payment_method', function ($row) {
                    return 'Offline';
                })
                ->editColumn('request_date', function ($row) {
                    return date('d-m-Y h:i a', strtotime($row['request_date']));
                })
                ->editColumn('status', function ($row) {
                    $status = '';

                    $status = $row['status'] == 0 ? '<span class="d-flex"><a href="' . url('admin/customer/referral-withdraw-approve/' . $row['id']) . '" class="btn btn-sm btn-success approve_btn mx-1">Approve</a><a href="' . url('admin/customer/referral-withdraw-reject/' . $row['id']) . '" class="btn btn-sm btn-danger reject_btn">Reject</a></span>' : ($row['status'] == 1 ? '<span  class="badge btn-success">Approved</span>' : '<span class="badge btn-danger">Rejected</span>');
                    return $status;
                })
                ->removeColumn('id')
                ->rawColumns(['reference_id', 'request_date', 'payment_method', 'status'])->make(true);
        }

    }

    public function referral_withdraw_approve(Request $request, $id)
    {
        try {
            // Fetch withdrawal request data
            $withdraw_request_data = UserReferralWithdrawRequest::find($id);

            if (! $withdraw_request_data) {
                return Redirect::back()->with('error', 'Withdrawal request not found!');
            }

            // Check if the requested balance is available
            $wallet_data = User::select('user_reffer_balance')
                ->where('id', $withdraw_request_data->user_id)
                ->first();

            if ($wallet_data && $wallet_data->user_reffer_balance >= $withdraw_request_data->request_amount) {
                // Calculate new balance after withdrawal
                $newuser_reffer_balance = $wallet_data->user_reffer_balance - $withdraw_request_data->request_amount;

                // Update user's referral balance
                User::where('id', $withdraw_request_data->user_id)
                    ->update(['user_reffer_balance' => $newuser_reffer_balance]);

                // Approve the withdrawal request
                UserReferralWithdrawRequest::where('id', $id)->update(['status' => 1]);

                return Redirect::back()->with('success', 'Referral Amount Withdrawn Successfully!');
            } else {
                // Reject the withdrawal due to insufficient balance
                UserReferralWithdrawRequest::where('id', $id)->update(['status' => 2]);
                return Redirect::back()->with('error', 'Insufficient balance for withdrawal.');
            }
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function referral_withdraw_reject(Request $request, $id)
    {
        UserReferralWithdrawRequest::where('id', $id)->update(['status' => 2]);

        return Redirect::back()->with('error', 'Fund Request Rejected Successfully !!');
    }

    public function user_referandcommission(Request $request, $id)
    {
        if ($request->ajax()) {
            $records = RefferEarnsLedger::where('user_id', $id)
                ->orderBy('id', 'desc')
                ->whereNull('deleted_at')
                ->get();

            return DataTables::of($records)
                ->editColumn('date', function ($row) {
                    return date('d-m-Y', strtotime($row->date));
                })
                ->editColumn('rate_of_intrest', function ($row) {
                    return $row->rate_of_intrest > 0 ? $row->rate_of_intrest . '%' : '--';
                })
                ->editColumn('debit', function ($row) {
                    return $row->debit ? '<span class="text-danger">' . number_format($row->debit, 2) . '</span>' : '--';
                })
                ->editColumn('credit', function ($row) {
                    return $row->credit ? '<span class="text-success">' . number_format($row->credit, 2) . '</span>' : '--';
                })
                ->editColumn('balance', function ($row) {
                    return '<span class="fw-bold text-primary">' . number_format($row->balance, 2) . '</span>';
                })
                ->removeColumn('id')
                ->rawColumns(['debit', 'credit', 'balance']) // Allow HTML formatting
                ->make(true);
        }
    }

    public function user_address(Request $request, $id)
    {

        if ($request->ajax()) {
            $records = UserAddress::select('user_addresses.*', 'users.name as customer_name', 'countries.name as country', 'states.name as state', 'cities.name as city')
                ->where([['user_addresses.user_id', $id]])
                ->leftjoin('users', 'users.id', '=', 'user_addresses.user_id')
                ->leftjoin('countries', 'countries.id', '=', 'user_addresses.country_id')
                ->leftjoin('states', 'states.id', '=', 'user_addresses.state_id')
                ->leftjoin('cities', 'cities.id', '=', 'user_addresses.city_id')->get();

            return DataTables::of($records)
                ->editColumn('default_id', function ($row) {
                    return $row->default_id == 1 ? 'Yes' : 'No';
                })

                ->removeColumn('id')
                ->rawColumns(['default_id'])->make(true);
        }

    }

    public function user_rewards(Request $request, $id)
    {

        if ($request->ajax()) {
            $records = UserRewardLog::select('user_reward_logs.*', 'users.name as customer_name', 'reward_masters.name as reward')
                ->where([['user_reward_logs.user_id', $id]])
                ->leftjoin('users', 'users.id', '=', 'user_reward_logs.user_id')
                ->leftjoin('reward_masters', 'reward_masters.id', '=', 'user_reward_logs.reward_id')->get();

            return DataTables::of($records)
                ->editColumn('reward_status', function ($row) {
                    $reward_status = '';

                    $reward_status = $row['reward_status'] == 0 ? '<span  class="badge btn-secondary">Pending</span>' : ($row['reward_status'] == 1 ? '<span  class="badge btn-success">Approved</span>' : '<span class="badge btn-danger">Rejected</span>');
                    return $reward_status;
                })
                ->editColumn('date', function ($row) {
                    return date('d-m-Y h:i a', strtotime($row['date']));
                })

                ->removeColumn('id')
                ->rawColumns(['date', 'reward_status'])->make(true);
        }

    }

    public function user_profit_sharing(Request $request, $id)
    {

        if ($request->ajax()) {
            $records = UserProfitSharingLog::select('user_profit_sharing_logs.*', 'users.name as customer_name', 'admins.name as created_by')
                ->where([['user_profit_sharing_logs.user_id', $id]])
                ->leftjoin('users', 'users.id', '=', 'user_profit_sharing_logs.user_id')
                ->leftjoin('admins', 'admins.id', '=', 'user_profit_sharing_logs.create_by')->get();

            return DataTables::of($records)

                ->editColumn('date', function ($row) {
                    return date('d-m-Y h:i a', strtotime($row['date']));
                })

                ->removeColumn('id')
                ->rawColumns(['date'])->make(true);
        }

    }

}
