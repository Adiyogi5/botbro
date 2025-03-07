@extends('frontend.layouts.app')

@section('content')
    <main>
        @php
            $routeurl = $slug;
        @endphp
        {{-- ===============Breadcrumb Start============= --}}
        @include('frontend.includes.profile_header')
        {{-- ===============Breadcrumb End============= --}}


        {{-- ===============Products Start============= --}}
        <section id="dashboard">
            <div class="container my-lg-5 my-md-4 my-3">
                <div class="row pt-lg-4 pt-3">
                    <div class="col-lg-3 col-md-4 col-12">
                        @if (!empty($CategoriesData) && count($CategoriesData) > 0)
                            <h3 class="sidebar-blog-title mt-5">CATEGORIES</h3>
                            <div class="section-block category_block">
                                <ul class="left-category treeview-list treeview">
                                    @foreach ($CategoriesData as $childCategory)
                                        <li class="expandable">
                                            @if (!empty($childCategory['children']))
                                                <div class="hitarea expandable-hitarea"></div>
                                            @endif
                                            <a
                                                href="{{ route('frontend.products', ['slug' => $childCategory['slug']]) }}">{{ $childCategory['name'] }}</a>
                                            @if (!empty($childCategory['children']))
                                                <ul class="menu-dropdown" style="display: none;">
                                                    @foreach ($childCategory['children'] as $child1Category)
                                                        <li class="expandable">
                                                            @if (!empty($child1Category['children']))
                                                                <div class="hitarea expandable-hitarea"></div>
                                                            @endif
                                                            <a
                                                                href="{{ route('frontend.products', ['slug' => $child1Category['slug']]) }}">{{ $child1Category['name'] }}</a>
                                                            @if (!empty($child1Category['children']))
                                                                <ul class="menu-dropdown" style="display: none;">
                                                                    @foreach ($child1Category['children'] as $child2Category)
                                                                        <li>
                                                                            <a
                                                                                href="{{ route('frontend.products', ['slug' => $child2Category['slug']]) }}">{{ $child2Category['name'] }}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-9 col-md-8 col-12">
                        <div class="row mb-3">
                            <form method="GET" action="{{ route('frontend.products') }}"
                                class="form-inline form-sort d-flex text-end justify-content-end" id="filterForm">
                                <div class="d-flex">
                                    <label for="sort" class="form-control-text pe-2 w-100 align-self-center">Sort
                                        By:</label>
                                    <select name="sort" id="sort" class="form-control form-control-text">
                                        <option value="">Price</option>
                                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>
                                            Price Low to High
                                        </option>
                                        <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>
                                            Price High to Low
                                        </option>
                                    </select>
                                </div>
                            </form>
                        </div>

                        <div class="row" id="dynamicContent">
                            @if (!empty($productsData) && count($productsData) > 0)
                                @foreach ($productsData as $products)
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 card-left mb-lg-5 mb-3">
                                        <div class="card h-100">
                                            <img class="card-img-top img-fluid img-responsive"
                                                src="{{ imageexist($products->image) }}" alt="Card image cap ratio-1x1">
                                            <div class="card-body">
                                                <h5 class="product-title ">{{ $products->name }}</h5>
                                                <p class="product-price">Price: ${{ $products->price }}</p>
                                                <div class="text-center justify-content-center">
                                                    <a href="{{ route('frontend.productdetails', ['slug' => $products->slug]) }}"
                                                        class="btn btn-md btn-warning text-white btn-upaycard">
                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Display custom pagination links --}}
                                <div class="pagination text-center justify-content-center pt-lg-5 pt-3">
                                    {{-- Previous Page Link --}}
                                    @if ($productsData->onFirstPage())
                                        <span class="blog-page-link"><i class="fa-solid fa-arrow-left"></i></span>
                                    @else
                                        <a class="blog-page-link" href="{{ $productsData->previousPageUrl() }}"
                                            rel="prev"><i class="fa-solid fa-arrow-left"></i></a>
                                    @endif

                                    {{-- Page Number Links --}}
                                    @for ($i = 1; $i <= $productsData->lastPage(); $i++)
                                        <a href="{{ $productsData->url($i) }}"
                                            class="{{ $productsData->currentPage() == $i ? 'active-page' : 'blog-page-link' }}">{{ $i }}</a>
                                    @endfor

                                    @if ($productsData->hasMorePages())
                                        <a class="blog-page-link" href="{{ $productsData->nextPageUrl() }}"
                                            rel="next"><i class="fa-solid fa-arrow-right"></i></a>
                                    @else
                                        <span class="blog-page-link"><i class="fa-solid fa-arrow-right"></i></span>
                                    @endif
                                </div>
                            @else
                                <div class="text-center justify-content-center py-lg-5 py-3">
                                    <h2 class="mx-auto">No Product Found!!</h2>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- ===============Products End============= --}}

    </main>
@endsection

@section('js')
    <script src="{{ ASSETS }}js/sweetalert2.min.js"></script>
    <script src="{{ ASSETS }}js/jstree.min.js"></script>
    <script>
        /* JS is for left category tree view  */
        function categorytreeview() {
            if ($('.left-category').hasClass('treeview') == true) {
                $(".treeview-list").treeview({
                    animated: "slow",
                    collapsed: true,
                    unique: true
                });
                $('.left-category.treeview-list a.active').parent().removeClass('expandable');
                $('.left-category.treeview-list a.active').parent().addClass('collapsable');
                $('.left-category.treeview-list .collapsable > ul.collapsable').css('display', 'block');
            }
        }

        $(document).ready(function() {
            categorytreeview();
            // Listen for changes on the category and sort dropdowns
            $('#filterForm select').on('change', function() {
                // Trigger form submission when a dropdown changes
                $('#filterForm').submit();
            });

        });
    </script>
@endsection
