@extends('admin.layouts.login')

@section('content')
  <div class="card card-outline card-primary">
    <div class="card-header text-center">{{ __('Reset Password') }}</div>

       <div class="card-body">
            <form method="POST" action="{{ route('admin.password.update') }}">
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
                    <label for="password" class="col-md-12 col-form-label">{{ __('Password') }}</label>

                    <div class="col-md-12">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" autofocus>
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
                    <label for="password-confirm" class="col-md-12 col-form-label">{{ __('Confirm Password') }}</label>

                    <div class="col-md-12">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>       
    </div>
</div>
@endsection
