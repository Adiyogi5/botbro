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
              <a href="{{ route('admin.offer.index') }}" class="btn btn-success"><i class="fa fa-list"></i> Offer Lists</a>
            </div>
          </div>
          <form id="bannerForm" method="post" action="{{ route('admin.offer.update', [$data->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="name">Offer Name<i class="text-danger">*</i></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $data->name) }}" id="name" placeholder="Enter Offer name">
                    <label class="error">{{ $errors->first('name') }}</label>
                  </div>
                </div>
                {{-- <div class="col-md-6">
                  <div class="form-group">
                    <label for="url">Offer URL<i class="text-danger">*</i></label>
                    <input type="text" name="url" class="form-control" value="{{ old('url', $data->url) }}" id="url" placeholder="Enter Offer URL">
                    <label class="error">{{ $errors->first('url') }}</label>
                  </div>
                </div> --}}
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="sort_order">Offer Order<i class="text-danger">*</i></label>
                    <input type="text" name="sort_order" class="form-control" value="{{ old('sort_order', $data->sort_order) }}" id="sort_order" placeholder="Enter Offer Sort Order" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g, '')">
                    <label class="error">{{ $errors->first('sort_order') }}</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="image">Offer Image<i class="text-danger">*</i></label>
                    <input type="file" class="form-control" name="image" id="image">
                    <p><small class="text-success">Allowed Types: gif, jpg, png, jpeg</small></p>
                    <input type="hidden" name="old_image" value="<?php echo html_escape(@$data->image); ?>">
                    <?php if(!empty($data->image)): ?>
                         <p><img class="img-fluid border border-dark p-1 rounded ms-1" style="width: 100px;height: 60px;margin-top:6px;" src="{{ imageexist($data['old_image']??($data->image??''), 'products') }}"></p>
                    <?php endif; ?>
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
        sort_order: "required",
        image: {
          required: false,
          extension: "jpg|png|gif|jpeg",
        },
      },
      messages: {
        name: "Please Enter Offer Name",
        sort_order: "Please Enter Offer Sort Order",
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