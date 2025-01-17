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
              <a href="{{ url('admin/gift') }}" class="btn btn-success"><i class="fa fa-list"></i> Gift Lists</a>
            </div>
          </div>
          <form id="giftForm" method="post" action="{{ url('admin/gift', ['id' => $data->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="name">Name<i class="text-danger">*</i></label>
                    <input type="text" name="name" class="form-control " value="{{ old('name',$data->name) }}" id="name" placeholder="Enter  name">
                    <label class="error">{{ $errors->first('name') }}</label>
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
<script type="text/javascript"> var bPath = '<?=BASE_PATH?>'; </script>
<script src="{{CKEDITOR}}ckeditor.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#parent_id').select2();
    // jquery validation    
    $("#giftForm").validate({
      rules: {
        name: "required",
      },
      messages: {
        name: "Please Enter Name",
      }
    });
    $("body").on("click", ".btn-submit", function(e) {
      if ($("#giftForm").valid()) {
        $("#giftForm").submit();
      }
    });
  });
</script>
@endsection