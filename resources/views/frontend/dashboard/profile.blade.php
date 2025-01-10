@extends('frontend.layouts.app')

@section('content')
    <main>        
        @include('frontend.includes.profile_header')
       
        <section id="dashboard">
            <div class="container my-lg-5 my-md-4 my-3">
                <div class="row pt-lg-4 pt-3">
                    <div class="col-lg-3 col-md-4 col-12">

                        @include('frontend.includes.sidebar_inner')

                    </div>
                    <div class="col-lg-9 col-md-8 col-12 mt-md-0 mt-3">
                        
                            <div class="col-12">
                                <p class="dash-category">My Profile</p>
                            </div>
                            <div
                                class="col-12 form-right bg-white form-confirmpass p-2">
                                <div class="card-body p-md-3 p-1">
                                    <form class="row g-3" method="POST" id="profileUpdate" action="{{ route('frontend.profile') }}"
                                        enctype='multipart/form-data'>
                                        @csrf
                                        @method('PUT')

                                        <div class="col-md-6 col-12">
                                            <label class="form-label" for="name">Name</label>
                                            <input class="form-control get-profile" id="name" name="name"
                                                type="text" value="{{ old('name', $user->name) }}" />
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <label class="form-label" for="email">Email</label>
                                            <input class="form-control get-profile" id="email" type="email"
                                                name="email" value="{{ old('email', $user->email) }}" />
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <label class="form-label" for="mobile">Mobile</label>
                                            <input class="form-control get-profile" id="mobile" name="mobile"
                                                type="text" value="{{ old('mobile', $user->mobile) }}" />
                                            @error('mobile')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <label class="form-label" for="reffer_code">Reffer Code</label>
                                            <input readonly class="form-control get-profile" type="text"
                                                value="{{ $user->reffer_code }}" disabled/>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <label class="form-label" for="image">Image</label>
                                            <input class="form-control get-profile" id="image" name="image"
                                                type="file" value="{{ old('image', $user->image) }}" />
                                            @error('image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="col-3">
                                                <div class="img-group my-2 mx-2">
                                                    <img class=" rounded-circle bg-secondary p-1"
                                                        style="height:80px; width:80px"
                                                        src="{{ imageexist($user->image) }}" alt="">
                                                </div>
                                            </div>
                                        </div>

                                        @if(!empty($refferal->referral_id))
                                        <div class="col-md-6 col-12">
                                            <label class="form-label" for="referral">Reffer By and Referral Code</label>
                                            <input readonly class="form-control get-profile getreffer" type="text"
                                                value="{{ $refferal->name }} - {{ $refferal->reffer_code }}" disabled/>
                                        </div>
                                        @else
                                        <div class="col-md-6 col-12">
                                            <label class="form-label" for="referral">Reffer By and Referral Code</label>
                                            <input readonly class="form-control get-profile" type="text"
                                                value="You have not use any Referral Code" />
                                        </div>
                                        @endif

                                        <hr style="margin-bottom: 0px;">
                                        <h5>Bank Account Details</h5>

                                        <div class="col-md-6 col-12">
                                            <label class="form-label" for="name">Bank Account Name</label>
                                            <input class="form-control get-profile" id="bank_account_name" name="bank_account_name" type="text" value="{{ old('bank_account_name', $user->bank_account_name) }}" />
                                            @error('bank_account_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label class="form-label" for="bank_account_number">Bank Account Number</label>
                                            <input class="form-control get-profile" id="bank_account_number" name="bank_account_number" type="text" value="{{ old('bank_account_number', $user->bank_account_number) }}" />
                                            @error('bank_account_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label class="form-label" for="bank_name">Bank Name</label>
                                            <input class="form-control get-profile" id="bank_name" name="bank_name" type="text" value="{{ old('bank_name', $user->bank_name) }}" />
                                            @error('bank_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <label class="form-label" for="ifsc">IFSC</label>
                                            <input class="form-control get-profile" id="ifsc" name="ifsc" type="text" value="{{ old('ifsc', $user->ifsc) }}" />
                                            @error('ifsc')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
 

                                        <div class="col-12 d-flex justify-content-start text-start">
                                            <button class="btn btn-order-ship" type="submit">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        
                    </div>
                </div>
            </div>
            </div>
        </section>
        {{-- ===============Dashboard End============= --}}


    </main>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#profileUpdate").validate({
                errorClass: "text-danger fs--1",
                errorElement: "span",
                rules: {
                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 200
                    },
                    email: {
                        required: true,
                        email: true,
                        minlength: 2,
                        maxlength: 250
                    },
                    mobile: {
                        required: true,
                        number: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    bank_account_name: {
                        required: true,
                        minlength: 2,
                        maxlength: 200
                    },
                    bank_account_number: {
                        required: true,
                        minlength: 2,
                        maxlength: 50
                    },
                    ifsc: {
                        required: true,
                        minlength: 2,
                        maxlength: 100
                    },
                    bank_name: {
                        required: true,
                        minlength: 2,
                        maxlength: 200
                    },
                },
                messages: {
                    name: {
                        required: "Please enter name",
                        minlength: "Name must be at least 2 characters",
                        maxlength: "Name cannot exceed 200 characters"
                    },
                    email: {
                        required: "Please enter Email",
                        email: "Please enter a valid email address",
                        minlength: "Email must be at least 2 characters",
                        maxlength: "Email cannot exceed 250 characters"
                    },
                    mobile: {
                        required: "Please enter Mobile number",
                        number: "Please enter a valid number",
                        minlength: "Mobile number must be exactly 10 digits",
                        maxlength: "Mobile number must be exactly 10 digits"
                    },
                    bank_account_name: {
                        required: "Please enter Bank Account Name",
                        minlength: "Bank Account Name must be at least 2 characters",
                        maxlength: "Bank Account Name cannot exceed 200 characters"
                    },
                    bank_account_number: {
                        required: "Please enter Bank Account Number",
                        minlength: "Bank Account Number must be at least 2 characters",
                        maxlength: "Bank Account Number cannot exceed 50 characters"
                    },
                    ifsc: {
                        required: "Please enter ifsc",
                        minlength: "ifsc must be at least 2 characters",
                        maxlength: "ifsc cannot exceed 50 characters"
                    },
                    bank_name: {
                        required: "Please enter Bank Name",
                        minlength: "Bank Name must be at least 2 characters",
                        maxlength: "Bank Name cannot exceed 200 characters"
                    },
                },
            });
        });
    </script>
@endsection
