@extends('admin.layouts.main')
@section('header_scripts')
<script src="{{VENDOR}}ckeditor/ckeditor/ckeditor.js"></script>
@stop
@section('content')
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex justify-content-end">
                <div class="card-title pull-right">
                  <a href="{{ url('admin/faqs')}}" class="btn btn-success"><i class="fa fa-list"></i> Faqs Lists</a>
                </div>
              </div>
              <form id="faqsForm" method="post" action="{{ url('admin/faqs') }}">
                @csrf
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label for="faq_type_id">Faq Type<i class="text-danger">*</i></label>
                        <select name="faq_type_id" id="faq_type_id"class="form-control">
                            <option value="">Select Faq Type</option>
                            @foreach($faqtype as $value)
                            <option value="{{$value->id}}" <?php if(old('faq_type_id')== $value['id']){ echo 'selected'; } ?> >{{$value->name}}</option>
                            @endforeach
                          </select>
                          <div class="row"><label id="faq_type_id-error" class="error" for="faq_type_id"></label></div> 
                          <label class="error">{{ $errors->first('faq_type_id') }}</label>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group">
                        <label for="sort_order">Faq Sort Order<i class="text-danger">*</i></label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order') }}" id="sort_order" placeholder="Enter Sort Order">
                          <label class="error">{{ $errors->first('sort_order') }}</label>
                      </div>
                    </div>
                  </div>
                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label for="question">Question<i class="text-danger">*</i></label>
                          <input type="text" name="question" class="form-control" value="{{ old('question') }}" id="question" placeholder="Enter Faq Question">
                            <label class="error">{{ $errors->first('question') }}</label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label for="answer">Answer<i class="text-danger">*</i></label>
                          <textarea id="answer" name="answer" class="form-control ckeditor" rows="4" id="answer">{{ old('answer') }} </textarea>  
                            <label class="error">{{ $errors->first('answer') }}</label> 
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

     $("#faqsForm").validate({
        rules: {
           faq_type_id :"required",
           sort_order:"required",
           question:"required",
           answer:"required",


         },
        messages: {
            question:"Please Enter Question",
            answer:"Please Enter Answer",
            sort_order:"Please Enter Sort Order",
            faq_type_id:"Please Select Faq Type"
           
        }
    });
    $("body").on("click", ".btn-submit", function(e){
        if ($("#faqsForm").valid()){
            $("#faqsForm").submit();
        }
    }); 


  });  
</script>
@endsection
