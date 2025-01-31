@extends('frontend.layouts.app')

@section('content')
    {{-- Categories , Sliders . Today's deal --}}
<?php $device_type = detectUserAgentDevice();   ?>


<style>
/*	.home_slider_video.iframe-container {
  position: relative;
  overflow: hidden;
  padding-top: 52.75%; 
}

.home_slider_video.iframe-container iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: 0;
}
*/
div.home_slider_video {
    position: relative;
}

.overlay_new {
    position: absolute;
    top: -6px;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3); 
}

<?php if($device_type == "mobile"){ ?>
.overlay_new {top: -3px;opacity:0.2}
<?php } ?>
</style>

 @if(get_setting('topbar_banner_video') != null)
    <?php $banner_video = uploaded_asset(get_setting('topbar_banner_video')); 
    //echo '<pre>video';print_r($banner_video);die;
    ?>
        <div class="home_slider_video" data-autoplay="true">
            <video id="background-video" autoplay loop muted style="width:100%">
                <source src="<?php echo $banner_video ?>">
            </video>
            <div class="overlay_new"></div>
        </div>
    @endif

    <!--slider area end-->

    
    
    <!--banner area start-->
    {{-- Banner section 1 --}}
    @if (get_setting('home_banner1_images') != null)

    <div class="banner_section home_banner1_images">
        <div class="container">
            <div class="row ">
                @php $banner_1_imags = json_decode(get_setting('home_banner1_images')); @endphp

                @foreach ($banner_1_imags as $key => $value)
                <div class="col-lg-4 col-md-6 banner_home">
                   <div class="single_banner">
                       <div class="banner_thumb banner_size">
                            <a href="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}">
                                <img src="{{ uploaded_asset($banner_1_imags[$key]) }}" data-src="{{ uploaded_asset($banner_1_imags[$key]) }}" alt="Top online jewelry stores in USA" class="img-fluid lazyload w-100 img-fit mx-auto h-md-500px">
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

    <!-- New product section area start-->
        <section class="product_section p_bottom p_section1">
         @if (count($newest_products) > 0)
        <div class="container">
            <div class="row">
               <div class="col-12">
                    <div class="section_title new_section_title">
                        <h2>{{ translate('New Arrivals Gemstone Jewelry') }}</h2>
                        <p>Explore our latest luxury gems and unique designs</p>
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
    <!--New product section area end-->    



    <!--banner fullwidth start-->
    @if(get_setting('topbar_banner') != null)
        <section class="banner_fullwidth" 
        style="background: url(<?php echo uploaded_asset(get_setting('topbar_banner')) ?>) no-repeat scroll center center/cover !important ">
            <div class="container">
                <div class="row align-items-center middle_banner">
                    <div class="col-12">
                      <!-- <div class="banner_text">
                            <p>Sale Off 20% All Products</p>
                            <h2>New Trending Collection</h2>
                            <h4 style="color: #fff;">We Believe That Good Design is Always in Season</h4>
                             <a href="javascript:void(0)">shopping Now</a>                           
                       </div>-->                        
                    </div>
                </div>   
            </div>
        </section>
    @endif
    <!--banner area end-->

    <!--Featured product section area start-->
    <section class="product_section p_section1 2">
        <div class="container">
            <div class="row">   
                <div class="col-12 " style="padding-left: 0px;">
                    <div class="section_title featured_section_title">
                        <h2>Featured Products</h2>
                    </div> 
                    <div class="product_area">
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
    <!--Featured product section area end-->

 
        @if(get_setting('topbar_banner') != null)
            <section class="new_banner_fullwidth">
                <div class="container">
                    <div class="row align-items-center new_middle_banner">
                        <div class="col-12">
                            <!-- Your new banner content -->
                            <div class="new_banner-content">
                                <h2>Why Choose {{ env('APP_NAME') }}?</h2>
                                <div class="new_features">
                                    <div class="new_feature">
                                        <img src="{{ static_asset('assets/img/banner/Hallmarked.png') }}" alt="Hallmark Icon">
                                        <p>Hallmarked Jewellery</p>
                                    </div>
                                    <div class="new_feature">
                                        <img src="{{ static_asset('assets/img/banner/verified.png') }}" alt="Certified Icon">
                                        <p>Certified Jewellery</p>
                                    </div>
                                    <div class="new_feature">
                                        <img src="{{ static_asset('assets/img/banner/trust.png') }}" alt="Best online jewelry store in USA">
                                        <p>Trusted best online jewelry store in USA</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>   
                </div>
            </section>
        @endif

    <!--Best Selling product section area start-->
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
                    <div class="section_title new_section_title">
                        <h1>{{ translate('Best online jewelry store in USA') }}</h1>
                        <p>Shop our most-loved gemstone jewelry</p>
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

    
    <!--banner area start-->
    @if (get_setting('home_banner2_images') != null)
    <div class="banner_section bottom_banner">
        <div class="container">
            <div class="row ">
                @php $banner_2_imags = json_decode(get_setting('home_banner2_images')); @endphp
                @foreach ($banner_2_imags as $key => $value)
                <div class="col-lg-4 col-md-6 banner_home">
                   <div class="single_banner">
                       <div class="banner_thumb">
                            <a href="{{ json_decode(get_setting('home_banner2_links'), true)[$key] }}">
                                <img src="{{ uploaded_asset($banner_2_imags[$key]) }}" alt="Buy gemstones online in USA"
                                class="img-fluid lazyload w-100 img-fit mx-auto  h-md-500px " >
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

 <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
      var video = document.getElementById("background-video");
      video.load();
    });
</script>   

@endsection


