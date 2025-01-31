@extends('frontend.layouts.app')

@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <h1>
                          Frequently Questions
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs area end-->
  <div class="footer_position">
    <!--faq area start-->
    <div class="faq_content_area faq ">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="faq_content_wrapper">
                        <h1>Below are frequently asked questions, you may find the answer for yourself</h1>
                    </div>
                </div>
            </div> 
        </div>    
    </div>
    
    <!--Accordion area-->
    <div class="accordion_area">
        <div class="container">
            <div class="row">
                <div class="col-12"> 
                    <div id="accordion" class="card__accordion">
                        @foreach($faqs as $index => $faq)
                            <div class="card card_dipult">
                                <div class="faq-header card-header card_accor" id="heading{{ $index }}">
                                    <button class="btn btn-link {{ $index === 0 ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                        {{ $faq->question }}
                                        <i class="fa fa-plus"></i>
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <div id="collapse{{ $index }}" class="collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-parent="#accordion">
                                    <div class="card-body">
                                        <p class="faq_answer">{{ $faq->answer }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Accordion area end-->
    <!--faq area end-->
  </div>
@endsection
