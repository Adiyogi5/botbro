@extends('admin.layouts.main')

@section('content')
<section class="content">
    <div class="container-fluid">
       <div class="row"> 
        <div class="card">
            <form id="changepassForm" method="post" action="{{ url('admin/change-password') }}" enctype="multipart/form-data">
                @csrf    
                <div class="card-header p-2 bg-transparent">
                    <div class="d-inline-block">
                      <h3 class="card-title"> <i class="fa fa-pencil"></i>
                      &nbsp; Change Password </h3>
                    </div> 
                </div>
                <div class="card-body">    
                    <div class="form-group row">
                        <div class="col-md-12">    
                        <label for="name" class="col-sm-12 control-label">Old Password</label> 
                        <div class="col-md-12">
                        <input type="password" name="old_password" value="{{old('old_password')}}" class="form-control" id="name"  autocomplete="">
                        </div>
                        </div> 
                    </div> 

                    <div class="form-group row">
                        <div class="col-md-12">    
                        <label for="password" class="col-sm-12 control-label">New Password</label>
                        <div class="col-md-12">
                        <input type="password" name="password" value="{{old('password')}}" class="form-control" id="password"  autocomplete="off">
                        </div>
                        </div>
                    </div>        

                    <div class="form-group row"> 
                        <div class="col-md-12">  
                        <label for="password_confirmation" class="col-sm-12 control-label">Confirm Password</label>
                        <div class="col-md-12">
                        <input type="password" name="password_confirmation" value="{{old('password_confirmation')}}" class="form-control" id="password_confirmation" autocomplete="off" >
                        </div>
                        </div> 
                    </div> 
                    <div class="form-group">
                    <div class="col-md-12">
                      <input type="submit" name="submit" value="Submit" class="btn btn-submit btn-primary pull-right">
                    </div>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
</section>
@endsection

@section('footer_scripts')
<script type="text/javascript">
  $(document).ready(function(){     
     $("#changepassForm").validate({
        rules: {
            old_password:"required", 
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
          old_password:"Please Enter Old Password",
           password: {
                "required": "Please Enter Password",
            },
            password_confirmation: {
                "required": "Please Enter Confirm Password",
                "equalTo": "Password And Confirm Password Should Be Same",
            },
        }
    });
    $("body").on("click", ".btn-submit", function(e){
        if ($("#changepassForm").valid()){
            $("#changepassForm").submit();
        }
    });
  });  
</script>
@endsection