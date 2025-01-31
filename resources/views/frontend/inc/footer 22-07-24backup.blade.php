<!--footer area start-->
<footer class="footer_widgets" style="max-width:100%; overflow: hidden; border-top: 1px solid #d3d1d0; padding-top: 0.5rem; box-shadow: 0 1px 3px rgb(0 0 0 / 11%);">
    <div class="container">  
        <div class="footer_top">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-8">
                    <div class="footer_logo">
                        <a href="{{ route('home') }}">
                            @if(get_setting('footer_logo') != null)
                                <img src="{{ uploaded_asset(get_setting('footer_logo')) }}" alt="{{ env('APP_NAME') }}" style="height: 60px;">
                            @else
                                <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}" style="height: 60px;">
                            @endif
                        </a>
                        <div style="margin-left: -15px; padding-top: 5px;">
                            <h6>Elevate Your Elegance with Gemstone Grace</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-4 col-6">
                    <div class="widgets_container widget_menu">
                        <h3>{{ get_setting('widget_one', null, App::getLocale()) }}</h3>
                        <div class="footer_menu">
                            <ul>
                                @if (get_setting('widget_one_labels', null, App::getLocale()) != null)
                                    @foreach (json_decode(get_setting('widget_one_labels', null, App::getLocale()), true) as $key => $value)
                                        <li><a href="{{ json_decode(get_setting('widget_one_links'), true)[$key] }}">{{ $value }}</a></li>
                                    @endforeach
                                @endif
                                <li><a href="{{ route('terms') }}">{{ translate('Terms & conditions') }}</a></li>
                                <li><a href="{{ route('returnpolicy') }}">Return Policy</a></li>
                                <li><a href="{{ route('privacypolicy') }}">Privacy Policy</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- New Widget Container for Our Promises -->
                <div class="col-lg-2 col-md-6 col-sm-4 col-6">
                    <div class="widgets_container widget_menu">
                        <h3>OUR PROMISES</h3>
                        <div class="footer_menu">
                            <ul>
                                <li>Only Certified Jewellery</li>
                                <li>100 % Hallmarked Jewellery</li>
                                <li>Transparent Pricing</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-5 col-6">
                    <div class="widgets_container widget_menu">
                        <h3>{{ translate('My Account') }}</h3>
                        <div class="footer_menu">
                            <ul>
                                @if (Auth::check())
                                    <li><a href="{{ route('logout') }}">{{ translate('Logout') }}</a></li>
                                @else
                                    <li><a href="{{ route('user.login') }}">{{ translate('Login') }}</a></li>
                                @endif
                                <li><a href="{{ route('purchase_history.index') }}">{{ translate('Order History') }}</a></li>
                                <li><a href="{{ route('wishlists.index') }}">{{ translate('My Wishlist') }}</a></li>
                                @if (addon_is_activated('affiliate_system'))
                                    <li><a href="{{ route('affiliate.apply') }}">{{ translate('Be an affiliate partner')}}</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-7">
                    <div class="widgets_container contact_us">
                        <h3>{{ translate('Contact Info') }}</h3>
                        <div class="footer_contact">
                            <p><a href="mailto:{{ get_setting('contact_email') }}" class="text-reset email_contact">Email: {{ get_setting('contact_email') }}</a></p>
                            @if (get_setting('show_social_links'))
                                <ul>
                                    @if (get_setting('facebook_link') != null)
                                        <li><a href="{{ get_setting('facebook_link') }}" target="_blank"><img alt="facebook" src="{{ static_asset('assets/img/icon/facebook.png') }}" /></a></li>
                                    @endif
                                    @if (get_setting('twitter_link') != null)
                                        <li><a href="{{ get_setting('twitter_link') }}" target="_blank"><img alt="twitter" src="{{ static_asset('assets/img/icon/twitter.png') }}" /></a></li>
                                    @endif
                                    @if (get_setting('youtube_link') != null)
                                        <li><a href="{{ get_setting('youtube_link') }}" target="_blank"><img alt="youtube" src="{{ static_asset('assets/img/icon/youtube.png') }}" /></a></li>
                                    @endif
                                    @if (get_setting('instagram_link') != null)
                                        <li><a href="{{ get_setting('instagram_link') }}" target="_blank"><img alt="instagram" src="{{ static_asset('assets/img/icon/instagram.png') }}" /></a></li>
                                    @endif
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer_middel">
            <div class="row">
                <div class="col-12">
                    <div class="footer_middel_menu">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="text-left text-md-left">
                                        <p class="copyright_text">© <?php echo date('Y'); ?> <a href="https://bautlr.com" target="_blank">BAUTLR</a> All rights reserved.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="text-right text-md-right">
                                        <ul class="list-inline mb-0">
                                            <li class="list-inline-item">
                                                <img src="https://bautlr.com/public/uploads/all/0R9FbsDQUYhAcU6W604ixH9Rg4bMwYC8EDXfZjyv.webp" height="30" class="mw-100 h-auto" style="max-height: 30px" alt="">
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!--footer area end-->

<div class="aiz-mobile-bottom-nav d-xl-none fixed-bottom bg-white shadow-lg border-top rounded-top" style="box-shadow: 0px -1px 10px rgb(0 0 0 / 15%)!important;">
    <div class="row align-items-center gutters-5">
        <div class="col">
            <a href="{{ route('home') }}" class="text-reset d-block text-center pb-2 pt-3">
                <i class="las la-home fs-20 opacity-60 {{ areActiveRoutes(['home'],'opacity-100 text-primary')}}"></i>
                <span class="d-block fs-10 fw-600 opacity-60 {{ areActiveRoutes(['home'],'opacity-100 fw-600')}}">{{ translate('Home') }}</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('brands.all') }}" class="text-reset d-block text-center pb-2 pt-3">
                <i class="las la-list-ul fs-20 opacity-60 {{ areActiveRoutes(['brands.all'],'opacity-100 text-primary')}}"></i>
                <span class="d-block fs-10 fw-600 opacity-60 {{ areActiveRoutes(['brands.all'],'opacity-100 fw-600')}}">{{ translate('Gemstones') }}</span>
            </a>
        </div>
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
        <div class="col-auto">
            <a href="{{ route('cart') }}" class="text-reset d-block text-center pb-2 pt-3">
                <span class="align-items-center bg-primary border border-white border-width-4 d-flex justify-content-center position-relative rounded-circle size-50px" style="margin-top: -33px;box-shadow: 0px -5px 10px rgb(0 0 0 / 15%);border-color: #fff !important;">
                    <i class="las la-shopping-bag la-2x text-white"></i>
                </span>
                <span class="d-block mt-1 fs-10 fw-600 opacity-60 {{ areActiveRoutes(['cart'],'opacity-100 fw-600')}}">
                    {{ translate('Cart') }}
                    @php
                        $count = (isset($cart) && count($cart)) ? count($cart) : 0;
                    @endphp
                    (<span class="cart-count">{{$count}}</span>)
                </span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('all-notifications') }}" class="text-reset d-block text-center pb-2 pt-3">
                <span class="d-inline-block position-relative px-2">
                    <i class="las la-bell fs-20 opacity-60 {{ areActiveRoutes(['all-notifications'],'opacity-100 text-primary')}}"></i>
                    @if(Auth::check() && count(Auth::user()->unreadNotifications) > 0)
                        <span class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right" style="right: 7px;top: -2px;"></span>
                    @endif
                </span>
                <span class="d-block fs-10 fw-600 opacity-60 {{ areActiveRoutes(['all-notifications'],'opacity-100 fw-600')}}">{{ translate('Notifications') }}</span>
            </a>
        </div>
        <div class="col">
        @if (Auth::check())
            @if(isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="text-reset d-block text-center pb-2 pt-3">
                    <span class="d-block mx-auto">
                        @if(Auth::user()->photo != null)
                            <img src="{{ custom_asset(Auth::user()->avatar_original)}}" class=" rounded-circle size-20px " style="height:35px !important; width: 40px !important;">
                        @else
                            <img src="{{ static_asset('assets/img/avatar-place.png') }}" class=" rounded-circle size-20px" style="height:35px !important; width: 40px !important;">
                        @endif
                    </span>
                    <span class="d-block fs-10 fw-600 opacity-60">{{ translate('Account') }}</span>
                </a>
            @else
                <a href="javascript:void(0)" class="text-reset d-block text-center pb-2 pt-3 mobile-side-nav-thumb" data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav">
                    <span class="d-block mx-auto">
                        @if(Auth::user()->photo != null)
                            <img src="{{ custom_asset(Auth::user()->avatar_original)}}" class="rounded-circle size-20px" style="height:35px !important; width: 40px !important;">
                        @else
                            <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="rounded-circle size-20px" style="height:35px !important; width: 40px !important;">
                        @endif
                    </span>
                    <span class="d-block fs-10 fw-600 opacity-60">{{ translate('Account') }}</span>
                </a>
            @endif
        @else
            <a href="{{ route('user.login') }}" class="text-reset d-block text-center pb-2 pt-3">
                <span class="d-block mx-auto">
                    <img src="{{ static_asset('assets/img/avatar-place.png') }}" class=" rounded-circle size-20px" style="height:35px !important; width: 40px !important;">
                </span>
                <span class="d-block fs-10 fw-600 opacity-60">{{ translate('Account') }}</span>
            </a>
        @endif
        </div>
    </div>
</div>
@if (Auth::check() && !isAdmin())
    <!-- <div class="aiz-mobile-side-nav collapse-sidebar-wrap sidebar-xl d-xl-none z-1035">
        <div class="overlay dark c-pointer overlay-fixed" data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb"></div>
        <div class="collapse-sidebar bg-white">
            @include('frontend.inc.user_side_nav')
        </div>
    </div> -->
@endif
