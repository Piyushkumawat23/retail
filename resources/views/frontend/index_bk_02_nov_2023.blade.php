@extends('frontend.layouts.app')

@section('content')
    {{-- Categories , Sliders . Today's deal --}}
<?php $device_type = detectUserAgentDevice();   ?>


<style>
	.iframe-container {
  position: relative;
  overflow: hidden;
  padding-top: 52.75%; /* 16:9 aspect ratio (change this to match your iframe's aspect ratio if needed) */
}

.iframe-container iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: 0;
}
</style>

    <!--slider area start-->
	<div class="home_slider_video iframe-container">
		<iframe  id="iframe_lazy_load"  src="//player.vimeo.com/video/880454581?background=0&amp;autoplay=1&amp;loop=1"  video_path="" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" ></iframe>
		
		<!--<div class="slider_video_img"><img class="lazyload" src="{{static_asset('assets/img/home-video-banner.jpg')}}"></img></div>
		
		<div class="slider_video_iframe" style="display:none"><iframe  id="iframe_lazy_load"  src="{{static_asset('assets/img/home-video-banner.jpg')}}"  video_path="//player.vimeo.com/video/880454581?background=1&amp;autoplay=1&amp;loop=1" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" height="800px" width="100%"></iframe></div>-->
		<!--<img class="lazyload" src="{{static_asset('assets/img/home-video-banner.jpg')}}">-->
		<!--<iframe id="iframe_lazy_load"  aria-label="Youtube video" src="{{static_asset('assets/img/home-video-banner.jpg')}}" video_path="https://www.youtube.com/embed/dKHeXC_2bHE?autoplay=1&modestbranding=1&rel=0&controls=0&showinfo=0" frameborder="0" allowfullscreen ></iframe>-->
		
		<!---->
	</div>
    <!--<div class="slider_area single_slider owl-carousel" data-autoplay="true">
        <video id="background-video" class="carousel-box" autoplay loop muted style="width:100%" >
        <source  src="{{static_asset('assets/banner-video/5.mp4')}}">
        </video>
    </div>--> 
	

    <!--slider area end-->
 
    <!--banner area start-->
    {{-- Banner section 1 --}}
    @if (get_setting('home_banner1_images') != null)

    <div class="banner_section">
        <div class="container">
            <div class="row ">
                @php $banner_1_imags = json_decode(get_setting('home_banner1_images')); @endphp

                @foreach ($banner_1_imags as $key => $value)
                <div class="col-lg-4 col-md-6">
                   <div class="single_banner">
                       <div class="banner_thumb">
                            <a href="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}">
                                <img src="{{ uploaded_asset($banner_1_imags[$key]) }}" data-src="{{ uploaded_asset($banner_1_imags[$key]) }}" alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload w-100 img-fit mx-auto h-md-250px">
                            </a>
                            <div class="banner_content">
                            </div>
                        </div>
                   </div>                    
                </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="messages_info">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!--banner area end-->


        <!--product section area start-->
    <section class="product_section p_section1">
        <div class="container">
            <div class="row">   
                <div class="col-12">
                    <div class="section_title">
                        <h2>Featured Products</h2>
                    </div> 
                    <div class="product_area"> 
                        <div class="product_tab_button">
                           
                        </div>
                         <div class="tab-content">
                              <div class="tab-pane fade show active" id="featured" role="tabpanel">
                                     <div class="product_container">
                                        <div class="custom-row product_column3">
                                            @if (count($featured_products) > 0)
                                           
                                                @foreach ($featured_products as $key => $new_product)
                                                 @include('frontend.partials.product_box_1',['product' => $new_product,'device_type'=>$device_type]) 
                                                @endforeach
                                            
                                            @endif
                                        </div>
                                    </div>
                              </div> 
                        </div>
                    </div>

                </div>
            </div>    
        </div>
    </section>
    <!--product section area end-->
     <!--banner fullwidth start-->
 @if(get_setting('topbar_banner') != null)
    <section class="banner_fullwidth" style="background: url(<?php echo uploaded_asset(get_setting('topbar_banner')) ?>) no-repeat scroll center center/cover !important ">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                   <div class="banner_text">
                        <!-- <p>Sale Off 20% All Products</p>
                        <h2>New Trending Collection</h2> -->
                        <h4 style="color: #fff;">We Believe That Good Design is Always in Season</h4>
                        <!-- <a href="javascript:void(0)">shopping Now</a> -->
                       
                   </div>
                    
                </div>
            </div>   
        </div>
    </section>
    @endif
    <!--banner area end-->


        <!--product section area start-->
        @php
            $best_selling_products = Cache::remember('best_selling_products', 86400, function () {
                return filter_products(\App\Models\Product::where('list_product',1)->where('published', 1)->orderBy('num_of_sale', 'desc'))->limit(20)->get();
            });   
        @endphp
        @if (get_setting('best_selling') == 1)
    <section class="product_section p_bottom p_section1">
        <div class="container">
            <div class="row">
               <div class="col-12">
                    <div class="section_title">
                        <h2>{{ translate('Best Selling') }}</h2>
                    </div> 
                </div>  
                <div class="col-12">
                    <div class="product_area ">
                         <div class="product_container bottom">
                            <div class="custom-row product_row1">
                                @foreach ($best_selling_products as $key => $product)
                               @include('frontend.partials.product_box_1',['product' => $product])                               
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
        @endif
    </section>
    <!--product section area end-->

    <!--banner area end-->
        <section class="product_section p_bottom p_section1">
         @if (count($newest_products) > 0)
        <div class="container">
            <div class="row">
               <div class="col-12">
                    <div class="section_title">
                        <h2>{{ translate('New Arrivals') }}</h2>
                    </div> 
                </div>  
                <div class="col-12">
                    <div class="product_area ">
                         <div class="product_container bottom">
                            <div class="custom-row product_row1">
                                @foreach ($newest_products as $key => $new_product)
                                @include('frontend.partials.product_box_1',['product' => $new_product])                                
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
        @endif
    </section>

    
    <!--banner area start-->
    @if (get_setting('home_banner2_images') != null)
    <div class="banner_section">
        <div class="container">
            <div class="row ">
                @php $banner_2_imags = json_decode(get_setting('home_banner2_images')); @endphp
                @foreach ($banner_2_imags as $key => $value)
                <div class="col-lg-4 col-md-6">
                   <div class="single_banner">
                       <div class="banner_thumb">
                            <a href="{{ json_decode(get_setting('home_banner2_links'), true)[$key] }}">
                                <img src="{{ uploaded_asset($banner_2_imags[$key]) }}" alt="{{ env('APP_NAME') }} promo"
                                class="img-fluid lazyload w-100 img-fit mx-auto  h-md-250px " >
                            </a>                            
                            <div class="banner_content">
                                <!-- <p>Design Creative</p>
                                <h2>Ring Jewelry Design</h2>
                                <span>From $60.99 – Sale 20%</span> -->
                            </div>
                        </div>
                   </div>                    
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    <!--banner area end-->

    

@endsection

