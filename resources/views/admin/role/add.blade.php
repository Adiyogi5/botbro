@extends('admin.layouts.main')

@section('content')
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex justify-content-end">
                <div class="card-title pull-right">
                  <a href="{{ url('admin/role')}}" class="btn btn-success"><i class="fa fa-list"></i> Role Lists</a>
                </div>
              </div>
              <form id="roleForm" method="post" action="{{ url('admin/role') }}">
                @csrf
                <div class="card-body">
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label for="name">Role Name<i class="text-danger">*</i></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="name" placeholder="Enter Role name">
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
<script type="text/javascript">
  $(document).ready(function(){     
     $("#roleForm").validate({
        rules: {
            name:"required",
         },
        messages: {
            name:"Please Enter Role Name",
           
        }
    });
    $("body").on("click", ".btn-submit", function(e){
        if ($("#roleForm").valid()){
            $("#roleForm").submit();
        }
    }); 
  });  
</script>
@endsection
