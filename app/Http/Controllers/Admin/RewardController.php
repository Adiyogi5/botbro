<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserRewardLog;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class RewardController extends Controller
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
            $date     = $request->date ?? null;
            $reward_status   = $request->reward_status ?? null;

            $records = UserRewardLog::select('user_reward_logs.*','users.name as customer_name','reward_masters.name as reward')
            
            ->leftjoin('users','users.id','=','user_reward_logs.user_id')
            ->leftjoin('reward_masters','reward_masters.id','=','user_reward_logs.reward_id');

            if (!empty($date)) {
                $records = $records->whereDate('user_reward_logs.date', '=', date('Y-m-d', strtotime($date)));
            }

            if (!empty($reward_status)) {
                $records = $records->where('user_reward_logs.reward_status', '=', $reward_status);
            } 
            
            return DataTables::of($records)
                ->editColumn('reward_status',function($row){
                    $reward_status = '';
                    $reward_status = $row['reward_status'] == 0 ?'<span class="d-flex"><a href="'.url('admin/rewards/approve/'.$row['id']).'" class="btn btn-sm btn-success approve_btn mx-1">Approve</a><a href="'.url('admin/rewards/reject/'.$row['id']).'" class="btn btn-sm btn-danger reject_btn">Reject</a></span>' : ($row['reward_status'] == 1 ? '<span  class="badge btn-success">Approved</span>' : '<span class="badge btn-danger">Rejected</span>');  
                    return $reward_status;
                })
                ->editColumn('date', function ($row) {
                    return date('d-m-Y h:i a', strtotime($row['date']));
                })
               
                ->removeColumn('id')
                ->rawColumns(['date','reward_status'])->make(true);
        }

        $title = "Reward Logs";
        return view('admin.reward.index', compact('title'));
    }

    public function reject(Request $request, $id){

        UserRewardLog::where('id',$id)->update(['reward_status' => 2]);

        return Redirect::back()->with('error','Reward Request Rejected Successfully !!');

    }

    public function approve(Request $request, $id){

        UserRewardLog::where('id',$id)->update(['reward_status' => 1]);

        return Redirect::back()->with('success','Reward Request Approved Successfully !!');

    }
}
