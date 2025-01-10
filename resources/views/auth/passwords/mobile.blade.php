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
                                <a href="javascript:void(0);" class="h1"><b>Reset</b> Password</a>
                            </div>
                            <div class="row">
                                @if(session()->has("message"))
                                        <div class="alert alert-success" role="alert">
                                            {{session("message")}}
                                        </div>
                                    @endif
                                <form method="POST" id="ResetPasswordFrom" action="{{ route('frontend.password.mobile') }}">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <input id="mobile" type="number" placeholder="mobile"
                                            class="form-control  @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autocomplete="mobile" autofocus>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-envelope"></span>
                                            </div>
                                        </div>
                                        @error('mobile')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="row ">
                                        <div class="d-flex">
                                            <a class="btn btn-login-back mx-auto" href="{{ route('frontend.login') }}">Back</a>
                                            <button type="submit" id="submitBtn" class="btn btn-login mx-auto">{{ __('Send OTP') }}</button>
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

@section('js')
<script type="text/javascript">
    $(document).ready(function () {
        $('#ResetPasswordFrom').on('submit', function (event) {
            var $this = $('#submitBtn');
            BtnLoading($this);
            $(this).submit();
        });
    });

    function BtnLoading(elem) {
        $(elem).attr("data-original-text", $(elem).html());
        $(elem).prop("disabled", true);
        $(elem).html('<i class="spinner-border spinner-border-sm"></i> Loading...');
    }

    function BtnReset(elem) {
        $(elem).prop("disabled", false);
        $(elem).html($(elem).attr("data-original-text"));
    }

</script>
@endsection