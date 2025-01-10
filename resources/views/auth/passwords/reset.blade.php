@extends('frontend.layouts.login')

@section('content')
    <div class="container my-3 my-lg-5 py-lg-5 my-3 py-3">
        <div class="row main-content">
            <div class="col-xl-8 col-lg-10 col-md-12 col-11 mx-auto">
                <div class="row my-lg-5 my-3">
                    <div class="col-md-4 col-12 company__info">
                        <img class="company__logo footer-logo mx-auto d-md-block d-none" alt="footer-logo"
                            src="{{ asset($site_settings['footer_logo']) }}" />
                    </div>
                    <div class="col-12 d-block d-md-none text-center justify-content-center p-2">
                        <img class="company__logo footer-logo mx-auto" alt="footer-logo"
                            src="{{ asset($site_settings['logo']) }}" />
                    </div>

                    <div class="col-md-8 col-12 login_form p-3 text-center justify-content-center">
                        <div class="container">

                            <div class="">
                                <div class="card-header text-center mb-lg-4 mb-3">
                                    <a href="javascript:void(0);" class="h1">{{ __('Reset Password') }}</a>
                                </div>
                                <div class="row">
                                    @if(session()->has("message"))
                                        <div class="alert alert-success" role="alert">
                                            {{session("message")}}
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('frontend.password.update') }}">
                                        @csrf
                                        <input type="hidden" name="mobile" value="{{ $token }}">
                                        <div class="input-group mb-3">
                                            <label for="password"
                                                class="col-md-12 col-form-label" style="text-align: left;">{{ __('OTP') }}</label>
                                            <div class="col-md-12">
                                                <input id="otp" type="text"
                                                    class="form-control @error('otp') is-invalid @enderror" name="otp"
                                                    value="{{ $otp ?? old('otp') }}" required >
                                                @error('otp')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <label for="password"
                                                class="col-md-12 col-form-label" style="text-align: left;">{{ __('Password') }}</label>

                                            <div class="col-md-12">
                                                <input id="password" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" required autocomplete="new-password" autofocus>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="input-group mb-3">
                                            <label for="password-confirm"
                                                class="col-md-12 col-form-label" style="text-align: left;">{{ __('Confirm Password') }}</label>

                                            <div class="col-md-12">
                                                <input id="password-confirm" type="password" class="form-control"  name="password_confirmation" required autocomplete="new-password">
                                            </div>
                                        </div>

                                        <div class="row ">
                                            <div class="d-flex">
                                                <a class="btn btn-login-back mx-auto"
                                                    href="{{ route('frontend.login') }}">Back</a>
                                                <button type="submit"
                                                    class="btn btn-login mx-auto">{{ __('Reset Password') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
