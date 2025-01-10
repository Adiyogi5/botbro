@extends('admin.layouts.main')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-end">
            <div class="card-title pull-right">
              <a href="{{ url('admin/banner')}}" class="btn btn-success"><i class="fa fa-list"></i> Banner Lists</a>
            </div>
          </div>
          <form id="bannerForm" method="post" action="{{ url('admin/banner') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="name">Banner Name<i class="text-danger">*</i></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="name" placeholder="Enter Banner name">
                    <label class="error">{{ $errors->first('name') }}</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="url">Banner URL<i class="text-danger">*</i></label>
                    <input type="text" name="url" class="form-control" value="{{ old('url') }}" id="title" placeholder="Enter Banner URL">
                    <label class="error">{{ $errors->first('url') }}</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="title">Banner Title<i class="text-danger">*</i></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" id="title" placeholder="Enter Banner title">
                      <label class="error">{{ $errors->first('title') }}</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="content">Banner Content<i class="text-danger">*</i></label>
                    <textarea id="content" name="content" class="form-control" id="content">{{ old('content') }}</textarea>  
                        <label class="error">{{ $errors->first('content') }}</label> 
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="sort_order">Banner Order<i class="text-danger">*</i></label>
                    <input type="text" name="sort_order" class="form-control" value="{{ old('sort_order') }}" id="sort_order" placeholder="Enter Banner Sort Order" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g, '')">
                    <label class="error">{{ $errors->first('sort_order') }}</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="image">Banner Image<i class="text-danger">*</i></label>
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
    $("#bannerForm").validate({
      rules: {
        name: "required",
        url: "required",
        sort_order: "required",
        image: {
          required: true,
          extension: "jpg|png|gif|jpeg",
        },
      },
      messages: {
        name: "Please Enter Banner Name",
        url: "Please Enter Banner URL",
        sort_order: "Please Enter Banner Sort Order",
        image: {
          required: "Please Select Photo",
          extension: "Please upload file in these format only (jpg, jpeg, png, gif)",
        },

      }
    });
    $("body").on("click", ".btn-submit", function(e) {
      if ($("#bannerForm").valid()) {
        $("#bannerForm").submit();
      }
    });
  });
</script>
@endsection