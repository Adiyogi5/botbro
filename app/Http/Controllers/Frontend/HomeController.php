<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\HomeCms;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $title = 'U Pays';

        $banners = Banner::select('id', 'banner_type', 'name', 'title', 'content', 'url', 'image', 'status')
            ->where(array('banner_type'=> 1,'status'=>1))
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        $homecms = HomeCms::whereNull('deleted_at')
            ->where('status', '1')
            ->get();

        $offers = Banner::select('id', 'banner_type', 'name','image', 'status')
            ->where(array('banner_type'=>2,'status'=>1))->limit(3)
            ->whereNull('deleted_at')
            ->orderBy('id', 'asc')
            ->get();

        $testimonials = Testimonial::where('status', '1')
            ->whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('frontend.index', compact('title','banners', 'homecms', 'offers', 'testimonials'));
    }


}
