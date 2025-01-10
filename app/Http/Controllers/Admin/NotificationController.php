<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use App\Jobs\PushNotificationJob;
use Yajra\DataTables\DataTables;

class NotificationController extends Controller
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
            $records = Notification::select('id', 'title', 'message', 'attachment', 'users', 'created_at')->where([['deleted_at', NULL]])->get();

            return DataTables::of($records)
                ->addColumn('action', function ($row) {
                    $userlist =  json_decode($row->users);

                    return $action_btn = '<button data-id="' . $row->id . '" class="btn btn-sm btn-danger delete_record"><i class="fa fa-trash"></i> Delete</button> 
                 &nbsp; <button title="Member List"class="btn btn-outline-info memberData" id="' . $row["id"] . '" data-toggle="modal" data-target="#myModal">' . count($userlist) . '</button>';
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at));
                })
                ->editColumn('attachment', function ($row) {
                    if ($row->attachment) {
                        return '<a href="' . url(asset($row->attachment)) . '" target="_blank"><i class="fa fa-download"></a>';
                    } else {
                        return "No Attachment";
                    }
                })
                ->removeColumn('id')
                ->rawColumns(['status', 'created_at', 'attachment', 'action'])->make(true);
        }
        $title = "Push Notification";
        return view('admin.notifications.index', compact('title'));
    }

    public function create(Request $request)
    {
        $title = "Push Notification";
        return view('admin.notifications.send', compact('title'));
    }


    public function store(Request $request)
    {
        $validate = $request->validate([
            'usertype' => 'required|',
            'message' => 'required|',
            'title' => 'required|',
            'attachment' => 'mimes:jpeg,png,jpg,gif,pdf,xls,xlsx,csv,doc,docx|max:2048',
        ]);

        $users = [];
        $usersId = explode(',', $request->user_id);
        if ($request->usertype == 1) {
            $users = User::select('id', 'name', 'mobile', 'fcm_id', 'device_id')->where([['deleted_at', null]])->get();
        } elseif ($request->usertype == 2) {
            $users = User::select('id', 'name', 'mobile', 'fcm_id', 'device_id')->where('deleted_at', null)->whereIn('id', $usersId)->get();
        }

        $data = new Notification;
        $data->message = $request->message;
        $data->title = $request->title;
        $notiusers = [];
        foreach ($users as $ukey => $uvalue) {
            $notiusers[] = array(
                'id' => (string)$uvalue->id,
                'name' => $uvalue->name,
                'mobile' => $uvalue->mobile,
            );
        }
        $data->users = json_encode($notiusers);

        $attachment_file = "";
        if ($file = $request->file('attachment')) {
            $destinationPath    = UPLOADFILES . 'notifications/';
            if (!empty($request->old_attachment)) {
                delete_file($destinationPath . $request->old_attachment);
            }
            $uploadfile = time() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $uploadfile);
            $attachment_file = $destinationPath . $uploadfile;
        }
        $data->attachment = $attachment_file;
        $data->save();
        $details = [];
        foreach ($users as $key => $value) {
            $details[] = array(
                'mobile' => $value->mobile,
                'user' => $value->name,
                'device_id' => $value->device_id,
                'fcm_id' => $value->fcm_id,
                'title' => $request->title,
                'message' => $request->message,
                'attachment' => (!empty($attachment_file)) ? asset($attachment_file) : '',
            );
        }

        // send main by laravel job
        PushNotificationJob::dispatch($details);

        $request->session()->flash('success', 'Notification Send Successfully!!');
        return redirect(url('admin/notifications'));
    }

    public function get_users(Request $request)
    {
        $user = $request->data_id;
        $users = '';
        if ($user == 2) {
            $users = User::select('id', 'mobile', 'name')->where([['deleted_at', null]])->get();
        }
        $data = '<option value="">Select Users</option>';
        foreach ($users as $key => $value) {
            $data .= '<option value="' . $value->id . '">' . $value->mobile . ' (' . $value->name . ')</option>';
        }
        $res = array('status' => TRUE, 'data' => $data);
        return response()->json($res);
    }


    public function getusersdata(Request $request)
    {
        $records = Notification::select('id', 'title', 'message', 'attachment', 'users', 'created_at')->where([['id', $request->id]])->where([['deleted_at', NULL]])->first();
        $title = "Member List";
        return view('admin.notifications.getusersdata', compact('title', 'records'));
    }
}
