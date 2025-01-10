@extends('frontend.layouts.app')

@section('content')
    {{-- ######################## Breadcrumb Start ############################# --}}
    @include('frontend.includes.profile_header')
    {{-- ######################## Breadcrumb End ############################# --}}



    {{-- ######################## Why Upay Start ############################# --}}
    @if(!empty($whyupay_cms))
    <section>
        <div class="container my-3 my-lg-5">
            
            {!! str_replace('{slug}',IMAGES, $whyupay_cms['cms_contant']) !!}
            
        </div>
    </section>
    @endif
    {{-- ######################## Why Upay End ############################# --}}
@endsection



@section('js')
<script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
@endsection
