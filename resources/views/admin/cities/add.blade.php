@extends('admin.layouts.main')

@section('content')
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex justify-content-end">
                <div class="card-title pull-right">
                  <a href="{{ url('admin/cities')}}" class="btn btn-success"><i class="fa fa-list"></i> City Lists</a>
                </div>
              </div>
              <form id="citiesForm" method="post" action="{{ url('admin/cities') }}">
                @csrf
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label for="country_id">Country<i class="text-danger">*</i></label>
                        <select name="country_id" id="country_id" class="form-control">
                            <option value="">Select Country</option>
                            @foreach($country as $value)
                            <option value="{{$value->id}}" <?php if(old('country_id')== $value['id']){ echo 'selected'; } ?> >{{$value->name}}</option>
                            @endforeach
                          </select>
                          <div class="row"><label id="country_id-error" class="error" for="country_id"></label></div> 
                        <label class="error">{{ $errors->first('country_id') }}</label>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group">
                        <label for="state_id">State<i class="text-danger">*</i></label>
                        <select name="state_id" id="state_id" class="form-control">

                        <option value="">Select State</option>
                        </select>
                        <div class="row"><label id="state_id-error" class="error" for="state_id"></label></div> 
                        <label class="error">{{ $errors->first('state_id') }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label for="name">City Name<i class="text-danger">*</i></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="name" placeholder="Enter cities name">
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

    $('#country_id').select2();
    $('#state_id').select2();

    $("#citiesForm").validate({
        rules: {
            name:"required",
            country_id:"required",
            state_id:"required",


         },
        messages: {
            name:"Please Enter State Name",
            country_id:"Please Select Country",
            state_id:"Please Select state",
        }
    });

  });

    setTimeout(function(){
      $('#country_id').change();
    },1000); 

    $('#country_id').on('change', function () {
      var idCountry = this.value;
      var old_state_id = '<?=old('state_id')?>'
     // $("#state_id").html('');
      $.ajax({
        url: "{{url('admin/getstate')}}",
        type: "POST",
        data: {
          country_id: idCountry,
          _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function (result) { 
          if (result.status==true) {
            $('#state_id').html(result.data);
          }  
          $('#state_id').val(old_state_id);           
        }
      });
    });
</script>
@endsection
