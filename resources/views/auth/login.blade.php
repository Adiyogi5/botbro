@extends('frontend.layouts.login')

@section('content')

    <div class="container my-3 my-lg-5 py-lg-5 my-3 py-3">
        <div class="row main-content">
            <div class="col-xl-8 col-lg-10 col-md-11 col-11 mx-auto">
                <div class="row">
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
                                <div class="card-header text-center">
                                    <a href="javascript:void(0);" class="h1"><b class="text-primary">Customer </b>{{ __('Login') }}</a>
                                </div>
                                <div class="row">
                                    <p class="login-box-msg">Sign in to start your session</p>
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    @include('frontend.includes.flash-message')
                                    <form method="POST" action="{{ route('frontend.login') }}">
                                        @csrf
                                        <div class="input-group mb-3">
                                            <input id="mobile" type="text" placeholder="Mobile"
                                                class="form-control @error('mobile') is-invalid @enderror" name="mobile"
                                                value="{{ old('mobile') }}" required autocomplete="mobile" autofocus>
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-phone"></span>
                                                </div>
                                            </div>
                                            @error('mobile')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="input-group mb-3">
                                            <input id="password" type="password" placeholder="Password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                required autocomplete="current-password">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-eye-slash" id="togglePassword" style="cursor: pointer;"></span>
                                                </div>
                                            </div>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="icheck-primary" style="float: left;">
                                                    <input class="form-check-input" type="checkbox" name="remember"
                                                        id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    <label for="remember">
                                                        {{ __('Remember Me') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex" style="float: right;">

                                                    <button type="submit" class="btn btn-login mx-auto">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row" style="text-align: left;">
                                        <p class="account">
                                            <a href="{{ route('frontend.password.request') }}">Forgot Password</a>
                                        </p>
                                        <p class="account">Don't have an account? <a
                                                href="{{ route('frontend.joinus') }}">Register Here</a>
                                        </p>
                                        <hr style="margin:5px 0px;">
                                        <p class="account"> <a href="{{ route('frontend.home') }}">Click here to back Home
                                                Page!!</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <div class="container-fluid text-center footer mt-3">
                <span> {{ $site_settings['copyright'] }}</span>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Function to automatically fade out and remove flash messages
        function fadeOutFlashMessages() {
            $(".alert").delay(10000).fadeOut(1000, function() {
                $(this).remove();
            });
        }

        // Call the function when the document is ready
        $(document).ready(function() {
            fadeOutFlashMessages();
        });
        
        document.getElementById('togglePassword').addEventListener('click', function () {
        let passwordField = document.getElementById('password');
        let icon = this;

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            passwordField.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
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
