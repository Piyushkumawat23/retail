<!DOCTYPE html>
 <?php

    //$actual_link = "$_SERVER[REQUEST_URI]";
    //$rest_country = restrictAccessByCountry();
    //echo '<pre>';print_r($actual_link);die;
?>
@section('script')
    <script  type="text/javascript" >
            
        /*var res_country = '<?php //echo $rest_country; ?>';
        if(res_country == 1){
            window.location.href = "{{ route('restricted.country') }}";
        }*/
    </script>
    <?php 
   /*if($rest_country == 1){
        die;
    }*/
    ?>
@endsection
@if(\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif
<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <title>@yield('meta_title', get_setting('site_motto').' | '.get_setting('website_name'))</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="description" content="@yield('meta_description', get_setting('meta_description') )">

    <meta name="keywords" content="@yield('meta_keywords', get_setting('meta_keywords') )">
    <meta name="facebook-domain-verification" content="164qvk1vmhxz8mfd0wv1uc26dmgrt1" />
    <meta name="p:domain_verify" content="52d79714abd703a3ef94c78312e5dd78"/>
    <?php 
    if(Route::currentRouteName() == 'home'){    ?>
    <link rel="canonical" href="https://bautlr.com/">
    <?php } ?>

    @yield('meta')

    @if(!isset($detailedProduct) && !isset($customer_product) && !isset($shop) && !isset($page) && !isset($blog))
        <!-- Schema.org markup for Google+ -->
        <meta itemprop="name" content="{{ get_setting('meta_title') }}">
        <meta itemprop="description" content="{{ get_setting('meta_description') }}">
        <meta itemprop="image" content="{{ uploaded_asset(get_setting('meta_image')) }}">

        <!-- Twitter Card data -->
        <meta name="twitter:card" content="product">
        <meta name="twitter:site" content="@publisher_handle">
        <meta name="twitter:title" content="{{ get_setting('meta_title') }}">
        <meta name="twitter:description" content="{{ get_setting('meta_description') }}">
        <meta name="twitter:creator" content="@author_handle">
        <meta name="twitter:image" content="{{ uploaded_asset(get_setting('meta_image')) }}">

        <!-- Open Graph data -->
        <meta property="og:title" content="{{ get_setting('meta_title') }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ route('home') }}">
        <meta property="og:image" content="{{ uploaded_asset(get_setting('meta_image')) }}">
        <meta property="og:description" content="{{ get_setting('meta_description') }}">
        <meta property="og:site_name" content="{{ env('APP_NAME') }}">
        <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
    @endif

    <!-- Favicon -->
    <link rel="icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/development.css') }}?v=<?php echo time(); ?>">
    <script  src="{{ static_asset('assets/js/vendor/modernizr-3.7.1.min.js') }}"></script>
    <script  src="{{ static_asset('assets/js/vendor/jquery-3.4.1.min.js') }}"></script>

   <!--  Schema Tag- -->
    <script  type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Bautlr",
      "alternateName": "Best Online Jewelry Store",
      "url": "https://bautlr.com/",
      "logo": "https://bautlr.com/public/uploads/all/4MN2v2ndHZQUf4PHhdc6xHasNcbnj9xGtohmk9ar.webp",
      "sameAs": "https://www.instagram.com/bautlr_official"
    }
    </script>

    <script >
        var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: '{!! translate('Nothing selected', null, true) !!}',
            nothing_found: '{!! translate('Nothing found', null, true) !!}',
            choose_file: '{{ translate('Choose file') }}',
            file_selected: '{{ translate('File selected') }}',
            files_selected: '{{ translate('Files selected') }}',
            add_more_files: '{{ translate('Add more files') }}',
            adding_more_files: '{{ translate('Adding more files') }}',
            drop_files_here_paste_or: '{{ translate('Drop files here, paste or') }}',
            browse: '{{ translate('Browse') }}',
            upload_complete: '{{ translate('Upload complete') }}',
            upload_paused: '{{ translate('Upload paused') }}',
            resume_upload: '{{ translate('Resume upload') }}',
            pause_upload: '{{ translate('Pause upload') }}',
            retry_upload: '{{ translate('Retry upload') }}',
            cancel_upload: '{{ translate('Cancel upload') }}',
            uploading: '{{ translate('Uploading') }}',
            processing: '{{ translate('Processing') }}',
            complete: '{{ translate('Complete') }}',
            file: '{{ translate('File') }}',
            files: '{{ translate('Files') }}',
        }
    </script>

    <style>
        body{
            font-family: Georgia,serif;
            /*font-family: "Tenor Sans", sans-serif;*/
           /* font-family: Rubik,Arial,Helvetica,sans-serif;*/
            font-weight: 600;
        }
        :root{
            --primary: {{ get_setting('base_color', '#e62d04') }};
            --hov-primary: {{ get_setting('base_hov_color', '#c52907') }};
            --soft-primary: {{ hex2rgba(get_setting('base_color','#e62d04'),.15) }};
			--custom-color: #c09578;
        }

        #map{
            width: 100%;
            height: 250px;
        }
        #edit_map{
            width: 100%;
            height: 250px;
        }

        .pac-container { z-index: 100000; }
    </style>

@if (get_setting('google_analytics') == 1)
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script  async src="https://www.googletagmanager.com/gtag/js?id={{ env('TRACKING_ID') }}"></script>

    <script >
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        
        gtag('config', '{{ env('TRACKING_ID') }}');
    </script>
@endif

@if (get_setting('facebook_pixel') == 1)
    <!-- Facebook Pixel Code -->
    <script >
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ env('FACEBOOK_PIXEL_ID') }}');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ env('FACEBOOK_PIXEL_ID') }}&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
@endif

@php
    echo get_setting('header_script');
@endphp

</head>
<body>
    <!-- aiz-main-wrapper -->
    <div class="aiz-main-wrapper d-flex flex-column">
        
        <!-- Header -->
        @include('frontend.inc.nav')
         @if(Route::currentRouteName() != 'home')
         <div class="new-header" style="<?php echo (Route::currentRouteName() == 'product') ? 'margin-bottom: 75px;'  : 'margin-bottom: 75px;'?>"></div>
       @endif
        
        @yield('content')
        @yield('modal')
        @include('frontend.inc.footer')

    </div>

    
    
    @if (get_setting('show_cookies_agreement') == 'on')
        <div class="aiz-cookie-alert shadow-xl">
            <div class="p-3 bg-dark rounded">
                <div class="text-white mb-3">
                    @php
                        echo get_setting('cookies_agreement_text');
                    @endphp
                </div>
                <button class="btn btn-primary aiz-cookie-accept">
                    {{ translate('Ok. I Understood') }}
                </button>
            </div>
        </div>
    @endif

    @if (get_setting('show_website_popup') == 'on')
        <div class="modal website-popup removable-session d-none" data-key="website-popup" data-value="removed">
            <div class="absolute-full bg-black opacity-60"></div>
            <div class="modal-dialog modal-dialog-centered modal-dialog-zoom modal-md">
                <div class="modal-content position-relative border-0 rounded-0">
                    <div class="aiz-editor-data">
                        {!! get_setting('website_popup_content') !!}
                    </div>
                    @if (get_setting('show_subscribe_form') == 'on')
                        <div class="pb-5 pt-4 px-5">
                            <form class="" method="POST" action="{{ route('subscribers.store') }}">
                                @csrf
                                <div class="form-group mb-0">
                                    <input type="email" class="form-control" placeholder="{{ translate('Your Email Address') }}" name="email" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block mt-3">
                                    {{ translate('Subscribe Now') }}
                                </button>
                            </form>
                        </div>
                    @endif
                    <button class="absolute-top-right bg-white shadow-lg btn btn-circle btn-icon mr-n3 mt-n3 set-session" data-key="website-popup" data-value="removed" data-toggle="remove-parent" data-parent=".website-popup">
                        <i class="la la-close fs-20"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif


   <!--  <script  src="{{ static_asset('assets/js/visualization.js') }}"></script> -->

<!-- Scripts at the end -->
    <script  src="{{ static_asset('assets/js/vendors.js') }}" ></script>
    <script  src="{{ static_asset('assets/js/aiz-core.js') }}" ></script>    
    <script  src="{{ static_asset('assets/js/owl.carousel.main.js') }}"></script>
    <script  src="{{ static_asset('assets/js/jquery.elevatezoom.js') }}" ></script>
    <script  src="{{ static_asset('assets/js/popper.js') }}" ></script>

    <?php 
    if (isset($_SERVER['REDIRECT_URL'])) {
        $isHomePage = ($_SERVER['REDIRECT_URL'] == '/' || $_SERVER['REDIRECT_URL'] == '/home');
        $isPurchaseHistory = (strpos($_SERVER['REDIRECT_URL'], 'purchase_history/details') !== false);
        
        if (!$isHomePage && !$isPurchaseHistory) {
    ?>
        <script  src="{{ static_asset('assets/js/bootstrap.min.js') }}" ></script>   
    <?php 
        }
    }
    ?>    

    <script  src="{{ static_asset('assets/js/scrollup.js') }}" ></script>
    <script  src="{{ static_asset('assets/js/ajax.chimp.js') }}" ></script>
    <script  src="{{ static_asset('assets/js/jquery.ui.js') }}" ></script>
    <script  src="{{ static_asset('assets/js/jquery.nice.select.js') }}"></script>
    <script  src="{{ static_asset('assets/js/plugins.js') }}" ></script>
    {{-- <script  src="{{ static_asset('assets/js/jquery.elevatezoom.js') }}" ></script> --}}
    <script  src="{{ static_asset('assets/js/imagesloaded.js') }}" ></script>
    <script  src="{{ static_asset('assets/js/isotope.main.js') }}" ></script>
    <script  src="{{ static_asset('assets/js/jqquery.ripples.js') }}" ></script>
    <script  src="{{ static_asset('assets/js/jquery.cookie.js') }}" ></script>
    <script  src="{{ static_asset('assets/js/bpopup.js') }}" ></script>

    <!-- Main JS -->
    <script  src="{{ static_asset('assets/js/main.js') }}" ></script>




    @if (get_setting('facebook_chat') == 1)
        <script  type="text/javascript">
            window.fbAsyncInit = function() {
                FB.init({
                  xfbml            : true,
                  version          : 'v3.3'
                });
              };

              (function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <div id="fb-root"></div>
        <!-- Your customer chat code -->
        <div class="fb-customerchat"
          attribution=setup_tool
          page_id="{{ env('FACEBOOK_PAGE_ID') }}">
        </div>
    @endif

    <script>
        @foreach (session('flash_notification', collect())->toArray() as $message)
            AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
        @endforeach
    </script>


    <script >
        $(document).ready(function() {
            $('.primary_img').hover(function() {
                $(this).find('.primary-image').hide();
				var img_path = $(this).find('.secondary-image').attr('img_src');
                $(this).find('.secondary-image').attr('src',img_path);
				
                $(this).find('.secondary-image').show();
            }, function() {
                $(this).find('.primary-image').show();
				$(this).find('.secondary-image').attr('src','<?php //echo static_asset('assets/img/placeholder.webp'); ?>');
                $(this).find('.secondary-image').hide();
            });
        });

        document.querySelectorAll('.primary_img').forEach(function(item) {
        item.addEventListener('mouseenter', function() {
            let secondaryElement = this.querySelector('.secondary-video') || this.querySelector('.secondary-image');
            if (secondaryElement) {
                secondaryElement.style.display = 'inline-block';
            }
        });
        item.addEventListener('mouseleave', function() {
            let secondaryElement = this.querySelector('.secondary-video') || this.querySelector('.secondary-image');
            if (secondaryElement) {
                secondaryElement.style.display = 'none';
            }
        });
    });

        var out_of_stock_min = '{{get_setting('out_stock_minimum_order')}}';
        $(document).ready(function() {
            //console.log('hello');
            $('.category-nav-element').each(function(i, el) {
                $(el).on('mouseover', function(){
                    if(!$(el).find('.sub-cat-menu').hasClass('loaded')){
                        $.post('{{ route('category.elements') }}', {_token: AIZ.data.csrf, id:$(el).data('id')}, function(data){
                            $(el).find('.sub-cat-menu').addClass('loaded').html(data);
                        });
                    }
                });
            });
        });

        $('#search').on('keyup', function(){
           
            search();
        });

        $('#search').on('focus', function(){
            search();
        });

        function search(){
            var searchKey = $('#search').val();
            if(searchKey.length > 0){
                $('body').addClass("typed-search-box-shown");

                $('.typed-search-box').removeClass('d-none');
                $('.search-preloader').removeClass('d-none');
                $.post('{{ route('search.ajax') }}', { _token: AIZ.data.csrf, search:searchKey}, function(data){
                    if(data == '0'){
                        // $('.typed-search-box').addClass('d-none');
                        $('#search-content').html(null);
                        $('.typed-search-box .search-nothing').removeClass('d-none').html('Sorry, nothing found for <strong>"'+searchKey+'"</strong>');
                        $('.search-preloader').addClass('d-none');

                    }
                    else{
                        $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                        $('#search-content').html(data);
                        $('.search-preloader').addClass('d-none');
                    }
                });
            }
            else {
                $('.typed-search-box').addClass('d-none');
                $('body').removeClass("typed-search-box-shown");
            }
        }

        function updateNavCart(view,count){
            $('.cart-count').html(count);
            $('#cart_items').html(view);
        }

        function removeFromCart(key){
            $.post('{{ route('cart.removeFromCart') }}', {
                _token  : AIZ.data.csrf,
                id      :  key
            }, function(data){
                updateNavCart(data.nav_cart_view,data.cart_count);
                $('#cart-summary').html(data.cart_view);
                AIZ.plugins.notify('success', "{{ translate('Item has been removed from cart') }}");
                $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())-1);
                
                // Refresh the page to reflect all updates
                location.reload();
            });
        }

        function addToCompare(id){
            $.post('{{ route('compare.addToCompare') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                $('#compare').html(data);
                AIZ.plugins.notify('success', "{{ translate('Item has been added to compare list') }}");
                $('#compare_items_sidenav').html(parseInt($('#compare_items_sidenav').html())+1);
            });
        }

        function addToWishList(id){
            @if (Auth::check() && (Auth::user()->user_type == 'customer' || Auth::user()->user_type == 'seller'))
                $.post('{{ route('wishlists.store') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                    if(data != 0){
                        $('#wishlist').html(data);
                        AIZ.plugins.notify('success', "{{ translate('Item has been added to wishlist') }}");
                    }
                    else{
                        AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
                    }
                });
            @else
                AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
            @endif
        }

        function showAddToCartModal(id){
            //alert('hello')
            if(!$('#modal-size').hasClass('modal-lg')){
                $('#modal-size').addClass('modal-lg');
            }
            $('#addToCart-modal-body').html(null);
            $('#addToCart').modal();
            $('.c-preloader').show();

            $.post('{{ route('cart.showCartModal') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                
                $('.c-preloader').hide();
                $('#addToCart-modal-body').html(data);

                //console.log('hello');
                AIZ.plugins.slickCarousel();
                AIZ.plugins.zoom();
                AIZ.extra.plusMinus();
                getVariantPrice();
                $( "#add-to-cart" ).trigger( "click" );
            });
        }
        getVariantPrice();
        $('#option-choice-form input').on('change', function(){
            getVariantPrice();
        });

        function getVariantPrice(){
            
            var in_stock = '{{ get_setting('in_stock_text') }}';
            var out_stock = '{{ get_setting('out_stock_text') }}';
            
            if($('#option-choice-form input[name=quantity]').val() > 0 && checkAddToCartValidity()){
                //console.log('hello');
                $.ajax({
                   type:"POST",
                   url: '{{ route('products.variant_price') }}',
                   data: $('#option-choice-form').serializeArray(),
                   success: function(data){ 

                        $('.product-gallery-thumb .carousel-box').each(function (i) {
                            if($(this).data('variation') && data.variation == $(this).data('variation')){
                                $('.product-gallery-thumb').slick('slickGoTo', i);
                            }
                        })

                        $('#option-choice-form #chosen_price_div').removeClass('d-none');
                        $('#option-choice-form #chosen_price_div #chosen_price').html(data.price);
                        $('#available-quantity').html(data.quantity);
                        if(data.quantity > 0){
                            $('#in-stock-quantity').html(in_stock)
                        }else{
                            $('#in-stock-quantity').html(out_stock)
                        }
                        $('.input-number').prop('max', data.max_limit);
                        if(parseInt(data.in_stock) == 0 && data.digital  == 0){
                           $('.buy-now').addClass('d-none');
                           $('.add-to-cart').addClass('d-none');
                           $('.out-of-stock').removeClass('d-none');
                           $('.out-stock-order').removeClass('d-none');
                        }
                        else{
                           $('.buy-now').removeClass('d-none');
                           $('.add-to-cart').removeClass('d-none');
                           $('.out-of-stock').addClass('d-none');
                           $('.out-stock-order').addClass('d-none');   
                        }

                        AIZ.extra.plusMinus();
                   }
               });
            }
        }

        function checkAddToCartValidity(){
            var names = {};
            $('#option-choice-form input:radio').each(function() { // find unique names
                names[$(this).attr('name')] = true;
            });
            var count = 0;
            $.each(names, function() { // then count them
                count++;
            });

            if($('#option-choice-form input:radio:checked').length == count){
                return true;
            }

            return false;
        }
        
        $('.shipping_type_payment').click(function(){
            var shipping_type_payment = $(this).val();
            <?php if ($user_id = session()->get('temp_user_id')){ ?>

                var inputValue = 'normal'; // Default value if no checkbox is checked
                if ($(this).is(':checked')) {
                    // Get the value of the input field
                    inputValue = $(this).val(); // Assign the value here
                    console.log(inputValue);
                }

                var country_code_s = $('#selected_country_code').val();
                var selectedOption = $('[name=state_id]').find('option:selected');
                if(inputValue == 'priority'){
                    var shippingValue = selectedOption.attr('express_shipping');
                   // alert(shippingValue);
               }else{
                    var shippingValue = selectedOption.attr('shipping');
                   // alert(shippingValue);
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
            //window.location.href = "<?php echo URL::to('/').'/guestcheckout/?shipping_type='; ?>"+shipping_type_payment;
            <?php  
                }else{ 
            ?>
            window.location.href = "<?php echo URL::to('/').'/checkout/?shipping_type='; ?>"+shipping_type_payment;
            <?php } ?>
        })

        function addToCart(){
            //console.log('hello');
            if(checkAddToCartValidity()) {
                //$('#addToCart').modal();
                //$('.c-preloader').show();
                $.ajax({
                    type:"POST",
                    url: '{{ route('cart.addToCart') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data){
                        if(data.status == 1){

                                //$('#addToCart-modal-body').html(null);
                                updateNavCart(data.nav_cart_view,data.cart_count);
                                window.location.replace("{{ route('cart') }}");
                           }
                        else{//alert('hello');
                       $('#addToCart-modal-body').html(null);
                       $('.c-preloader').hide();
                        //window.location.href ("{{ route('cart') }}");
                       $('#modal-size').removeClass('modal-lg');
                       $('#addToCart-modal-body').html(data.modal_view);
                       }
                       AIZ.extra.plusMinus();
                       AIZ.plugins.slickCarousel();
                       updateNavCart(data.nav_cart_view,data.cart_count);
                    }
                });
            }
            else{
                AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
            }
        }

        function buyNow(){
            var mini_order = {{ get_setting('out_stock_minimum_order') }};
            var maxValue = $('.aiz-plus-minus input').attr("max");
            // $('.').attr("name");
            /*console.log($('.quantity_instock').val());
            console.log("max"+maxValue);*/

            /*if($('#out-stock-quantity').val() >= mini_order || $('.quantity_instock').val() > maxValue)
             { */ 
                //console.log("dat");return false;
                if(checkAddToCartValidity()) {
                    $('#addToCart-modal-body').html(null);
                    $('#addToCart').modal();
                    $('.c-preloader').show();
                    $.ajax({
                       type:"POST",
                       url: '{{ route('cart.addToCart') }}',
                       data: $('#option-choice-form').serializeArray(),
                       success: function(data){
                           if(data.status == 1){

                                $('#addToCart-modal-body').html(data.modal_view);
                                updateNavCart(data.nav_cart_view,data.cart_count);

                                window.location.replace("{{ route('cart') }}");
                           }
                           else{
                                $('#addToCart-modal-body').html(null);
                                $('.c-preloader').hide();
                                $('#modal-size').removeClass('modal-lg');
                                $('#addToCart-modal-body').html(data.modal_view);
                           }
                       }
                   });
                }
                else{
                    AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
                }
            /*}*/
        }

        function showLoginCartModal(){
            $('#login-modal-new').modal();
        }

        function add_customer(){
            $('#new-customer-modal').modal('show');
        }
	
            $(window).on('scroll',function() {
		   //alert('dafadsfdsf');
           var scroll = $(window).scrollTop();
           if (scroll < 150) {
           /* $(".sticky-header").hide();
            $(".sticky-header").removeClass("sticky");*/
            $(".sticky-header").addClass("sticky");
            $(".sticky-header").show();
           }else{
            $(".sticky-header").addClass("sticky");
			$(".sticky-header").show();
           }
    });
        $(document).ready(function() {
            $('body').on('click','#termscondition', function(){
            /*$('#checkout').css('pointer-events', 'none');*/
            if ($(this).is(':checked')) {
                /*$('#checkout').css('pointer-events', 'auto');*/
                $("#checkout").show();
                $("#checkout-required").hide();
            }else{
                $("#checkout").hide();
                $("#checkout-required").show();
            }
          });
        });

       $(document).ready(function(){
            $('body').on('click','#checkout-required', function(){
            if($('#termscondition').prop("checked") == true){

            }
            else if($('#termscondition').prop("checked") == false){
            alert("You have checked the Terms & Condition checkbox!");
            $("#checkout").hide();
            $("#checkout-required").show();
            }
            });
        });

        $(document).ready(function(){

            $('.tax_rate').click(function(){
               var checkbox = $(this).find(".tax_radio");
            if (checkbox.prop("checked")) {
                var zip_code =   $(this).attr("zip_code");
                var address_id =   $(this).attr("address_id");
                $.ajax({
                    type:"POST",
                    dataType : 'json',
                    url: '{{ route('checkout.getTaxRate') }}',
                    data: {zip_code: zip_code,address_id: address_id,_token : AIZ.data.csrf},
                    success: function(data){
                          location.reload();

                    }
                });
            }
            });


            $('body').on('click','.custom-drodown-nav', function(){
                var request_type = $(this).attr('request_type');
                if(request_type == "hide"){
                    $('.custom-drodown-nav').parent('.menu-item-has-children').find('ul').hide();
                    $('.custom-drodown-nav').attr('request_type','hide')
                    $(this).parent('.menu-item-has-children').find('ul').show();
                    $(this).attr('request_type','show');
                }else{
                    $(this).parent('.menu-item-has-children').find('ul').hide();
                    $(this).attr('request_type','hide')
                }
                
            })
        });
    </script>
	
	


    @yield('script')

    @php
        echo get_setting('footer_script');
    @endphp

</body>
</html>
