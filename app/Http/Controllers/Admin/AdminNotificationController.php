<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AdminNotificationController extends Controller
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
        checkPermission($this, 119);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = AdminNotification::select('id', 'title', 'message' ,'is_read', 'created_at');

            return DataTables::of($records)
                ->addColumn('action', function ($row) {
                    return '<button title="Member List"class="btn btn-sm btn-info details" id="' . $row["id"] . '" data-toggle="modal" data-target="#myModal"><i class="fa fa-eye"></i> View</button>';
                })
                ->editColumn('is_read', function ($row) {
                    return ($row->is_read == 1) ? '<span class="text-success">Read</span>' : '<span class="text-danger">Not Read</span>';
                })
                ->editColumn('created_at', function ($row) {
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->filterColumn('created_at', function($query, $keyword) {
                    $query->whereDate('created_at', date('Y-m-d', strtotime("{$keyword}")));
                })
                ->orderColumn('created_at', function ($row, $order) {
                    $row->orderBy('created_at', $order);
                })
                ->removeColumn('id')
                ->rawColumns(['is_read', 'created_at', 'action'])->make(true);
        }
        $title = "Admin Notifications";
        return view('admin.admin_notifications.index', compact('title'));
    }

    public function show(Request $request, $id)
    {
        $admin_notification = AdminNotification::select('id', 'title', 'message')
            ->where('id', $id)->first();

        $admin_notification->update(['is_read' => 1]);

        $data = '<ul class="list-group">
            <li class="list-group-item">
                <div class="row mx-0">
                    <div class="col-12 col-sm-4 col-md-4 fw-bold">Title</div>
                    <div class="col-12 col-sm-8 col-md-8">' . $admin_notification->title . '</div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row mx-0">
                    <div class="col-12 col-sm-4 col-md-4 fw-bold">Message</div>
                    <div class="col-12 col-sm-8 col-md-8">' . $admin_notification->message . '</div>
                </div>
            </li>
        </ul>';
        echo $data;
    }
}
