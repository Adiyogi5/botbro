@extends('admin.layouts.main')

@section('content')
<section class="content">
    <div class="container-fluid">
       <div class="row"> 
        <div class="card">
            <form id="profileForm" method="post" action="{{ url('admin/profile') }}" enctype="multipart/form-data">
                @csrf    
                <div class="card-header p-2 bg-transparent">
                     <div class="d-inline-block">
                          <h3 class="card-title"> <i class="fa fa-pencil"></i>
                          &nbsp; Update Profile </h3>
                      </div>
                      <div class="d-inline-block float-right">
                        <a href="{{ url('admin/change-password') }}" class="btn btn-success"><i class="fa fa-list"></i> Change Password</a>
                      </div>
                </div>
                <div class="card-body">    
                    <div class="form-group row">
                        <div class="col-md-6">    
                        <label for="name" class="col-sm-12 control-label">Name</label> 
                        <div class="col-md-12">
                        <input type="text" name="name" value="{{old('name',$record->name)}}" class="form-control" id="name" placeholder="">
                        </div>
                        </div> 
                        <div class="col-md-6">    
                        <label for="email" class="col-sm-12 control-label">Email</label>

                        <div class="col-md-12">
                        <input type="email" name="email" value="{{old('email',$record->email)}}" class="form-control" id="email" placeholder="">
                        </div>
                        </div>
                    </div>              
                    <div class="form-group row"> 
                        <div class="col-md-6">  
                        <label for="mobile" class="col-sm-12 control-label">Mobile No</label>

                        <div class="col-md-12">
                        <input type="number" name="mobile" value="{{old('mobile',$record->mobile)}}" class="form-control" id="mobile" placeholder="">
                        </div>
                        </div>
                        <div class="col-md-6">  
                        <label for="image" class="col-sm-12 control-label">Image</label> 
                        <div class="col-md-12">
                         <input type="file" class="form-control" name="image" id="image">
                         <p><small class="text-success">Allowed Types: gif, jpg, png, jpeg</small></p>
                         <input type="hidden" name="old_image" value="<?php echo html_escape(@$record->image); ?>">

                         <?php if(!empty($record->image)): ?>
                             <p><img src="{{asset($record->image)}}" class="image logosmallimg"></p>
                         <?php endif; ?>
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
     $("#profileForm").validate({
        rules: {
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
         },
        messages: {
            name:"Please Enter Name",
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
           
        }
    });
    $("body").on("click", ".btn-submit", function(e){
        if ($("#profileForm").valid()){
            $("#profileForm").submit();
        }
    });
  });  
</script>
@endsection