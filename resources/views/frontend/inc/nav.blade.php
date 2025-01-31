
@if(get_setting('topbar_banner') != null)
<!-- <div class="position-relative top-banner removable-session z-1035 d-none" data-key="top-banner" data-value="removed">
    <a href="{{ get_setting('topbar_banner_link') }}" class="d-block text-reset">
        <img loading="lazy" src="{{ uploaded_asset(get_setting('topbar_banner')) }}" class="w-100 mw-100 h-50px h-lg-auto img-fit">
    </a>
    <button class="btn text-white absolute-top-right set-session" data-key="top-banner" data-value="removed" data-toggle="remove-parent" data-parent=".top-banner">
        <i class="la la-close la-2x"></i>
    </button>
</div> -->
@endif
<!-- Top Bar -->

<?php 
	$menu_categories = \App\Models\Category::select(['name','slug','icon'])->where('level', 0)->orderBy('order_level','asc')->get();
	$menu_gemstones = \App\Models\Brand::select(['name','slug','id'])->where('active',1)->orderBy('name','asc')->get();
    $menu_months = array('January','February','March','April','May','June','July','August','September','October','November','December');
?>

<!-- Main Wrapper Start -->
    <!--Offcanvas menu area start-->

    <div class="Offcanvas_menu">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="canvas_open">
                        <a href="javascript:void(0)"><i class="ion-navicon"></i></a>
                    </div>
                    <div class="Offcanvas_menu_wrapper">
                        <div class="canvas_close">
                              <a href="javascript:void(0)"><i class="ion-android-close"></i></a>  
                        </div>
                        <div class="welcome_text">
                             <a href="{{ route('home') }}">
                                @php
                                    $header_logo = get_setting('header_logo');
                                @endphp
                                @if($header_logo != null)
                                    <img loading="lazy" src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}" class="mw-100 h-30px h-md-auto logo-mid" height="40">
                                @else
                                    <img loading="lazy" src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}" class="mw-100 h-30px h-md-auto" height="40">
                                @endif 
                            </a>    
                           <!-- <p>Welcome to <span>{{ get_setting('site_name') }}</span></p>  -->
                        </div>
                        
                        <div class="top_right text-right">
                            <ul>
                               <li class="top_links"><a href="#">My Account <i class="ion-chevron-down"></i></a>
                                    <ul class="dropdown_links">
                                        @auth
                                        @if(isAdmin())
                                        <li><a href="{{ route('admin.dashboard') }}">{{ translate('My Account')}}</a></li>
                                        @else
                                        <li>
                                            @if (Auth::user()->user_type == 'seller')
                                            <a href="{{ route('seller.dashboard') }}">{{ translate('My Account')}}</a>
                                            @else
                                            <a href="{{ route('dashboard') }}">{{ translate('My Account')}}</a>
                                            @endif
                                        </li>
                                        @endif
                                        <li>
                                            <a href="{{ route('logout') }}">{{ translate('Logout')}}</a>
                                        </li>
                                        @else
                                            <li><a href="{{ route('user.login') }}">Login</a></li>
                                            <li><a href="{{ route('user.registration') }}">Registration</a></li>
                                        @endauth
                                    </ul>
                                </li> 
                            </ul>
                        </div> 


                        <div id="menu" class="text-left">
                            <ul class="offcanvas_main_menu"> 
                                <li class="cart-items-right active" id="cart_items">          
                                    @include('frontend.partials.cart')
                                </li>
                                <li class="menu-item-has-children active">
                                    <a href="{{ route('home') }}">Home</a>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="javascript:void(0)" class="custom-drodown-nav" request_type="hide">Jewelry</a>
                                    <ul class="sub-menu" id="jewelry-submenu">
                                        <li><a href="javascript:void(0)">Categories</a>
                                            <ul>
                                                <?php foreach($menu_categories as $menu_category){ ?>
                                                <li>
                                                    <a href="{{ route('products.category', $menu_category->slug) }}">
                                                        {{ $menu_category->getTranslation('name') }}
                                                    </a>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <li><a href="javascript:void(0)">Gemstones</a>
                                            <ul>
                                                <?php foreach($menu_gemstones as $menu_gemstone){ ?>
                                                <li><a href="{{ route('products.gemstone', $menu_gemstone->slug) }}">{{ $menu_gemstone->getTranslation('name') }}</a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        
                                    </ul>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="javascript:void(0)"  class="custom-drodown-nav"  request_type="hide">Pages</a>
                                    <ul class="sub-menu" id="pages-submenu">                                        
                                        {{-- <li><a href="{{ route('blog') }}">Blogs</a></li> --}}
                                        <li><a href="{{ route('contact-us') }}">Contact Us</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>


                        <div class="home_contact">
                            <?php 
                            $contactAddress = get_setting('contact_address', null, App::getLocale());
                            if($contactAddress != null){ ?>
                            <div class="contact_box">
                                <label>Address:</label>
                                <p><?php echo e($contactAddress); ?></p>
                            </div>
                            <?php 
                            }
                            if(get_setting('contact_phone') != null){
                            ?>
                            <div class="contact_box">
                                <label>Phone:</label>
                                <p><a href="{{ get_setting('contact_phone') }}">{{ get_setting('contact_phone') }}</a></p>
                            </div> 
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Offcanvas menu area end-->
    
     <!--header area start-->
    <header class="header_area">
        <!--header top start-->
        <div class="header_top display-none">
            <div class="container">   
                <div class="row align-items-center">

                    <div class="col-lg-7 col-md-12">
                        <div class="welcome_text">
                            <p>Welcome to <span>“{{ get_setting('site_name') }}”</span></p>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-12">
                        <div class="top_right text-right">
                            <ul>
                                <li class="top_links"><a href="#">My Account <i class="ion-chevron-down"></i></a>
                                    
                                    <ul class="dropdown_links">
                                        @auth
                                        @if(isAdmin())
                                            <li>
                                                <a href="{{ route('admin.dashboard') }}">{{ translate('My Account')}}</a>
                                            </li>
                                        @else
                                        <li>
                                        @if (Auth::user()->user_type == 'seller')
                                            <a href="{{ route('seller.dashboard') }}">{{ translate('My Account')}}</a>
                                        @else
                                            <a href="{{ route('dashboard') }}">{{ translate('My Account')}}</a>
                                        @endif
                                        </li>
                                        @endif
                                        <li>
                                            <a href="{{ route('logout') }}">{{ translate('Logout')}}</a>
                                        </li>
                                        @else
                                            <li><a title="Login" href="{{ route('user.login') }}">Login</a></li>
                                            <li><a title="Login" href="{{ route('user.registration') }}">Registration</a></li>
                                        @endauth
                                      
                                    </ul>
                                </li> 
                            </ul>
                        </div>   
                    </div>
                </div>
            </div>
        </div>
        <!--header top start-->

        

        <!--header bottom satrt-->
        <div class="header_bottom sticky-header sticky">
            <div class="container custom_container">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="main_menu_inner">
                          <div class="col-5"> 
                            <div class="main_menu"> 
                                <nav>  
                                    <ul>
                                        
                                        <li class="active"><a href="{{ route('home') }}">Home</a></li>
                                        <li><a href="javascript:void(0)">Jewelry <i class="fa fa-angle-down"></i></a>
                                            
                                            <ul class="mega_menu">
                                                <?php if(!empty($menu_categories)){ ?>
                                                    <li><a href="javascript:void(0)">Categories</a>
                                                        <ul>
                                                            <?php foreach($menu_categories as $menu_category){ ?>
                                                            <li>
                                                                <a href="{{ route('products.category', $menu_category->slug) }}">
                                                                   <!--  <?php //   if(!empty($menu_category->icon)){ ?>
                                                                        <img loading="lazy" src="{{ uploaded_asset($menu_category->icon) }}" alt="{{ $menu_category->getTranslation('name') }} icon" class="menu-icon"> 
                                                                    <?php //} else { ?>
                                                                        <span class="menu-icon-placeholder"></span>
                                                                    <?php //} ?> -->
                                                                    <span class="menu-text">{{ $menu_category->getTranslation('name') }}</span>
                                                                </a>
                                                            </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </li>
                                                    
                                                    
                                                    <?php } ?>
                                               



                                                <!-- <?php //$gemstone_sticky="Gemstone";                                                       
                                                ?>
                                                <li><a href="javascript:void(0)"><?php //echo $gemstone_sticky; ?></a>
                                                    <ul>
                                                        <li class=""><a href="{{ route('brands.all') }}">View All</a></li>
                                                    </ul>
                                                </li> -->


                                            <?php 
                                                $menuGemstonesArr = array();
                                                if(!empty($menu_gemstones)){
                                                    $i = 0;
                                                    foreach($menu_gemstones as $k => $menu_gemstone){
                                                       
                                                        if (checkProductIsCreated($menu_gemstone->id) == 1) {
                                                            if($k % 12 == 0 ){
                                                        
                                                                $i++;
                                                            }                
                                                           $menuGemstonesArr[$i][$k] = $menu_gemstone;
                                                        }
                                                    }
                                                }                                            
                                            ?>
                                            <?php 
                                                $title_gemstone= 'Gemstones';
                                                if(!empty($menuGemstonesArr)){ 
                                                    foreach($menuGemstonesArr as $menu_gemstones){
                                            ?>
                                                    <li><a href="javascript:void(0)"><?php echo $title_gemstone ?></a>
                                                    <ul>
                                                    <?php foreach($menu_gemstones as $menu_gemstone){ ?>
                                                            <li><a href="{{ route('products.gemstone', $menu_gemstone->slug) }}">{{ $menu_gemstone->getTranslation('name') }}</a></li>
                                                    
                                                    <?php } ?>

                                                    </ul>
                                                    
                                                <?php
                                                $title_gemstone = " ";
                                                } }  ?>
                                                
                                                    
                                                
                                            </ul>
                                        </li>
                                        {{-- <li class=""><a href="{{ route('blog') }}">Blogs</a></li> --}}
                                        <li class=""><a href="{{ route('contact-us') }}">Contact Us</a></li>
                                    </ul>  
                                </nav> 
                            </div>
                        </div>
                        <div class="col-2"> 
                            <div class="logo_sticky">
                               <a href="{{ route('home') }}">
                                @php
                                    $header_logo = get_setting('header_logo');
                                @endphp
                                @if($header_logo != null)
                                    <img loading="lazy" src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}" class="mw-100 h-30px h-md-auto logo-mid" height="40">
                                @else
                                    <img loading="lazy" src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}" class="mw-100 h-30px h-md-auto" height="40">
                                @endif 
                                </a>
                           </div>
                        </div>
                        <div class="col-5">
                            <div class="top_right  header-right-panel">
                                <ul>
                                    <li>
                                        @if((Auth::user()))
                                        <form action="{{ route('search') }}" method="GET" class="stop-propagation">
                                            <div class="d-flex position-relative align-items-center">
                                                <div class="d-lg-none" data-toggle="class-toggle" data-target=".front-header-search">
                                                    <button class="btn px-2" type="button"><i class="la la-2x la-long-arrow-left"></i></button>
                                                </div>
                                                <div class="input-group searching_input_area">
                                                    <input type="text" class="border-0 border-lg form-control" id="search" name="keyword" @isset($query)
                                                        value="{{ $query }}"
                                                    @endisset placeholder="{{translate('I am shopping for...')}}" autocomplete="off">
                                                    <div class="input-group-append d-none d-lg-block">
                                                        <button class="btn btn-primary" type="submit">
                                                            <i class="ion-ios-search-strong"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        <form action="{{ route('search') }}" method="GET" class="stop-propagation">
                                            <div class="d-flex position-relative align-items-center">
                                                <div class="d-lg-none" data-toggle="class-toggle" data-target=".front-header-search">
                                                    <button class="btn px-2" type="button"><i class="la la-2x la-long-arrow-left"></i></button>
                                                </div>
                                                <div class="input-group searching_input_area">
                                                    <input type="text" class="border-0 border-lg form-control" id="search" onclick="showLoginCartModal()"  name="keyword"  placeholder="{{translate('I am shopping for...')}}" autocomplete="off">
                                                    <div class="input-group-append d-none d-lg-block">
                                                        <button class="btn btn-primary" onclick="showLoginCartModal()"  >
                                                           <i class="ion-ios-search-strong"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endif    

                                    <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100" style="min-height: 200px">
                                        <div class="search-preloader absolute-top-center">
                                            <div class="dot-loader"><div></div><div></div><div></div></div>
                                        </div>
                                        <div class="search-nothing d-none p-3 text-center fs-16">

                                        </div>
                                        <div id="search-content" class="text-left">

                                        </div>
                                    </div>    
                                    </li>
                                    <li id="cart_items">@include('frontend.partials.cart')</li>

                                     <li class="top_links"><a href="#">My Account <i class="ion-chevron-down"></i></a>
                                        
                                        <ul class="dropdown_links">
                                            @auth
                                            @if(isAdmin())
                                                <li>
                                                    <a href="{{ route('admin.dashboard') }}">{{ translate('My Account')}}</a>
                                                </li>
                                            @else
                                            <li>
                                            @if (Auth::user()->user_type == 'seller')
                                                <a href="{{ route('seller.dashboard') }}">{{ translate('My Account')}}</a>
                                            @else
                                                <a href="{{ route('dashboard') }}">{{ translate('My Account')}}</a>
                                            @endif
                                            </li>
                                            @endif
                                            <li>
                                                <a href="{{ route('logout') }}">{{ translate('Logout')}}</a>
                                            </li>
                                            @else
                                                <li><a title="Login" href="{{ route('user.login') }}">Login</a></li>
                                                <li><a title="Registration" href="{{ route('user.registration') }}">Registration</a></li>
                                            @endauth
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            </div>    
                        </div> 
                    </div>
                   
                </div>
            </div>
        </div>
        <!--header bottom end-->
    </header>
    <!--header area end-->

@section('script')
    <script type="text/javascript">

        function show_order_details(order_id)
        {
            $('#order-details-modal-body').html(null);

            if(!$('#modal-size').hasClass('modal-lg')){
                $('#modal-size').addClass('modal-lg');
            }

            $.post('{{ route('orders.details') }}', { _token : AIZ.data.csrf, order_id : order_id}, function(data){
                $('#order-details-modal-body').html(data);
                $('#order_details').modal();
                $('.c-preloader').hide();
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }
		
		
    </script>
@endsection
