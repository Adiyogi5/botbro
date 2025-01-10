<?php

namespace App\Traits;

use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

trait Invoice
{
    // protected static function boot()
    // {
    //     // parent::boot();
    // }

    public function viewInvoice(Request $request,$order_id)
    {
        $data = Order::where('id',$order_id)->whereNotNull('invoice_no')->first();
        
        if(empty($data))
        {
            return redirect(route('admin.orders'))->with('error',"Invalid request");
        }

        $invoice_products =  OrderProduct::select("order_products.*","product_images.image")
        ->where('order_id',$order_id)
        ->join('product_images', 'product_images.product_id', '=', 'order_products.product_id')
        ->with(['vendor','deliveryPartner','deliveryBoy','deliveryStatus','attributes'])
        ->groupBy('order_products.id')
        ->get()->toArray();

        $setting_data = Setting::get()->toArray();
        $settings = array_combine(array_column($setting_data,'setting_name'),array_column($setting_data,'filed_value'));
        



        $mpdf = new \Mpdf\Mpdf();
        $mpdf->setAutoTopMargin = 'stretch';
        
        $logo = imageexist($settings['logo']);


        $bill_to = $data['customer_name']."<br>";
        
        $bill_to .=$data['shipping_address_1'].'<br>'; 
        if($data['shipping_address_2']){
            $bill_to .= $data['shipping_address_2'].'';
        }
        $bill_to .= $data['shipping_city'].', '.$data['shipping_state'].', '.$data['shipping_country'].'<br>';
        $bill_to .= $data['shipping_postcode'];
    
    

        $stylesheet = '*{box-sizing:border-box}.table-bordered td,.table-bordered th{border:1px solid #ddd;padding:10px;word-break:break-all}body{font-family:Arial,Helvetica,sans-serif;margin:0;padding:0;font-size:16px}.h4-14 h4{font-size:12px;margin-top:0;margin-bottom:5px}.img{margin-left:"auto";margin-top:"auto";height:30px}pre,p{padding:0;margin:0}table{font-family:arial,sans-serif;width:100%;border-collapse:collapse;padding:1px}.hm-p p{text-align:left;padding:1px;padding:5px 4px}td,th{text-align:left;padding:8px 6px}.table-b td,.table-b th{border:1px solid #ddd}.hm-p td,.hm-p th{padding:3px 0}.cropped{float:right;margin-bottom:20px;height:100px;overflow:hidden}.cropped img{width:400px;margin:8px 0 0 80px}.main-pd-wrapper{box-shadow:0 0 10px #ddd;background-color:#fff;border-radius:10px;padding:15px}.table-bordered td,.table-bordered th{border:1px solid #ddd;padding:10px;font-size:14px}';
        $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
        
        $mpdf->SetHTMLHeader(' <div style="display: table-header-group"><table style="width: 100%; table-layout: fixed"><tr><td style="border-left: 1px solid #ddd; border-right: 1px solid #ddd" ><div style=" text-align: center; margin: auto; line-height: 1.5; font-size: 14px; color: #4a4a4a; " ><img width="100" height="100" src="'.$logo.'" style="object-fit:cover;"   /></div></td><td align="right" style=" text-align: right; padding-left: 50px; line-height: 1.5; color: #323232; " ><div><h4 style="margin-top: 5px; margin-bottom: 5px">Bill to</h4><p style="font-size: 14px">'.$bill_to.'<br />Tel: <a href="tel:'.$data['customer_mobile'].'" style="color: #00bb07" >'.$data['customer_mobile'].'</a ></p></div></td></tr></table></div>');


        $mpdf->SetFooter('{PAGENO}');


        $products = '';

        foreach($invoice_products as $key => $product)
        {
            $products.=" <tr> <td>".($key+1)."</td> <td>".($product['product_name'])."</td> <td>".($product['quantity'])."</td> <td>".($product['total_price'])."</td> </tr> ";
        }

        $totals='';

        $totals .='<tr >
            <th colspan="3" style="text-align: right;">Subtotal </th>
            <td style="text-align: right;">'.$data["subtotal"].'</td>
        </tr>';

         $totals .= '<tr >
            <th colspan="3" style="text-align: right;">Wallet Amount </th>
            <td style="text-align: right; ">'.$data["wallet_amount"].'</td>
        </tr>';

        $totals .= '<tr >
            <th colspan="3" style="text-align: right;">Platform Fee</th>
            <td style="text-align: right; ">'.$data["platform_fee"].'</td>
        </tr>';

        $totals .= '<tr >
            <th colspan="3" style="text-align: right;">Tax </th>
            <td style="text-align: right; ">'.$data["tax_amount"].'</td>
        </tr> ';

        $totals .= ' <tr >
            <th colspan="3" style="text-align: right;">Shipping </th>
            <td style="text-align: right; ">'.$data["shipping_amount"].'</td>
        </tr>'; 

        $totals .= ' <tr >
            <th colspan="3" style="text-align: right;">Discount </th>
            <td style="text-align: right; ">'.$data["discount"].'</td>
        </tr>';

        $totals .= ' <tr style="background-color: #dfdede!important;">
            <th colspan="3" style="text-align: right;">Grand Total </th>
            <td style="text-align: right; ">'.$data["total"].'</td>
        </tr>';

        $mpdf->WriteHTML('<table class="table table-bordered h4-14" style="width: 100%; -fs-table-paginate: paginate; margin-top: 15px">
         <thead style="display: table-header-group">
        <tr style="
              margin: 0;
              padding: 15px;
              padding-left: 20px;
              -webkit-print-color-adjust: exact;
            ">
      <td colspan="2">
        <h5>
          Invoice No : '.$data['invoice_no'].'
         
        </h5>
        <h5>
          Order Date : '.date('d-F-Y',strtotime($data['date'])).'
         
        </h5>
        <h5>
          Invoice Date : '.date('d-F-Y',strtotime($data['invoice_date'])).'
         
        </h5>
      </td>
      
      <td colspan="2" style="width: 300px">
        <h4 style="margin: 0">Sold By:</h4>
        <p>
          '.$settings['application_name'].'<br />
          '.$settings['address'].'
        </p>
      </td>
        </tr>

        <tr>
      <th style="width: 50px">S.no</th>
      <th style="width: 150px">
        <h4>Name</h4>
      </th>
      <th style="width: 80px">
        <h4>QTY</h4>
      </th>
     
      <th style="width: 120px">
        <h4>Total ('.config('constant.CURRENCY_SYMBOL').')</h4>
      </th>
        </tr>

        </thead>
        <tbody>
        '.$products.'
        </tbody>
        <tfoot>
        '.$totals.'
        </tfoot>
        </table>');

       
        $mpdf->Output('Invoice-'.$data['invoice_no'].".pdf","D");

    }

}
