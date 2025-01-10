@extends('admin.layouts.main')
@section('header_scripts')
<script src="{{ASSETS}}ckeditor/ckeditor.js"></script>
@stop
@section('content')
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex justify-content-end">
                <div class="card-title pull-right">
                  <a href="{{ url('admin/blog')}}" class="btn btn-success"><i class="fa fa-list"></i> Blog Lists</a>
                </div>
              </div>
              <form id="blogForm" method="post" action="{{ url('admin/blog') }}"  enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                       <label for="name">Select Blog Category<i class="text-danger">*</i></label>
                         <select name="category_1" id="category_1" class="form-select">
                          <option value="">Select Category</option>
                              @if (!empty($level1_categories))
                              @foreach ($level1_categories as $l1Key => $l1Value)
                              <option value="{{ $l1Value['id'] }}">{{ $l1Value['name'] }}</option>
                              @endforeach
                              @endif
                          </select>
                     </div>
               </div>
                </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="title">Blog Title<i class="text-danger">*</i></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" id="title" placeholder="Enter Blog title">
                          <label class="error">{{ $errors->first('title') }}</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="post_by">Blog Post By<i class="text-danger">*</i></label>
                        <input type="text" name="post_by" class="form-control" value="{{ old('post_by') }}" id="post_by" placeholder="Enter Blog Post By">
                          <label class="error">{{ $errors->first('post_by') }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="sort_order">Blog Order<i class="text-danger">*</i></label>
                        <input type="text" name="sort_order" class="form-control" value="{{ old('sort_order') }}" id="sort_order" placeholder="Enter Blog Sort Order"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g, '')">
                          <label class="error">{{ $errors->first('sort_order') }}</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="image">Blog Image<i class="text-danger">*</i></label>
                        <input type="file" class="form-control" name="image" id="image">
                          <p class="mb-0"><small class="text-success">Allowed Types: gif, jpg, png, jpeg</small></p>
                          <input type="hidden" name="old_image" value="<?php echo html_escape(@$data->image); ?>">
                          <label class="error">{{ $errors->first('image') }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="description">Blog Sort Description<i class="text-danger">*</i></label>
                        <textarea id="sort_description" name="sort_description" class="form-control" id="sort_description">{{ old('sort_description') }}</textarea>  
                        <label class="error">{{ $errors->first('sort_description') }}</label> 
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="description">Blog Description<i class="text-danger">*</i></label>
                        <textarea id="description" name="description" class="form-control ckeditor" rows="4" id="description">{{ old('description') }}</textarea>  
                        <label class="error">{{ $errors->first('description') }}</label> 
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="meta_title">Meta Title<i class="text-danger">*</i></label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}" id="meta_title" placeholder="Enter Meta Title"> 
                        <label class="error">{{ $errors->first('meta_title') }}</label> 
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="meta_keyword">Meta Keyword<i class="text-danger">*</i></label>
                        <textarea name="meta_keyword" class="form-control" id="meta_keyword" placeholder="Enter Meta Keyword">{{ old('meta_keyword') }}</textarea> 
                          <label class="error">{{ $errors->first('meta_keyword') }}</label> 
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="meta_description">Meta Description<i class="text-danger">*</i></label>
                        <textarea name="meta_description" class="form-control" rows="4" id="meta_description">{{ old('meta_description') }}</textarea>  
                          <label class="error">{{ $errors->first('meta_description') }}</label> 
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
     
    $('#category_1').select2();
     
    $(window).on('load', function (){
      $( '.ckeditor' ).ckeditor();
    });

    $("#blogForm").validate({
        rules: {
            title:"required",
            category_1:"required",
            post_by:"required", 
            sort_order:"required",
            image:{
                    required:true,
                    extension:"jpg|png|gif|jpeg",
            },
            sort_description:"required",
            description:"required",
            meta_title:"required",
            meta_keyword:"required",
            meta_description:"required"
         },
        messages: {
            title:"Please Enter Blog Title",
            category_1:"Please Select Blog Category",
            post_by:"Please Enter Blog Post By Name", 
            sort_order:"Please Enter Blog Sort Order",
            image:{
                    required:"Please Select Photo",
                    extension:"Please upload file in these format only (jpg, jpeg, png, gif)",
            },
            sort_description:"Please Enter Blog Sort Description",
            description:"Please Enter Blog Description",  
            meta_title:"Please Enter Meta Title",
            meta_keyword:"Please Enter Meta Keyword",
            meta_description:"Please Enter Meta Description"
        }
    });
    $("body").on("click", ".btn-submit", function(e){
        if ($("#blogForm").valid()){
            $("#blogForm").submit();
        }
    }); 
  });  
</script>
@endsection
