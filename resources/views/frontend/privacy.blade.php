@extends('frontend.layouts.app')

@section('content')
    {{-- ######################## Breadcrumb Start ############################# --}}
    @include('frontend.includes.profile_header')
    {{-- ######################## Breadcrumb End ############################# --}}



    {{-- ######################## Privacy Policy Start ############################# --}}
    @if(!empty($policy_cms))
    <div class="container my-lg-5 my-3">
        <div class="row">
            {!! str_replace('{slug}',IMAGES, $policy_cms['cms_contant']) !!}
        </div>
    </div>
    @endif
    {{-- ######################## Privacy Policy End ############################# --}}
@endsection
