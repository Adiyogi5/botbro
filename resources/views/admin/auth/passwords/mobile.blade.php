@extends('admin.layouts.login')

@section('content')
<div class="login-box">
<div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="javascript:void(0);" class="h1"><b>Reset</b> Password</a>
    </div> 
    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif 
        <form method="POST" id="ResetPasswordFrom" action="{{ route('admin.password.mobile') }}">
            @csrf

            <div class="row mb-3">
                <label for="mobile" class="col-md-12 col-form-label ">Mobile</label>

                <div class="col-md-12">
                    <input id="mobile" type="mobile" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autocomplete="mobile" autofocus>

                    @error('mobile')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-md-10">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        {{ __('Send OTP') }}
                    </button>
                </div> 
            </div>
            <p class="mb-1"> 
            @if (Route::has('admin.login'))
                <a href="{{ route('admin.login') }}">
                    {{ __('Back To Login') }}
                </a>
            @endif
          </p> 
        </form>
    </div>            
</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(document).ready(function () {
        $('#ResetPasswordFrom').on('submit', function (event) {
            event.preventDefault();
            var $this = $('#submitBtn');
            BtnLoading($this);

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