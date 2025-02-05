@extends('frontend.layouts.app')

@section('content')
    {{-- ######################## Breadcrumb Start ############################# --}}
    @include('frontend.includes.profile_header')
    {{-- ######################## Breadcrumb End ############################# --}}



    {{-- ######################## Contact Us Start ############################# --}}
    <section>
        <div class="container my-3 my-lg-5">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-12 form-join form-join-border" id="contact-scroll">

                    <h3 class="mb-3 text-center mx-auto">Contact Us</h3>
                    
                    {{-- @if ($message = Session::get('success'))
                        <div id="success-message" class="alert alert-success alert-block margin10 flash-message">
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div id="error-message" class="alert alert-danger alert-block margin10 flash-message">
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif --}}

                    <form method="post" action="{{ route('frontend.submitcontact') }}" id="contactformvalidate"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control rounded-0" value="{{ old('name') }}"
                                    id="name" name="name" placeholder="Enter Your Name">
                                <label class="error" id="personError">{{ $errors->first('name') }}</label>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control rounded-0" value="{{ old('email') }}"
                                    id="email" name="email" placeholder="Enter Your Email">
                                <label class="error" id="personError">{{ $errors->first('email') }}</label>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="mobile" class="form-label">Mobile</label>
                                <input type="tel" class="form-control rounded-0" value="{{ old('mobile') }}"
                                    id="mobile" name="mobile" placeholder="Enter Your Mobile Number">
                                <label class="error" id="personError">{{ $errors->first('mobile') }}</label>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="tel" class="form-control rounded-0" value="{{ old('subject') }}"
                                    id="subject" name="subject" placeholder="Enter subject">
                                <label class="error" id="personError">{{ $errors->first('subject') }}</label>
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">Message</label>
                                <div class="input-group">
                                    <textarea class="form-control rounded-0 w-100" name="message" id="message" rows="4"
                                        placeholder="Any Message you'd like to share....">{{ old('message') }}</textarea>
                                </div>
                                <label class="error" id="personError">{{ $errors->first('message') }}</label>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <strong>ReCaptcha:</strong>
                                    <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                    @endif
                                </div>   
                            </div>
                            {{-- <div class="col-md-6 col-12">
                                <label for="captcha" class="mb-2 contact-text">Captcha <i> *</i></label>
                                <div class="captcha mb-2">
                                    <span>{!! captcha_img('flat') !!}</span>
                                    <button type="button" class="btn btn-warning text-white" class="reload" id="reload">
                                        &#x21bb;
                                    </button>
                                </div>
                                <div class="form-group">
                                    <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha"
                                        name="captcha">
                                </div>
                                <label class="error" id="personError">{{ $errors->first('captcha') }}</label>
                            </div> --}}
                        </div>
                        <div class="col-12 mt-md-3 mt-2">
                            <input type="submit" value="Submit" name="submit" class="btn btn-order-ship submitbtn" />
                        </div>
                    </form>
                </div>
                {{-- <div class="col-lg-5 col-12 bg-contactus">
                    <div class="join-form-margin py-lg-0 py-3">
                        <!--Icons-->
                        <div class="row text-center">
                            <div class="col-lg-12 col-md-4 col-12">
                                <a class="bg-contact-icon px-3 py-2 rounded-circle mb-2 d-inline-block"><i
                                        class="fa fa-map-marker"></i></a>
                                <p class="text-white"> {{ $site_settings['address'] }}</p>
                            </div>
                            <div class="col-lg-12 col-md-4 col-12">
                                <a class="bg-contact-icon px-3 py-2 rounded-circle mb-2 d-inline-block"><i
                                        class="fa fa-phone"></i></a>
                                <p class="text-white"> {{ $site_settings['phone'] }}</p>
                            </div>
                            <div class="col-lg-12 col-md-4 col-12">
                                <a class="bg-contact-icon px-3 py-2 rounded-circle mb-2 d-inline-block"><i
                                        class="fa fa-envelope"></i></a>
                                <p class="text-white"> {{ $site_settings['email'] }}</p>
                            </div>
                        </div>
                        <!--Google map-->
                        <div class="mt-lg-4 mt-2 w-100">
                            {!! $site_settings['google_iframe'] !!}
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
    {{-- ######################## Contact Us End ############################# --}}
@endsection


@section('js')
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#contactformvalidate").validate({
                rules: {
                    name: "required",
                    email: {
                        required: true,
                        email: true
                    },
                    mobile: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
                    subject: "required",
                    message: "required",
                    captcha: "required",
                },
                messages: {
                    name: "Please Enter Name",
                    email: "Please Enter Valid Email Address",
                    mobile: {
                        required: "Please Enter Mobile No",
                        number: "Please Enter Valid Mobile No",
                        minlength: "Mobile Should Be 10 Digits",
                        maxlength: "Mobile Should Be 10 Digits",
                    },
                    subject: "Please Enter Subject",
                    message: "Please Enter Message",
                    captcha: "Please Enter captcha",
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });

        $('#reload').click(function() {
            $.ajax({
                type: 'GET',
                url: '{{ url('reload-captcha') }}',
                success: function(data) {
                    $(".captcha span").html(data.captcha);
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var successMessage = document.querySelector('.alert-success');
                var errorMessage = document.querySelector('.alert-danger');

                if (successMessage) {
                    successMessage.style.display = 'none';
                }

                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
            }, 5000);
        });
    </script>
@endsection
