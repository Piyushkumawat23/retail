@extends('frontend.layouts.app')

@section('meta_title'){{ get_setting('website_name').' | '. $page->meta_title }}@stop

@section('meta_description')<?php echo $page->meta_description;?>@stop

@section('meta_keywords'){{ $page->tags }}@stop

@section('meta')
    <link rel="canonical" href="https://bautlr.com/return-policy">
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $page->meta_title }}">
    <meta itemprop="description" content="{{ $page->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($page->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="website">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $page->meta_title }}">
    <meta name="twitter:description" content="{{ $page->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($page->meta_img) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $page->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ URL($page->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($page->meta_img) }}" />
    <meta property="og:description" content="{{ $page->meta_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
@endsection

@section('content')
<div class="breadcrumbs_area">
   <div class="container">
      <div class="row">
         <div class="col-12">
            <div class="breadcrumb_content">
               <h2>
                 {{ $page->getTranslation('title') }}
               </h2>
               <ul>
                  <li> <a class="text-reset" href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                  <li>&gt;</li>
                  <li><a class="text-reset" href="{{ route('returnpolicy') }}">{{ translate('Return Policy') }}</a></li>
                  <li>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>

<section class="mb-4">
    <div class="container">
        <div class="row">
			 <div class="col-12 text-center">
					<h1 class="page_heading">{{ $page->getTranslation('title') }}</h1>
			 </div>
		</div>
        <div class="p-4 bg-white rounded shadow-sm overflow-hidden mw-100 text-left page_content">
            @php
                
                echo str_replace('white-space: pre;','',$page->getTranslation('content'));
            @endphp
        </div>
    </div>
</section>
@endsection
