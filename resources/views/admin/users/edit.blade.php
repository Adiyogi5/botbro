@extends('admin.layouts.main')

@section('content')
<!-- Main content -->
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
                    <form id="customerForm" method="post" action="{{ route('admin.customer.update', [$data->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name<i class="text-danger">*</i></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $data->name) }}" id="name" placeholder="Enter  name">
                                        <label class="error">{{ $errors->first('name') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Mobile<i class="text-danger">*</i></label>
                                        <input type="number" name="mobile" class="form-control" value="{{ old('mobile', $data->mobile) }}" id="mobile" placeholder="Enter mobile number">
                                        <label class="error">{{ $errors->first('mobile') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email<i class="text-danger">*</i></label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $data->email) }}" id="email" placeholder="Enter email">
                                        <label class="error">{{ $errors->first('email') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image">Image<small>( Image Size must be 250px x 250px )</small></label>
                                        <div class="input-group border flex-nowrap rounded">
                                            <a href="{{ get_image($data->image, 'map') }}" target="_blank">
                                                <img class="input-group-text p-1 bg-transparent img-thumbnail h-100" style="width: 40px;" src="{{ get_image($data->image, 'map') }}">
                                                <input type="hidden" name="old_image" value="{{ $data->image??Null }}" />
                                            </a>
                                            <input class="form-control" id="image" name="image" type="file" />
                                            <label class="error">{{ $errors->first('image') }}</label>
                                        </div>
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
        $('#country_id').trigger('change');
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
                },
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
                    "required": "Please Enter Mobile No",
                    "number": "Please Enter Valid Mobile No",
                    "minlength": "Mobile Should Be 10 Digits",
                    "maxlength": "Mobile Should Be 10 Digits",
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