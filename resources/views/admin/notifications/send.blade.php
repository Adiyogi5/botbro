@extends('admin.layouts.main')
@section('content')
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex justify-content-end">
                <div class="card-title pull-right">
                  <a href="{{ url('admin/notifications')}}" class="btn btn-success"><i class="fa fa-list"></i> Notificatin Lists</a>
                </div>
              </div>
              <form id="notificationslForm" method="post" action="{{ url('admin/notifications/store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                  <div class="row">
                     <div class="col">
                      <div class="form-group">
                        <label for="usertype">Customer Type <i class="text-danger">*</i></label>
                            <select name="usertype" id="usertype"class="form-control">
                                <option value="">Select Customer Type </option>
                                <option value="1">All Customers</option>
                                <option value="2">Selected Customers</option>
                            </select>
                          <div class="row"><label id="usertype-error" class="error" for="usertype"></label></div> 
                          <label class="error">{{ $errors->first('usertype') }}</label>
                      </div>
                    </div>
                  </div> 
                  <div class="row mainuser_hide" style="display: none">
                    <div class="col">
                      <div class="form-group">
                        <label for="main_user_id" class="col-sm-6 control-label">Users <i class="text-danger">*</i></label>
                        <select class="form-control select2 getusers" name="main_user_id" id="main_user_id">
                         <option value="">Select Users</option>  
                        </select>  
                        <div class="" style="margin-top:1px;">
                        <input type="hidden" name="user_id" id="user_id" value="">
                          <div class="form-control minheightdiv" id="select_user_id"> </div>
                        <label id="main_user_id-error" class="error" for="main_user_id"></label>  
                        </div> 
                        </div>
                      </div>
                  </div> 

                  <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label for="title">Title<i class="text-danger">*</i></label>
                          <input type="text" name="title" class="form-control" value="{{ old('title') }}" id="title" placeholder="Enter  Title"> 
                          <label class="error">{{ $errors->first('title') }}</label> 
                        </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label for="message">Message<i class="text-danger">*</i></label>
                          <textarea id="message" name="message" class="form-control" rows="4" id="message">{{ old('message') }} </textarea>  
                            <label class="error">{{ $errors->first('message') }}</label> 
                        </div>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label for="attachment">Attachment</label>
                        <input type="file" class="form-control" name="attachment" id="attachment">
                         <p><small class="text-success">Allowed Types: gif, jpg, png, jpeg</small></p>
                         <input type="hidden" name="old_attachment" value="<?php echo html_escape(@$data->attachment); ?>">
                          <label class="error">{{ $errors->first('attachment') }}</label> 
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

     $('#main_user_id').select2(); 
    
    // jquery validation    
    $("#notificationslForm").validate({
        rules: {
            usertype:"required",
            title:"required",
            message:{
                required: true,
                minlength: 5,  
            },
            attachment:{
                    required:false,
                    extension:"jpeg|png|jpg|gif|pdf|xls|xlsx|csv|doc|docx",
                    },

         },
        messages: {
            usertype:"Please Select User Type",
            message:"Please Enter  Message",
            title:"Please Enter  Title",
            attachment:{
                  required:"Please Select File",
                  extension:"Please upload file in these format only (jpeg,png,jpg,gif,pdf,xls,xlsx,csv,doc,docx)",
                   },
         
        }
    });
    $("body").on("click", ".btn-submit", function(e){
        if ($("#notificationslForm").valid()){
            $("#notificationslForm").submit();
        }
    });

    $("#usertype").on('change',function(){
      let valu = $('#usertype').val();
      if (valu == 1 || valu == 3 || valu == '') {
        $('.mainuser_hide').hide();
      }else{
        $('.mainuser_hide').show();
        $.ajax({
        url: "{{url('admin/notifications/users')}}",
        type: "POST",
        data: {
          data_id: valu,
          _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function (result) { 
          if (result.status==true) {
            $('#main_user_id').html(result.data);
          }  
        }
      });
      }
    });

    $('#main_user_id').on('change', function() { 
      var mailid = $('#user_id').val();
      var pmailid = $("#main_user_id option:selected").val();
      if(pmailid!=''){
          var str = $('#user_id').val().split(',');
          if ($.inArray(pmailid, str) !== -1) {
            alert('Mobile allready added!!!');
            return false;
          }else{
            if(mailid==''){
              mailid = $("#main_user_id option:selected").val();
            }else{
              mailid = mailid+','+$("#main_user_id option:selected").val();
            }      
            var html = '<div class="col-md-12 user'+pmailid+'">'+ $("#main_user_id option:selected").text()+' <span class="removeuser" mid="'+pmailid+'"><i class="fa fa-trash"></i></span></div>';
            $('#user_id').val(mailid);
            $('#select_user_id').append(html);
          }
        }
    });

    $('#select_user_id').delegate('.removeuser', 'click', function() { 
      var user_id = $(this).attr('mid'); 
      $('.user'+user_id).remove();
      var nmail = '';
      var str = $('#user_id').val().split(',');
      $.each(str, function(key,value) {
        if (user_id!=value) {
          nmail = value+','+nmail;
        }
      }); 
      var lastChar = nmail.slice(-1);
      if (lastChar == ',') {
          nmail = nmail.slice(0, -1);
      }
      $('#user_id').val(nmail);
    }); 

  });  

</script>
@endsection