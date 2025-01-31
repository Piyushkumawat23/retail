@extends('frontend.layouts.app')

@section('content')

    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <h3>Shopping Cart</h3>
                        <ul>
                            <li><a href="{{ route('home') }}">home</a></li>
                            <li>></li>
                            <li>Shopping Cart</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>         
    </div>
    <!--breadcrumbs area end-->
    
    <!--shopping cart area start -->
    <div class="shopping_cart_area"id="cart-summary">
        <div class="container"> 
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
                                           <td class="product_remove"><a href="javascript:void(0)" onclick="removeFromCartView(event, {{ $cartItem['id'] }})"><i class="fa fa-trash-o"></i></a></td>

                                            <td class="product_thumb"><img loading="lazy" src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name')  }}"></td>

                                            <td class="product_name">{{ $product_name_with_choice }}</td>
                                            <td class="product-price">{{ single_price($cartItem['price']) }}</td>
                                            <td class="product-price">{{ single_price($cartItem['tax']) }}</td>
                                            <td class="product_quantity">
                                                <label>Quantity</label>
                                                @if($cartItem['digital'] != 1 && $product->auction_product == 0)
                                                    <div class="row no-gutters align-items-center aiz-plus-minus mr-2 ml-0">
                                                        
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
                 </div>

                 <!--coupon code area start-->
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
                                        I agree to &nbsp;<a href="{{ route('terms') }}">Terms & Conditions</a>
                                    </label>
                                   </div>
                                   <div class="checkout_btn">
                                        @if(Auth::check())
                                           <a href="{{ route('checkout.shipping_info') }}" style="display: none;" id="checkout">{{translate('Checkout')}}</a>
                                           <a href="javascript:void(0)" style="" id="checkout-required">{{translate('Checkout')}}</a>
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
        </div>     
    </div>
    @else
    <div class="row">
        <div class="mx-auto">
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
    
@endsection
@section('modal')
    <div class="modal fade" id="login-modal">
        <div class="modal-dialog modal-dialog-zoom">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Login')}}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                            @csrf
                            @if (addon_is_activated('otp_system') && env("DEMO_MODE") != "On")
                                <div class="form-group phone-form-group mb-1">
                                    <input type="tel" id="phone-code" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="" name="phone" autocomplete="off">
                                </div>

                                <input type="hidden" name="country_code" value="">

                                <div class="form-group email-form-group mb-1 d-none">
                                    <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email" id="email" autocomplete="off">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group text-right">
                                    <button class="btn btn-link p-0 opacity-50 text-reset" type="button" onclick="toggleEmailPhone(this)">{{ translate('Use Email Instead') }}</button>
                                </div>
                            @else
                                <div class="form-group">
                                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email" id="email" autocomplete="off">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div class="form-group">
                                <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ translate('Password')}}" name="password" id="password">
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

                    </div>
                    <div class="text-center mb-3">
                        <p class="text-muted mb-0">{{ translate('Dont have an account?')}}</p>
                        <a href="{{ route('user.registration') }}">{{ translate('Register Now')}}</a>
                    </div>
                    @if(get_setting('google_login') == 1 || get_setting('facebook_login') == 1 || get_setting('twitter_login') == 1)
                        <div class="separator mb-3">
                            <span class="bg-white px-3 opacity-60">{{ translate('Or Login With')}}</span>
                        </div>
                        <ul class="list-inline social colored text-center mb-3">
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
</div>
</div>
</div>
</div>
</div>
@endsection
@section('script')
    <script type="text/javascript">
        function removeFromCartView(e, key){
            e.preventDefault();
            removeFromCart(key);
        }

        function updateQuantity(key, element){
            $.post('{{ route('cart.updateQuantity') }}', {
                _token   :  AIZ.data.csrf,
                id       :  key,
                quantity :  element.value
            }, function(data){
                updateNavCart(data.nav_cart_view,data.cart_count);
                $('#cart-summary').html(data.cart_view);
            });
        }
        
        function showCheckoutModal(){
            $('#login-modal').modal();
        }

        // Country Code
        var isPhoneShown = true,
            countryData = window.intlTelInputGlobals.getCountryData(),
            input = document.querySelector("#phone-code");

        for (var i = 0; i < countryData.length; i++) {
            var country = countryData[i];
            if(country.iso2 == 'bd'){
                country.dialCode = '88';
            }
        }

        var iti = intlTelInput(input, {
            separateDialCode: true,
            utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
            onlyCountries: @php echo json_encode(\App\Models\Country::where('status', 1)->pluck('code')->toArray()) @endphp,
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                if(selectedCountryData.iso2 == 'bd'){
                    return "01xxxxxxxxx";
                }
                return selectedCountryPlaceholder;
            }
        });

        var country = iti.getSelectedCountryData();
        $('input[name=country_code]').val(country.dialCode);

        input.addEventListener("countrychange", function(e) {
            // var currentMask = e.currentTarget.placeholder;

            var country = iti.getSelectedCountryData();
            $('input[name=country_code]').val(country.dialCode);

        });

        function toggleEmailPhone(el){
            if(isPhoneShown){
                $('.phone-form-group').addClass('d-none');
                $('.email-form-group').removeClass('d-none');
                $('input[name=phone]').val(null);
                isPhoneShown = false;
                $(el).html('{{ translate('Use Phone Instead') }}');
            }
            else{
                $('.phone-form-group').removeClass('d-none');
                $('.email-form-group').addClass('d-none');
                $('input[name=email]').val(null);
                isPhoneShown = true;
                $(el).html('{{ translate('Use Email Instead') }}');
            }
        }
    </script>
@endsection
