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

@section('meta_title'){{ get_setting('website_name').' | '.$meta_title }}@stop
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
<!--     <div class="container">
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
    </div> -->
    <!--breadcrumbs area end-->
    <section class="mb-6 product_listing_page">
        <div class="container sm-px-0">
            <form class="" id="search-form" action="" method="GET">
                <div class="row">
                    <!-- Sidebar -->
                    <div class="col-xl-12">
                          <!--shop  area start-->
                        <div class="shop_area">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg col-10">
                                     <!--   <h1 class="h6 fw-600 text-body">
                                            @if(isset($category_id))
                                                {{ \App\Models\Category::find($category_id)->getTranslation('name') }}
                                            @elseif(isset($query))
                                                {{ translate('Search result for ') }}"{{ $query }}"
                                            @else
                                                {{ translate('All Products') }}
                                            @endif
                                        </h1> -->
                                        <input type="hidden" name="keyword" value="{{ $query }}">
                                    </div>
                                    <!-- <div class="col-2 col-lg-auto d-xl-none mb-lg-3 text-right">
                                        <button type="button" class="btn btn-icon p-0" data-toggle="class-toggle" data-target=".aiz-filter-sidebar">
                                            <i class="la la-filter la-2x"></i>
                                        </button>
                                    </div> -->
                                    <div class="col-lg-9 col-md-12">
                                        <!--shop wrapper start-->
                                        <!--shop toolbar start-->
									
                                    <div class="shop_toolbar">
										
                                        <div class="orderby_wrapper">
                                        @if (Route::currentRouteName() != 'products.gemstone')
                                           <!--<h3>{{ translate('Gemstones')}} : </h3>-->
                                            <div class=" ">
                                                <div class="order_by col-6 col-lg-auto mb-3 w-lg-200px">                                     
                                                        <label class="mb-0 opacity-50">{{ translate('Gemstones')}}</label>
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
                                          <!--  <h3>Sort By : </h3>-->
                                            <div class=" ">
                                                <div class="order_by col-6 col-lg-auto mb-3 w-lg-200px">
                                                    <label class="mb-0 opacity-50">{{ translate('Sort by')}}</label> 
                                                    <select class="form-control form-control-sm aiz-selectpicker listing-select" name="sort_by" onchange="filter()">
                                                        <option value="newest" @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>{{ translate('Newest')}}</option>
                                                        <option value="oldest" @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>{{ translate('Oldest')}}</option>
                                                        <option value="price-asc" @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset>{{ translate('Price low to high')}}</option>
                                                        <option value="price-desc" @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset>{{ translate('Price high to low')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- <div class="page_amount">
                                                 <p>Showing 1–9 of 21 results</p>
                                            </div> -->
                                        </div>
                                    </div>
                            <!--shop toolbar end-->
                                <div class="tab-content">
                                    <div class="tab-pane grid_view fade show active" id="large" role="tabpanel">
                                        <div class="row gutters-5 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-4 row-cols-md-3 row-cols-2">
                                            <?php
                                         //echo '<pre>';print_r($product);die;
                                            if(!empty($products) && count($products) > 0){ ?>
                                        @foreach ($products as $key => $product)
                                           <div class="product_list col-lg-4 col-md-4 col-sm-6">
                                                @include('frontend.partials.product_box_1',['product' => $product,'page_type'=>'listing','device_type'=>$device_type])
                                            </div>
                                        @endforeach
                                    <?php }else{  ?>
                                        <img
                                            class="img-fit lazyload  mx-auto custom-mt"
                                            src="{{ static_asset('assets/img/no-products-found.png') }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/no-products-found.png') }}';"
                                        >
                                        <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <!--shop toolbar start-->
                                    <div class="aiz-pagination aiz-pagination-center mt-4 shop_toolbar t_bottom">
                                        <div class="pagination">
                                            {{ $products->appends(request()->input())->links() }}
                                        </div>
                                    </div>
                                        <!--shop toolbar end-->
                                        <!--shop wrapper end-->
                                    </div>
                                    <div class="col-lg-3 col-md-12">
                                       <!--sidebar widget start-->
                                        <div class="sidebar_widget">
                                            <div class="widget_list widget_categories">
                                                <h2>{{ translate('Categories')}}</h2>
                                                <ul>
                                                    @if (!isset($category_id))
                                                        @foreach (\App\Models\Category::where('level', 0)->get() as $category)
                                                    <li>
                                                        <a href="{{ route('products.category', $category->slug) }}">{{ $category->getTranslation('name') }}</a>
                                                    </li>
                                                        @endforeach
                                                    @else
                                                    <li>
                                                        <a href="{{ route('search') }}">
                                                            <i class="las la-angle-left"></i>
                                                            {{ translate('All Categories')}}
                                                        </a>
                                                    </li>
                                                    @if (\App\Models\Category::find($category_id)->parent_id != 0)
                                                    <li>
                                                        <a href="{{ route('products.category', \App\Models\Category::find(\App\Models\Category::find($category_id)->parent_id)->slug) }}">
                                                            <i class="las la-angle-left"></i>
                                                            {{ \App\Models\Category::find(\App\Models\Category::find($category_id)->parent_id)->getTranslation('name') }}</a>
                                                    </li>
                                                    @endif
                                                    <li>
                                                        <a href="{{ route('products.category', \App\Models\Category::find($category_id)->slug) }}">
                                                            <i class="las la-angle-left"></i>
                                                            {{ \App\Models\Category::find($category_id)->getTranslation('name') }}</a>
                                                    </li>
                                                     @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category_id) as $key => $id)

                                                    <li>
                                                        <a href="{{ route('products.category', \App\Models\Category::find($id)->slug) }}">
                                                            {{ \App\Models\Category::find($id)->getTranslation('name') }}</a>
                                                    </li>
                                                @endforeach
                                            @endif
                                                </ul>
                                            </div>
                                            @if (isset(Auth::user()->id))
                                            <div class="widget_list widget_filter">
                                                <h2>{{ translate('Price range')}}</h2>
                                                <form action="#">
                                                    <div id="slider-range"
                                                    data-range-value-min="@if(\App\Models\Product::count() < 1) 0 @else {{ \App\Models\Product::min('unit_price') }} @endif"
                                                    data-range-value-max="@if(\App\Models\Product::count() < 1) 0 @else {{ \App\Models\Product::max('unit_price') }} @endif" ></div>
                                                    <div class="row mt-2">
                                                        <div class="col-6">
                                                            <span class="range-slider-value value-low fs-14 fw-600 opacity-70"
                                                                @if (isset($min_price))
                                                                    data-range-value-low="{{ $min_price }}"
                                                                @elseif($products->min('unit_price') > 0)
                                                                    data-range-value-low="{{ $products->min('unit_price') }}"
                                                                @else
                                                                    data-range-value-low="0"
                                                                @endif
                                                                id="input-slider-range-value-low"
                                                            ></span>
                                                        </div>
                                                        <div class="col-6 text-right">
                                                            <span class="range-slider-value value-high fs-14 fw-600 opacity-70"
                                                                @if (isset($max_price))
                                                                    data-range-value-high="{{ $max_price }}"
                                                                @elseif($products->max('unit_price') > 0)
                                                                    data-range-value-high="{{ $products->max('unit_price') }}"
                                                                @else
                                                                    data-range-value-high="0"
                                                                @endif
                                                                id="input-slider-range-value-high"
                                                            ></span>
                                                        </div>
                                                    </div>
                                                    <input class="price_range_filter" type="text" name="text" id="amount" />
                                                    <button class="price_range_filter_button" type="submit">Filter</button>


                                                </form>
                                            </div>
                                            @endif
                                            <?php
                                            $is_ring = 0;
                                                $attribute_category = \App\Models\Category::find($category_id);
                                                //echo '<pre>';print_r($attribute_category);die;
                                                if(!empty($attribute_category) && $attribute_category->name == 'Rings'){
                                                    $is_ring = 1;
                                                }
                                            ?>
                                            @foreach ($attributes as $attribute)
                                            <?php
                                                $attr_name = $attribute->getTranslation('name');
                                                if($is_ring == 1 && $attr_name == 'Sizes'){

                                                ?>
                                            <div class="widget_list widget_brands">
                                                <h2>{{ $attribute->getTranslation('name') }}</h2>
                                                <div class="aiz-checkbox-list">
                                                    @foreach ($attribute->attribute_values as $attribute_value)

                                                        <label class="aiz-checkbox">
                                                            <input
                                                                type="checkbox"
                                                                name="selected_attribute_values[]"
                                                                value="{{ $attribute_value->value }}" @if (in_array($attribute_value->value, $selected_attribute_values)) checked @endif
                                                                onchange="filter()"
                                                            >
                                                            <span class="aiz-square-check"></span>
                                                            <span class="checkbox_filter">{{ $attribute_value->value }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <?php }elseif($attr_name != 'Sizes'){ ?>
                                                <div class="widget_list widget_brands">
                                                <h2>{{ $attribute->getTranslation('name') }}</h2>
                                                <div class="aiz-checkbox-list">
                                                    @foreach ($attribute->attribute_values as $attribute_value)

                                                        <label class="aiz-checkbox">
                                                            <input
                                                                type="checkbox"
                                                                name="selected_attribute_values[]"
                                                                value="{{ $attribute_value->value }}" @if (in_array($attribute_value->value, $selected_attribute_values)) checked @endif
                                                                onchange="filter()"
                                                            >
                                                            <span class="aiz-square-check"></span>
                                                            <span class="checkbox_filter">{{ $attribute_value->value }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                                <?php }?>
                                            @endforeach

                                            <div class="widget_list widget_brands">
                                                <h2>{{ translate('Gemstones')}}</h2>
                                                <div class="aiz-checkbox-list">
                                                    @foreach (\App\Models\Brand::where('active',1)->orderBy('name', 'asc')->get() as $brand)
                                                    <?php
                                                            if((isset($category_id))){
                                                                $brand_product = \App\Models\Product::where('brand_id',$brand->id)->where('published',1)->where('category_id',$category_id)->count();
                                                                if(!empty($brand_product) && $brand_product > 0){
                                                    ?>
                                                        <label class="aiz-checkbox">
                                                            <input
                                                                type="checkbox"
                                                                name="brand_values[]"
                                                                value="{{ $brand->id }}" @if (in_array($brand->id, $brand_values)) checked @endif onchange="filter()">{{ $brand->getTranslation('name') }}


                                                            <span class="aiz-square-check"></span>
                                                            <span class="checkbox_filter">{{ $brand->value }}</span>
                                                        </label>
                                                        <?php }
                                                        }
                                                        ?>
                                                    @endforeach
                                                </div>
                                            </div>

                                           </div>
                                        <!--sidebar widget end-->
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

/*        $(document).ready(function(){
            $('ul.list li').click(function() {
                var tableid = $(this).closest('li').attr('data-value');
                filter();
            });
            $('ul.list li').click(function() {
              var value = $(this).data("value");
              $('.brand-val').val(value);
              console.log($('.brand-val').val());
            });
        })*/
    </script>
@endsection
