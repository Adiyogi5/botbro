@extends('frontend.layouts.app')

@section('content')
    @php
        $routeurl = $slug;
    @endphp

    {{-- ######################## Breadcrumb Start ############################# --}}
    @include('frontend.includes.profile_header') 
    
    <section>
        <div class="container my-3 my-lg-5">
            <div class="row">
                <div class="col-md-8 order-md-1 order-2">
                    <div class="row g-3">
                        @if (!empty($allblog) && count($allblog) > 0)
                            @foreach ($allblog as $key => $value)
                                <div class="col-md-6 col-12">
                                    <div class="card mb-3 h-100">
                                        <img src="{{ imageexist($value['image']) }}" class="blog-img-lg card-img-top"
                                            alt="...">
                                        <div class="card-body">
                                            <h5 class="blog-title card-title">{{ $value->title }}</h5>
                                            <p class="blog-content card-text">{{ $value->sort_description }}</p>
                                            <div class="row d-flex">
                                                <div class="col-lg-6 col-md-12 col-6 text-lg-start justify-content-lg-start text-md-center justify-content-md-center text-start justify-content-start">
                                                    <p class="blog-text card-text">Date : <small class="text-muted">
                                                        {{ date('d-m-Y', strtotime($value->created_at)) }}</small>
                                                    </p>
                                                    <p class="blog-text card-text">Posted By : <small class="text-muted">
                                                            {{ $value->post_by }}</small>
                                                    </p>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-6 m-auto text-lg-end justify-content-lg-end text-md-center justify-content-md-center text-end justify-content-end">
                                                    <a href="{{ url('blog-details/' . $value['slug']) }}"
                                                        class="btn btn-order-ship">
                                                        Read More
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            {{-- Display custom pagination links --}}
                            <div class="pagination text-center justify-content-center pt-lg-5 pt-3">
                                {{-- Previous Page Link --}}
                                @if ($allblog->onFirstPage())
                                    <span class="blog-page-link"><i class="fa-solid fa-arrow-left"></i></span>
                                @else
                                    <a class="blog-page-link" href="{{ $allblog->previousPageUrl() }}" rel="prev"><i
                                            class="fa-solid fa-arrow-left"></i></a>
                                @endif

                                {{-- Page Number Links --}}
                                @for ($i = 1; $i <= $allblog->lastPage(); $i++)
                                    <a href="{{ $allblog->url($i) }}"
                                        class="{{ $allblog->currentPage() == $i ? 'active-page' : 'blog-page-link' }}">{{ $i }}</a>
                                @endfor

                                @if ($allblog->hasMorePages())
                                    <a class="blog-page-link" href="{{ $allblog->nextPageUrl() }}" rel="next"><i
                                            class="fa-solid fa-arrow-right"></i></a>
                                @else
                                    <span class="blog-page-link"><i class="fa-solid fa-arrow-right"></i></span>
                                @endif
                            </div>
                            @else
                                <div class="text-center justify-content-center py-lg-5 py-3">
                                    <h2 class="mx-auto">No Blog Found in this Category</h2>
                                </div>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-4 order-md-2 order-1">
                    <div class="card mb-0">
                    @if (!empty($allblogcat) && count($allblogcat) > 0)
                            <div class="card-body">
                                <h5 class="blog-cat-title">Categories</h5>
                                <ul class="list-unstyled ps-3 mb-0">
                                    <?php if($routeurl == NULL) {?>
                                    <li class="blog-cat-list">
                                        <a href="{{ BASE_PATH }}blog" class="text-decoration-none blog-active">
                                            All Category</a>
                                    </li>
                                    <?php } else {?>
                                    <li class="blog-cat-list">
                                        <a href="{{ BASE_PATH }}blog" class="text-decoration-none"> All
                                            Category </a>
                                    </li>
                                    <?php } ?>
                                    @foreach ($allblogcat as $category)
                                        <li class="blog-cat-list">
                                            @php
                                                $catname = $category->name;
                                            @endphp
                                            <a class="text-decoration-none <?php if($routeurl==$category->slug) { ?>  blog-active <?php } ?>"
                                                href="{{ route('frontend.blog', ['slug' => $category->slug]) }}">
                                                {{ $category->name }} </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                    @endif

                    @if (!empty($allsidebarblog))
                            <div class="card-body d-none d-md-block">
                                <h5 class="blog-cat-title">Latest Blogs</h5>
                                @foreach ($allsidebarblog as $key => $value)
                                    <div class="card mb-3">
                                        <div class="row g-0">
                                            <div class="col-xl-4 col-lg-5 col-12 m-auto">
                                                <img src="{{ imageexist($value['image']) }}"
                                                    class="blog-img-lg card-img-top" alt="...">
                                            </div>
                                            <div class="col-xl-8 col-lg-7 col-12">
                                                <div class="card-body p-2">
                                                    <div class="row">
                                                        <h5 class="blog-title-md card-title">{{ $value->title }}</h5>
                                                    </div>
                                                    <div class="d-xl-flex d-lg-grid justify-content-between">
                                                        <p class="blog-text-md card-text">Date : <small class="text-muted">
                                                            {{ date('d-m-Y', strtotime($value->created_at)) }}</small>
                                                        </p>
                                                        <p class="blog-text-md card-text">Posted By : <small
                                                                class="text-muted">
                                                                {{ $value->post_by }}</small>
                                                        </p>
                                                    </div>
                                                    <a href="{{ url('blog-details/' . $value['slug']) }}"
                                                        class="nav-link text-project">
                                                        Read More
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
    </section>
    {{-- ######################## Blog End ############################# --}}
@endsection



@section('js')
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
@endsection
