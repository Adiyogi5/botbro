@extends('frontend.layouts.app')

@section('content')
    {{-- ######################## Breadcrumb Start ############################# --}}
    @include('frontend.includes.profile_header')
    {{-- ######################## Breadcrumb End ############################# --}}



    {{-- ######################## Faqs Start ############################# --}}
    <section>
        @if (!empty($faqs))
            <div class="container my-3 my-lg-5">
                <div class="ag-faq_list">

                    @foreach ($faqs as $faq)
                        <div class="ag-faq_item">
                            <div class="js-ag-faq_title">
                                {{ $faq['question'] }}
                            </div>

                            <div class="js-ag-faq_text">
                                {!! $faq['answer'] !!}
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        @endif
    </section>
    {{-- ######################## Faqs End ############################# --}}
@endsection



@section('js')
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
    <script>
      (function ($) {
          $(function () {
              // Set initial content of titles with the "+" sign
              $('.js-ag-faq_title').html('<span class="plus">&#43;</span> ' + $('.js-ag-faq_title').html());
  
              $('.js-ag-faq_title').on('click', function () {
                  // Toggle the visibility of the FAQ text
                  $(this).next('.js-ag-faq_text').slideToggle();
  
                  // Toggle the "+" and "-" signs
                  $(this).toggleClass('active');
  
                  // Update the content of the title based on its current state
                  $('.js-ag-faq_title').each(function () {
                      var icon = $(this).hasClass('active') ? '<span class="plus">&#8211;</span>' : '<span class="plus">&#43;</span>';
                      $(this).html(icon + ' ' + $(this).text().substring(2));
                  });
  
                  // Hide the FAQ text of other elements
                  $('.js-ag-faq_title').not(this).removeClass('active').next('.js-ag-faq_text').slideUp();
              });
  
              // Initially hide all js-ag-faq_text elements
              $('.js-ag-faq_text').hide();
          });
      })(jQuery);
  </script>  
@endsection
