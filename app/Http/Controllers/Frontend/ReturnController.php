<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cms;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        
        $return_cms = Cms::where('cms.slug', 'cancel-refund-policy')
        ->where('status', '1')->first();
        $title = $return_cms->name;
        
        return view('frontend.return', compact('title','return_cms'));
    }


}
