@extends('frontend.layouts.app')

@section('content')
    {{-- ######################## Breadcrumb Start ############################# --}}
    @include('frontend.includes.profile_header')
    {{-- ######################## Breadcrumb End ############################# --}}



    {{-- ######################## Terms of Use Start ############################# --}}
    @if(!empty($terms_cms))
    <div class="container my-lg-5 my-3">
        <div class="row">
            {!! str_replace('{slug}',IMAGES, $terms_cms['cms_contant']) !!}
        </div>
    </div>
    @endif
    {{-- ######################## Terms of Use End ############################# --}}
@endsection
