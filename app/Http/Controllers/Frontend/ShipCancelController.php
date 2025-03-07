<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cms;
use Illuminate\Http\Request;

class ShipCancelController extends Controller
{
     public function index(Request $request)
    {
        
        $shipandcencel_cms = Cms::where('cms.slug', 'cancellation-and-refund-policy')
        ->where('status', '1')->first();
        $title = $shipandcencel_cms->name;
        
        return view('frontend.shipandcancel', compact('title','shipandcencel_cms'));
    }



}
