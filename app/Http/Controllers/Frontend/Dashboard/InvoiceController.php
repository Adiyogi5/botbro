<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
// use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class InvoiceController extends Controller
{
    public function index(Request $request, $id)
    {
        $title = 'Order Invoice';

        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        $my_order_detail = Order::Where('user_id', $user->id)
            ->Where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        return View('frontend.dashboard.invoice', compact('title', 'my_order_detail'));

    }

    public function generateAndDownloadPDF($id)
    {
        $user = auth('web')->user();

        DB::statement("SET SQL_MODE = ''");
        $my_order_detail = Order::Where('user_id', $user->id)
            ->Where('id', $id)
            ->whereNull('deleted_at')
            ->get();
        // dd($my_order_detail);
        // $data  = [
        //     'name' => $my_order_detail->customer_name,
        //     'date' => $my_order_detail->date,

        // ];

        $pdf = PDF::loadView('frontend.dashboard.invoicepdf', compact('my_order_detail'));
        return $pdf->download('invoice.pdf');
    }

}
