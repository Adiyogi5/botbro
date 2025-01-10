@extends('frontend.layouts.app')

@section('content')
    <main>
        {{-- ===============Breadcrumb Start============= --}}
        @include('frontend.includes.profile_header')
        {{-- ===============Breadcrumb End============= --}}


        {{-- ===============Dashboard Start============= --}}
        <section id="dashboard">
            <div class="container my-lg-5 my-md-4 my-3">
                <div class="row pt-lg-4 pt-3">
                    <div class="col-lg-3 col-md-12 col-12">

                        @include('frontend.includes.sidebar_inner')

                    </div>
                    <div class="col-lg-9 col-md-12 col-12">
                        <div class="row row-cols-1 g-3">
                            <div class="col-12 ms-lg-2 ms-md-0 ms-0">
                                <p class="dash-category">Recent</p>
                            </div>
                            @if ($notifications->isEmpty())
                                <div class="col-12">
                                    <h4 class="fw-500 mt-lg-5 mt-3 text-center justify-content-center">No Notification
                                        Found.</h4>
                                </div>
                            @else
                                @foreach ($notifications as $notification)
                                    <div class="col-md-6 col-12 my-auto">
                                        <div class="card h-100 mb-3 mx-lg-2 mx-md-0 mx-2 card-noti ">
                                            <div class="row g-0 padd-small">
                                                <div class="col-md-3 col-3 mx-auto my-auto text-end justify-content-end">
                                                    <img src="{{ imageexist($notification['attachment']) }}" alt=""
                                                        class="img-fluid orderview-img"
                                                        style="border-radius: 50% !important" />
                                                </div>
                                                <div class="col-md-9 col-9 my-auto">
                                                    <div class="card-body card-ship-padd">
                                                        <h6>{{ $notification['title'] }}</h6>
                                                        <p class="noti-para">
                                                            {!! $notification['message'] !!}
                                                        </p>
                                                        <p class="noti-time">
                                                            {{ \Carbon\Carbon::createFromTimestamp(strtotime($notification['created_at']))->format('d-m-Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <!-- Your existing code to display List data -->
                                @isset($notifications[0])
                                    <div class="col-12">
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination justify-content-center">
                                                {{ $notifications->links() }}
                                            </ul>
                                        </nav>
                                    </div>
                                @endisset
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- ===============Dashboard End============= --}}
    </main>
@endsection
