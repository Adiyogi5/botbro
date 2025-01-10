<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cms;
use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    public function index(Request $request)
    {
        
        $policy_cms =  Cms::where('cms.slug', 'privacy-policy')
        ->where('status', '1')->first();
        $title = $policy_cms->name;
        
        return view('frontend.privacy', compact('title','policy_cms'));
    }


}
