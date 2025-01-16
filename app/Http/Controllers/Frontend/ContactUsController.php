<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\Request;
use App\Rules\ReCaptcha;

class ContactUsController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Contact Us';
        
        return view('frontend.contactus', compact('title'));
    }
    
    public function submitcontact(Request $request)
    {
        // dd($request);
        $validate = $request->validate([
            'name' => 'required|max:30',
            'email' => 'required|email',
            'mobile' => 'required|numeric|min:10',
            'subject' => 'required',
            'message' => 'required',
            'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);

        try {
            $data = new ContactInquiry();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->mobile = $request->mobile;
            $data->subject = $request->subject;
            $data->message = $request->message;
            $data->save();

            $request->session()->flash('success', 'Thanks For Contacting Us');
            return redirect()->back();
        } catch (\Exception $e) {
            $errorMessage = 'Failed to save contact inquiry: ' . $e->getMessage();
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha' => captcha_img('flat')]);
    }

}
