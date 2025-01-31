
<div class="aiz-user-sidenav-wrap position-relative z-1 shadow-sm">
    <div class="aiz-user-sidenav rounded overflow-auto c-scrollbar-light pb-5 pb-xl-0">
        <div class="p-4 text-xl-center mb-4 border-bottom bg-primary text-white position-relative user-bg-primary">
            <h4 class="user-text h5 fs-16 mb-1 fw-600">{{ Auth::user()->name }}</h4>
                <div class="text-truncate opacity-60">{{ Auth::user()->email }}</div>
        </div>
        <div class="sidemnenu mb-3">
            <div class="dashboard_tab_button">
                <ul role="tablist" class="nav flex-column dashboard-list">
                    <li>
                        <a href="{{ route('dashboard') }}"  class="nav-link {{ areActiveRoutes(['dashboard'])}}">{{ translate('Dashboard') }}
                        </a>
                    </li>

                    @if(Auth::user()->user_type == 'delivery_boy')
                    <li>
                        <a href="{{ route('assigned-deliveries') }}" class="nav-link {{ areActiveRoutes(['completed-delivery'])}}">{{ translate('Assigned Delivery') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pickup-deliveries') }}"  class="nav-link {{ areActiveRoutes(['completed-delivery'])}}">{{ translate('Pickup Delivery') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('on-the-way-deliveries') }}"  class="nav-link {{ areActiveRoutes(['completed-delivery'])}}">{{ translate('On The Way Delivery') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('completed-deliveries') }}"  class="nav-link {{ areActiveRoutes(['completed-delivery'])}}">{{ translate('Completed Delivery') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pending-deliveries') }}"  class="nav-link {{ areActiveRoutes(['pending-delivery'])}}">{{ translate('Pending Delivery') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cancelled-deliveries') }}"  class="nav-link {{ areActiveRoutes(['cancelled-delivery'])}}">{{ translate('Cancelled Delivery') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cancel-request-list') }}"  class="nav-link {{ areActiveRoutes(['cancel-request-list'])}}">{{ translate('Request Cancelled Delivery') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('total-collection') }}"  class="nav-link {{ areActiveRoutes(['today-collection'])}}">{{ translate('Total Collections') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('total-earnings') }}"  class="nav-link {{ areActiveRoutes(['total-earnings'])}}">{{ translate('Total Earnings') }}
                        </a>
                    </li>
                    @else

                    @php
                        $delivery_viewed = App\Models\Order::where('user_id', Auth::user()->id)->where('delivery_viewed', 0)->get()->count();
                        $payment_status_viewed = App\Models\Order::where('user_id', Auth::user()->id)->where('payment_status_viewed', 0)->get()->count();
                    @endphp
                    @if(Auth::user()->user_type == 'customer')
                        @if(Auth::user()->is_salesperson == 0)
                            <li>
                                <a href="{{ route('purchase_history.index') }}" class="nav-link {{ areActiveRoutes(['purchase_history.index','purchase_history.details'])}}">
                                    {{ translate('Purchase History') }}
                                     @if($delivery_viewed > 0 || $payment_status_viewed > 0)
                                     <span class="badge badge-inline badge-success">{{ translate('New') }}
                                     </span>
                                     @endif
                                </a>
                            </li>
                        @endif

                    @if(Auth::user()->is_salesperson == 1)
                        <li>
                            <a href="{{ route('salesperson-customer-purchase-history') }}"  class="nav-link">{{ translate('Customer Purchase History') }}
                                @if($delivery_viewed > 0 || $payment_status_viewed > 0)
                                <span class="badge badge-inline badge-success">{{ translate('New') }}
                                </span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('salesperson-monthly-revenue') }}"  class="nav-link">{{ translate('Monthly Revenue') }}
                                
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"  class="nav-link" onclick="add_customer()">{{ translate('Add New Customer') }}
                            </a>
                        </li>
                    @endif
                    @endif
                    @if (addon_is_activated('refund_request'))
                        <li>
                            <a href="{{ route('customer_refund_request') }}"  class="nav-link {{ areActiveRoutes(['customer_refund_request'])}}">{{ translate('Sent Refund Request') }}
                            </a>
                        </li>
                    @endif
                        <li>
                            <a href="{{ route('wishlists.index') }}"  class="nav-link {{ areActiveRoutes(['wishlists.index'])}}">{{ translate('Wishlist') }}
                            </a>
                        </li>
                    @if(get_setting('classified_product') == 1)
                        <li>
                            <a href="{{ route('customer_products.index') }}"  class="nav-link {{ areActiveRoutes(['customer_products.index', 'customer_products.create', 'customer_products.edit'])}}">{{ translate('Classified Products') }}
                            </a>
                        </li>
                    @endif
                    @if (get_setting('wallet_system') == 1)
                        <li>
                            <a href="{{ route('wallet.index') }}"  class="nav-link {{ areActiveRoutes(['wallet.index'])}}">{{translate('My Wallet')}}
                            </a>
                        </li>                    
                    @endif
                    @if (addon_is_activated('club_point'))
                        <li>
                            <a href="{{ route('earnng_point_for_user') }}"  class="nav-link {{ areActiveRoutes(['earnng_point_for_user'])}}">{{translate('Earning Points')}}
                            </a>
                        </li>                    
                    @endif
                    @if (addon_is_activated('affiliate_system') && Auth::user()->affiliate_user != null && Auth::user()->affiliate_user->status)
                        <li>
                            <a href="{{ route('wallet.index') }}"  class="nav-link {{ areActiveRoutes(['wallet.index'])}}">{{translate('My Wallet')}}
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('affiliate.user.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Affiliate System') }}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('affiliate.user.payment_history') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Payment History') }}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('affiliate.user.withdraw_request_history') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Withdraw request history') }}</span>
                                    </a>
                                </li>
                            </ul>

                        </li>                    
                    @endif
                    @php
                        $support_ticket = DB::table('tickets')
                                    ->where('client_viewed', 0)
                                    ->where('user_id', Auth::user()->id)
                                    ->count();
                    @endphp
            @endif
                    <li>
                        <a href="{{ route('profile') }}"  class="nav-link {{ areActiveRoutes(['profile'])}}">{{translate('Manage Profile')}}
                        </a>
                    </li> 
                    <li>
                        <a href="{{ route('conversations.index') }}"  class="nav-link {{ areActiveRoutes(['conversations.index'])}}">{{translate('Conversation')}}
                        </a>
                    </li> 
                </ul>
            </div>
        </div>

    </div>

    <div class="fixed-bottom d-xl-none bg-white border-top d-flex justify-content-between px-2" style="box-shadow: 0 -5px 10px rgb(0 0 0 / 10%);">
        <a class="btn btn-sm p-2 d-flex align-items-center" href="{{ route('logout') }}">
            <i class="las la-sign-out-alt fs-18 mr-2"></i>
            <span>{{ translate('Logout') }}</span>
        </a>
        <button class="btn btn-sm p-2 " data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb">
            <i class="las la-times la-2x"></i>
        </button>
    </div>
</div>