@extends('frontend.layouts.app')

@section('content')
    {{-- ######################## Breadcrumb Start ############################# --}}
    @include('frontend.includes.profile_header')
    {{-- ######################## Breadcrumb End ############################# --}}



    {{-- ######################## Our Leadership Start ############################# --}}
    @if(!empty($ourleadership_cms))
    <section>
        <div class="container my-3 my-lg-5">
            
            {!! str_replace('{slug}',IMAGES, $ourleadership_cms['cms_contant']) !!}
            
        </div>
    </section>
    @endif
    {{-- ######################## Our Leadership End ############################# --}}
@endsection



@section('js')
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
@endsection
