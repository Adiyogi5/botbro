@extends('admin.layouts.main')
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-center">
                        <div class="card-title mt-2">
                            <h1 class="fw-bold">{{ $roledata->name }} </h2>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">

                        <table class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Id</th>
                                    <th>Module</th>
                                    <th>Add</th>
                                    <th>Edit</th>
                                    <th>View</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody id="result">
                                @php $i = 1  @endphp
                                @foreach($moduledata as $key => $value)
                                <tr id="{{$value->id}}row">
                                    <td class="text-center">{{$i}}</td>
                                    <td class="text-center">{{ucwords(str_replace('_',' ',$value->name))}}</td>
                                    <td class="text-center">
                                        @if($value->can_add == 1)
                                        <input type="checkbox" class="form-check-input mt-0 pt-0" id="{{@$mdata[$value->module_id]['id'] }}" 
                                            data-type="can_add" data-roleid="{{$roledata->id}}" data-modelid="{{$value->module_id}}" @if(@$mdata[$value->module_id]['can_add'] == 1) checked @endif>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($value->can_edit == 1)
                                        <input type="checkbox" class="form-check-input mt-0 pt-0" id="{{@$mdata[$value->module_id]['id'] }}"
                                            data-type="can_edit" data-roleid="{{$roledata->id}}" data-modelid="{{$value->module_id}}" @if(@$mdata[$value->module_id]['can_edit'] == 1) checked @endif>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($value->can_view == 1)
                                        <input type="checkbox" class="form-check-input mt-0 pt-0" id="{{@$mdata[$value->module_id]['id'] }}"
                                            data-type="can_view" data-roleid="{{$roledata->id}}" data-modelid="{{$value->module_id}}" @if(@$mdata[$value->module_id]['can_view'] == 1) checked @endif
                                        >
                                        @endif
                                    </td>
                                    <td class="text-center">
                                         @if($value->can_delete == 1)
                                        <input type="checkbox" class="form-check-input mt-0 pt-0" id="{{@$mdata[$value->module_id]['id'] }}"
                                            data-type="can_delete" data-roleid="{{$roledata->id}}" data-modelid="{{$value->module_id}}" @if(@$mdata[$value->module_id]['can_delete'] == 1) checked @endif>
                                         @endif
                                    </td>
                                </tr>
                            
                                @php $i++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
@endsection

@section('footer_scripts')


<script type="text/javascript">
$(".form-check-input").click(function() {

    var type = $(this).attr('data-type');
    var id = $(this).attr('id');
    var model_id = $(this).attr('data-modelid');
    var role_id = $(this).attr('data-roleid');
    var status = $(this).is(':checked') == true ? 1 : 0

    $.post('{{ url("admin/role_permission/status") }}', {
            _token: "{{ csrf_token() }}",
            id: id,
            type: type,
            model_id : model_id,
            role_id : role_id,
            status: status
        },
        function(data) {
            toastr.success('Permission Changed Successfully');
        });
});
</script>

@endsection()