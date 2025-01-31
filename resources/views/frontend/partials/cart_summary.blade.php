<!-- <div class="card border-0 shadow-sm rounded">
                    <div class="col-lg-6 col-md-6">   -->
                            <h3>Your order </h3> 
                            <span class="badge badge-inline badge-primary">
                                {{ count($carts_del_info) }} {{translate('Items')}}
                                </span>
                                @php $subtotal_for_min_order_amount = 500; @endphp
                                @foreach ($carts_del_info as $key => $cartItem)
                                    @php $subtotal_for_min_order_amount += $cartItem['price'] * $cartItem['quantity']; @endphp
                                @endforeach
                                @if(get_setting('minimum_order_amount_check') == 1 && $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
                                    <span class="badge badge-inline badge-primary">
                                        {{translate('Minimum Order Amount').' '.single_price(get_setting('minimum_order_amount'))}} 
                                    </span>
                                @endif
                            <div class="order_table table-responsive table-responsive-cart">
                                    @if (addon_is_activated('club_point'))
                                        @php
                                            $total_point = 0;
                                        @endphp
                                        @foreach ($carts as $key => $cartItem)
                                            @php
                                                $product = \App\Models\Product::find($cartItem['product_id']);
                                                $total_point += $product->earn_point * $cartItem['quantity'];
                                            @endphp
                                        @endforeach
                                        
                                        <div class="rounded px-2 mb-2 bg-soft-primary border-soft-primary border">
                                            {{ translate("Total Club point") }}:
                                            <span class="fw-700 float-right">{{ $total_point }}</span>
                                        </div>
                                    @endif
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $subtotal = 0;
                                            $tax = 0;
                                            $shipping = 0;
                                            $product_shipping_cost = 0;
                                            $shipping_region = (isset($shipping_info['city']) && $shipping_info['city'] != '') ? $shipping_info['city'] : '';
                                        @endphp    
                                        @foreach ($carts as $key => $cartItem)
                                            @php
                                                $product = \App\Models\Product::find($cartItem['product_id']);
                                                $subtotal += $cartItem['price'] * $cartItem['quantity'];
                                                $tax += $cartItem['tax'] * $cartItem['quantity'];
                                                $product_shipping_cost = $cartItem['shipping_cost'];

                                                if(isset($_GET['shipping_type']) && $_GET['shipping_type'] == "priority"){
                                                    $shipping += getShippingCost($carts, $key);
                                                }else{
                                                    $shipping += $product_shipping_cost;
                                                }
                                                
                                                
                                                $product_name_with_choice = $product->getTranslation('name');
                                                if ($cartItem['variant'] != null) {
                                                    $product_name_with_choice = $product->getTranslation('name').' - '.$cartItem['variant'];
                                                }
                                            @endphp


                                        <tr>
                                            <td> {{ $product_name_with_choice }} <strong> × {{ $cartItem['quantity'] }}</strong></td>
                                            <td> {{ single_price($cartItem['price']*$cartItem['quantity']) }}</td>
                                        </tr>
                                        @endforeach
                                        <?php                                            
                                            $wholesaleCommissionAmount=wholesaleCommissionAmount($subtotal);
                                            $international_shipping = isset($shipping_country['country_code']) ? $shipping_country['country_code'] : 'US';
                                            $normal_ship_cost = isset($shipping_country['normal_shipping_cost']) ? $shipping_country['normal_shipping_cost'] : '0';
                                            $min_shipping = 75;
                                            
                                            if(!empty($shipping_country)){
                                                $tax_on_total = $shipping_country['taxrate'];  
                                            }else{                                                  
                                                $tax_on_total = 0;
                                            }
                                            
                                            $total_tax = $subtotal * $tax_on_total;

                                            $totalwithoutshipping = ($subtotal + $total_tax) - $wholesaleCommissionAmount;
                                            if(!empty($shipping)){
                                                $shipping = $shipping;
                                            }elseif ($international_shipping != "US") {
                                                $shipping = 20;
                                            } elseif ($totalwithoutshipping >= $min_shipping) {
                                                $shipping = 0;
                                            }else{
                                                $shipping = $normal_ship_cost;
                                            }


                                            $total = ($subtotal + $total_tax + $shipping) - $wholesaleCommissionAmount;                                            
                                            if(Session::has('club_point')) {
                                                $total -= Session::get('club_point');
                                            }
                                            if ($carts->sum('discount') > 0){
                                                $total -= $carts->sum('discount');
                                            }


                                        ?>
                                    </tbody>
                                    <input type="hidden" id="sub_total" value="{{ $subtotal }}">
                                    <tfoot>
                                        <tr>
                                            <th>Cart Subtotal</th>
                                            <td id="guest_subtotal_amount">{{ single_price($subtotal) }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{translate('Tax')}}</th>
                                            <td id="guest_tax">{{ single_price($total_tax) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Shipping                                                
                                            <label style="font-size: small; color: red;">(*Shipping free above $75)</label>
                                            </th>
                                            <td id="guest_shipping">{{ single_price($shipping) }}</td>
                                        </tr>
                                        @if ($wholesaleCommissionAmount > 0)
                                        <tr>
                                            <th>{{translate('Wholesale Discount')}}</th>
                                            <td>{{ '$'.number_format($wholesaleCommissionAmount,2,'.') }}</td>
                                        </tr>
                                        @endif
                                        @if (Session::has('club_point'))
                                        <tr>
                                            <th>{{translate('Redeem point')}}</th>
                                            <td>{{ single_price(Session::get('club_point')) }}</td>
                                        </tr>
                                        @endif
                                        @if ($carts->sum('discount') > 0)
                                        <tr>
                                            <th>{{translate('Coupon Discount')}}</th>
                                            <td>{{ single_price($carts->sum('discount')) }}</td>
                                        </tr>
                                        @endif
                                        <tr class="order_total">
                                            <th>Order Total</th>
                                            <td id="guest_total"><strong>{{ single_price($total) }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>                                
                                @if (addon_is_activated('club_point'))
                                    @if (Session::has('club_point'))
                                        <div class="mt-3">
                                            <form class="" action="{{ route('checkout.remove_club_point') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="input-group">
                                                    <div class="form-control">{{ Session::get('club_point')}}</div>
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-primary">{{translate('Remove Redeem Point')}}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                @endif

                                @if (Auth::check() && get_setting('coupon_system') == 1)
                                    @php 
                                        $coupon_discount = $carts->sum('discount'); 
                                        $coupon_code = null;
                                    @endphp
                                    @foreach ($carts as $key => $cartItem)
                                        @if($cartItem->coupon_applied == 1 && $cartItem->discount > 0)
                                            @php
                                                $coupon_code = $cartItem->coupon_code;
                                                break;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @if ($coupon_discount > 0 && $coupon_code)
                                        <div class="mt-3">
                                            <form class="" id="remove-coupon-form" enctype="multipart/form-data">
                                                @csrf
                                                <div class="input-group">
                                                    <div class="form-control">{{ $coupon_code }}</div>
                                                    <div class="input-group-append">
                                                        <button type="button" id="coupon-remove" class="btn btn-primary">{{translate('Change Coupon')}}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @else
                                        <div class="mt-3">
                                            <form class="" id="apply-coupon-form" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="code" onkeydown="return event.key != 'Enter';" placeholder="{{translate('Have coupon code? Enter here')}}" required>
                                                    <div class="input-group-append">
                                                        <button type="button" id="coupon-apply" class="btn btn-primary">{{translate('Apply')}}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                @endif
     
                            </div><!-- 
                            <div class="payment_method">
                               <div class="panel-default">
                                    <input id="payment" name="check_method" type="radio" data-bs-target="createp_account" />
                                    <label for="payment" data-bs-toggle="collapse" data-bs-target="#method" aria-controls="method">Create an account?</label>

                                    <div id="method" class="collapse one" data-parent="#accordion">
                                        <div class="card-body1">
                                           <p>Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.</p>
                                        </div>
                                    </div>
                                </div> 
                               <div class="panel-default">
                                    <input id="payment_defult" name="check_method" type="radio" data-bs-target="createp_account" />
                                    <label for="payment_defult" data-bs-toggle="collapse" data-bs-target="#collapsedefult" aria-controls="collapsedefult">PayPal <img src="assets/img/icon/papyel.png" alt=""></label>

                                    <div id="collapsedefult" class="collapse one" data-parent="#accordion">
                                        <div class="card-body1">
                                           <p>Pay via PayPal; you can pay with your credit card if you don’t have a PayPal account.</p> 
                                        </div>
                                    </div>
                                </div>
                                <div class="order_button">
                                    <button  type="submit">Proceed to PayPal</button> 
                                </div>    
                            </div>  -->
                                 
<!--                     </div>
</div>
 -->
                                 <!-- <div class="order_button">
                                     <button type="submit" class="btn fw-600 btn-primary">{{ translate('Continue to Payment')}}</button> 
                                </div>  -->
