@extends('frontend.layouts.user_panel')
@section('meta_title')<?php echo $page->meta_title; ?>@stop
@section('meta_description')<?php echo $page->meta_description; ?>@stop
    @section('meta')
    <link rel="canonical" href="https://bautlr.com/wishlists">
    @stop
@section('panel_content')
    <!--wishlist area start -->
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <b class="h4">{{ translate('Wishlist')}}</b>
            </div>
        </div>
    </div>
    <div class="wishlist_area">
                <div class="container"> 
                        <div class="row">
                           
                            <div class="col-12">
                                <div class="table_desc wishlist">
                                    <div class="cart_page table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th class="product_remove">Delete</th>
                                                    <th class="product_thumb">Image</th>
                                                    <th class="product_name">Product</th>
                                                    <th class="product-price">Price</th>
                                                    <th class="">Rating</th>
                                                    <th class="product_total">Add To Cart</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($wishlists as $key => $wishlist)
                                                @if ($wishlist->product != null)
                                                <tr id="wishlist_{{ $wishlist->id }}"> 

                                                   <td class="product_remove">
                                                        <a href="#" data-toggle="tooltip" data-placement="top" title="Remove from wishlist" class="link link--style-3" onclick="removeFromWishlist({{ $wishlist->id }})">X
                                                        </a>
                                                    </td>
                                                    <td class="product_thumb"><a href="{{ route('product', $wishlist->product->slug) }}"><img loading="lazy" src="{{ uploaded_asset($wishlist->product->thumbnail_img) }}" class="img-fit h-140px h-md-200px" alt=""></a></td>
                                                    <td class="product_name"><a href="{{ route('product', $wishlist->product->slug) }}">{{ $wishlist->product->getTranslation('name') }}</a></td>
                                                    <td class="product-price">
                                                        @if(home_base_price($wishlist->product) != home_discounted_base_price($wishlist->product))
                                                        <del class="opacity-60 mr-1">{{ home_base_price($wishlist->product) }}</del>
                                                        @endif
                                                        <span class="fw-600 text-primary">{{ home_discounted_base_price($wishlist->product) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="rating rating-sm mb-1">
                                                            {{ renderStarRating($wishlist->product->rating) }}
                                                        </div>
                                                    </td>
                                                    <td class="product_total">
                                                        <button type="button" class="btn btn-sm btn-block btn-primary ml-3 wishlist_cart" onclick="showAddToCartModal({{ $wishlist->product->id }})">
                                                            <i class="la la-shopping-cart mr-2"></i>{{ translate('Add to cart')}}
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endif
                                                @empty
                                                    <div class="col">
                                                        <div class="text-center bg-white p-4 rounded shadow">
                                                            <img loading="lazy" class="mw-100 h-200px" src="{{ static_asset('assets/img/nothing.svg') }}" alt="Image">
                                                            <h5 class="mb-0 h5 mt-3">{{ translate("There isn't anything added yet")}}</h5>
                                                        </div>
                                                    </div>
                                                @endforelse 
                                            </tbody>
                                        </table>   
                                    </div>
                                </div>
                            </div>                           
                    </div>
                    <div class="aiz-pagination">
                        {{ $wishlists->links() }}
                    </div>                 
                </div>
            </div>
    <!--wishlist area end -->

@endsection

@section('modal')

<div class="modal fade" id="addToCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
        <div class="modal-content position-relative">
            <div class="c-preloader">
                <i class="fa fa-spin fa-spinner"></i>
            </div>
            <button type="button" class="close absolute-close-btn" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div id="addToCart-modal-body">

            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
    <script type="text/javascript">
        function removeFromWishlist(id){
            $.post('{{ route('wishlists.remove') }}',{_token:'{{ csrf_token() }}', id:id}, function(data){
                $('#wishlist').html(data);
                $('#wishlist_'+id).hide();
                AIZ.plugins.notify('success', '{{ translate('Item has been renoved from wishlist') }}');
            })
        }
    </script>
@endsection
