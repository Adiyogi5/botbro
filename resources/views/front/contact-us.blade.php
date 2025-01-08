@extends('layouts.front')

@section('content')
    @include('partial.frontend.breadcrumb')

    <!-- Full Screen Search Start -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content" style="background: rgba(20, 24, 62, 0.7);">
                <div class="modal-header border-0">
                    <button type="button" class="btn btn-square bg-white btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center">
                    <div class="input-group" style="max-width: 600px;">
                        <input type="text" class="form-control bg-transparent border-light p-3"
                            placeholder="Type search keyword">
                        <button class="btn btn-light px-4"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Full Screen Search End -->


    <!-- Contact Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="mx-auto text-center wow fadeIn" data-wow-delay="0.1s" style="max-width: 500px;">
                <div class="btn btn-sm border rounded-pill text-primary px-3 mb-3">Contact Us</div>
                <h1 class="mb-4">If You Have Any Query, Please Contact Us</h1>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <p class="text-center mb-4">The contact form is currently inactive. Get a functional and working contact
                        form with Ajax & PHP in a few minutes. Just copy and paste the files, add a little code and you're
                        done. <a href="#">Download Now</a>.</p>
                    <div class="wow fadeIn" data-wow-delay="0.3s">
                        <form action="{{ request()->url() }}" method="post" id="contactUs">
                            <div class="row g-3">
                                @error('name')
                                    <p class="small mb-0 text-danger">{{ $message }}</p>
                                @enderror
                                @csrf
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Your Name" value="{{ old('name') }}">
                                        <label for="name">Your Name</label>
                                    </div>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Your Email" value="{{ old('email') }}">
                                        <label for="email">Your Email</label>
                                    </div>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="subject" name="subject"
                                            placeholder="Subject" value="{{ old('subject') }}">
                                        <label for="subject">Subject</label>
                                    </div>
                                    @error('subject')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a message here" id="message" name="message" rows="3">{{ old('message') }}</textarea>
                                        <label for="message">Message</label>
                                    </div>
                                    @error('message')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->


    <!-- Newsletter Start -->
    <div class="container-fluid bg-primary newsletter py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-md-5 ps-lg-0 pt-5 pt-md-0 text-start wow fadeIn" data-wow-delay="0.3s">
                    <img class="img-fluid" src="{{ asset('assets/img/newsletter.png') }}" alt="">
                </div>
                <div class="col-md-7 py-5 newsletter-text wow fadeIn" data-wow-delay="0.5s">
                    <div class="btn btn-sm border rounded-pill text-white px-3 mb-3">Newsletter</div>
                    <h1 class="text-white mb-4">Let's subscribe the newsletter</h1>
                    <div class="position-relative w-100 mt-3 mb-2">
                        <input class="form-control border-0 rounded-pill w-100 ps-4 pe-5" type="text"
                            placeholder="Enter Your Email" style="height: 48px;">
                        <button type="button" class="btn shadow-none position-absolute top-0 end-0 mt-1 me-2"><i
                                class="fa fa-paper-plane text-primary fs-4"></i></button>
                    </div>
                    <small class="text-white-50">Diam sed sed dolor stet amet eirmod</small>
                </div>
            </div>
        </div>
    </div>
    <!-- Newsletter End -->

@endsection

@section('js')
    <script>
        $(function() {
            $("#contactUs").validate({
                errorClass: "text-danger fs--1",
                errorElement: "small",
                rules: {
                    name: {
                        required: true,
                        minlength: 5,
                        maxlength: 100
                    },
                    email: {
                        required: true,
                        email: true,
                        customEmail: true,
                        minlength: 2,
                        maxlength: 100
                    },
                    subject: {
                        required: true,
                        minlength: 5,
                        maxlength: 100
                    },
                    message: {
                        required: true,
                        minlength: 10,
                        maxlength: 1000
                    },
                },
                messages: {
                    name: {
                        required: "Please enter name",
                    },
                    email: {
                        required: "Please enter Email",
                    },
                    subject: {
                        required: "Please enter Subject",
                    },
                    message: {
                        required: "Please enter message.",
                    },
                },
            });
        })
    </script>
@endsection
