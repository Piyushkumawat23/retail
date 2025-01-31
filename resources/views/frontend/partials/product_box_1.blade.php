<?php $device_type = (isset($device_type)) ? $device_type : 'desktop';
//echo '<pre>';print_r($product->hover_video);
 ?>
<div class="single_product_box">
<div class="custom-col-5 aiz-card-box border border-light rounded hov-shadow-md mt-1 mb-2 has-transition bg-white margin_left">
    <div class="single_product">
        <div class="product_thumb" >
            @php
                $product_url = route('product', $product->slug);
                if($product->auction_product == 1) {
                    $product_url = route('auction-product', $product->slug);
                }
            @endphp
			
			<?php  
                $product_image = get_product_image($product->thumbnail_img,$device_type);
    			$product_hover_image = get_product_image($product->hover_img,$device_type);
                $product_hover_video = $product->hover_video; // Assuming this contains the hover video URL or file
 
		    ?>
            <a class="primary_img 1" href="{{ $product_url }}">
                <img  src="{{ $product_image }}" class="product-img-fit img-fit lazyload mx-auto  primary-image" 
                     alt="{{ $product->img_alt_text ?? $product->getTranslation('name') }}"
                     onerror="this.onerror=null;this.src='{{ $product_image }}';">
                
                <!-- <img loading="lazy" src="{{ $product_image }}" 
                     img_src="{{ isset($product_hover_image) && !empty($product_hover_image) ? $product_hover_image : $product_image }}" 
                     class="product-img-fit img-fit lazyload mx-auto h-200px h-md-300px secondary-image" 
                     alt="{{$product->getTranslation('name') }}"
                     onerror="this.onerror=null;this.src='{{ $product_image }}';" 
                     style="display:none;"> -->
                     <!-- Hover Video or Image -->
                @if(isset($product_hover_video) && !empty($product_hover_video))
                    <!-- Show Hover Video if available -->
                    <video class="product-img-fit img-fit lazyload mx-auto  secondary-video" 
                           autoplay muted loop 
                           onerror="this.onerror=null;this.src='{{ $product_image }}';" 
                           style="display:none; ">
                        <source src="{{ uploaded_asset($product_hover_video) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <!-- Fallback to Hover Image if no video -->
                    <img  src="{{ isset($product_hover_image) && !empty($product_hover_image) ? $product_hover_image : $product_image }}"
                         img_src="{{ isset($product_hover_image) && !empty($product_hover_image) ? $product_hover_image : $product_image }}"  
                         class="product-img-fit img-fit lazyload mx-auto secondary-image" 
                         alt="{{ $product->getTranslation('name') }}"
                         onerror="this.onerror=null;this.src='{{ $product_image }}';" 
                         style="display:none;">
                @endif
            </a>

<!-- 
            <?php //if($product->made_to_order == 1){ ?>
            <div class="quick_button">
                <a href="#" data-bs-toggle="modal" data-bs-target="#modal_box" data-placement="top" data-original-title=" Made To Order" class="made_order">  Made To Order</a>
            </div>
            <?php //} ?> -->
        </div>
        <div class="product_content product_hover_content">
            <!-- <p>{{ $product->brand->name ?? '' }}</p> -->
            <h3><a href="{{ $product_url }}">{{  $product->getTranslation('name')  }}</a></h3>
            <!-- <span class="old_price text-primary">{{ $product->brand->name ?? '' }}</span> -->
            
            @if(home_base_price($product) != home_discounted_base_price($product))
            <del class="old_price opacity-50 mr-1">{{ home_base_price($product) }}</del>
            @endif
            <span class="old_price text-primary">{{ home_discounted_base_price($product) }}</span>
            <?php if($product->made_to_order == 1){ ?>
            <span class="made-order">(Made To Order)</span>
            <?php } ?>

            <div class="product_hover">
                <!--<div class="product_ratings">
                    {{ renderStarRating($product->rating) }}
                </div>-->
                <div class="product_desc">
                    <p>
                        @if(home_base_price($product) != home_discounted_base_price($product))
                        <del class="old_price  opacity-50 mr-1">{{ home_base_price($product) }}</del>
                        @endif
                        <span class="old_price text-primary">{{ home_discounted_base_price($product) }}</span>
                        @if(discount_in_percentage($product) > 0)
                            <span class="badge-custom">{{ translate('OFF') }}<span class="box ml-1 mr-0">&nbsp;{{discount_in_percentage($product)}}%</span></span>
                        @endif
                    </p>
                    
                    

                   <?php // echo Str::limit(strip_tags($product->description) ,70); ?></p>
                </div>

                <!-- <div class="action_links  ">
                    <ul>
                        <li><a href="javascript:void(0)" onclick="addToWishList({{ $product->id }})" data-placement="top" title="Add to Wishlist" data-bs-toggle="tooltip"><span class="icon icon-Heart"></span></a></li>
                         <li class="add_to_cart"><a href="javascript:void(0)" onclick="showAddToCartModal({{ $product->id }})" title="add to cart">add to cart</a></li>
                         <li><a href="javascript:void(0)" onclick="addToCompare({{ $product->id }})" title="compare"><i class="ion-ios-settings-strong"></i></a></li>
                    </ul>
                </div> -->
            </div>
        </div>
    </div>
</div>
</div>

