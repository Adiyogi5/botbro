<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        
        $title = 'FAQs';

        $faqs = Faq::where('status', '1')
                    ->get();
        
        return view('frontend.faqs', compact('title','faqs'));
    }


}
