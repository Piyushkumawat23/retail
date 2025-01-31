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
                            @if($detailedProduct->gemstone_size)
                            <!-- <hr> -->
                            @endif

                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <small class="mr-2 opacity-50">{{ translate('Sold by')}}: </small><br>
                                    @if ($detailedProduct->added_by == 'seller' && get_setting('vendor_system_activation') == 1)
                                        <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}" class="text-reset">{{ $detailedProduct->user->shop->name }}</a>
                                    @else
                                        {{  translate('Inhouse product') }}
                                    @endif
                                </div>
                                @if (get_setting('conversation_system') == 1)
                                    <div class="col-auto    ">
                                        <button class="btn btn-sm btn-soft-primary" onclick="show_chat_modal()">{{ translate('Message Seller')}}</button>
                                    </div>
                                @endif                              
                            </div>
                            <div class="row clearfix">
                                    <?php if(!empty($detailedProduct->product_weight)){ ?>
                                    <div class="product_meta">
                                        <span>Product Weight:
                                            <a href="javascript:void(0)">
                                            <?php echo $detailedProduct->product_weight; ?>                                                
                                            </a>
                                        </span>
                                        </div>
                                    <?php } ?>

                                    @if ($detailedProduct->brand != null)
                                        <div class="product_meta">
                                            <span>Gemstone:
                                            <a href="{{ route('products.gemstone',$detailedProduct->brand->slug) }}">{{ $detailedProduct->brand->getTranslation('name') }}
                                            </a></span>
                                        </div>                                  
                                    @endif
                                    
                                    <?php if(!empty($detailedProduct->gemstone_size)){ ?>
                                    <div class="product_meta">
                                            <span>Gemstone Size:
                                            <a href="javascript:void(0)"><?php echo $detailedProduct->gemstone_size; ?></a></span>
                                        </div>
                                    <?php } ?>
                                    
                                    <?php if(!empty($detailedProduct->gemstone_weight)){ ?>
                                    <div class="product_meta">
                                            <span>Gemstone Weight:
                                            <a href="javascript:void(0)"><?php echo $detailedProduct->gemstone_weight; ?></a></span>
                                        </div>
                                    <?php } ?>
                            </div>
                            @if($detailedProduct->gemstone_size)
                            <!-- <hr> -->
                            @endif

                            <!-- <div class="product_price">                             
                                @if(home_price($detailedProduct) != home_discounted_price($detailedProduct))
                                <del class="old_price">{{ home_price($detailedProduct) }}</del>

                                <span class="current_price">{{ home_discounted_price($detailedProduct) }}</span>
                                @else
                                <span class="current_price">{{ home_discounted_price($detailedProduct) }}</span>
                                 @endif
                            </div> -->

                        <form id="option-choice-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $detailedProduct->id }}">
                                <div class="row no-gutters mt-4 gemstone-list-product">
                                @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)

                                    <?php 
                                        $attribute_name = \App\Models\Attribute::find($choice->attribute_id)->getTranslation('name'); ?>
                                        <?php if($choice->attribute_id == 4){ 
                                            //echo'<pre>choice'; print_r($choice->values);die;
                                            ?>

                                        @foreach ($choice->values as $key => $metal_value)
                                        <div class="col-sm-12">
                                        <?php 
                                        
                                        foreach ($subProduct as $key => $value){ 
                                            $active=($detailedProduct->slug==$value->slug)?'active':''; 

                                            $url=($detailedProduct->slug==$value->slug)?"javascript:void(0)":"route('product', $value->slug) "; ?>                                            

                                            @if($detailedProduct->brand_id)
                                            <span class="name">
                                            <?php 
                                             $metal_images = get_metal_image($value->id, $metal_value, $value->thumbnail_img );
                                            ?>                                                
                                            </span>
                                            <?php if(!empty($metal_images)){?>
                                            <div class="col-sm-3 col-4 float-left" style="padding-left: 0px;    padding-right: 5px;">
                                                
                                                <a 
                                                    href="{{ ($detailedProduct->slug==$value->slug)?'javascript:void(0)':route('product', $value->slug) }}?metal={{ str_replace(' ','',$metal_value) }}" 
                                                    class="d-block <?php echo ($detailedProduct->slug==$value->slug)? 'selected-metal' : '' ;?> text-reset {{$active}} change-image rt{{ $value->slug }} rt{{ $value->slug }}{{ str_replace(' ','',$metal_value) }} <?php echo ($attribute_name == 'Metals') ? 'metal_attribute' : ''; ?>" 
                                                    style="border: 1px solid #ededed;padding: 5px;text-align: center;" 
                                                    metal ="{{ $metal_value }}"
                                                    metal_product_slug = "{{ $detailedProduct->slug }}"
                                                    metal_attr_id = "{{ $choice->attribute_id }}" 
                                                >

                                                <img class="img-fit lazyload h-xxl-80px h-xl-20px h-50px" src="{{ uploaded_asset($metal_images) }}" alt="">
                                                @if(isset($value->brand->name))
                                                <!-- <p>{{$metal_value}}</p> -->
                                                @endif
                                                </a>   
                                                 
                                            </div>
                                            <?php }?>
                                            @endif
                                        <?php }
                                        ?>                                    
                                    </div>                                    
                                        @endforeach
                                    <?php } ?>
                                    @endforeach
                                </div>
                                <!-- <hr> -->
                                <div class="product_desc">
                                   <!--  <p><?php echo $detailedProduct->getTranslation('description'); ?> </p> -->
                                </div>
                                           
                                <!-- <hr> -->
                                @if ($detailedProduct->choice_options != null)
                                    @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)
                                    <?php 
                                    $attribute_name = \App\Models\Attribute::find($choice->attribute_id)->getTranslation('name'); ?>
                                    <div class="row no-gutters product_variant qty_box">
                                        <div class="col-sm-2">
                                            <div class="opacity-50 my-2">{{ $attribute_name }}:</div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="aiz-radio-inline">
                                                @foreach ($choice->values as $key => $value)
                                                <label class="aiz-megabox pl-0 mr-2">
                                                    <input
                                                        type="radio" 
                                                        onchange="change_product_image(this)"
                                                        data-attribute_name = "{{ $attribute_name }}"
                                                        data-product_id = "{{ $detailedProduct->id }}"
                                                        class="attribute_id selected-attribute"
                                                        name="attribute_id_{{ $choice->attribute_id }}"
                                                        value="{{ $value }}"
                                                        @if($key == 0) checked @endif
                                                    >
                                                    <span class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center py-2 px-3 mb-2">
                                                        {{ $value }}
                                                    </span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    @endforeach
                                @endif

                                @if (count(json_decode($detailedProduct->colors)) > 0)
                                    <div class="row no-gutters">
                                        <div class="col-sm-2">
                                            <div class="opacity-50 my-2">{{ translate('Color')}}:</div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="aiz-radio-inline">
                                                @foreach (json_decode($detailedProduct->colors) as $key => $color)
                                                <label class="aiz-megabox pl-0 mr-2" data-toggle="tooltip" data-title="{{ \App\Models\Color::where('code', $color)->first()->name }}">
                                                    <input
                                                        type="radio"
                                                        name="color"
                                                        value="{{ \App\Models\Color::where('code', $color)->first()->name }}"
                                                        @if($key == 0) checked @endif   
                                                    >
                                                    <span class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center p-1 mb-2">
                                                        <span class="size-30px d-inline-block rounded" style="background: {{ $color }};"></span>
                                                    </span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <hr> -->
                                @endif
                                <!-- Quantity + Add to cart -->
                                <div class="row no-gutters product_variant qty_box">
                                    <div class="col-sm-2">
                                        <label>{{ translate('Quantity')}}:</label>
                                    </div>@php
                                                $qty = 0;
                                                foreach ($detailedProduct->stocks as $key => $stock) {
                                                    $qty += $stock->qty;
                                                }
                                            @endphp
                                    <div class="col-sm-10">
                                        <div class="product-quantity d-flex align-items-center">
                                            @if($qty == 0 || !empty($slug2))

                                            <div class="row no-gutters align-items-center aiz-plus-minus mr-3" style="width: 130px;">
                                                <button class="btn col-auto btn-icon btn-sm btn-circle btn-light" type="button" data-type="minus" data-field="quantity" >
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                                <input type="number" name="quantity" id="out-stock-quantity" class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1" value="{{get_setting('out_stock_minimum_order')}}" min="{{get_setting('out_stock_minimum_order')}}" max="10" oofsq="1" lang="en">
                                                <button class="btn  col-auto btn-icon btn-sm btn-circle btn-light" type="button" data-type="plus" data-field="quantity">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                            @else 
                                            <div class="row no-gutters align-items-center aiz-plus-minus mr-3" style="width: 130px;">
                                                <button class="btn col-auto btn-icon btn-sm btn-circle btn-light" type="button" data-type="minus" data-field="quantity" disabled="">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                                <input type="number" name="quantity" class="col border-0 text-center flex-grow-1 fs-16 input-number quantity_instock" placeholder="1" value="{{ $detailedProduct->min_qty }}" min="{{ $detailedProduct->min_qty }}" oofsq="0" max="10" lang="en">
                                                <button class="btn  col-auto btn-icon btn-sm btn-circle btn-light" type="button" data-type="plus" data-field="quantity">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>                                            
                                            @endif
                                            @if(!$slug2 && $qty != 0)
                                            <div class="avialable-amount opacity-60" >
                                                @if($detailedProduct->stock_visibility_state == 'quantity')
                                                (<span id="available-quantity">{{ $qty }}</span> {{ translate('available')}})
                                                @elseif($detailedProduct->stock_visibility_state == 'text' && $qty >= 1)
                                                    (<span id="available-quantity">{{ translate('In Stock') }}</span>)
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- <hr> --> 
                                <div class="row no-gutters pb-3" id="chosen_price_div">
                                    <div class="col-sm-2">
                                        <div class="product_variant">{{ translate('Total Price')}}:</div>
                                    </div>
                                    <div class="col-sm-10 product_variant">
                                        <div class="product-price">
                                            <strong id="chosen_price" class="h4 fw-600 text-primary">

                                            </strong>
                                        </div>
                                    </div>
                                    <div class="product_detail_page">(*Shipping free above $75)</div>
                                </div>
                        </form>
                            

                            <div class="product_variant quantity">
                                <!-- <label>quantity</label>
                                <input min="1" max="100" value="1" type="number"> -->
                                @if(Auth::check())
                                    <button class="button" type="submit" onclick="addToCart()">add to cart</button>  
                                    <button class="button" type="submit" onclick="buyNow()">Buy Now</button>  
                                @else
                                    <button class="button" type="submit" onclick="addToCart()">add to cart</button>
                                @endif
                            </div>
                            <div class=" product_d_action">
                               <ul>
                                   <li><a href="#" onclick="addToWishList({{ $detailedProduct->id }})" title="Add to wishlist">+ Add to Wishlist</a></li><!-- 
                                   <li><a href="#" onclick="addToCompare({{ $detailedProduct->id }})" title="Add to wishlist">+ Compare</a></li> -->
                               </ul>
                            </div>
                            <div class="product_meta">
                                <!-- <span>Category: <a href="#">Clothing</a></span> -->
                            </div>
                        <div class="priduct_social">
                            @if ( get_setting('show_social_links') )
                            <ul>
                                @if ( get_setting('facebook_link') !=  null )
                                <li><a href="{{ get_setting('facebook_link') }}" title="facebook"><i class="fa fa-facebook"></i></a></li>
                                @endif
                                @if ( get_setting('twitter_link') !=  null )           
                                <li><a href="{{ get_setting('twitter_link') }}" title="twitter"><i class="fa fa-twitter"></i></a></li> 
                                @endif
                                @if ( get_setting('youtube_link') !=  null )          
                                <li><a href="{{ get_setting('youtube_link') }}" title="pinterest"><i class="ion-social-youtube"></i></a></li> 
                                @endif      
                            </ul> 
                            @endif     
                        </div>

                    </div>
                </div>
            </div>
        </div>    
    </div>
    <!--product details end-->
    
    <!--product info start-->
    <div class="product_d_info">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="product_d_inner">   
                        <div class="product_info_button">    
                            <ul class="nav" role="tablist">
                                <li >
                                    <a class="active" data-bs-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="false">Description</a>
                                </li>
                                @php
                                        $total = 0;
                                        $total += $detailedProduct->reviews->count();
                                @endphp
                            <?php if($total > 0){
                            ?>
                                <li>                                     
                                   <a data-bs-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Reviews ({{ $total }})</a>
                                </li>
                            <?php } ?>

                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="info" role="tabpanel" >
                                <div class="product_info_content">
                                    <p><?php echo $detailedProduct->getTranslation('description'); ?></p>
                                </div>    
                            </div>
                            <div class="tab-pane fade" id="reviews" role="tabpanel" >
                                <div class="reviews_wrapper">
                                    @foreach ($detailedProduct->reviews as $key => $review)
                                    @if($review->user != null)
                                    <!-- <h2>1 review for Donec eu furniture</h2> -->
                                    <div class="reviews_comment_box">
                                        <div class="comment_thmb">
                                            <img src="{{ static_asset('assets/img/placeholder.webp') }}" 
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.webp') }}';"
                                            @if($review->user->avatar_original !=null)
                                                data-src="{{ uploaded_asset($review->user->avatar_original) }}"
                                            @else
                                                data-src="{{ static_asset('assets/img/placeholder.webp') }}"
                                            @endif>
                                        </div>
                                        <div class="comment_text">
                                            <div class="reviews_meta">
                                                <div class="star_rating">
                                                    <ul>
                                                        <li>
                                                            @for ($i=0; $i < $review->rating; $i++)
                                                            <i class="las la-star active"></i>
                                                            @endfor
                                                            @for ($i=0; $i < 5-$review->rating; $i++)
                                                                <i class="las la-star"></i>
                                                            @endfor
                                                        </li>
                                                    </ul>   
                                                </div>
                                                <p><strong>{{ $review->user->name }} </strong>{{ date('d-m-Y', strtotime($review->created_at)) }}</p>
                                                <span>{{ $review->comment }}</span>
                                            </div>
                                        </div>
                                    </div>                                                
                                    @endif
                                    @endforeach
                                    @if(count($detailedProduct->reviews) <= 0)
                                        <div class="text-center fs-18 opacity-70">
                                            {{  translate('There have been no reviews for this product yet.') }}
                                        </div>
                                    @endif 
                                </div>    
                            </div>
                        </div>
                    </div>     
                </div>
            </div>
        </div>    
    </div>  
    <!--product info end-->
        <!--Accordion area-->
    <div class="accordion_area">
        <div class="container">
            <div class="row">
            <div class="col-12"> 
                <div id="accordion" class="card__accordion product_detail_accordion">
                  <?php if(get_setting('show_product_detail') == 'on'){ ?>
                  <div class="card  card_dipult">
                    <div class="card-header card_accor" id="headingTwo">
                        <button class="btn btn-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                          Delivery & Return
                           <i class="fa fa-plus"></i>
                           <i class="fa fa-minus"></i>

                        </button>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                      <div class="card-body">
                        <p><?php  echo get_setting('product_delivery_return'); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="card  card_dipult">
                    <div class="card-header card_accor" id="headingThree">
                        <button class="btn btn-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                         Warranty
                           <i class="fa fa-plus"></i>
                           <i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                      <div class="card-body">
                        <p><?php  echo get_setting('product_warranty'); ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="card  card_dipult">
                    <div class="card-header card_accor" id="headingfour">
                        <button class="btn btn-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseeight" aria-expanded="false" aria-controls="collapseeight">
                         Metal
                           <i class="fa fa-plus"></i>
                           <i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <div id="collapseeight" class="collapse" aria-labelledby="headingfour" data-parent="#accordion">
                      <div class="card-body">
                        <p><?php  echo get_setting('product_metal'); ?></p>
                      </div>
                    </div>
                  </div>
                  <?php } ?>
                </div>
            </div>
        </div>
        </div>
    </div>
    <!--Accordion area end-->
    <!--product section area start-->
    <section class="product_section  p_section1">
        <div class="container">
            <div class="row">
               <div class="col-12">
                    <div class="section_title">
                        <h2>{{ translate('Top Selling Products')}}</h2>
                    </div> 
                </div>  
                <div class="col-12">
                    <div class="product_area ">
                         <div class="product_container bottom">
                            <div class="custom-row product_row1">
                                    @foreach (filter_products(\App\Models\Product::where('user_id', $detailedProduct->user_id)->orderBy('num_of_sale', 'desc'))->limit(10)->get() as $key => $top_product)
									<div class="single_product_box">
                                <div class="custom-col-5 aiz-card-box border border-light rounded hov-shadow-md mt-1 mb-2 has-transition bg-white margin_left">
                                    <div class="single_product">
                                        <div class="product_thumb">
                                            <a class="primary_img" href="{{ route('product', $top_product->slug) }}"><img src="{{ uploaded_asset($top_product->thumbnail_img) }}" class="img-fit lazyload mx-auto h-140px h-md-280px" alt=""></a>
                                            <a class="secondary_img" href="{{ route('product', $top_product->slug) }}"><img src="{{ uploaded_asset($top_product->thumbnail_img) }}" class="img-fit lazyload mx-auto h-140px h-md-280px" alt=""></a><!-- 
                                            <div class="quick_button">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#modal_box" data-placement="top" data-original-title="quick view"> quick view</a>
                                            </div> -->
                                        </div>
                                        <div class="product_content">
                                            <!-- <div class="tag_cate">
                                                <a href="#">Clothing,</a>
                                                <a href="#">Potato chips</a>
                                            </div> -->
                                            <h3><a href="{{ route('product', $top_product->slug) }}">{{ $top_product->getTranslation('name') }}</a></h3>
                                            
                                            <span class="current_price">{{ home_discounted_base_price($top_product) }}</span>
                                            <div class="product_hover">
                                                <div class="product_ratings">
                                                    <ul>
                                                        <!--<li><a href="#">{{ renderStarRating($top_product->rating) }}</i></a></li>-->
                                                        <span class="current_price">{{ home_discounted_base_price($top_product) }}</span>
                                                    </ul>
                                                </div>
                                                <!--<div class="product_desc">
                                                    <p><?php 
                                                    //echo '<pre>';print_r($top_product);die;
                                                    //echo Str::limit(strip_tags($top_product->description) ,70); ?> </p>
                                                </div>-->
                                                <!-- <div class="action_links">
                                                    <ul>
                                                        <li><a href="wishlist.html" data-placement="top" title="Add to Wishlist" data-bs-toggle="tooltip"><span class="icon icon-Heart"></span></a></li>
                                                        <li class="add_to_cart"><a href="cart.html" title="add to cart">add to cart</a></li>
                                                        <li><a href="compare.html" title="compare"><i class="ion-ios-settings-strong"></i></a></li>
                                                    </ul>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
								</div>
                                @endforeach

                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </section>
    <!--product section area end-->
        <!-- BUY NOW MODAL -->
        <div class="modal fade" id="out-stock-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" >Confirm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="font-size: 120%;">
              <i class="las la-exclamation-triangle" style="font-size: 900% !important;margin-left: 31%;color: #c09578;" ></i><br>
                This is a special request, out of stock order hence can take <span >upto 10 to 12 business days to be shipped <sup>*T&C apply</sup> </span>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="buyNow()">Proceed</button>
              </div>
            </div>
          </div>
        </div>
        <!-- MESSAGE MODAL -->
        <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
                <div class="modal-content position-relative">
                    <div class="modal-header">
                        <h5 class="modal-title fw-600 h5">{{ translate('Any query about this product')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                    <form class="" action="{{ route('conversations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                        <div class="modal-body gry-bg px-3 pt-3">
                            <div class="form-group">
                                <input type="text" class="form-control mb-3" name="title" value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}" required>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" rows="8" name="message" required placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary fw-600" data-bs-dismiss="modal">{{ translate('Cancel')}}</button>
                            <button type="submit" class="btn btn-primary fw-600">{{ translate('Send')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- LOGIN Modal -->
    <div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document" style="margin-top: 140px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Login')}}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                @if (addon_is_activated('otp_system'))
                                    <input type="text" class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ translate('Email Or Phone')}}" name="email" id="email">
                                @else
                                    <input type="email" class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email">
                                @endif
                                @if (addon_is_activated('otp_system'))
                                    <span class="opacity-60">{{  translate('Use country code before number') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control h-auto form-control-lg" placeholder="{{ translate('Password')}}">
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class=opacity-60>{{  translate('Remember Me') }}</span>
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="{{ route('password.request') }}" class="text-reset opacity-60 fs-14">{{ translate('Forgot password?')}}</a>
                                </div>
                            </div>

                            <div class="mb-5">
                                <button type="submit" class="btn btn-primary btn-block fw-600">{{  translate('Login') }}</button>
                            </div>
                        </form>

                        <div class="text-center mb-3">
                            <p class="text-muted mb-0">{{ translate('Dont have an account?')}}</p>
                            <a href="{{ route('user.registration') }}">{{ translate('Register Now')}}</a>
                        </div>
                        @if(get_setting('google_login') == 1 ||
                            get_setting('facebook_login') == 1 ||
                            get_setting('twitter_login') == 1)
                            <div class="separator mb-3">
                                <span class="bg-white px-3 opacity-60">{{ translate('Or Login With')}}</span>
                            </div>
                            <ul class="list-inline social colored text-center mb-5">
                                @if (get_setting('facebook_login') == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="facebook">
                                            <i class="lab la-facebook-f"></i>
                                        </a>
                                    </li>
                                @endif
                                @if(get_setting('google_login') == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google">
                                            <i class="lab la-google"></i>
                                        </a>
                                    </li>
                                @endif
                                @if (get_setting('twitter_login') == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="twitter">
                                            <i class="lab la-twitter"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script') 
    <script type="text/javascript">
    $(window).load(function(){

       <?php //if(!isset($_GET['metal'])){ 

         ?> 
        $.each($('.attribute_id'),function(){
            var attribute_type = $(this).attr('data-attribute_name');

            //console.log(attribute_type);
            if(attribute_type == "Metals" && $(this).prop("checked") == true){
               var attribute_value  = $(this).val();
              attribute_value =  attribute_value.replace(/\s/g, '');
              //alert(attribute_value);
               $.each($('.change-image'), function(){              
                    var metal_product_slug = $(this).attr('metal_product_slug');
                    var metal_product_slug_metel = $('.rt'+metal_product_slug).attr('metal');
                    metal_product_slug_metel = metal_product_slug_metel.replace(/\s/g, '');
                    $('.rt'+metal_product_slug).removeClass('active');
                   
                    if(metal_product_slug_metel == attribute_value) {
                        //alert(metal_product_slug_metel);
                        $('.rt'+metal_product_slug+attribute_value).addClass('active');
                    }
                });
            }
        })

    <?php //}  ?>
    })
    <?php if(isset($_GET['metal']) && !empty($_GET['metal'])){
        //echo '<pre>metal';print_r($_GET['metal']);die;
       ?> 
        let searchParams = new URLSearchParams(window.location.search);
        searchParams.has('metal');
        let param = searchParams.get('metal');
        //alert($('.selected-metal'));
        $('.selected-metal').removeClass('active');
       $.each($('.selected-metal'), function(){              
        var selected_params = $(this).attr('metal');
        var selected_attr = $(this).attr('metal');
        selected_params = selected_params.replace(/\s/g, '');
        //console.log(selected_params);
        if(selected_params == param) {
            $.each($('.selected-attribute'), function(){
                var selected_met_attr = $(this).val();
                if(selected_met_attr == selected_attr) {
                    $(this).trigger( "click" );
                }                
            })                         
            $(this).addClass('active');
        }
    });
    <?php } ?>

        $(document).ready(function() {
            $("#loader").css("display",'block');
            //return false;
            $.each($('.attribute_id'), function(){
                if($(this).prop("checked") == true){
                  
                    change_product_image(this);
                }else{
                    
                }

            });
              
            

        });

          $('.change-image').click(function() { 
            $('.change-image').removeClass('active');
                var attribute_value = $(this).attr('metal');

                var metal_attr_id = $(this).attr('metal_attr_id');
                $('[name="attribute_id_'+metal_attr_id+'"]').removeAttr('checked');
               $('input[name=attribute_id_'+metal_attr_id+'][value="' + attribute_value + '"]').prop('checked', true);

                $(this).addClass('active');
                $.each($('.attribute_id'), function(){
                    if($(this).prop("checked") == true){
                      
                        change_product_image(this);
                    }else{
                        
                    }
                });
            });

        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
            // if (document.selection) {
            //     var range = document.body.createTextRange();
            //     range.moveToElementText(document.getElementById(containerid));
            //     range.select().createTextRange();
            //     document.execCommand("Copy");

            // } else if (window.getSelection) {
            //     var range = document.createRange();
            //     document.getElementById(containerid).style.display = "block";
            //     range.selectNode(document.getElementById(containerid));
            //     window.getSelection().addRange(range);
            //     document.execCommand("Copy");
            //     document.getElementById(containerid).style.display = "none";

            // }
            // AIZ.plugins.notify('success', 'Copied');
        }
        function show_chat_modal(){
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function change_product_image(el){
            //return false;
            //alert('hi');

            var attribute_value = $(el).val();
            var attribute_name = $(el).data('attribute_name');
            var product_id = $(el).data('product_id');

            if (attribute_name == 'Metals') {
                
                //$("#loader").css("display",'block');
                $("#overlay").css("display",'block');
                $("#PleaseWait").css("display",'block');
                //return false;
                //$(".product_img_all_new").css("display",'none');
                $.ajax({
                   type:"POST",
                   headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                   url:'{{ route('products.change-product-image') }}',
                   data:{attribute_value:attribute_value, attribute_name:attribute_name, product_id:product_id},
                   success: function(data) {
                    
                        if (data != false) {
                            
                            
                            $("#overlay").css("display",'none');
                            $("#PleaseWait").css("display",'none');
                            
                            $('#product_img_all').html(null);
                            $('#product_img_all').html(data);
                           /* $('#product_img_all').trigger('destroy.owl.carousel');
                            $('#product_img_all').owlCarousel().trigger('add.owl.carousel').trigger('refresh.owl.carousel');*/
                             //$("#loader").css("display",'none');
                        }else{
                             //$("#loader").css("display",'none');
                            $("#overlay").css("display",'none');
                            $("#PleaseWait").css("display",'none');
                        }
                         //$("#loader").css("display",'none');
                         /*$( "#zoom2" ).empty();
                          $( "#zoom2" ).append("<img  id='yourImageID' src='https://hamza.es/assets/images/logo.png' data-zoom-image='https://hamza.es/assets/images/logo.png' alt=''>");
                        $("#zoom2").elevateZoom({tint:true, tintColour:'#01714d', tintOpacity:0.5, scrollZoom : true });  */ 
                   },
                  error: function(e) {
                        
                        $("#overlay").css("display",'none');
                        $("#PleaseWait").css("display",'none');
                        //$("#loader").css("display",'none');
                        
                  }
                        
               });

            }else{
                       
                        //$("#loader").css("display",'none');
                        //$(".main-product-detail").css("display",'block');
            }
        }

    </script>
@endsection
