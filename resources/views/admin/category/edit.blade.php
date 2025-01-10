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
              <a href="{{ url('admin/categories') }}" class="btn btn-success"><i class="fa fa-list"></i> Product Category Lists</a>
            </div>
          </div>
          <form id="categoriesForm" method="post" action="{{ url('admin/categories', ['id' => $data->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="card-body">
              <div class="row">
                <input type="hidden" name="parent_id" id="parent_id" value="0">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="name">Category Name<i class="text-danger">*</i></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $data->name) }}" id="name" placeholder="Enter Banner name">
                    <label class="error">{{ $errors->first('name') }}</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="image">Image<i class="text-danger">*</i><small> ( Image Size must be 250px x 250px )</small></label>
                    <input type="file" class="form-control" name="image" id="image">
                    <p><small class="text-success">Allowed Types: gif, jpg, png, jpeg</small></p>
                    <input type="hidden" name="old_image" value="<?php echo html_escape(@$data->image); ?>">
                    <?php if(!empty($data->image)): ?>
                          <p><img class="img-fluid border border-dark p-1 rounded ms-2" style="width: 80px;height: 80px;margin-top:6px;" src="{{ imageexist($data['old_image']??($data->image??''), 'products') }}"></p>
                    <?php endif; ?>
                    <label class="error">{{ $errors->first('image') }}</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $data->sort_order) }}" id="sort_order" placeholder="Enter Sort Order">
                    <label class="error">{{ $errors->first('sort_order') }}</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="description">Sort Description<i class="text-danger">*</i></label>
                    <textarea id="sort_description" name="sort_description" class="form-control" id="sort_description">{{ old('sort_description', $data->sort_description) }}</textarea>
                    <label class="error">{{ $errors->first('sort_description') }}</label>
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
    $("#categoriesForm").validate({
      rules: {
        name: "required",
        sort_description: "required",

        image: {
          required: false,
          extension: "jpg|png|gif|jpeg",
        },
      },
      messages: {
        name: "Please Enter Category Name",
        sort_description: "Please Enter Sort Description",
        image: {
          required: "Please Select Photo",
          extension: "Please upload file in these format only (jpg, jpeg, png, gif)",
        },

      }
    });
    $("body").on("click", ".btn-submit", function(e) {
      if ($("#categoriesForm").valid()) {
        $("#categoriesForm").submit();
      }
    });
  });
</script>
@endsection