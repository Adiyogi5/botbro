<?php

namespace App\Http\Controllers;

use App\Models\Cms;
use App\Models\ContactUs;
use App\Models\Setting;
use App\Rules\Recaptcha;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    protected $general_settings;

    public function __construct()
    {
        $this->general_settings = Setting::pluck('filed_value', 'setting_name')->toArray();
    }

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
        $general_settings = $this->general_settings;

        return view('front.contact-us', compact('pageName','general_settings'));
    }

    public function contactUsSave(Request $request)
    {
        $recaptcha_secret_key = $this->general_settings['recaptcha_secret_key'];

        $validated = $request->validate([
            'name'      => ['required', 'string', 'min:6', 'max:100'],
            'email'     => ['required', 'string', 'min:10', 'max:100', 'email'],
            'subject'      => ['required', 'string', 'min:6', 'max:100'],
            'message'   => ['required', 'string', 'min:10', 'max:1000'],
            'g-recaptcha-response' => ['required', new Recaptcha($recaptcha_secret_key)],
        ]);

        ContactUs::create($validated);
        return to_route('front.contact-us')->withSuccess('Message saved successfully..!!');
    }

    public function features(Request $request)
    {
        $pageName = 'Features';

        return view('front.features', compact('pageName'));
    }

    public function faqs(Request $request)
    {
        $pageName = 'Faqs';

        return view('front.faqs', compact('pageName'));
    }

    public function teams(Request $request)
    {
        $pageName = 'Teams';

        return view('front.teams', compact('pageName'));
    }

    public function testimonials(Request $request)
    {
        $pageName = 'Testimonials';

        return view('front.testimonials', compact('pageName'));
    }
    
}
