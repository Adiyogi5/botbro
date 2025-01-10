@extends('admin.layouts.error')

@section('content')
<!-- Main content -->
<section class="content"> 
    <div class="error-page">
      <div class="error-content"> <h3> {!! @$title !!} </h3></div>
      <div class="error-justifed"> <p> {!! $message !!} </p></div>
    </div> 
</section>
@endsection
