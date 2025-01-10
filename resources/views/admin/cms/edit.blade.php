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
              <a href="{{ url('admin/cms') }}" class="btn btn-success"><i class="fa fa-list"></i> Cms Lists</a>
            </div>
          </div>
          <form id="cmsForm" method="post" action="{{ url('admin/cms', ['id' => $data->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="name">Name<i class="text-danger">*</i></label>
                    <div class="form-control bggray">{{$data->name}}</div>
                    <input type="hidden" name="name" class="form-control " value="{{ old('name',$data->name) }}" id="name" placeholder="Enter  name">
                    <label class="error">{{ $errors->first('name') }}</label>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="cms_title">Cms Title<i class="text-danger">*</i></label>
                    <input type="text" name="cms_title" class="form-control" value="{{ old('cms_title',$data->cms_title) }}" id="cms_title" placeholder="Enter  Cms Title">
                    <label class="error">{{ $errors->first('cms_title') }}</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="meta_title">Meta Title<i class="text-danger">*</i></label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title',$data->meta_title) }}" id="meta_title" placeholder="Enter Meta Title">
                    <label class="error">{{ $errors->first('meta_title') }}</label>
                  </div>
                </div>

                <div class="col">
                  <div class="form-group">
                    <label for="meta_keyword">Meta Keyword<i class="text-danger">*</i></label>
                    <input type="text" name="meta_keyword" class="form-control" value="{{ old('meta_keyword',$data->meta_keyword) }}" id="meta_keyword" placeholder="Enter Meta Keyword">
                    <label class="error">{{ $errors->first('meta_keyword') }}</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="meta_description">Meta Description<i class="text-danger">*</i></label>
                    <textarea name="meta_description" class="form-control" rows="4" id="meta_description">{{ old('meta_description',$data->meta_description) }} </textarea>
                    <label class="error">{{ $errors->first('meta_description') }}</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="cms_contant">Cms Contant<i class="text-danger">*</i></label>
                    <textarea id="cms_contant" name="cms_contant" class="form-control ckeditor" rows="4" id="cms_contant">{{ old('cms_contant',$data->cms_contant) }} </textarea>
                    <label class="error">{{ $errors->first('cms_contant') }}</label>
                  </div>
                </div>
              </div>
              {{-- <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control" name="image" id="image">
                    <p><small class="text-success">Allowed Types: gif, jpg, png, jpeg</small></p>
                    <input type="hidden" name="old_image" value="<?php echo html_escape(@$data->image); ?>">
                    @if($data->image)
                    <p><img src="{{asset($data->image)}}" class="image logosmallimg"></p>
                    @endif
                    <label class="error">{{ $errors->first('image') }}</label>
                  </div>
                </div>
              </div> --}}
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
<script type="text/javascript"> var bPath = '<?=BASE_PATH?>'; </script>
<script src="{{CKEDITOR}}ckeditor.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#parent_id').select2();
    // jquery validation    
    $("#cmsForm").validate({
      rules: {
        cms_title: "required",
        name: "required",
        meta_title: "required",
        meta_keyword: "required",
        meta_description: {
          required: true,
          minlength: 5,
        },
        slug: "required",
        image: {
          required: false,
          extension: "jpg|png|gif|jpeg",
        },

      },
      messages: {
        cms_title: "Please Cms Title",
        name: "Please Enter  Name",
        meta_title: "Please Enter  Meta Title",
        meta_keyword: "Please Enter  Meta Keyword",
        meta_description: "Please Enter  Meta Description",
        slug: "Please Enter  Slug",
        image: {
          required: "Please Select Photo",
          extension: "Please upload file in these format only (jpg, jpeg, png, gif)",
        },

      }
    });
    $("body").on("click", ".btn-submit", function(e) {
      if ($("#cmsForm").valid()) {
        $("#cmsForm").submit();
      }
    });
  });
</script>
@endsection