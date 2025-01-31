<!--footer area start-->
<footer class="footer_widgets" style="max-width:100%; overflow: hidden; border-top: 1px solid #d3d1d0; padding-top: 0.5rem; box-shadow: 0 1px 3px rgb(0 0 0 / 11%);">
    <div class="container">  
        <div class="footer_top">
            <div class="row widget_1">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer_logo">
                        <a href="{{ route('home') }}">
                            @if(get_setting('footer_logo') != null)
                                <img loading="lazy" src="{{ uploaded_asset(get_setting('footer_logo')) }}" alt="{{ env('APP_NAME') }}" style="height: 50px;">
                            @else
                                <img loading="lazy" src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}" style="height: 50px;">
                            @endif
                        </a>
                        <div style="padding-top: 5px;">
                            <h2>Discover unique and best gemstone jewelry designs</h2>
                        </div>
                    </div>
                    <div class="widgets_container contact_us" style="padding-top: 40px;">
                        <!-- <h3>{{ translate('Contact Info') }}</h3> -->
                        <div class="footer_contact">
                            <p><a href="mailto:{{ get_setting('contact_email') }}" class="text-reset email_contact">Email: {{ get_setting('contact_email')  }}</a></p>
                            @if ( get_setting('show_social_links') )
                                <ul>
                                    @if ( get_setting('facebook_link') !=  null )
                                        <li><a href="{{ get_setting('facebook_link') }}" target="_blank"><img loading="lazy" alt="facebook" src="{{ static_asset('assets/img/icon/facebook.png') }}" /></a></li>
                                    @endif
                                    @if ( get_setting('twitter_link') !=  null )
                                        <li><a href="{{ get_setting('twitter_link') }}" target="_blank"><img loading="lazy" alt="twitter" src="{{ static_asset('assets/img/icon/twitter.png') }}" /></a></li>
                                    @endif
                                    @if ( get_setting('youtube_link') !=  null )
                                        <li><a href="{{ get_setting('youtube_link') }}" target="_blank"><img loading="lazy" alt="youtube" src="{{ static_asset('assets/img/icon/youtube.png') }}" /></a></li>
                                    @endif
                                    @if ( get_setting('instagram_link') !=  null )
                                        <li><a href="{{ get_setting('instagram_link') }}" target="_blank"><img loading="lazy" alt="instagram" src="{{ static_asset('assets/img/icon/instagram.png') }}" /></a></li>
                                    @endif
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
                

                <div class="col-lg-3 col-md-6 col-sm-6">                                    
                    <div class="widgets_container widget_menu widget_2">
                        <!-- Heading with Link -->
                        <h3>
                            <a href="https://bautlr.com/" target="_blank" style="text-decoration: none; color: inherit;">
                                {{ translate('Buy Gemstone Jewelry Online in USA') }}
                            </a>
                        </h3>
                        <div class="footer_menu">
                            <ul>
                                <!-- List Items with Links -->
                                <li><a href="https://bautlr.com/category/rings" target="_blank">Buy Gemstone Ring Online in USA</a></li>
                                <li><a href="https://bautlr.com/category/earrings" target="_blank">Buy Gemstone Earrings Online in USA</a></li>
                                <li><a href="https://bautlr.com/category/necklace" target="_blank">Buy Gemstone Necklace Online in USA</a></li>
                                <li><a href="https://bautlr.com/category/bracelets" target="_blank">Buy Gemstone Bracelets Online in USA</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="widgets_container widget_menu">
                        <h3>{{ get_setting('widget_one',null,App::getLocale()) }}</h3>
                        <div class="footer_menu">
                            <ul>
                                @if ( get_setting('widget_one_labels',null,App::getLocale()) !=  null )
                                    @foreach (json_decode( get_setting('widget_one_labels',null,App::getLocale()), true) as $key => $value)
                                        <li><a href="{{ json_decode( get_setting('widget_one_links'), true)[$key] }}">{{ $value }}</a></li>
                                    @endforeach
                                @endif
                                <li><a href="{{ route('terms') }}">{{ translate('Terms & conditions') }}</a></li>
                                <li><a href="{{ route('returnpolicy') }}">Return Policy</a></li>
                                <li><a href="{{ route('privacypolicy') }}">Privacy Policy</a></li>
                                <li class=""><a href="{{ route('blog') }}">Blogs</a></li>
                                <li><a href="{{ route('faq') }}">FAQ</a></li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="widgets_container widget_menu widget_2">
                        <h3>{{ translate('Our Promises') }}</h3>
                        <div class="footer_menu">
                            <ul>
                                <li>Only Certified Jewellery</li>
                                <li>100 % Hallmarked Jewellery</li>
                                <li>Transparent Pricing</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="widgets_container widget_menu">
                        <h3>{{ translate('My Account') }}</h3>
                        <div class="footer_menu">
                            <ul>
                                @if (Auth::check())
                                    <li><a href="{{ route('logout') }}">{{ translate('Logout') }}</a></li>
                                <li><a href="{{ route('purchase_history.index') }}">{{ translate('Order History') }}</a></li>
                                <li><a href="{{ route('wishlists.index') }}">{{ translate('My Wishlist') }}</a></li>
                                @else
                                    <li><a href="{{ route('user.login') }}">{{ translate('Login') }}</a></li>
                                    <li><a title="Login" href="{{ route('user.registration') }}">Registration</a></li>
                                @endif
                                @if (addon_is_activated('affiliate_system'))
                                    <li><a href="{{ route('affiliate.apply') }}">{{ translate('Be an affiliate partner')}}</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
               <!--  <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="widgets_container contact_us">
                        <h3>{{ translate('Contact Info') }}</h3>
                        <div class="footer_contact">
                            <p><a href="mailto:{{ get_setting('contact_email') }}" class="text-reset email_contact">Email: {{ get_setting('contact_email')  }}</a></p>
                            @if ( get_setting('show_social_links') )
                                <ul>
                                    @if ( get_setting('facebook_link') !=  null )
                                        <li><a href="{{ get_setting('facebook_link') }}" target="_blank"><img loading="lazy" alt="facebook" src="{{ static_asset('assets/img/icon/facebook.png') }}" /></a></li>
                                    @endif
                                    @if ( get_setting('twitter_link') !=  null )
                                        <li><a href="{{ get_setting('twitter_link') }}" target="_blank"><img loading="lazy" alt="twitter" src="{{ static_asset('assets/img/icon/twitter.png') }}" /></a></li>
                                    @endif
                                    @if ( get_setting('youtube_link') !=  null )
                                        <li><a href="{{ get_setting('youtube_link') }}" target="_blank"><img loading="lazy" alt="youtube" src="{{ static_asset('assets/img/icon/youtube.png') }}" /></a></li>
                                    @endif
                                    @if ( get_setting('instagram_link') !=  null )
                                        <li><a href="{{ get_setting('instagram_link') }}" target="_blank"><img loading="lazy" alt="instagram" src="{{ static_asset('assets/img/icon/instagram.png') }}" /></a></li>
                                    @endif
                                </ul>
                            @endif
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
        <div class="footer_middel">
            <div class="row">
                <div class="col-12">
                    <div class="footer_middel_menu">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="text-left text-md-left" current-verison="6.1">
                                        <p class="copyright_text">© <?php echo date('Y'); ?> <a href="https://bautlr.com" target="_blank">BAUTLR</a>&nbsp; All rights reserved.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="text-right text-md-right">
                                        <ul class="list-inline mb-0">
                                            <li class="list-inline-item">
                                                <img loading="lazy" src="https://bautlr.com/public/uploads/all/0R9FbsDQUYhAcU6W604ixH9Rg4bMwYC8EDXfZjyv.webp" height="30" class="mw-100 h-auto" style="max-height: 30px" alt="">
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
