<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cms;
use Illuminate\Http\Request;

class WhyUpayController extends Controller
{
    public function index(Request $request)
    {
        $whyupay_cms = Cms::where('cms.slug', 'why-join-robo-trade')
        ->where('status', '1')->first();
        
        $title = $whyupay_cms->name;
        
        return view('frontend.whyupay', compact('title','whyupay_cms'));
    }


}
