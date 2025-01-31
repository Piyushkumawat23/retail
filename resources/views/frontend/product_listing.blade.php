@extends('frontend.layouts.app')

@if (isset($category_id))
    @php
        $meta_title = \App\Models\Category::find($category_id)->meta_title;
        $meta_description = \App\Models\Category::find($category_id)->meta_description;
        $canonical_tag = \App\Models\Category::find($category_id)->canonical_tag;
    @endphp
@elseif (isset($brand_id))
    @php
        $meta_title = \App\Models\Brand::find($brand_id)->meta_title;
        $meta_description = \App\Models\Brand::find($brand_id)->meta_description;
        $canonical_tag = \App\Models\Brand::find($brand_id)->canonical_tag;
    @endphp
@else
    @php
        $meta_title         = get_setting('meta_title');
        $meta_description   = get_setting('meta_description');
        $canonical_tag = 'https://www.bautlr.com/search';
    @endphp
@endif

@section('meta_title'){{ $meta_title.' | '.get_setting('website_name') }}@stop
@section('meta_description'){{ $meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $meta_title }}">
    <meta itemprop="description" content="{{ $meta_description }}">
    <link rel="canonical" href="{{ $canonical_tag }}">
    <!-- Twitter Card data -->
    <meta name="twitter:title" content="{{ $meta_title }}">
    <meta name="twitter:description" content="{{ $meta_description }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $meta_title }}">
    <meta property="og:description" content="{{ $meta_description }}">
    
@endsection

@section('content')
@if(!empty($products) && count($products) === 0)
    <meta http-equiv="refresh" content="0;url={{ route('home') }}">
@endif
<?php $device_type = detectUserAgentDevice();   ?>

       <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <h1>
                            @if(isset($category_id))
                                @php
                                    $category = \App\Models\Category::find($category_id);
                                @endphp
                                {{ $h1_tag ? $h1_tag : $category->getTranslation('name') }}
                            @elseif(isset($query))
                                {{ translate('Search result for ') }}"{{ $query }}"
                            @elseif (isset($brand_id))
                                @php
                                    $brand = \App\Models\Brand::find($brand_id);
                                @endphp
                                {{ $h1_tag ? $h1_tag : $brand->getTranslation('name') }}
                            @else
                                {{ translate('All Products') }}
                            @endif
                        </h1>
                        <ul>
                             <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                            <li>></li>
                            @if(!isset($category_id))
                            <li><a href="{{ route('search') }}">{{ translate('All Categories')}}</a></li>
                            @else
                            <li><a href="{{ route('search') }}">{{ translate('All Categories')}}</a></li>
                            @endif
                            <li>></li>
                            @if(isset($category_id))
                            <li>
                                <!-- <a href="{{ route('products.category', \App\Models\Category::find($category_id)->slug) }}"> -->{{ \App\Models\Category::find($category_id)->getTranslation('name') }}<!-- </a> -->
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


    
    <section class="mb-6 product_listing_page">
        <div class="container sm-px-0">
            <form class="" id="search-form" action="" method="GET">
                <ul><li id="filter_sidebar">@include('frontend.partials.filter_sidebar')</li></ul>
                <div class="row">
                    <!-- Sidebar -->
                    <div class="col-xl-12">
                          <!--shop  area start-->
                        <div class="shop_area">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg col-10">
                                        <input type="hidden" name="keyword" value="{{ $query }}">
                                    </div>
                                    <div class="col-12">
                                        <!--shop wrapper start-->
                                        <!--shop toolbar start-->
                                    
                                    <div class="shop_toolbar">
                                        <div class="orderby_wrapper">
                                            @if (Route::currentRouteName() != 'products.gemstone')
                                            <!--<h3>{{ translate('Gemstones')}} : </h3>-->
                                                <div class=" ">
                                                    <div class="order_by col-6 col-lg-auto mb-3 w-lg-200px">                                     
                                                        <!-- <label class="mb-0 opacity-50">{{ translate('Gemstones')}}</label> -->
                                                        <select class="form-control form-control-sm aiz-selectpicker listing-select" data-live-search="true" name="brand" onchange="filter()">
                                                            <option value="">{{ translate('All Gemstones')}}</option>
                                                            @foreach (\App\Models\Brand::where('active',1)->orderBy('name', 'asc')->get() as $brand)
                                                            <?php
                                                            if((isset($category_id))){
                                                                $brand_product = \App\Models\Product::where('brand_id',$brand->id)->where('published',1)->where('category_id',$category_id)->count();
                                                                if(!empty($brand_product) && $brand_product > 0){
                                                            ?>
                                                                <option value="{{ $brand->slug }}" @isset($brand_id) @if ($brand_id == $brand->id) selected @endif @endisset>{{ $brand->getTranslation('name') }}</option>
                                                                <?php }
                                                                } ?>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="orderby_wrapper ">
                                            <div class=" ">
                                                <div class="order_by col-6 col-lg-auto mb-3 w-lg-200px">
                                                    <!-- <label class="mb-0 opacity-50">{{ translate('Sort by')}}</label>  -->
                                                    <select class="form-control form-control-sm aiz-selectpicker listing-select" name="sort_by" onchange="filter()">
                                                        <option value="">{{ translate('Sort by')}}</option>
                                                        <option value="newest" @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>{{ translate('Newest')}}</option>
                                                        <option value="oldest" @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>{{ translate('Oldest')}}</option>
                                                        <option value="price-asc" @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset>{{ translate('Price low to high')}}</option>
                                                        <option value="price-desc" @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset>{{ translate('Price high to low')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Add filter button here -->
                                        <div class="filter_link">
                                            <a href="javascript:void(0)" class="filter_line"><i class="ion-android-options"></i><i class="fa fa-angle-down"></i> {{ translate('Filters') }}</a>
                                        </div>
                                            <!-- Conditionally display the Reset button -->
                                            @if(request('keyword') || request('brand') || request('sort_by') || request('min_price') || request('max_price') || request('category_id') || !empty(request('selected_attribute_values')) || !empty(request('brand_values')))
                                            <div class="reset_button">
                                                <a href="{{ route('products.category', \App\Models\Category::find($category_id)->slug) }}" >{{ translate('Reset Filters') }}</a>
                                            </div>
                                            @endif
                                    </div>
                            <!--shop toolbar end-->
                                <div class="tab-content">
                                    <div class="tab-pane grid_view fade show active" id="large" role="tabpanel">
                                        <div class="row gutters-5 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-4 row-cols-md-3 row-cols-2">
                                            <?php 
                                                if(!empty($products) && count($products) > 0){ 
                                            ?>
                                        @foreach ($products as $key => $product)
                                           <div class="product_list col-lg-3 col-md-3 col-sm-6">
                                                @include('frontend.partials.product_box_1',['product' => $product,'page_type'=>'listing','device_type'=>$device_type])
                                            </div>
                                        @endforeach
                                    <?php 
                                        }else{  
                                    ?>
                                    <img
                                        class="img-fit lazyload  mx-auto custom-mt"
                                        src="{{ static_asset('assets/img/no-products-found.png') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/no-products-found.png') }}';">
                                        <?php 
                                            } 
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            <!--shop toolbar start-->
                                    <div class=" col-12 aiz-pagination aiz-pagination-center mt-4 shop_toolbar t_bottom">
                                        <div class="pagination">
                                            {{ $products->appends(request()->input())->links() }}
                                        </div>
                                    </div>
                                        <!--shop toolbar end-->
                                        <!--shop wrapper end-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--shop  area end-->
                        <input type="hidden" name="min_price" value="">
                        <input type="hidden" name="max_price" value="">

                    </div>
                </div>
            </form>
        </div>
    </section>


        <!-- Add SEO Content before the footer -->
<?php 
    

    if(isset($faqs)) {
        // Check if brand_seo_content is not empty first
        if(!empty($brand_seo_content)) {
    ?>
        <div class="seo-content">
            {!! $brand_seo_content !!}
        </div>
    <?php 
        } elseif(!empty($seo_content)) { // Else, check if seo_content is available
    ?>
        <div class="seo-content">
            {!! $seo_content !!}
            <div>
            <!--faq area start-->
            <div class="faq ">  
                <div class="row">
                    <div class="col-12">
                        <div class="faq_content_wrapper">
                            <span style="font-size: 30px !important;">FAQs</span>
                        </div>
                    </div>
                </div>    
            </div>            
            <!--Accordion area-->
            <div class="faq_listing_area">
                    <div class="row">
                        <div class="col-12"> 
                            <div id="accordion" class="card__accordion">
                                @foreach($faqs as $index => $faq)
                                    <div class="card card_dipult">
                                        <div class="faq-header faq-list-header card-header card_accor" id="heading{{ $index }}">
                                            <button class="btn btn-link {{ $index === 0 ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                                {{ $faq->question }}
                                                <i class="fa fa-plus"></i>
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <div id="collapse{{ $index }}" class="collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-parent="#accordion">
                                            <div class="card-body">
                                                <p class="faq_answer"><?php echo $faq->answer; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
            </div>
            <!--Accordion area end-->
            <!--faq area end-->
          </div>
        </div>
    <?php 
        }
    }
?>

@endsection

@section('script')
    <script type="text/javascript">
        function filter(){
            //alert('hello');
            $('#search-form').submit();
        }
        function rangefilter(arg){
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }

   function resetFilters() {
        // Clear all filter values
        $('input[name=keyword]').val('');
        $('select[name=brand]').val('');
        $('select[name=sort_by]').val('');
        $('input[name=min_price]').val('');
        $('input[name=max_price]').val('');
        document.querySelectorAll('input[type=checkbox]').forEach(function(checkbox) {
            checkbox.checked = false;
        });
        // Submit the form to update the URL
        filter();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const filterLink = document.querySelector('.filter_link a');
        const filterSidebar = document.querySelector('.filter_sidebar');
        const filterClose = document.querySelector('.filter_sidebar_close a');
        const body = document.querySelector('body');
        const resetButton = document.querySelector('.reset_button a');

        filterLink.addEventListener('click', function () {
            filterSidebar.classList.toggle('open');
        });

        filterClose.addEventListener('click', function () {
            filterSidebar.classList.remove('open');
        });

        body.addEventListener('click', function (event) {
            if (!filterSidebar.contains(event.target) && !filterLink.contains(event.target)) {
                filterSidebar.classList.remove('open');
            }
        });

        if (resetButton) {
            resetButton.addEventListener('click', function (e) {
                e.preventDefault();
                resetFilters();
            });
        }
    });


    </script>
@endsection
