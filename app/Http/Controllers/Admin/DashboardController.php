<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\ContactInquiry;
use App\Models\Order;
use App\Models\Product;
use App\Models\Returns;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\UserProfitSharingLog;
use App\Models\UserRewardLog;
use Illuminate\Notifications\Notification;

class DashboardController extends Controller
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
        checkPermission($this, 101);
    }

    /**
     * Show Admin Dashboard.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $title = "Dashboard";
        $admincount = Admin::where([['deleted_at', NULL]])->where([['id', '!=', 1]])->count(); 
        $usercount = User::where([['deleted_at', NULL]])->count(); 
        $bannercount = Banner::where([['deleted_at', NULL]])->count(); 
        $productcount = Product::where([['deleted_at', NULL]])->count(); 
        $ordercount = Order::where([['deleted_at', NULL]])->count(); 
        $returncount = Returns::count(); 
        $rewardcount = UserRewardLog::count();
        $profitsharecount = UserProfitSharingLog::count(); 
        $blogcount = Blog::where([['deleted_at', NULL]])->count(); 
        $testimonialcount = Testimonial::where([['deleted_at', NULL]])->count(); 
        $inquirycount = ContactInquiry::count(); 
        $notificationcount = AdminNotification::where([['deleted_at', NULL]])->count();

        return view('admin.dashboard', compact('title', 'admincount', 'usercount', 'bannercount', 'productcount', 'ordercount', 'returncount', 'rewardcount', 'profitsharecount', 'blogcount', 'testimonialcount','inquirycount', 'notificationcount'));
    }


    public function permission_denied(Request $request)
    {
        $title = "Permission Denied";
        $message = "You Don’t Have Permission To Access Module, Please Contact To Administrator For More Information";
        return view('admin.error', compact('title', 'message'));
    }
}
