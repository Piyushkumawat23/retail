<?php $device_type = (isset($device_type)) ? $device_type : 'desktop';
//echo '<pre>';print_r($product->hover_video);
 ?>
@php
if(auth()->user() != null) {
    $user_id = Auth::user()->id;
    $cart = \App\Models\Cart::where('user_id', $user_id)->get();
} else {
    $temp_user_id = Session()->get('temp_user_id');
    if($temp_user_id) {
        $cart = \App\Models\Cart::where('temp_user_id', $temp_user_id)->get();
    }
}
@endphp

<div class="cart_link">
    <a href="javascript:void(0)"><i class="ion-android-cart"></i><i class="fa fa-angle-down"></i></a>
    <!-- <span class="cart_quantity">2</span> -->
    <!--mini cart-->
     <div class="mini_cart">
        <div class="cart_close">
             
            <div class="cart_text">
                <h3>{{translate('Cart Items')}}</h3>
            </div>
            <div class="mini_cart_close">
                <a href="javascript:void(0)"><i class="ion-android-close"></i></a>
            </div>
        </div>
        @if(isset($cart) && count($cart) > 0)
        @php
        $total = 0;
        @endphp
        @foreach($cart as $key => $cartItem)
        @php
            $product = \App\Models\Product::find($cartItem['product_id']);
            $total = $total + ($cartItem['price'] + $cartItem['tax']) * $cartItem['quantity'];
        @endphp
        <?php 
        $product_image = get_product_image($product->thumbnail_img,$device_type);
        ?>
        @if ($product != null)
        <div class="cart_item">
           <div class="cart_img">
               <a href="{{ route('product', $product->slug) }}">
                <img
                    src="{{ uploaded_asset($product_image) }}"
                    data-src="{{ uploaded_asset($product_image) }}"
                    class="img-fit lazyload size-60px rounded"
                    alt="{{  $product->getTranslation('name')  }}"
                    onerror="this.onerror=null;this.src='{{ $product_image }}';">
                </a>
           </div>
            <div class="cart_info">
                <a href="{{ route('product', $product->slug) }}">{{  $product->getTranslation('name')  }}</a>

                <span class="quantity">Qty: {{ $cartItem['quantity'] }}</span>
                <span class="price_cart">{{ single_price($cartItem['price'] + $cartItem['tax']) }}</span>

            </div>
            <div class="">
                <button onclick="removeFromCart({{ $cartItem['id'] }})" class="btn btn-sm btn-icon stop-propagation">
                    <i class="ion-android-close"></i>
                </button>
            </div>
        </div>
        @endif
        @endforeach
        <div class="cart_total">
            <span>{{translate('Subtotal')}}:</span>
            <span>{{ single_price($total) }}</span>
        </div> 
        <div class="mini_cart_footer">
           <div class="cart_button view_cart">
                <a href="{{ route('cart') }}">{{translate('View cart')}}</a>
            </div>
            <?php $min_cart_amount= get_setting('min_checkout');  ?>
            @if (Auth::check())
                @if($total >= $min_cart_amount)
                <div class="cart_button checkout">
                    <a class="active" href="{{ route('checkout.shipping_info') }}">{{translate('Checkout')}}</a>
                </div>
                @else
                <div class="cart_button checkout">
                    <a disabled href="{{ route('checkout.shipping_info') }}">{{translate('Checkout')}}</a>
                </div>
                @endif
            @endif
        </div>
        @else
        <div class="text-center p-3">
            <i class="las la-frown la-3x opacity-60 mb-3"></i>
            <h3 class="h6 fw-700">{{translate('Your Cart is empty')}}</h3>
        </div>
        @endif
    </div>
    <!--mini cart end-->
</div>