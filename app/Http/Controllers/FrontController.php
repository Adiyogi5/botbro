<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Cms;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FrontController extends Controller
{
    public function index(Request $request)
    {
        
        return view('front.home');
    }

    
    public function showCms(Request $request, $slug)
    {
        switch ($slug) {
            case 'about-us':
                $content = Cms::find(2);
                $pageName = 'About Us';
                return view('front.about-us', compact('content', 'pageName'));

            case 'privacy-policy':
                $content = Cms::find(3);
                $pageName = 'Privacy Policy';
                return view('front.privacy-policy', compact('content', 'pageName'));

            case 'terms-and-conditions':
                $content = Cms::find(4);
                $pageName = 'Terms Condition';
                return view('front.terms-and-conditions', compact('content', 'pageName'));

            default:
                abort(404);
                break;
        }
    }

    public function contactUs(Request $request)
    {
        $pageName = 'Contact Us';
        return view('front.contact-us', compact('pageName'));
    }

    public function contactUsSave(Request $request)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'min:6', 'max:100'],
            'email'     => ['required', 'string', 'min:10', 'max:100', 'email'],
            'subject'      => ['required', 'string', 'min:6', 'max:100'],
            'message'   => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        ContactUs::create($validated);
        return to_route('front.contact-us')->withSuccess('Message saved successfully..!!');
    }
}
