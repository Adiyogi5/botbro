<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cms;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function index(Request $request)
    {
        $aboutus_cms = Cms::where('cms.slug', 'about-us')
        ->where('status', '1')->first();
        $title = $aboutus_cms->name;
        
        return view('frontend.aboutus', compact('title','aboutus_cms'));
    }


}
