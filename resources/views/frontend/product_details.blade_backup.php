@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}">
    <meta property="og:type" content="og:product">
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}">
    <meta property="og:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}">
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}">
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta property="product:price:currency" content="{{ \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code }}">
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endsection

@section('content')
          <!--breadcrumbs area start-->
    <div class="breadcrumbs_area" >
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
					<h2>{{ $detailedProduct->getTranslation('name') }}</h2>
                        <ul>
                            <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                            <li>></li>
                            @if(!isset($category_id))
                            <li><a href="{{ route('search') }}">{{ translate('All Categories')}}</a></li>
                            @else
                            <li><a href="{{ route('search') }}">{{ translate('All Categories')}}</a></li>
                            @endif
                            <li>></li>
                            @if(isset($detailedProduct->category_id))
                            
                            <li>
                                <a href="{{ route('products.category', \App\Models\Category::find($detailedProduct->category_id)->slug) }}">{{ \App\Models\Category::find($detailedProduct->category_id)->getTranslation('name') }}</a>
                            </li><li>></li>
                            @endif
                            <li>
                                {{ $detailedProduct->getTranslation('name') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>         
    </div>
    <!--breadcrumbs area end-->
    
     <!--product details start-->
    <div class="product_details product-detail-page">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 product_img_all_new" id="product_img_all">
                   <div class="product-details-tab">
                        @php
                            $photos = explode(',', $detailedProduct->photos);
                        @endphp
                        <div>
                            <div id="img-1" class="zoomWrapper single-zoom ">
                                <a href="#">
                                    <?php 
                                    $i=1; 
                                    foreach ($photos as $key => $photo){
                                          if($i==1){

                                                       
                                        ?>
                                    <img class="product-zoom-thumbs" id="zoom{{$i}}" src="{{ uploaded_asset($photo) }}" data-zoom-image="{{ uploaded_asset($photo) }}"  title="{{ uploaded_asset($photo) }}" alt="{{ uploaded_asset($photo) }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/waiting_big.gif') }}';">
                                    <?php

                                        } $i++;

                                    }
                                    ?>

                                </a>
                            </div>
                        </div>

                        <div class="single-zoom-thumb">
                            <ul class="s-tab-zoom owl-carousel single-product-active" id="gallery_01">
                                 @foreach ($photos as $key => $photo)
                                <li>
                                    <a href="#" class="elevatezoom-gallery" data-update="" data-image="{{ uploaded_asset($photo) }}" data-zoom-image="{{ uploaded_asset($photo) }}">
                                        <img 
                                        src="{{ uploaded_asset($photo) }}" alt="{{ uploaded_asset($photo) }}" 
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/waiting.gif') }}';"/>
                                    </a>

                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>                        
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="product_d_right">                           
                        <h1>{{ $detailedProduct->getTranslation('name') }}</h1>
                            <div class=" product_ratting">
                                <ul>
                                     @php
                                        $total = 0;
                                        $total += $detailedProduct->reviews->count();
                                    @endphp
                                    <li> {{ renderStarRating($detailedProduct->rating) }}</li>
                                    
                                    <li><a href="javascript:void(0)"> ({{ $total }} Reviews ) </a></li>
                                </ul>
                            </div>

                        <form id="option-choice-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $detailedProduct->id }}">
                                <div class="row no-gutters mt-4 gemstone-list-product">
                                
                                        <div class="col-sm-12">
                                        <?php                                         
                                        foreach ($subProduct as $key => $value){ 
                                            $active=($detailedProduct->slug==$value->slug)?'active':''; 

                                            $url=($detailedProduct->slug==$valu