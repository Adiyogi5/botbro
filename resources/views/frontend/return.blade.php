@extends('frontend.layouts.app')

@section('content')
    {{-- ######################## Breadcrumb Start ############################# --}}
    @include('frontend.includes.profile_header')
    {{-- ######################## Breadcrumb End ############################# --}}



    {{-- ######################## Retun Policy Start ############################# --}}
    @if(!empty($return_cms))
    <div class="container my-lg-5 my-3">
        <div class="row">
            {!! str_replace('{slug}',IMAGES, $return_cms['cms_contant']) !!}
        </div>
    </div>
    @endif
    {{-- ######################## Return Policy End ############################# --}}
@endsection
