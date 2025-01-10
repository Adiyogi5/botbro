@extends('frontend.layouts.app')

@section('content')
    <main>
        {{-- ===============Breadcrumb Start============= --}}
        @include('frontend.includes.profile_header')
        {{-- ===============Breadcrumb End============= --}}


        {{-- ===============Reffer Histrory Start============= --}}
        <section id="dashboard">
            <div class="container my-lg-5 my-md-4 my-3">
                <div class="row pt-lg-4 pt-3">
                    <div class="col-lg-3 col-md-4 col-12">

                        @include('frontend.includes.sidebar_inner')

                    </div>
                    <div class="col-lg-9 col-md-8 col-12 mt-md-0 mt-3">
                        <div class="row row-cols-1 g-3">
                            <div class="col-12">
                                <p class="dash-category">Referral History</p>
                            </div>
                            <div class="col-12 overflow-scroll">
                                <table class="table table-bordered">
                                    <thead class="text-center justify-content-center">
                                        <tr>
                                            <th scope="col">S.no</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Mobile Number</th>
                                            <th scope="col">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center justify-content-center">
                                        @if ($my_reffer->isEmpty())
                                            <tr class="text-center">
                                                <td colspan="4" class="text-danger"> Referral History Not Found </td>
                                            </tr>
                                        @else
                                            @foreach ($my_reffer as $key => $reffer)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        {{ $reffer['name'] }}
                                                    </td>
                                                    <td>
                                                        {{ $reffer['mobile'] }}
                                                    </td>
                                                    <td>
                                                        {{ date('d-m-Y', strtotime($reffer['created_at'])) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- ===============Reffer Histrory End============= --}}


    </main>
@endsection

@section('js')
    <script src="{{ ASSETS }}js/sweetalert2.min.js"></script>
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
@endsection
