@extends('frontend.layouts.app')

@section('content')
    <main>
        {{-- ===============Breadcrumb Start============= --}}
        @include('frontend.includes.profile_header')
        {{-- ===============Breadcrumb End============= --}}


        {{-- ===============Address Start============= --}}
        <section id="dashboard">
            <div class="container my-lg-5 my-md-4 my-3">
                <div class="row pt-lg-4 pt-3">
                    <div class="col-lg-3 col-md-4 col-12">

                        @include('frontend.includes.sidebar_inner')

                    </div>
                    <div class="col-lg-9 col-md-8 col-12 mt-md-0 mt-3">
                        <div class="row row-cols-1 g-3">
                            <div class="col-12 d-flex justify-content-between">
                                <p class="dash-category">My Address</p>
                                <span>
                                    <a class="btn btn-transparent btn-wallet-main float-end add" data-bs-toggle="modal"
                                        data-bs-target="#addModal"><i class="fa-solid fa-plus"></i> Add Address </a>
                                </span>
                            </div>

                            @if (session('Success'))
                                <div class="alert alert-success">
                                    {{ session('Success') }}
                                </div>
                            @endif
                            <div id="success-message" style="display:none;" class="alert alert-success"></div>

                            @if ($user_address_data->isEmpty())
                                <div class="col-12">
                                    <h4 class="fw-500 mt-lg-5 mt-3 text-center justify-content-center">No Address Found!!
                                    </h4>
                                </div>
                            @else
                                @foreach ($user_address_data as $userdata)
                                    <div class="col-12">
                                        <div class="card-wallet-brd bg-white px-lg-3 px-md-3 px-3 py-lg-3 py-md-2 py-2">
                                            <div class="row border-bottom mb-2">
                                                <div class="col-md-8 col-7">
                                                    <p class="modal-ship-title"><i class="fa-solid fa-home faa-modal"></i>
                                                    </p>
                                                </div>
                                                <div class="col-md-4 col-5 text-end justify-content-end">
                                                    <div class="float-end">
                                                        <a type="button" class="mx-1 float-end faa-dott delete"
                                                            data-id="{{ $userdata['id'] }}">
                                                            <i class="fa-solid fa-trash faa-wallet-trash text-danger"></i>
                                                        </a>
                                                    </div>
                                                    <div class="float-end">
                                                        <a type="button" class="mx-1 faa-dott text-success edit"
                                                            data-all="{{ json_encode($userdata) }}" style="padding: 12px;">
                                                            <i class="fa-solid fa-pen faa-wallet-trash text-success"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="modal-ship-content">
                                                        {!! $userdata['address_1'] !!}
                                                        <br>
                                                        {!! $userdata['address_2'] !!} {!! $userdata['postcode'] !!}
                                                    </p>
                                                </div>
                                                <div class="col-12 d-flex justify-content-between">
                                                    <p class="modal-ship-content">
                                                        <span class="text-dark">City : </span> {!! $userdata['city_name'] !!}
                                                    </p>
                                                    <p class="modal-ship-content">
                                                        <span class="text-dark">State : </span> {!! $userdata['state_name'] !!}
                                                    </p>
                                                    <p class="modal-ship-content">
                                                        <span class="text-dark">Country : </span> {!! $userdata['country_name'] !!}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- ===============Address End============= --}}


        {{-- ############# Modal For Address Add and Edit ##############  --}}
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content p-3">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 ">
                                <span>
                                    <p class="modal-category">Add Address</p>
                                </span>
                                <div id="error-message" style="display: none;" class="alert alert-danger"></div>

                                <form id="addForm">
                                    @csrf
                                    <div class="">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input type="hidden" name="mode" id="mode" value="add">
                                                <input type="hidden" name="id" id="id" value="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 mt-2">
                                                <div class="form-group">
                                                    <label>First Address <span class="text-danger">*</span></label>
                                                    <input type="text" name="address_1" id="address_1"
                                                        placeholder="Enter First Address" class="form-control"
                                                        maxlength="250" value="{{ old('address_1') }}" />
                                                    <label id="address_1-error" class="error" for="address_1"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Second Address <span class="text-danger">*</span></label>
                                                    <input type="text" name="address_2" id="address_2"
                                                        placeholder="Enter Second Address" class="form-control"
                                                        maxlength="250" value="{{ old('address_2') }}" />
                                                    <label id="address_2-error" class="error" for="address_2"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="country_id">Country <span
                                                            class="required">*</span></label>
                                                    <select name="country_id" class="form-select country_id"
                                                        id="country_id">
                                                        <option value="">State Country</option>
                                                        @foreach ($countries as $key => $value)
                                                            <option value="{{ $key }}"
                                                                {{ $key == old('country_id') ? 'selected' : '' }}>
                                                                {{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('country_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <label class="form-label" for="state_id">State <span
                                                        class="required">*</span></label>
                                                <select name="state_id" class="form-select state_id" id="state_id">
                                                    <option value="">Select State</option>
                                                </select>
                                                @error('state_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <label class="form-label" for="city_id">City <span
                                                        class="required">*</span></label>
                                                <select name="city_id" class="form-select city_id" id="city_id">
                                                    <option value="">Select City</option>
                                                </select>
                                                @error('city_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-label" for="postcode">City <span
                                                        class="required">*</span></label>
                                                <input type="text" name="postcode" id="postcode"
                                                    placeholder="Enter Postcode" class="form-control" maxlength="250"
                                                    value="{{ old('postcode') }}" />
                                                <label id="postcode-error" class="error" for="postcode"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="text-center justify-content-center pt-lg-4 pt-3 pb-3">
                                                <button type="submit" class="btn btn-order-ship submitbtn">
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection


@section('js')
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script src="{{ASSETS}}js/toastr.min.js"></script>
    <script src="{{ ASSETS }}js/sweetalert2.min.js"></script>
    <script>
        success: function(data) {
            if (data.success) {
                toastr.success(data.message);

                setTimeout(function() {
                    $('#addModal').modal('hide');
                }, 2000);

                setTimeout(function() {
                    location.reload();
                }, 2000);
            } else {
                $('#error-message').text(data.message).show();
            }
        }
    </script>
    <script type="text/javascript">
        // ################ Get States ################
        const getState = (country_id, old_state_id = null) => {
            $.ajax({
                url: "{{ route('frontend.states.ajax') }}",
                type: "POST",
                data: {
                    country_id,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    if (result.status == true) {
                        $('.state_id').html(result.data);
                    }
                    $('.state_id').val(old_state_id);
                }
            });
        }
        // ############# Get Cities ##############
        const getCities = (state_id, old_city_id = null) => {
            $.ajax({
                url: "{{ route('frontend.cities.ajax') }}",
                type: "POST",
                data: {
                    state_id,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    if (result.status == true) {
                        $('.city_id').html(result.data);
                    }
                    $('.city_id').val(old_city_id);
                }
            });
        }

        $('.country_id').on('change', function() {
            var old_state_id = "{{ old('state_id') }}"
            getState(this.value, old_state_id)
        });

        $('.state_id').on('change', function() {
            var old_city_id = "{{ old('city_id') }}"
            getCities(this.value, old_city_id)
        });

        setTimeout(function() {
            $('.country_id').change();
        }, 200);


        //#############  Add Address ##############
        $('.add').on('click', function() {
            document.forms['addForm']['mode'].value = 'add';
            document.forms['addForm']['id'].value = '';
            document.forms['addForm']['address_1'].value = '';
            document.forms['addForm']['address_2'].value = '';
            document.forms['addForm']['country_id'].value = '';
            document.forms['addForm']['state_id'].value = '';
            document.forms['addForm']['city_id'].value = '';
            document.forms['addForm']['postcode'].value = '';
            $('#addModal').modal('show');
        })

        $(document).on('click', ".edit", function() {
            var data = $(this).data('all')
            $('[name="id"]').val(data.id)
            document.forms['addForm']['mode'].value = 'edit';
            document.forms['addForm']['id'].value = data.id;
            document.forms['addForm']['address_1'].value = data.address_1;
            document.forms['addForm']['address_2'].value = data.address_2;
            document.forms['addForm']['country_id'].value = data.country_id;
            document.forms['addForm']['state_id'].value = data.state_id;
            document.forms['addForm']['city_id'].value = data.city_id;
            document.forms['addForm']['postcode'].value = data.postcode;
            getState(data.country_id, data.state_id)
            getCities(data.state_id, data.city_id)
            $('#addModal').modal('show');
        })

        $("#addForm").validate({
            debug: false,
            errorClass: "text-danger fs--1",
            errorElement: "span",
            rules: {
                address_1: {
                    required: true,
                    minlength: 2,
                },
                address_2: {
                    required: true,
                    minlength: 2,
                },
                country_id: {
                    required: true,
                },
                state_id: {
                    required: true,
                },
                city_id: {
                    required: true,
                },
                postcode: {
                    required: true,
                },
            },
            messages: {
                address_1: {
                    required: "Please enter Address",
                },
                address_2: {
                    required: "Please enter Address",
                },
                country_id: {
                    required: "Please Select Country",
                },
                state_id: {
                    required: "Please Select State",
                },
                city_id: {
                    required: "Please Select City",
                },
                postcode: {
                    required: "Please Enter Postcode",
                },
            },
            submitHandler: function(form) {
                var formData = new FormData(form);
                const formDataObj = {};
                if ($('#mode').val() == 'add') {
                    var utype = 'POST';
                } else {
                    var utype = 'PUT';
                }
                formData.forEach((value, key) => (formDataObj[key] = value));
                $.ajax({
                    url: "{{ route('frontend.my_address') }}",
                    data: formDataObj,
                    type: utype,
                    success: function(data) {
                        if (data.success) {
                            toastr.success(data?.message);
                            $('#addModal').modal('hide');
                            window.location.reload();
                        } else {
                            toastr.error(data?.message);
                        }
                    }
                });
            }
        });

        // ############# Delete Address ##############
        $(document).on('click', ".delete", function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this record!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('frontend.my_address.deleteaddress') }}",
                        data: {
                            'id': id,
                            "_token": "{{ csrf_token() }}",
                        },
                        type: 'DELETE',
                        success: function(data) {
                            if (data.success) {
                                Swal.fire({
                                    title: data.message,
                                    icon: "success"
                                }).then(function() {
                                    window.location.reload();
                                });
                            } else {
                                toastr.error(data.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            toastr.error("An error occurred while deleting the address.");
                        }
                    });
                }
            });
        });
    </script>
@endsection
