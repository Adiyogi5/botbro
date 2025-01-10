@extends('admin.layouts.main')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-end">
            <div class="card-title pull-right">
              <a href="{{ route('admin.reward_masters.index') }}" class="btn btn-success"><i class="fa fa-list"></i> Reward Master List</a>
            </div>
          </div>
          <form id="rewardMasterForm" method="post" action="{{ route('admin.reward_masters.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="name">Reward Name<i class="text-danger">*</i></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="name" placeholder="Enter Reward name">
                    <label class="error">{{ $errors->first('name') }}</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="min_products">Minimum Products<i class="text-danger">*</i></label>
                    <input type="number" name="min_products" class="form-control" value="{{ old('min_products') }}" id="min_products" min="1" placeholder="Enter Minimum Products">
                    <label class="error">{{ $errors->first('min_products') }}</label>
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
    $("#rewardMasterForm").validate({
      rules: {
        name: {
          required: true,
        },
        min_products: {
          required: true,
          numeric: true,
        },
      },
      messages: {
        name: {
          required: "Please enter blog category name",
        },
        min_products: {
          required: "Please enter minimum products value",
          numeric: "Minimum products value must me numeric",
        }
      }
    });

    $("body").on("click", ".btn-submit", function(e) {
      if ($("#rewardMasterForm").valid()) {
        $("#rewardMasterForm").submit();
      }
    });
  });
</script>
@endsection