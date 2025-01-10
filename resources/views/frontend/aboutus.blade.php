@extends('frontend.layouts.app')

@section('content')
    {{-- ######################## Breadcrumb Start ############################# --}}
    @include('frontend.includes.profile_header')
    {{-- ######################## Breadcrumb End ############################# --}}



    {{-- ######################## About Us Start ############################# --}}
    @if(!empty($aboutus_cms))
    <section>
        <div class="container my-3 my-lg-5">
            
            {!! str_replace('{slug}',IMAGES, $aboutus_cms['cms_contant']) !!}
            
        </div>
    </section>
    @endif
    {{-- ######################## About Us End ############################# --}}
@endsection



@section('js')
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
@endsection
