<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cms;
use Illuminate\Http\Request;

class OurLeadershipController extends Controller
{
    public function index(Request $request)
    {
        $ourleadership_cms = Cms::where('cms.slug', 'our-leadership')
        ->where('status', '1')->first();
        $title = $ourleadership_cms->name;
        
        return view('frontend.ourleadership', compact('title','ourleadership_cms'));
    }


}
