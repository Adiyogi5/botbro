<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cms;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function index(Request $request)
    {
        $terms_cms = Cms::where('cms.slug', 'terms-and-conditions')
        ->where('status', '1')->first();
        $title = $terms_cms->name;
        
        return view('frontend.terms', compact('title','terms_cms'));
    }


}
