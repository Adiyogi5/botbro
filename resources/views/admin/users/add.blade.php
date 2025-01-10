@extends('admin.layouts.main')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-end">
                            <div class="card-title pull-right">
                                <a href="{{ route('admin.customer.index') }}" class="btn btn-success"><i class="fa fa-list"></i> Customer Lists</a>
                            </div>
                        </div>
                        <form id="customerForm" method="post" action="{{ route('admin.customer.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name<i class="text-danger">*</i></label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name') }}" id="name" placeholder="Enter  name">
                                            <label class="error">{{ $errors->first('name') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mobile">Mobile<i class="text-danger">*</i></label>
                                            <input type="number" name="mobile" class="form-control"
                                                value="{{ old('mobile') }}" id="mobile"
                                                placeholder="Enter mobile number">
                                            <label class="error">{{ $errors->first('mobile') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email<i class="text-danger">*</i></label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ old('email') }}" id="email" placeholder="Enter email">
                                            <label class="error">{{ $errors->first('email') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password<i class="text-danger">*</i></label>
                                            <input type="password" name="password" class="form-control" value="{{ old('password') }}" id="password" autocomplete="false" placeholder="Enter  password">
                                            <label class="error">{{ $errors->first('password') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">Confrim Password<i
                                                    class="text-danger">*</i></label>
                                            <input type="password" name="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}" id="password_confirmation" autocomplete="false" placeholder="Enter confirm password">
                                            <label class="error">{{ $errors->first('password_confirmation') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="reffer_code">Reffer Code</label>
                                            <input type="text" name="reffer_code" class="form-control"
                                                value="{{ old('reffer_code') }}" id="reffer_code" placeholder="Enter reffer_code">
                                            <label class="error">{{ $errors->first('reffer_code') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="image">Image<small> ( Image Size must be 250px x 250px )</small></label>
                                            <input type="file" class="form-control" name="image" id="image">
                                            <p><small class="text-success">Allowed Types: gif, jpg, png, jpeg</small></p>
                                            <input type="hidden" name="old_image" value="<?php echo html_escape(@$data->image); ?>">
                                            <label class="error">{{ $errors->first('image') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="submit" name="submit" value="Submit" class="btn btn-submit btn-primary pull-right">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#customerForm").validate({
                rules: {
                    role_id: {
                        required: true,
                    },
                    name: {
                        required: true,
                    },
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
                    image: {
                        required: false,
                        extension: "jpg|png|gif|jpeg",
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 6,
                        equalTo: "#password"
                    }
                },
                messages: {
                    role_id: {
                        required: "Please Select  Role",
                    },
                    name: {
                        required: "Please Enter  Name",
                    },
                    email: {
                        required: "Please Enter Valid Email Address",
                    },
                    mobile: {
                        required: "Please Enter Mobile No",
                        number: "Please Enter Valid Mobile No",
                        minlength: "Mobile Should Be 10 Digits",
                        maxlength: "Mobile Should Be 10 Digits",
                    },
                    image: {
                        required: "Please Select Photo",
                        extension: "Please upload file in these format only (jpg, jpeg, png, gif)",
                    },
                    password: {
                        "required": "Please Enter Password",
                    },
                    password_confirmation: {
                        "required": "Please Enter Confirm Password",
                        "equalTo": "Password And Confirm Password Should Be Same",
                    }
                }
            });
        });
    </script>
@endsection
