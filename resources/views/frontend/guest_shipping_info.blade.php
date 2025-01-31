@extends('frontend.layouts.app')

@section('content')
<?php 
 $user_id = session()->get('temp_user_id');
?>

<section class="mb-4 gry-bg">
    <div class="container">
        <div class="row cols-xs-space cols-sm-space cols-md-space">
            <div class="col-xxl-8 col-xl-10 mx-auto">
                <form class="form-default" data-toggle="validator" action="{{ route('guestcheckout.store_shipping_infostore') }}" role="form" method="POST">
                    @csrf                            
                    <div class="row gutters-5">                              
                        <div class="col-md-12 mx-auto mb-3 border p-3 rounded mb-3 c-pointer text-center bg-white h-100 flex-column justify-content-center">
                        <?php 
                            $shipping_type_payment_normal = 1;
                            $shipping_type_payment_priority = 0;
                            if(isset($_GET['shipping_type']) && $_GET['shipping_type'] == "priority"){
                                $shipping_type_payment_normal = 0;
                               $shipping_type_payment_priority = 1;
                            }
                        ?>
                            <div class="col-md-4 float-left">
                                <label class="aiz-megabox d-block bg-white mb-0">
                                    <input type="radio"  class="shipping_type_payment" name="shipping_type_payment" value="normal" <?php echo ($shipping_type_payment_normal == 1) ? 'checked=checked' : ''; ?>>
                                    <span class="d-flex p-3 aiz-megabox-elem">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 fw-600">Standard Shipping</span>
                                    </span>
                                            <label style="padding-right: 100px; font-size: small; color: red;">*Taking 2-4 days</label>
                                </label>
                            </div>
                            <div class="col-md-4 float-left">
                            <label class="aiz-megabox d-block bg-white mb-0">
                                <input type="radio" class="shipping_type_payment"  name="shipping_type_payment" value="priority"  <?php echo ($shipping_type_payment_priority == 1) ? 'checked=checked' : ''; ?> >
                                <span class="d-flex p-3 aiz-megabox-elem">
                                    <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                    <span class="flex-grow-1 pl-3 fw-600">Priority Shipping</span>
                                </span>
                                        <label style="padding-right: 100px; font-size: small; color: red;">*Taking 1-2 days</label>
                            </label>
                            </div>


                        </div>
                    </div>
                        <div class="shadow-sm bg-white p-4 rounded mb-4">
                            <div class="row gutters-5">
                              
                                <div class="col-md-12 mx-auto mb-3 border p-3 rounded mb-3 c-pointer text-center bg-white h-100 flex-column justify-content-center">
                                    <div class="modal-body">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">{{ translate('Shipping Address') }}</h5>
                                        </div>
                    <div class="p-3">
                        <div class="row">
                            <div class="col-md-6 guest_address">
                                <label>Email</label>
                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} mb-3" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email" id="email" autocomplete="off">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6 guest_address">
                                <label>{{ translate('Phone')}}</label>
                                <input type="text" class="form-control mb-3" placeholder="{{ translate('')}}" name="phone" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 guest_address">                                
                                <label>{{ translate('Address')}}</label>
                                <textarea class="form-control mb-3" placeholder="{{ translate('Your Address')}}" rows="1" name="address" required></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 guest_address">
                                <label>{{ translate('Country')}}</label>
                                <div class="mb-3">
                                    <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                                        <option value="">{{ translate('Select your country') }}</option>
                                        @foreach (\App\Models\Country::where('status', 1)->get() as $key => $country)
                                            <option value="{{ $country->id }}" country_code ="{{ $country->code }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                            
                            <div class="col-md-6 guest_address">
                                <label>{{ translate('State')}}</label>
                                <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="state_id" required>

                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 guest_address">                                
                                <label>{{ translate('City')}}</label>
                                <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="city_id" required>

                                </select>
                            </div>
                            <div class="col-md-6 guest_address">
                                <label>{{ translate('ZIP code')}}</label>
                                <input type="text" class="form-control mb-3 "  placeholder="{{ translate('Your ZIP Code')}}" name="postal_code" value="" required>
                            </div>

                        </div>
                        <input type="hidden" name="" value="<?php echo $user_id?>">
                        <input type="hidden" id="selected_country_code" value="">
                        <!-- <input type="hidden" id="hidden_tax" name="hidden_tax"> -->
                        <input type="hidden" id="cart_id" name="" value="<?php echo $carts[0]->id; ?>">
                        <!-- <div class="form-group text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div> -->
                    </div>
                </div>

                </div>
            </div> 
        </div>

                    <!-- Delivery info -->

                    @csrf
                    @php
                        $admin_products = array();
                        $seller_products = array();
                        foreach ($carts as $key => $cartItem){
                            $product = \App\Models\Product::find($cartItem['product_id']);

                            if($product->added_by == 'admin'){
                                array_push($admin_products, $cartItem['product_id']);
                            }
                            else{
                                $product_ids = array();
                                if(isset($seller_products[$product->user_id])){
                                    $product_ids = $seller_products[$product->user_id];
                                }
                                array_push($product_ids, $cartItem['product_id']);
                                $seller_products[$product->user_id] = $product_ids;
                            }
                        }
                        
                        $pickup_point_list = array();
                        if (get_setting('pickup_point') == 1) {
                            $pickup_point_list = \App\Models\PickupPoint::where('pick_up_status',1)->get();
                        }
                    @endphp

                    @if (!empty($admin_products))
                    <div class="card mb-3 shadow-sm border-0 rounded">
                        <div class="card-header p-3">
                            <h5 class="fs-16 fw-600 mb-0">{{ get_setting('site_name') }} {{ translate('Products') }}</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach ($admin_products as $key => $productId)
                                @php
                                    $product = \App\Models\Product::find($productId);
                                @endphp
                                <li class="list-group-item">
                                    <div class="checkout-flex">
                                        <span class="mr-2">
                                            <img
                                                src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                class="img-fit size-60px rounded"
                                                alt="{{  $product->getTranslation('name')  }}"
                                            >
                                        </span>
                                        <span class="fs-14 opacity-60 checkout_item ">{{ $product->getTranslation('name') }}</span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            
                            <div class="row border-top pt-3">
                                <div class="col-md-6">
                                    <h6 class="fs-15 fw-600">{{ translate('Choose Delivery Type') }}</h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="row gutters-5">
                                        <div class="col-6">
                                            <label class="aiz-megabox d-block bg-white mb-0">
                                                <input
                                                    type="radio"
                                                    name="shipping_type_{{ \App\Models\User::where('user_type', 'admin')->first()->id }}"
                                                    value="home_delivery"
                                                    onchange="show_pickup_point(this)"
                                                    data-target=".pickup_point_id_admin"
                                                    checked
                                                >
                                                <span class="d-flex p-3 aiz-megabox-elem">
                                                    <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                    <span class="flex-grow-1 pl-3 fw-600">{{  translate('Home Delivery') }}</span>
                                                </span>
                                            </label>
                                        </div>
                                        @if ($pickup_point_list)
                                        <div class="col-6">
                                            <label class="aiz-megabox d-block bg-white mb-0">
                                                <input
                                                    type="radio"
                                                    name="shipping_type_{{ \App\Models\User::where('user_type', 'admin')->first()->id }}"
                                                    value="pickup_point"
                                                    onchange="show_pickup_point(this)"
                                                    data-target=".pickup_point_id_admin"
                                                >
                                                <span class="d-flex p-3 aiz-megabox-elem">
                                                    <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                    <span class="flex-grow-1 pl-3 fw-600">{{  translate('Local Pickup') }}</span>
                                                </span>
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                    @if ($pickup_point_list)
                                    <div class="mt-4 pickup_point_id_admin d-none">
                                        <select
                                            class="form-control aiz-selectpicker"
                                            name="pickup_point_id_{{ \App\Models\User::where('user_type', 'admin')->first()->id }}"
                                            data-live-search="true"
                                        >
                                                <option>{{ translate('Select your nearest pickup point')}}</option>
                                            @foreach ($pickup_point_list as $key => $pick_up_point)
                                                <option
                                                    value="{{ $pick_up_point->id }}"
                                                    data-content="<span class='d-block'>
                                                                    <span class='d-block fs-16 fw-600 mb-2'>{{ $pick_up_point->getTranslation('name') }}</span>
                                                                    <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ $pick_up_point->getTranslation('address') }}</span>
                                                                    <span class='d-block opacity-50 fs-12'><i class='las la-phone'></i>{{ $pick_up_point->phone }}</span>
                                                                </span>"
                                                >
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    @endif
                    @if (!empty($seller_products))
                        @foreach ($seller_products as $key => $seller_product)
                            <div class="card mb-3 shadow-sm border-0 rounded">
                                <div class="card-header p-3">
                                    <h5 class="fs-16 fw-600 mb-0">{{ \App\Models\Shop::where('user_id', $key)->first()->name }} {{ translate('Products') }}</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach ($seller_product as $cartItem)
                                        @php
                                            $product = \App\Models\Product::find($cartItem);
                                        @endphp
                                        <li class="list-group-item">
                                            <div class="d-flex">
                                                <span class="mr-2 d-flex-cart">
                                                    <img
                                                        src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                        class="img-fit size-60px rounded"
                                                        alt="{{  $product->getTranslation('name')  }}"
                                                    >
                                                </span>
                                                <span class="fs-14 opacity-60">{{ $product->getTranslation('name') }}</span>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                    
                                    <div class="row border-top pt-3">
                                        <div class="col-md-6">
                                            <h6 class="fs-15 fw-600">{{ translate('Choose Delivery Type') }}</h6>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row gutters-5">
                                                <div class="col-6">
                                                    <label class="aiz-megabox d-block bg-white mb-0">
                                                        <input
                                                            type="radio"
                                                            name="shipping_type_{{ $key }}"
                                                            value="home_delivery"
                                                            onchange="show_pickup_point(this)"
                                                            data-target=".pickup_point_id_{{ $key }}"
                                                            checked
                                                        >
                                                        <span class="d-flex p-3 aiz-megabox-elem">
                                                            <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                            <span class="flex-grow-1 pl-3 fw-600">{{  translate('Home Delivery') }}</span>
                                                        </span>
                                                    </label>
                                                </div>
                                                @if ($pickup_point_list)
                                                    <div class="col-6">
                                                        <label class="aiz-megabox d-block bg-white mb-0">
                                                            <input
                                                                type="radio"
                                                                name="shipping_type_{{ $key }}"
                                                                value="pickup_point"
                                                                onchange="show_pickup_point(this)"
                                                                data-target=".pickup_point_id_{{ $key }}"
                                                            >
                                                            <span class="d-flex p-3 aiz-megabox-elem">
                                                                <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                <span class="flex-grow-1 pl-3 fw-600">{{  translate('Local Pickup') }}</span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                            </div>
                                            @if ($pickup_point_list)
                                                <div class="mt-4 pickup_point_id_{{ $key }} d-none">
                                                    <select
                                                        class="form-control aiz-selectpicker"
                                                        name="pickup_point_id_{{ $key }}"
                                                        data-live-search="true"
                                                    >
                                                        <option>{{ translate('Select your nearest pickup point')}}</option>
                                                            @foreach ($pickup_point_list as $key => $pick_up_point)
                                                            <option
                                                                value="{{ $pick_up_point->id }}"
                                                                data-content="<span class='d-block'>
                                                                                <span class='d-block fs-16 fw-600 mb-2'>{{ $pick_up_point->getTranslation('name') }}</span>
                                                                                <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ $pick_up_point->getTranslation('address') }}</span>
                                                                                <span class='d-block opacity-50 fs-12'><i class='las la-phone'></i>{{ $pick_up_point->phone }}</span>
                                                                            </span>"
                                                            >
                                                            </option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <div class="pt-4 d-flex justify-content-between align-items-center">
                        <a href="{{ route('home') }}" >
                            <i class="la la-angle-left"></i>
                            {{ translate('Return to shop')}}
                        </a>
                        <button type="submit" class="btn fw-600 btn-primary">{{ translate('Continue to Payment')}}</button>
                    </div>
                </form>
            </div>
                    <div class="col-lg-4 mt-4 mt-lg-0 checkout_form" id="cart_summary">
                        @include('frontend.partials.cart_summary')
                    </div>
        </div>
    </div>
<!--         <div class="container">
        <div class="row">
            <div class="col-xxl-8 col-xl-10 mx-auto">
                <form class="form-default" action="{{ route('checkout.store_delivery_info') }}" role="form" method="POST">
                    
                </form>
            </div>
        </div>
    </div> -->
</section>


@endsection

@section('script')
    <script type="text/javascript">
        function display_option(key){

        }
        function show_pickup_point(el) {
            var value = $(el).val();
            var target = $(el).data('target');
            if(value == 'home_delivery'){
                if(!$(target).hasClass('d-none')){
                    $(target).addClass('d-none');
                }
            }else{
                $(target).removeClass('d-none');
            }
        }

        $(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
            // Get the selected option value
            var selectedValue = $('select[name="country_id"]').val();

            // Get the "country_code" attribute value of the selected option
            var countryCode = $('select[name="country_id"] option[value="' + selectedValue + '"]').attr('country_code');
            $("#selected_country_code").val(countryCode);
            get_states(country_id);
        });



        $(document).on('change', '[name=state_id]', function() {
            // Get the selected state_id
            var inputValue = 'normal'; // Default value if no checkbox is checked
            $.each($('.shipping_type_payment'),function(){
                 if ($(this).is(':checked')) {
                    // Get the value of the input field
                    inputValue = $(this).val(); // Assign the value here
                    console.log(inputValue);
                }
            })
            
        //   alert('inputValue==>'+inputValue);
            var state_id = $(this).val();
            var country_code_s = $('#selected_country_code').val();
            // Get the selected option
            var selectedOption = $(this).find('option:selected');
                // Get the value of the shipping attribute
            if(inputValue == 'priority'){
                var shippingValue = selectedOption.attr('express_shipping');
                //alert(shippingValue);
           }else{
                var shippingValue = selectedOption.attr('shipping');
                //alert(shippingValue);
           }           
            var guest_subtotal_amount = $("#guest_subtotal_amount").text();            
            var amount = guest_subtotal_amount.replace(/[$,]/g, "");
            var min_shipping = 75;

            if(amount >= min_shipping){
                guest_shipping_amount = 0;
            }else if(country_code_s != 'US'){
                guest_shipping_amount = 20;
            }else{
                var guest_shipping_amount = parseFloat(shippingValue);
            }

            $("#guest_shipping").html('$'+guest_shipping_amount.toFixed(2));
            get_city(state_id);
        });


        $(document).on('change', '[name=postal_code]', function() {
            var postal_code = $(this).val();            
            get_TaxRate(postal_code);
        });

        function get_TaxRate(postal_code) {
            var r = $("#guest_subtotal_amount").text();
            var v = $("#guest_shipping").text();
            var ship = v.replace(/[$,]/g, "");
            //alert(ship);
            const getAmount = (r) => {
              return parseFloat(r.replace(/[$,]/g, ""));            
            }
            $('[name="postal_code"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('guestcheckout.get-tax-rate')}}",
                type: 'POST',                
                dataType : 'json',
                data: {
                    postal_code  : postal_code
                },
                success: function (response) {
                    var obj = response;
                    AIZ.plugins.bootstrapSelect('refresh');
                    var guest_tax_amount = parseFloat(getAmount(r))*parseFloat(obj.tax_rate_value);
                    var guest_total = parseFloat(guest_tax_amount)+parseFloat(getAmount(r))+parseFloat(ship);
                    $("#guest_tax").html('$'+guest_tax_amount.toFixed(2));
                    $("#guest_total").html('$'+guest_total.toFixed(2));
                    var cart_id = $("#cart_id").val();

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{route('guestcheckout.save-guest-total')}}",
                        type: 'POST',                
                        dataType : 'json',
                        data: {
                            cart_id : cart_id,guest_tax_amount : guest_tax_amount, ship : ship

                        },
                        success: function (response) {  

                    }
                });
                   
                }
            });
        }

        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('guestcheckout.guest-get-state')}}",
                type: 'POST',
                data: {
                    country_id  : country_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="state_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        function get_city(state_id) {
            $('[name="city"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('guestcheckout.guest-get-city')}}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

    </script>
@endsection

