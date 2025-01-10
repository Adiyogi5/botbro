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
                            <a href="{{ route('admin.address.index', ['user_id' => $user->id]) }}" class="btn btn-success"><i class="fa fa-list"></i> Coustomer Address List</a>
                        </div>
                    </div>
                    <form id="customerForm" method="post" action="{{ route('admin.address.update', [$data->id, 'user_id' => $user->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="country">Country</label>
                                    <select name="country" id="country" class="form-select form-control formtext-color">
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $ck => $cv)
                                        <option value="{{ $cv->id }}" {{ old('country', $data->country_id) == $cv->id ? 'selected' : '' }}>
                                            {{ $cv->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <label class="error">{{ $errors->first('country') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <label for="state">State</label>
                                    <select name="state" id="state" class="form-select form-control formtext-color">
                                        <option value="">Select State</option>
                                    </select>
                                    <label class="error">{{ $errors->first('state') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <label for="city">City</label>
                                    <select name="city" id="city" class="form-select form-control formtext-color">
                                        <option value="">Select City</option>
                                    </select>
                                    <label class="error">{{ $errors->first('city') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="postcode">Postcode<i class="text-danger">*</i></label>
                                        <input type="number" name="postcode" class="form-control" value="{{ old('postcode', $data->postcode) }}" id="postcode" placeholder="Enter postcode">
                                        <label class="error">{{ $errors->first('postcode') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_1">Address 1<i class="text-danger">*</i></label>
                                        <textarea name="address_1" class="form-control" id="address_1" placeholder="Enter address 1">{{ old('address_1', $data->address_1) }}</textarea>
                                        <label class="error">{{ $errors->first('address_1') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_2">Address 2</label>
                                        <textarea name="address_2" class="form-control" id="address_2" placeholder="Enter address 2">{{ old('address_2', $data->address_2) }}</textarea>
                                        <label class="error">{{ $errors->first('address_2') }}</label>
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
        $('#country').trigger('change');

        $("#customerForm").validate({
            rules: {
                country: {
                    required: true,
                    number: true,
                },
                state: {
                    required: true,
                    number: true,
                },
                city: {
                    number: true,
                },
                postcode: {
                    required: true,
                    number: true,
                },
                address_1: {
                    required: true,
                    minlength: 2,
                    maxlength: 250,
                },
                address_2: {
                    maxlength: 250,
                },
            },
            messages: {
                country: {
                    required: "The country field is required.",
                    number: "The country must be a numeric",
                },
                state: {
                    required: "The state field is required.",
                    number: "The state must be a numeric",
                },
                city: {
                    number: "The city must be a numeric",
                },
                postcode: {
                    required: "The postcode field is required.",
                    number: "The postcode must be a numeric",
                },
                address_1: {
                    required: "The address 1 field is required.",
                    minlength: "The address 1 must not be less than 2 characters.",
                    maxlength: "The address 1 must not be greater than 250 characters.",
                },
                address_2: {
                    maxlength: "The address 2 must not be greater than 250 characters.",
                },
            }
        });
    });

    $('#country').on('change', function() {
        var idCountry = this.value;
        var old_state_id = "{{ old('state', $data->state_id) }}";
        $.ajax({
            url: "{{ route('admin.states.ajax') }}",
            type: "POST",
            data: {
                country_id: idCountry,
                state_id: old_state_id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {
                if (result.status == true) {
                    $('#state').html(result.data);
                    $('#state').val(old_state_id);
                    $('#state').trigger('change');
                }
            }
        });
    });

    $('#state').on('change', function() {
        var idState = this.value;
        var old_city_id = "{{ old('city', $data->city_id) }}";
        $.ajax({
            url: "{{ route('admin.cities.ajax') }}",
            type: "POST",
            data: {
                state_id: idState,
                city_id: old_city_id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {
                if (result.status == true) {
                    $('#city').html(result.data);
                }
                $('#city').val(old_city_id);
            }
        });
    });
</script>
@endsection