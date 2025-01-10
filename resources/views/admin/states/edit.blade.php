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
                <a href="{{ url('admin/states') }}" class="btn btn-success"><i class="fa fa-list"></i>  States Lists</a>
              </div>
            </div>
            <form id="statesForm" method="post" action="{{ url('admin/states', ['id' => $data->id]) }}">
              @method('PUT')
              @csrf
              <div class="card-body">
                <div class="row">
                   <div class="col">
                      <div class="form-group">
                        <label for="country_id">Country<i class="text-danger">*</i></label>
                        <select name="country_id" id="country_id"class="form-control">
                            <option value="">Select Country</option>
                            @foreach($country as $value)
                            <option value="{{$value->id}}" <?php if(old('country_id',$data->country_id)== $value['id']){ echo 'selected'; } ?>>{{$value->name}}</option>
                            @endforeach
                          </select>
                           <div class="row"><label id="country_id-error" class="error" for="country_id"></label></div> 
                          <label class="error">{{ $errors->first('country_id') }}</label>
                      </div>
                    </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="name">State name<i class="text-danger">*</i></label>
                      <input type="text" name="name" class="form-control" id="name" value="{{old('name', $data->name)}}" placeholder="Enter State Name">
                      <label class="error">{{ $errors->first('name') }}</label>
                    </div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label for="code">State Code<i class="text-danger">*</i></label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $data->code) }}" id="code" placeholder="Enter states code">
                          <label class="error">{{ $errors->first('code') }}</label>
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

    $('#country_id').select2(); 
       

     $("#statesForm").validate({
      rules: {
            name:"required",
            country_id:"required",
            code:"required",

         },
        messages: {
            name:"Please Enter State Name",
            country_id:"Please Select Country",
            code:"Please Enter Code"
           
        }
    });
    $("body").on("click", ".btn-submit", function(e){
        if ($("#statesForm").valid()){
            $("#statesForm").submit();
        }
    });

      
  });  

</script>
@endsection
