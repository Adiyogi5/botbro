@extends('admin.layouts.main')
@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-end">
            <div class="card-title pull-right">
              <a href="{{ route('admin.profit_shares.index') }}" class="btn btn-success"><i class="fa fa-list"></i> Profit Share Lists</a>
            </div>
          </div>
          <form id="notificationslForm" method="post" action="{{ route('admin.profit_shares.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="amount">Amount (₹)<i class="text-danger">*</i><br><small>Define the amount for profit sharing of each users. </small></label>
                    <input type="text" name="amount" class="form-control" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/[^\d\.]/g, '')" value="{{ old('amount') }}" id="amount" placeholder="Enter  amount">
                    <label class="error">{{ $errors->first('amount') }}</label>
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
    $("#notificationslForm").validate({
      rules: {
        amount: {
          required: true,
        },
      },
      messages: {
        amount: {
          required: "The amount field is required",
        },
      }
    });
  });
</script>
@endsection