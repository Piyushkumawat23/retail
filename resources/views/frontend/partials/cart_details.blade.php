  
    <!--shopping cart area start -->
    <div class="shopping_cart_area"id="cart-summary">
        <div class="container">  
            <form action="#"> 
                <div class="row">
                    <div class="col-12">
                        <?php if($carts && count($carts) == 0){ ?>
                        <div class="empty_cart">
                            
                        </div>
                        <?php } ?>
                        <div class="table_desc">
                            @if($carts && count($carts) > 0)
                            <div class="cart_page table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="product_remove">{{ translate('Remove')}}</th>
                                            <th class="product_thumb">Image</th>
                                            <th class="product_name">{{ translate('Product')}}</th>
                                            <th class="product-price">{{ translate('Price')}}</th>
                                            <th class="product-price">{{ translate('Tax')}}</th>
                                            <th class="product_quantity">{{ translate('Quantity')}}</th>
                                            <th class="product_total">{{ translate('Total')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @php
                                                $total = 0;
                                            @endphp
                                            @foreach ($carts as $key => $cartItem)
                                                @php
                                                $product = \App\Models\Product::find($cartItem['product_id']);
                                                $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
                                                $total = $total + ($cartItem['price'] + $cartItem['tax']) * $cartItem['quantity'];
                                                $product_name_with_choice = $product->getTranslation('name');
                                                if ($cartItem['variation'] != null) {
                                                    $product_name_with_choice = $product->getTranslation('name').' - '.$cartItem['variation'];
                                                }
                                            @endphp
                                            <?php //echo '<pre>cart';print_r($cartItem);die;?>
                                           <td class="product_remove"><a href="javascript:void(0)" onclick="removeFromCartView(event, {{ $cartItem['id'] }})"><i class="fa fa-trash-o"></i></a></td>

                                            <td class="product_thumb"><img loading="lazy" src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name')  }}"></td>

                                            <td class="product_name">{{ $product_name_with_choice }}</td>
                                            <td class="product-price">{{ single_price($cartItem['price']) }}</td>
                                            <td class="product-price">{{ single_price($cartItem['tax']) }}</td>
                                            <td class="product_quantity">
                                                <label>Quantity</label>
                                                @if($cartItem['digital'] != 1 && $product->auction_product == 0)
                                                    <div class="row no-gutters aiz-plus-minus mr-2 ml-0">
                                                        </button>
                                                        <input type="number" name="quantity[{{ $cartItem['id'] }}]" class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1" value="{{ $cartItem['quantity'] }}" min="{{ $product->min_qty }}" max="{{ $product_stock->qty }}" onchange="updateQuantity({{ $cartItem['id'] }}, this)" oofsq="{{ $cartItem['is_out_stock_item'] }}">
                                                    </div>
                                                @elseif($product->auction_product == 1)
                                                    <span class="fw-600 fs-16">1</span>
                                                @endif
                                             </td>
                                            <td class="product_total">{{ single_price(($cartItem['price'] + $cartItem['tax']) * $cartItem['quantity']) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>   
                            </div>   
                        </div>
                     </div>
                 </div><!--coupon code area start-->
                <div class="coupon_area">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="coupon_code right">
                                <h3>Cart Totals</h3>
                                <div class="coupon_inner">
                                   <div class="cart_subtotal">
                                       <p>Subtotal</p>
                                       <p class="cart_amount">{{ single_price($total) }}</p>
                                   </div>
                                   <div >
                                        <a href="{{ route('home') }}" class="btn btn-link">
                                            <i class="las la-arrow-left"></i>
                                            {{ translate('Return to shop')}}
                                        </a>
                                    </div>

                                    <div>
                                    <input type="checkbox" name="terms" id="termscondition">
                                    <label style="display: inline-flex !important;">
                                        I agree to <a href="{{ route('terms') }}">Terms & Conditions</a>
                                    </label>
                                   </div>
                                   <div class="checkout_btn">
                                    <?php $min_cart_amount= get_setting('min_checkout');  ?>
                                        @if(Auth::check())
                                        @if($total >= $min_cart_amount)
                                       <a href="{{ route('checkout.shipping_info') }}" style="display: none;" id="checkout">
                                                {{translate('Checkout')}}
                                        </a>
                                        <a href="javascript:void(0)" style="" id="checkout-required">{{translate('Checkout')}}</a>
                                        @else
                                        <button type="button" disabled class="btn btn-primary fw-600">
                                                {{translate('Checkout')}}
                                        </button>
                                        <a href="javascript:void(0)" style="" id="checkout-required">{{translate('Checkout')}}</a>
                                               <br>
                                                {{ translate('Minimum Purchase Amount - ')}} {{(single_price($min_cart_amount))}}
                                           
                                        
                                        @endif
                                        @else
                                            <a href="{{ route('guestcheckout.shipping_info') }}" style="display: none;" id="checkout">Checkout As A Guest</a>
                                            <a href="javascript:void(0)" style="" id="checkout-required">Checkout As A Guest</a>
                                           OR
                                           <a href="{{ route('user.login') }}">login</a>
                                        @endif
                                   </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--coupon code area end-->
            </form> 
        </div>     
    </div>
    @else
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="shadow-sm bg-white p-4 rounded">
                <div class="text-center p-3">
                    <i class="las la-frown la-3x opacity-60 mb-3"></i>
                    <h3 class="h4 fw-700">{{translate('Your Cart is empty')}}</h3>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!--shopping cart area end -->
<script type="text/javascript">
AIZ.extra.plusMinus();
</script>