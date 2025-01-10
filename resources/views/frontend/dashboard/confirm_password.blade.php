@extends('frontend.layouts.app')

@section('content')
    <main>
        {{-- ===============Breadcrumb Start============= --}}
        @include('frontend.includes.profile_header')
        {{-- ===============Breadcrumb End============= --}}


        {{-- ===============Dashboard Start============= --}}
        <section id="dashboard">
            <div class="container my-lg-5 my-md-4 my-3">
                <div class="row pt-lg-4 pt-3">
                    <div class="col-lg-3 col-md-4 col-12">

                        @include('frontend.includes.sidebar_inner')

                    </div>
                    <div class="col-lg-9 col-md-8 col-12 mt-md-0 mt-3">
                        <div class="col-12">
                                <p class="dash-category">Change Password</p>
                            </div>
                        <div
                                class="col-12 form-right bg-white form-confirmpass mt-3 ms-lg-3 ms-md-1 ms-0 px-xl-5 px-lg-4 px-md-0 px-2 py-xl-5 py-lg-4 py-3">

                            <form action="{{ route('frontend.confirm_password.change-password') }}" id="customerRigister"
                                class="row px-md-0 px-2" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @elseif (session('error'))
                                        <div class="alert alert-danger" role="alert">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    <div class="col-md-10 col-12 mb-lg-4 mb-3 mx-auto">
                                        <div class="input-group">
                                            <div class="input-group-text input-text-style">
                                                <i class="fa-solid fa-key faa-login"></i>
                                            </div>
                                            <input type="password" id="old_password" name="old_password"
                                                class="form-control rounded-0" placeholder="Old Password">
                                            <button type="button" class="btn btn-password" id="showOldPasswordToggle">
                                                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                                </button>
                                        </div>
                                        <label for="old_password" class="error"></label>
                                        @error('old_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-10 col-12 mb-lg-4 mb-3 mx-auto">
                                        <div class="input-group">
                                            <div class="input-group-text input-text-style">
                                                <i class="fa-solid fa-key faa-login"></i>
                                            </div>
                                            <input type="password" id="new_password" name="new_password"
                                                class="form-control rounded-0" placeholder="New Password">
                                            <button type="button" class="btn btn-password" id="showNewPasswordToggle">
                                                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                                </button>
                                        </div>
                                        <label for="new_password" class="error"></label>
                                        @error('new_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-10 col-12 mb-lg-4 mb-3 mx-auto">
                                        <div class="input-group">
                                            <div class="input-group-text input-text-style">
                                                <i class="fa-solid fa-key faa-login"></i>
                                            </div>
                                            <input type="password" id="new_password_confirmation"
                                                name="new_password_confirmation" class="form-control rounded-0"
                                                placeholder="Confirmed Password">
                                            <button type="button" class="btn btn-password" id="showConfirmPasswordToggle">
                                                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                                </button>
                                        </div>
                                        <label for="new_password_confirmation" class="error"></label>
                                        @error('new_password_confirmation')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div
                                        class="col-md-10 col-12 mx-auto pt-3 text-md-start justify-content-md-start text-center justify-content-center">
                                        <button type="submit" name="submit" class="btn btn-order-ship">
                                            Submit
                                        </button>
                                        {{-- <a class="btn btn-order-forgot mx-auto" href="{{route('frontend.password.request')}}">
                                            Forgot Password</a> --}}
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- ===============Dashboard End============= --}}

    </main>
@endsection


@section('js')
<script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#customerRigister").validate({
            rules: {
                old_password: "required",
                new_password: "required",
                new_password_confirmation: "required",
            },
            messages: {
                old_password: "Please Enter Old Password",
                new_password: "Please Enter New Password",
                new_password_confirmation: "Please Enter Confirm Password",
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    })
</script>
<script>
    $(document).ready(function() {
        $("#showOldPasswordToggle").click(function() {
            var passwordField = $("#old_password");
            var fieldType = passwordField.attr('type');

            if (fieldType === 'password') {
                passwordField.attr('type', 'text');
            } else {
                passwordField.attr('type', 'password');
            }
        });
        $("#showNewPasswordToggle").click(function() {
            var passwordField = $("#new_password");
            var fieldType = passwordField.attr('type');

            if (fieldType === 'password') {
                passwordField.attr('type', 'text');
            } else {
                passwordField.attr('type', 'password');
            }
        });
        $("#showConfirmPasswordToggle").click(function() {
            var passwordField = $("#new_password_confirmation");
            var fieldType = passwordField.attr('type');

            if (fieldType === 'password') {
                passwordField.attr('type', 'text');
            } else {
                passwordField.attr('type', 'password');
            }
        });
    });
</script>
@endsection
