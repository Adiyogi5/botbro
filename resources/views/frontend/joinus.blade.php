@extends('frontend.layouts.app')

@section('content')
    @include('frontend.includes.profile_header')

    <section>
        <div class="container my-3 my-lg-5">
            <div class="row justify-content-center">
                <div class="col-lg-9">

                    @if ($message = Session::get('success'))
                        <div id="success-message" class="alert alert-success alert-block margin10 flash-message">
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div id="error-message" class="alert alert-danger alert-block margin10 flash-message">
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif

                    <form method="post" action="{{ route('frontend.register_user') }}" id="registerformvalidate"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-join form-join-border">
                            <h3 class="mb-3">Personal Information</h3>
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-0" value="{{ old('name') }}"
                                        id="name" name="name" placeholder="Enter Your Name">
                                    <label for="name" class="error"></label>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-12">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control rounded-0" value="{{ old('email') }}"
                                        id="email" name="email" placeholder="Enter Your Email">
                                    <label for="email" class="error"></label>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-12">
                                    <label for="mobile" class="form-label">Mobile Number <span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex">
                                        <input type="tel" class="form-control rounded-0" value="{{ old('mobile') }}"
                                            aria-describedby="button-addon2" id="mobile" name="mobile"
                                            placeholder="Enter Your Mobile Number">
                                        {{-- <a class="btn btn-custom-form ms-2"
                                            id="send_otp" onclick="sendOTP()">OTP</a> --}}
                                    </div>
                                    <label for="mobile" class="error"></label>
                                    @error('mobile')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- <div class="col-md-6 col-12">
                                    <label for="otp" class="form-label">Enter Otp</label>
                                    <input type="text" class="form-control rounded-0" id="otp" name="otp"
                                        autocomplete="off" value="{{ old('otp') }}">
                                    @error('otp')
                                        <label id="otp-error" for="otp" class="error">{{ $errors->first('otp') }}</label>
                                    @enderror
                                </div> --}}

                                <!-- <div class="col-md-6 col-12">
                                    <label for="alternate_mobile" class="form-label">Alternate Mobile Number</label>
                                    <input type="tel" class="form-control rounded-0" id="alternate_mobile"
                                        name="alternate_mobile" value="{{ old('alternate_mobile') }}" placeholder="Enter Your Alternate Mobile Number">
                                </div> -->
                            </div>
                        </div>

                        <div class="form-join form-join-border">
                            <h3 class="mb-3">Credential Details</h3>
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <label for="password" class="form-label">Password <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input float-label="" type="password" class="form-control rounded-0" id="password"
                                            name="password" placeholder="Password*">
                                        <button type="button" class="btn btn-password" id="showPasswordToggle">
                                            <i class="fa-solid fa-eye toggleeye1" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <label for="password" class="error"></label>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 col-12">
                                    <label for="password" class="form-label">Confirm Password <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input float-label="" type="password" class="form-control rounded-0"
                                            id="confirm_password" name="confirm_password"
                                            placeholder="Confirm Password*">
                                        <button type="button" class="btn btn-password" id="showConfirmPasswordToggle">
                                            <i class="fa-solid fa-eye toggleeye2" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <label for="confirm_password" class="error"></label>
                                    @error('confirm_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-join form-join-border">
                            <h3 class="mb-3">Choose Your UPay Living Products Refers</h3>
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <div class="d-flex">
                                        <input type="text" class="form-control rounded-0" id="reffer_code"
                                            name="reffer_code" value="{{ old('reffer_code') }}" placeholder="Enter Refers">
                                        <a class="nav-link btn btn-custom-form ms-2" id="applyBtn">Apply</a>
                                    </div>
                                    <div class="success-message"></div>
                                    <div class="error-message"></div>
                                    <label for="reffer_code" class="error"></label>
                                    @error('reffer_code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-join form-join-border">
                            <h3 class="">Welcome to the UPay Family!</h3>
                            <p>Thank you for Joining.</p>
                            <div class="row g-3">
                                 <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <strong>ReCaptcha:</strong>
                                        <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
                                        @if ($errors->has('g-recaptcha-response'))
                                            <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                        @endif
                                    </div>   
                                </div>
                                <div class="col-12">
                                    <input type="checkbox" aria-label="" name="check_box" value="true"
                                        id="" class="check-box">
                                    <span> I agree to UPay <a class="modal-link" id="openModalLink">Terms of
                                            Use.</a></span>
                                    <br>
                                    <label for="check_box" class="error"></label>
                                    @error('check_box')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="submit" value="Submit" name="submit"
                                                class="nav-link btn btn-custom-form" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Bootstrap Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-contactus">
                                <h5 class="modal-title text-white" id="exampleModalLabel">Terms of Use</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body px-lg-4 px-3">
                                @if (!empty($terms_cms))
                                    {!! str_replace('{slug}', IMAGES, $terms_cms['cms_contant']) !!}
                                @else
                                    <h3 class="text-center justify-content-center"> No Terms And Condition Found</h3>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- ######################## Register User End ############################# --}}
@endsection



@section('js')
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    {{-- <script>
        function startTimer(duration, display) {
            let text_resend = "{{ 'Resend' }}";
            var timer = duration,
                minutes, seconds;
            var inter = setInterval(function() {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);
    
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                
                $(display).text(minutes + ":" + seconds);
    
                if (--timer < 0) {
                    clearTimeout(inter);
                    $(display).text(text_resend);
                }
            }, 1000);
        }

        function sendOTP() {
            var phone = $('#mobile').val();
            let text_resend = "{{  'Resend' }}";
    
            $.ajax({
                url: '{{  url("send-otp")}}',
                data: {
                    _token: '{{ csrf_token() }}',
                    mobile: phone,
                },
                success: function(result) {
    
                    if (result.status == true) {
                        $('#otp').val(result.otp);
                        toastr.success(result.message);
                        startTimer(120, $("#send_otp"));
                        $("#send_otp").attr('disabled', true);
                        $("#send_otp").attr('readonly', true);
                        $("#send_otp").removeClass('btn-custom-form');
                        $("#send_otp").addClass('btn-warning text-white');
    
                        setTimeout(function() {
                            $("#send_otp").attr('disabled', false);
                            $("#send_otp").attr('readonly', false);
                            $("#send_otp").removeClass('btn-custom-form');
                        }, 10000);
                    } else {
                        toastr.error(result.message);
                    }
                },
                error: function(error) {
                    toastr.error("{{ 'Something went wrong, Please try again.' }}");
                }
            });
        }
    </script> --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $("#registerformvalidate").validate({
                rules: {
                    name: "required",
                    email: {
                        required: false,
                        email: true
                    },
                    mobile: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        digits: true,
                    },
                     // otp: {
                    //    required: true,
                   //     digits: true,
                   // },
                    password: "required",
                    confirm_password: {
                        required: true,
                        equalTo: "#password", // Assuming your password field has id="password"
                    },
                    check_box: "required",
                    captcha: "required",
                },
                messages: {
                    name: "Please Enter Name",
                    email: "Please Enter Valid Email Address",
                    mobile: {
                        required: "Please Enter Mobile No",
                        digits: "Please Enter Valid Mobile No",
                        minlength: "Mobile Should Be 10 Digits",
                        maxlength: "Mobile Should Be 10 Digits",
                    },
                    //otp: {
                    //    required: "Please Enter OTP",
                    //    digits: "Please Enter Valid Digit",
                    //},
                    password: "Please Enter Password",
                    confirm_password: {
                        required: "Please Enter Confirm Password",
                        equalTo: "Passwords do not match",
                    },
                    check_box: "Please Agree to Terms of Use",
                    captcha: "Please Enter Captcha",
                },
                submitHandler: function(form) {
                    // Check if the referral code is verified before submitting the form
                    if (isReferralCodeVerified) {
                        $(form).ajaxSubmit({
                            success: function(response) {
                                console.log('Form submission success:', response);
                            },
                            error: function(xhr, status, error) {
                                console.error('Form submission error:', error);
                            }
                        });
                    } else {
                        console.log('Referral code is not verified. Form not submitted.');
                    }
                }
            });

            $('#applyBtn').on('click', function(e) {
                e.preventDefault();
                // Verify referral code when the "Apply" button is clicked
                verifyReferralCode("#registerformvalidate");
            });

            function verifyReferralCode(formSelector) {
                var referralCode = $('#reffer_code').val();

                $.ajax({
                    url: '{{ route("frontend.checkReferralCode") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        refers: referralCode
                    },
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status == true) {
                            $('.success-message').html(
                                '<span class="text-success">'+res.msg+'</span>');
                            $('.error-message').empty(); // Clear any previous error message
                            isReferralCodeVerified = true;
                            // Trigger form submission programmatically
                            return true;
                        } else {
                            $('.error-message').html(
                                '<span class="text-danger">'+res.msg+'</span>'
                                );
                            $('.success-message').empty(); // Clear any previous success message
                            isReferralCodeVerified = false;
                            return false;
                        }
                    },
                    error: function() {
                        console.log('Ajax request failed');
                        isReferralCodeVerified = false;
                        return false;
                    }
                });
            }

             
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#showPasswordToggle").click(function() {
                var passwordField = $("#password");
                var fieldType = passwordField.attr('type');
                var toggleIcon = document.querySelector(".toggleeye1");

                if (fieldType === 'password') {
                    passwordField.attr('type', 'text');
                    toggleIcon.classList.remove("fa-eye");
                    toggleIcon.classList.add("fa-eye-slash");
                } else {
                    passwordField.attr('type', 'password');
                    toggleIcon.classList.remove("fa-eye-slash");
                    toggleIcon.classList.add("fa-eye");
                }
            });
            $("#showConfirmPasswordToggle").click(function() {
                var passwordField = $("#confirm_password");
                var fieldType = passwordField.attr('type');
                var toggleIcon = document.querySelector(".toggleeye2");

                if (fieldType === 'password') {
                    passwordField.attr('type', 'text');
                    toggleIcon.classList.remove("fa-eye");
                    toggleIcon.classList.add("fa-eye-slash");
                } else {
                    passwordField.attr('type', 'password');
                    toggleIcon.classList.remove("fa-eye-slash");
                    toggleIcon.classList.add("fa-eye");
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
    <script>
        $(document).ready(function() {
            // Attach a click event to the link
            $('#openModalLink').click(function() {
                // Show the modal when the link is clicked
                $('#myModal').modal('show');
            });
        });
    </script>
@endsection
