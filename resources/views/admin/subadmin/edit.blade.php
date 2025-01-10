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
                <a href="{{ url('admin/subadmin') }}" class="btn btn-success"><i class="fa fa-list"></i>  Subadmin Lists</a>
              </div>
            </div>
            <form id="subadminForm" method="post" action="{{ url('admin/subadmin', ['id' => $data->id]) }}" enctype="multipart/form-data">
              @method('PUT')
              @csrf
               <div class="card-body">
                  <div class="row">
                    <div class="col">
                       <div class="form-group">
                        <label for="Role">Role<i class="text-danger">*</i></label>
                         <select name="role_id" id="role_id"class="form-control">
                            <option value="">Select Role</option>
                            @foreach($role as $value)
                            @if(old('role_id')== $value->id OR  $data->role_id == $value->id)
                            <option value="{{$value->id}}" selected>{{$value->name}}</option>
                             @else
                            <option value="{{$value->id}}">{{$value->name}}</option>
                            @endif
                            @endforeach
                          </select>
                          <div class="error">{{ $errors->first('role_id') }}</label>
                      </div>
                     </div>
                    <div class="col">
                      <div class="form-group">
                        <label for="name">Name<i class="text-danger">*</i></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name',$data->name) }}" id="name" placeholder="Enter  name">
                          <div class="error">{{ $errors->first('name') }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label for="mobile">Mobile<i class="text-danger">*</i></label>
                        <input type="number" name="mobile" class="form-control" value="{{ old('mobile',$data->mobile) }}" id="mobile" placeholder="Enter mobile number">
                          <div class="error">{{ $errors->first('mobile') }}</label>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group">
                        <label for="email">Email<i class="text-danger">*</i></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email',$data->email) }}" id="email" placeholder="Enter email">
                          <div class="error">{{ $errors->first('email') }}</label>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                         <label for="image">Image</label>
                        <input type="file" class="form-control" name="image" id="image">
                         <p><small class="text-success">Allowed Types: gif, jpg, png, jpeg</small></p>
                         <input type="hidden" name="old_image" value="<?php echo html_escape(@$data->image); ?>">
                          <div class="error">{{ $errors->first('image') }}</label>
                      </div>
                    </div>
                    <div class="col">
                         @if($data->image)
                             <p><img src="{{asset($data->image)}}" class="image logosmallimg"></p>
                         @endif
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
     $("#subadminForm").validate({
        rules: {
            role_id:"required",
            name:"required",
            email: { required: true, email: true},
            mobile: { 
                    required: true, minlength:10, 
                    maxlength:10, number: true, 
                }, 
            image:{
                    required:false,
                    extension:"jpg|png|gif|jpeg",
                    },
            password:{
                        required: true,
                        minlength: 6
                    },
            password_confirmation:{
                        required: true,
                        minlength: 6,
                        equalTo: "#password"
                    }, 


         },
        messages: {
            role_id:"Please Select  Role",
            name:"Please Enter  Name",
            email: "Please Enter Valid Email Address",
            mobile: {
                    "required": "Please Enter Mobile No",
                    "number": "Please Enter Valid Mobile No",
                    "minlength": "Mobile Should Be 10 Digits",
                    "maxlength": "Mobile Should Be 10 Digits",
                }, 
            image:{
                    required:"Please Select Photo",
                    extension:"Please upload file in these format only (jpg, jpeg, png, gif)",
                     },
            password: {
                "required": "Please Enter Password",
            },
            password_confirmation: {
                "required": "Please Enter Confirm Password",
                "equalTo": "Password And Confirm Password Should Be Same",
            }

           
        }
    });
    $("body").on("click", ".btn-submit", function(e){
        if ($("#subadminForm").valid()){
            $("#subadminForm").submit();
        }
    }); 
  });  
</script>
@endsection
