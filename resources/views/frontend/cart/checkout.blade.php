@extends('frontend.layouts.app')

@section('content')
    <main>
        {{-- ===============Breadcrumb Start============= --}}
        @include('frontend.includes.profile_header')
        {{-- ===============Breadcrumb End============= --}}


        {{-- ===============Checkout Start============= --}}
        <section>
            <div class="container my-lg-5 my-3">
                <form action="{{ url('placeorder') }}" method="post" id="checkout-form">
                    @csrf
                    <div class="row row-cols-1 g-3" id="products-div">
                        <table class="table table-bordered">
                            <thead>
                                <th class="heading-box">S.no</th>
                                <th class="heading-box">Image</th>
                                <th class="heading-box">Product Name</th>
                                <th class="heading-box">Modal</th>
                                <th class="heading-box">Quantity</th>
                                <th class="heading-box">Unit Price</th>
                                <th class="heading-box" colspan="2">Total</th>
                            </thead>
                            <tbody>
                                @if (!empty($products))
                                    @foreach ($products as $key => $product)
                                        <tr>
                                            <td>
                                                {{ $key + 1 }} &nbsp;
                                            </td>
                                            <td>
                                                <img width="100" height="100"
                                                    src="{{ $product['image'], 'product_grid', 1 }}" alt="Product image">
                                            </td>
                                            <td>
                                                <a class="text-decoration-none text-dark text-capitalize"
                                                    href="{{ url('product-details', ['product_slug' => $product['slug']]) }}">
                                                    {{ $product['name'] }}
                                                </a>
                                            </td>

                                            <td class="text-capitalize">
                                                {{ $product['model'] }}
                                            </td>

                                            <td>
                                                {{ $product['quantity'] }}
                                            </td>

                                            <td>
                                                {{ CURRENCY_SYMBOL }}{{ $product['price'] }}
                                            </td>
                                            <td colspan="2">
                                                {{ CURRENCY_SYMBOL }}{{ $product['total'] }}
                                            </td>

                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-3">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-md-0 mb-3">
                            <span>
                                <p class="ship-category">Choose Delivery Address
                                    <a href="#" class="btn btn-order-ship float-end mt-lg-3 mt-md-2 mt-2"
                                        data-bs-toggle="modal" data-bs-target="#editaddressModal"> Edit </a>
                                </p>
                            </span>

                            @php
                                $selected_address = '';
                                $selected_address_modal = '';
                                $modal_addresses = '';

                                if (!empty($addresses)) {
                                    foreach ($addresses as $key => $address) {
                                        if (($key == 0 && empty(old('address_id'))) || old('address_id') == $address['id']) {
                                            $selected_address =
                                                '<div class="card-order-ship selected-address px-lg-3 px-md-3 px-3 py-lg-3 py-md-2 py-2">
                                                    
                                                    <p class="order-ship-content">
                                                        ' .
                                                $address['name'] .
                                                ', ' .
                                                $address['address_1'] .
                                                ', ' .
                                                $address['address_2'] .
                                                '
                                                    </p>
                                                </div>';

                                            $selected_address_modal =
                                                '<div class="col-12 ">
    
                                                    <div class="card-modal-ship-active address-div px-lg-3 px-md-3 px-3 py-lg-3 py-md-2 py-2">
                                                        <span>
                                                            <p class="modal-ship-title">
                                                                Address
                                                            </p>
                                                        </span>
                                                        <p class="modal-ship-content">' .
                                                $address['name'] .
                                                ', ' .
                                                $address['address_1'] .
                                                ', ' .
                                                $address['address_2'] .
                                                '</p>
                                                        <div class="text-center justify-cintent-center pt-lg-1 pt-1 pb-2">
                                                            <button type="button" data-address-id="' .
                                                $address['id'] .
                                                '" class="btn btn-modal-active switch-address">
                                                            Set Default Address
                                                            </button>
                                                        </div>
                                                    </div>
                                                    </div>';
                                        } else {
                                            $modal_addresses .=
                                                '<div class="col-12 mt-lg-4 mt-3">
                                            <div class="card-modal-ship-inactive address-div px-lg-3 px-md-3 px-3 py-lg-3 py-md-2 py-2">
                                                <span>
                                                    <p class="modal-ship-title">Address 
                                                    </p>
                                                </span>
                                                <p class="modal-ship-content">' .
                                                $address['name'] .
                                                ', ' .
                                                $address['address_1'] .
                                                ', ' .
                                                $address['address_2'] .
                                                '</p>
                                                <div class="text-center justify-cintent-center pt-lg-1 pt-1 pb-2">
                                                    <button data-address-id="' .
                                                $address['id'] .
                                                '" type="button" class="btn btn-modal-inactive switch-address">
                                                        Set Default Address
                                                    </button>
                                                </div>
                                            </div>
                                        </div>';
                                        }
                                    }
                                }
                            @endphp

                            @if (!empty($addresses))
                                <input type="hidden" name="address_id" id="address_id"
                                    value="{{ old('address_id', $addresses[0]['id']) }}">

                                {!! $selected_address !!}
                            @else
                                <input type="hidden" name="address_id" id="address_id" value="">
                                <p>You have not added any address. Please add an address first.</p>

                                <a class="btn btn-order-ship float-end mt-lg-3 mt-md-2 mt-2" data-bs-toggle="modal"
                                        data-bs-target="#addModal"><i class="fa-solid fa-plus"></i> Add Address </a>
                            @endif
                        </div>

                        {{-- Payment option --}}
                        <div class="total-box ms-auto col-xl-5 col-lg-6 col-md-6 col-12">
                            @foreach ($totals as $key => $total)
                                <div class="d-flex justify-content-between">
                                    <span class="text-end fw-bold">{!! $total['title'] !!} : </span>
                                    <span class="text-end">
                                        {!! $total['value'] !!}
                                    </span>
                                </div>
                            @endforeach
                            <div
                                class="text-center text-md-end justify-content-center justify-content-md-end pt-lg-4 pt-3 pb-3">
                                <input type="submit" class="btn btn-md btn-warning text-white btn-upaycard px-lg-5 px-md-3"
                                    value="Place Order">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>


        {{-- ############# Modal For Address Edit ##############  --}}
        <div class="modal fade" id="editaddressModal" tabindex="-1" aria-labelledby="editaddressModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content p-3">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <span>
                                <p class="modal-category">Select Address
                                    <a class="btn btn-order-ship float-end mt-lg-3 mt-md-2 mt-2" data-bs-toggle="modal"
                                        data-bs-target="#addModal"><i class="fa-solid fa-plus"></i> Add Address </a>
                                </p>
                            </span>

                            {!! $selected_address_modal !!}
                            {!! $modal_addresses !!}

                        </div>
                    </div>
                    {{-- <div class="modal-footer">
                    </div> --}}
                </div>
            </div>
        </div>


        {{-- ############# Modal For Address Add ##############  --}}
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content px-lg-5 px-3 py-3">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 ">
                                <span>
                                    <p class="modal-category">Add Address</p>
                                </span>

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
                                                <label class="form-label" for="postcode">Postcode <span
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

    {{-- ===============Checkout End============= --}}
    
    </main>
@endsection


@section('js')
    <script src="{{ ASSETS }}js/sweetalert2.min.js"></script>
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
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
            // document.forms['addForm']['id'].value = data.id;
            // document.forms['addForm']['address_1'].value = data.address_1;
            // document.forms['addForm']['address_2'].value = data.address_2;
            // document.forms['addForm']['country_id'].value = data.country_id;
            // document.forms['addForm']['state_id'].value = data.state_id;
            // document.forms['addForm']['city_id'].value = data.city_id;
            // document.forms['addForm']['postcode'].value = data.postcode;
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
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                            
                        } else {
                            toastr.error(data?.message);
                        }
                    }
                });
            }
        });

        $(document).ready(function() {

            let address_id = $("#address_id").val();


            $(".switch-address").click(function() {

                let address_id = $(this).data('address-id');
                $.get('{{ url("switch_address") }}',
                    {
                    _token:"{{ csrf_token() }}",
                    address_id : address_id,
                    },
                    function(data){
                    toastr.success('Address Changed Successfully');
                });

                $(".address-div").removeClass('card-modal-ship-active');
                $(".address-div").removeClass('card-modal-ship-inactive');
                $(".address-div").addClass('card-modal-ship-inactive');

                $(".switch-address").removeClass('btn-modal-inactive');
                $(".switch-address").removeClass('btn-modal-active');
                $(".switch-address").addClass('btn-modal-inactive');

                $(this).removeClass('btn-modal-inactive');
                $(this).addClass('btn-modal-active');

                let parent_div = $(this).closest('.address-div');

                $(parent_div).removeClass('card-modal-ship-inactive');
                $(parent_div).addClass('card-modal-ship-active');

                

                $("#address_id").val($(this).data('address-id'));

                $(".selected-address").html($(parent_div).html());
                $(".selected-address").find('.modal-ship-content').next().remove();

            });

        });
    </script>
@endsection
