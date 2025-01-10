@extends('admin.layouts.main')
@section('header_scripts')
<script src="{{VENDOR}}ckeditor/ckeditor/ckeditor.js"></script>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-end">
                        <div class="card-title pull-right">
                            <a href="{{ route('admin.products.index')}}" class="btn btn-success"><i class="fa fa-list"></i> Product Lists</a>
                        </div>
                    </div>
                    <form id="productsForm" method="POST" action="{{ route('admin.products.store') }}" enctype='multipart/form-data'>
                        @csrf
                        <nav class="mt-2">
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-general-tab" data-bs-toggle="tab" data-bs-target="#nav-general" type="button" role="tab" aria-controls="nav-general" aria-selected="true">General</button>
                                <button class="nav-link" id="nav-media-tab" data-bs-toggle="tab" data-bs-target="#nav-media" type="button" role="tab" aria-controls="nav-media" aria-selected="false">Media</button>
                                <button class="nav-link" id="nav-category-tab" data-bs-toggle="tab" data-bs-target="#nav-category" type="button" role="tab" aria-controls="nav-category" aria-selected="false">Category</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-general" role="tabpanel" aria-labelledby="nav-general-tab">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Product Name <i class="text-danger">*</i></label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="name" placeholder="Enter Product Name">
                                                <label class="error">{{ $errors->first('name') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="model">Product Model <i class="text-danger">*</i></label>
                                                <input type="text" name="model" class="form-control" value="{{ old('model') }}" id="model" placeholder="Enter Product Model">
                                                <label class="error">{{ $errors->first('model') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">Product Price <i class="text-danger">*</i></label>
                                                <input type="text" name="price" class="form-control" value="{{ old('price') }}" id="price" placeholder="Enter Product Price"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/[^\d\.]/g, '')" >
                                                <label class="error">{{ $errors->first('price') }}</label>
                                            </div>
                                        </div>
                                       
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="stock_quantity">Stock Quantity</label>
                                                <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity') }}" id="stock_quantity" placeholder="Enter Stock Qty"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/[^\d\.]/g, '')" >
                                                <label class="error">{{ $errors->first('stock_quantity') }}</label>
                                            </div>
                                        </div>
                                         
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sort_order">Sort Order</label>
                                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order') }}" id="sort_order" placeholder="Enter Sort Order"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/[^\d\.]/g, '')" >
                                                <label class="error">{{ $errors->first('sort_order') }}</label>
                                            </div>
                                        </div>
                                         <div class="col-md-6">
                                            <label for="product_image">Attachment<small> ( Image Size must be 250px x 250px )</small></label>
                                            <input type="file" name="product_image" class="form-control" id="product_image">
                                            <label class="error">{{ $errors->first('product_image') }}</label>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="referral_price">From Referral Amount <i class="text-danger">*</i></label>
                                                <input type="text" name="referral_price" class="form-control" value="{{ old('referral_price') }}" id="referral_price" placeholder="Enter From Referral Amount"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/[^\d\.]/g, '')" >
                                                <label class="error">{{ $errors->first('referral_price') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="refer_price">To Referral Amount <i class="text-danger">*</i></label>
                                                <input type="text" name="refer_price" class="form-control" value="{{ old('refer_price') }}" id="refer_price" placeholder="Enter To Referral Amount"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/[^\d\.]/g, '')" >
                                                <label class="error">{{ $errors->first('refer_price') }}</label>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Product Description <i class="text-danger">*</i></label>
                                                <textarea type="text" name="description" class="form-control" id="description" rows="4" placeholder="Enter Product description">{{ old('description') }}</textarea>
                                                <label class="error">{{ $errors->first('description') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-media" role="tabpanel" aria-labelledby="nav-media-tab">
                                <div class="card-body">
                                    <div class="row"> 
                                        <div class="col-12">
                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                @foreach(config('constant.media_types')??[] as $key => $value)
                                                <li class="nav-item" id="{{$key}}" role="presentation">
                                                    <button class="nav-link" id="{{$key}}-tab" onclick="selectTab(this)" data-bs-toggle="tab" data-bs-target="#{{$key}}-tab-pane" type="button" role="tab" aria-controls="{{$key}}-tab-pane" aria-selected="false">
                                                        <span>{{$value}}</span>
                                                    </button>
                                                </li>
                                                @endforeach
                                            </ul>
                                            <div class="tab-content" id="myTabContent">
                                                @foreach(config('constant.media_types')??[] as $media_key => $media_value)
                                                @php
                                                $autokeynum = 0;
                                                $autokey = [];
                                                $autokey[$media_key]=$autokeynum;
                                                @endphp
                                                <div class="tab-pane fade" id="{{$media_key}}-tab-pane" role="tabpanel" aria-labelledby="{{$media_key}}-tab" tabindex="0">
                                                    <div class="row">
                                                        <div>
                                                            <div class="form-group row mx-0">
                                                                <div class="col-md-12 px-0 py-2 text-end">
                                                                    <input type="button" name="addpath" data-lang="{{$media_key}}" value="Add Image" class="btn btn-primary addpath">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mx-0">
                                                                <div class="col-md-12 px-0" id="objectives">
                                                                    <table class="table table-bordered" id="product_image_id">
                                                                        <thead>
                                                                            <tr>
                                                                                <!-- <th>Name</th> -->
                                                                                <th>{{ $media_value }}</th>
                                                                                <th>Sort Order</th>
                                                                                <th style="width: 100px;">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="{{ $media_key }}">
                                                                            @php
                                                                            if (!empty(old($media_key))) {
                                                                            $blog_medias = old($media_key);
                                                                            } else {
                                                                            $blog_medias = [];
                                                                            }
                                                                            @endphp

                                                                            @if(!empty($blog_medias))
                                                                            @foreach($blog_medias as $key => $media)
                                                                            @php $lang_id = $key; @endphp
                                                                            <tr id="path-row{{ $media_key.'_'.$key }}">
                                                                                <td class="form-group mb-0">
                                                                                    <a href="{{ imageexist($media['media']??old($media_key.'.'.$key.'.media')) }}" target="_blank">
                                                                                        <img class="img-fluid border border-dark p-1 rounded-circle" style="width: 40px;height: 40px;" src="{{ imageexist(old($media_key.'.'.$key.'.media')) }}">
                                                                                        <input type="hidden" name="{{ $media_key }}[{{ $key }}][old_media]" value="{{ old($media_key.'.'.$key.'.media')??Null }}" />
                                                                                    </a>
                                                                                    <input type="file" name="{{ $media_key }}[{{ $key }}][media]" class="@if($media_key == 'image') image_content  @else video_content @endif" />
                                                                                    @if ($errors->has($media_key.'.'.$key.'.media'))
                                                                                    <p class="mb-0">
                                                                                        <small class="error text-danger">{{ $errors->first($media_key.'.'.$key.'.media') }}</small>
                                                                                    </p>
                                                                                    @endif
                                                                                </td>
                                                                                <td class="form-group mb-0">
                                                                                    <input type="number" name="{{ $media_key }}[{{ $key }}][sort_order]" class="form-control text_content" value="{{ old($media_key.'.'.$key.'.sort_order') }}" />
                                                                                    @if ($errors->has($media_key.'.'.$key.'.sort_order'))
                                                                                    <p class="mb-0">
                                                                                        <small class="error text-danger">{{ $errors->first($media_key.'.'.$key.'.sort_order') }}</small>
                                                                                    </p>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    <span class="btn btn-danger btn-sm removepath_dynamic" cid="{{ $media_key.'_'.$key }}"><i class="fa fa-trash"></i></span>
                                                                                </td>
                                                                            </tr>
                                                                            @php
                                                                            $autokeynum = $autokeynum+1;
                                                                            $autokey[$media_key] = $autokeynum;
                                                                            @endphp
                                                                            @endforeach
                                                                            @endif
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-category" role="tabpanel" aria-labelledby="nav-category-tab">
                                <div class="card-body">
                                    <select id="category_id" class="form-control getrelated image_select2" style="width: 100%;">
                                        <option value="">Select Categories</option>
                                        @foreach ($product_categories as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="" style="margin-top:3px;">
                                        <?php
                                        $category_id = '';
                                        $category_list = '';
                                        if (count(old('product_category') ?? []) > 0) {
                                            foreach (old('product_category') as $key => $value) {
                                                $category_id = $value['id'] . ',' . $category_id;
                                                $category_list .= '<div class="col-md-12 related_' . $value['id'] . '">';
                                                $category_list .= $value->name;
                                                $category_list .= '<button type="button" class="btn py-0 px-1 align-baseline remove_related_category" rid="' . $value['id'] . '">
                                                    <i class="fa fa-trash"></i>
                                                    </button>
                                                    <hr class="my-2" />
                                                </div>';
                                            }
                                            if (strpos($category_id, ',') !== false) {
                                                $category_id = rtrim($category_id, ',');
                                            }
                                        }
                                        ?>
                                        <input type="hidden" name="product_category" id="product_category" value="{{ $category_id }}">
                                        <div class="form-control overflow-auto" style="height: 200px;" id="product_categories">{!! $category_list !!}</div>
                                    </div>
                                    <span id="error" class="error invalid-feedback d-inline"></span>
                                    <label class="error">{{ $errors->first('product_category') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span class="btn btn-submit btn-primary pull-right" id="submit">Submit</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<input type="hidden" name="data" value="{{ json_encode($autokey??[]) }}">
@endsection

@section('footer_scripts')
<script type="text/javascript">
    setTimeout(function() {
        var selected = $('#myTab #image button');
        selectTab(selected);
    }, 200);

    function selectTab(selected) {
        $('#myTab li button').removeClass('active');
        $('#myTab li button').attr('aria-selected', 'false');
        $('#myTab').next().children().removeClass('active show');
        $(selected).addClass('active');
        $(selected).attr('aria-selected', 'true');
        $("[aria-labelledby = " + $(selected).attr('id') + "]").addClass('active show');
    };

    var image_row = ($('[name="data"]').val()) ? JSON.parse($('[name="data"]').val()) : 0;

    $('.addpath').on('click', function() {
        var lang_id = $(this).attr('data-lang');

        var html = `<tr id="path-row` + lang_id + '_' + image_row[lang_id] + `">
            <td class="form-group mb-0">
                <input type="file" name="` + lang_id + `[` + image_row[lang_id] + `][media]" class="form-control ` + (lang_id == 'image' ? 'image_content' : 'video_content') + `"/>
            </td>
            <td class="form-group mb-0">
                <input type="number" name="` + lang_id + `[` + image_row[lang_id] + `][sort_order]" class="form-control numeric_content"/>
            </td>
            <td>
                <span class="btn btn-danger btn-sm removepath_dynamic" cid="` + lang_id + '_' + image_row[lang_id] + `"><i class="fa fa-trash"></i></span>
            </td>
        </tr>`;

        $('#objectives #' + lang_id).append(html);
        image_row[lang_id]++;
    });

    $(document).on('click', '.removepath_dynamic', function() {
        var id = $(this).attr('cid');
        $('tr#path-row' + id).remove();
    });

    // Manage questions
    $('.getrelated').on('change', function() {
        var Cat_id = $('#product_category').val();
        var prelatedid = $("#category_id option:selected").val();
        if (prelatedid != '') {
            var str = $('#product_category').val().split(',');
            if ($.inArray(prelatedid, str) !== -1) {
                $('#product_category-error').empty();
                $('#error').text('Product already added!!');
                return false;
            } else {
                if (Cat_id == '') {
                    Cat_id = $("#category_id option:selected").val();
                } else {
                    Cat_id = Cat_id + ',' + $("#category_id option:selected").val();
                }
                var html = '';
                html += `<div class="col-md-12 related_` + prelatedid + `">`;
                html += $("#category_id option:selected").text() + ` 
                      <span class="remove_related_category" rid="` + prelatedid + `">
                        <i class="fa fa-trash"></i>
                      </span>
                      <hr class="my-2" />
                    </div>`;
                $('#product_category').val(Cat_id);
                $('#product_categories').append(html);
            }
        }
    });

    $('#product_categories').delegate('.remove_related_category', 'click', function() {
        var pid = $(this).attr('rid');
        $('.related_' + pid).remove();
        var ncat = '';
        var str = $('#related_videos').val().split(',');
        $.each(str, function(key, value) {
            if (pid != value) {
                ncat = value + ',' + ncat;
            }
        });
        var lastChar = ncat.slice(-1);
        if (lastChar == ',') {
            ncat = ncat.slice(0, -1);
        }
        $('#related_videos').val(ncat);
    });

    $('#submit').on('click', function(e) {
        validator.resetForm();

        $('.image_content').each(function() {
            $(this).rules('add', {
                extension: "jpg|jpeg|png",
                messages: {
                    extension: "The image must be a file of type: JPG, JPEG, PNG.",
                }
            });
        });

        $('.text_content').each(function() {
            $(this).rules('add', {
                required: true,
            });
        });

        $('.numeric_content').each(function() {
            $(this).rules('add', {
                required: true,
                number: true
            });
        });

        if ($('#productsForm').valid()) {
            $('#productsForm').submit();
        } else {
            toastr.error('Please check the form carefully for errors!!');
        }
    });

    var validator = $('#productsForm').validate({
        rules: {
            name: {
                required: true,                
            },
            model: {
                required: true,                
            },
            price: {
                required: true,
                number: true, 
            },
            stock_quantity: {
                required: true,
                number: true, 
            }, 
            sort_order: {
                required: true,
                number: true,
            },
            referral_price: {
                required: true,
                number: true, 
            },
            refer_price: {
                required: true,
                number: true, 
            },
            description: {
                required: true, 
                maxlength: 5000,
            },
            product_image: {
                required: true,
                extension: "jpg|jpeg|png",
            },
        },
        messages: {
            name: {
                required: "Please enter product name",                
            },
            model: {
                required: "Please enter product model",                
            },
            price: {
                required: "Please enter product price",
                number: "Please enter Numaric value",
                
            },
            referral_price: {
                required: "Please enter from referral price",
                number: "Please enter Numeric value",
            },
            refer_price: {
                required: "Please enter to referral price",
                number: "Please enter Numeric value",
            },
            stock_quantity: {
                required: "Please enter stock quantity",
                number: "Please enter Numaric value",                
            }, 
            sort_order: {
                required: "Please enter sort order",
            },
            description: {
                required: "Please enter product description",
            },
            product_image: {
                required: "Please select product image",
                extension: "The product image must be a file of type: JPG, JPEG, PNG.",
            },
        }
    });
</script>
@endsection