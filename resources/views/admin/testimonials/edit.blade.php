@extends('admin.layouts.main')
@section('header_scripts')
<script src="{{VENDOR}}ckeditor/ckeditor/ckeditor.js"></script>
@stop
@section('content') 
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header d-flex justify-content-end">
              <div class="card-title pull-right">
                <a href="{{ url('admin/testimonials') }}" class="btn btn-success"><i class="fa fa-list"></i>  Testimonial Lists</a>
              </div>
            </div>
            <form id="testimonialsForm" method="post" action="{{ url('admin/testimonials', ['id' => $data->id]) }}" enctype="multipart/form-data">
              @method('PUT')
              @csrf
               <div class="card-body">
                  <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label for="name">Name<i class="text-danger">*</i></label>
                          <input type="text" name="name" class="form-control" value="{{ old('name',$data->name) }}" id="name" placeholder="Enter  name"> 
                          <label class="error">{{ $errors->first('name') }}</label> 
                        </div>
                      </div>
                      <div class="col">
                        <div class="form-group">
                          <label for="designation">Designation<i class="text-danger">*</i></label>
                          <input type="text" name="designation" class="form-control" value="{{ old('designation',$data->designation) }}" id="designation" placeholder="Enter  designation"> 
                          <label class="error">{{ $errors->first('designation') }}</label> 
                        </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label for="message">Message<i class="text-danger">*</i></label>
                          <textarea id="message" name="message" class="form-control" rows="4" id="message">{{ old('message',$data->message) }} </textarea>  
                            <label class="error">{{ $errors->first('message') }}</label> 
                        </div>
                      </div>
                  </div>
                   <div class="row">
                        <div class="col">
                          <div class="form-group">
                            <label for="sort_order">Sort Order<i class="text-danger">*</i></label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order',$data->sort_order) }}" id="sort_order" placeholder="Enter Sort Order">
                            <label class="error">{{ $errors->first('sort_order') }}</label>
                          </div>
                        </div>
                      <div class="col">
                          <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" name="image" id="image">
                             <p><small class="text-success">Allowed Types: gif, jpg, png, jpeg</small></p>
                             <input type="hidden" name="old_image" value="<?php echo html_escape(@$data->image); ?>">
                             <?php if(!empty($data->image)): ?>
                                   <p><img class="img-fluid border border-dark p-1 rounded ms-2" style="width: 80px;height: 80px;margin-top:6px;" src="{{ imageexist($data['old_image']??($data->image??''), 'products') }}"></p>
                             <?php endif; ?>
                              <label class="error">{{ $errors->first('image') }}</label> 
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
    

    // jquery validation    
    $("#testimonialsForm").validate({
        rules: {
            name:"required",
            designation:"required",
            message:{
                required: true,
                minlength: 5,  
            },
            sort_order:"required",
            image:{
                    required:false,
                    extension:"jpg|png|gif|jpeg",
                    },

         },
        messages: {
            name:"Please Enter  Name",
            designation:"Please Enter  Designation",
            message:"Please Enter  Message",
            sort_order:"Please Enter  Order",
            image:{
                  required:"Please Select Photo",
                  extension:"Please upload file in these format only (jpg, jpeg, png, gif)",
                   },
         
        }
    });
    $("body").on("click", ".btn-submit", function(e){
        if ($("#testimonialsForm").valid()){
            $("#testimonialsForm").submit();
        }
    }); 
  });  
</script>
@endsection
