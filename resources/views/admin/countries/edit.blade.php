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
                <a href="{{ url('admin/countries') }}" class="btn btn-success"><i class="fa fa-list"></i>  Country Lists</a>
              </div>
            </div>
            <form id="countriesForm" method="post" action="{{ url('admin/countries', ['id' => $data->id]) }}">
              @method('PUT')
              @csrf
              <div class="card-body">
                <div class="row">
                   <div class="col">
                      <div class="form-group">
                        <label for="country_code">Country Code<i class="text-danger">*</i></label>
                        <input type="number" name="country_code" class="form-control" value="{{ old('country_code',$data->country_code) }}" id="country_code" placeholder="Enter Country Code">
                          <label class="error">{{ $errors->first('country_code') }}</label>
                      </div>
                    </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="name">Country name<i class="text-danger">*</i></label>
                      <input type="text" name="name" class="form-control" id="name" value="{{old('name', $data->name)}}" placeholder="Enter Country Name">
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
     $("#countriesForm").validate({
        rules: {
            name:"required",
            country_code:"required",
         },
        messages: {
            name:"Please Enter Country Name",
            country_code:"Please Enter Country Code"
           
        }
    });
    $("body").on("click", ".btn-submit", function(e){
        if ($("#countriesForm").valid()){
            $("#countriesForm").submit();
        }
    }); 
  });  
</script>
@endsection
