@extends('frontend.layouts.app')

@section('content')
    {{-- ######################## Breadcrumb Start ############################# --}}
    @include('frontend.includes.profile_header')
    {{-- ######################## Breadcrumb End ############################# --}}



    {{-- ######################## Shipping and Cancelation Start ############################# --}}
    @if(!empty($shipandcencel_cms))
    <div class="container my-lg-5 my-3">
        <div class="row">
            {!! str_replace('{slug}',IMAGES, $shipandcencel_cms['cms_contant']) !!}
        </div>
    </div>
    @endif
    {{-- ######################## Shipping and Cancelation End ############################# --}}
@endsection
