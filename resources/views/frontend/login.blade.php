<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset($site_settings['favicon']) }}" />

    <meta name="site_url" content="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Styles -->
    <link href="{{ ASSETS }}css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ ASSETS }}css/navbar-1.css" rel="stylesheet">
    <link href="{{ ASSETS }}css/toastr.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ ASSETS }}fontawesome/css/all.min.css" rel="stylesheet">
    <link href="{{ ASSETS }}css/owl.carousel.min.css" rel="stylesheet">
    <link href="{{ ASSETS }}css/owl.theme.default.css" rel="stylesheet">
    <link href="{{ CSS }}app.css" rel="stylesheet">
    <link href="{{ CSS }}common.css" rel="stylesheet">
    <link href="{{ ASSETS }}css/style.css" rel="stylesheet">
    <link href="{{ ASSETS }}css/responsive.css" rel="stylesheet">

    @yield('header_scripts')
</head>

<body>
    <div class="content-margin">
        {{-- ######################## Login Start ############################# --}}
        <section>
            <div class="container my-3 my-lg-5 py-lg-5 my-3 py-3">
                <div class="row main-content">
                    <div class="col-xl-8 col-lg-10 col-md-10 col-11 mx-auto">
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
                                    <div class="row">
                                        <div class="alert-container">
                                            @if ($message = Session::get('success'))
                                                <div id="success-message"
                                                    class="alert alert-success alert-block margin10 flash-message">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @endif
                                            @if ($message = Session::get('error'))
                                                <div id="error-message" class="alert alert-danger alert-block margin10 flash-message">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                        <h2>Customer Log In</h2>
                                    </div>
                                    <div class="row">
                                        <form control="" class="login-form form-group">
                                            <div class="row">
                                                <input type="text" name="username" id="username"
                                                    class="login-form-input" placeholder="Username">
                                            </div>
                                            <div class="row">
                                                <input type="password" name="password" id="password"
                                                    class="login-form-input" placeholder="Password">
                                            </div>
                                            <div class="row ">
                                                <div class="d-flex">
                                                <a class="btn btn-login-back mx-auto" href="{{ url('/') }}">Back</a>
                                                <input type="submit" value="Login" class="btn btn-login mx-auto">
                                            </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="row">
                                        <p class="account">Don't have an account? <a href="{{ route('joinus') }}">Register Here</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <div class="container-fluid text-center footer">
                Coded with &hearts; by <a href="#">Adiyogi Technosoft.</a></p>
            </div>
        </section>
        {{-- ######################## Login End ############################# --}}
    </div>

    <!-- Scripts -->
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
    <script src="{{ ASSETS }}js/bootstrap.min.js"></script>
    <script src="{{ ASSETS }}js/bootstrap.bundle.min.js"></script>
    <script src="{{ JS }}app.js"></script>
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
</body>

</html>
