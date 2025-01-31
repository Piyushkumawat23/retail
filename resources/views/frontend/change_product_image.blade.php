
<div class="product-details-tab">
        @php
            $photos = explode(',', $product_variant_image->image);
            $videos = explode(',', $product_video->videos);
            $videos = (isset($videos[0]) && !empty($videos[0]))?$videos:array();
            $product_image = get_product_image($product_video->thumbnail_img);

        @endphp
    <div id="img-1" class="zoomWrapper single-zoom">
        <a href="#">
            <?php 
            $i=1; 
            foreach ($photos as $key => $photo){
                if ($i==1) {                  
                ?>
            <img loading="lazy" 
            class="product-zoom-thumb" 
            id="zoom2" 
            src="{{ uploaded_asset($photo) }}" 
            data-zoom-image="{{ uploaded_asset($photo) }}"  
            title="{{ uploaded_asset($photo) }}" 
            alt="{{ uploaded_asset($photo) }}"
            onerror="this.onerror=null;this.src='{{ $product_image }}';"
            >
            <?php
                    }
                    $i++;
            }
            ?>

        </a>
    </div>
    
    <div class="single-zoom-thumb">
        <ul class="s-tab-zoom owl-carousel single-product-active" id="gallery_02">
            
            @foreach ($photos as $key => $photo)
                <li class="product-zoom-gallery">
                    <a href="#" class="elevatezoom-gallery" data-update="" data-image="{{ uploaded_asset($photo) }}" data-zoom-image="{{ uploaded_asset($photo) }}">
                        <img loading="lazy"
                        class="lazyload mw-100 size-120px mx-auto " 
                        src="{{ uploaded_asset($photo) }}" 
                        alt="{{ uploaded_asset($photo) }}"
                        onerror="this.onerror=null;this.src='{{ $product_image }}';"
                        >
                    </a>

                </li>
            @endforeach
        </ul>
    </div>
</div>

<style type="text/css">
    .single-zoom-thumb {
    width: 100% !important;
}
.product_img_all_new {
    padding-right: 20px !important;
}
.product_img_all_new ul{
    padding-left: 0px !important;
}
</style>
<script type="text/javascript">
    /*---single product activation---*/
$('.single-product-active').owlCarousel({
    
    autoplay: true,
    loop: false,
    nav: true,
    autoplay: false,
    autoplayTimeout: 8000,
    items: 1,
    margin:15,
    dots:false,
    navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
    responsiveClass:true,
    responsive:{
            0:{
            items:1,
        },
        320:{
            items:3,
        },
        992:{
            items:3,
        },
        1200:{
            items:5,
        },


      }
});
/*$('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:5
        }
    }
})*/


$('.owl-tanants').owlCarousel('destroy');
$(".client_list_sec .item ul li").unwrap();
$(".client_list_sec .item li").unwrap();
//wrap every 2 li in ul
var ul = $(".client_list_sec li");
for(var i=0; i<ul.length; i+=2){
    ul.slice(i, i+2).wrapAll('<ul></ul>');
}
$(".client_list_sec ul").wrap('<div class="item"></div>');
$('.owl-tanants').owlCarousel({
    rtl:true,
    loop:true,
    touchDrag:true,
    autoplay:false,
    autoplayTimeout:2000,
    nav:true,
    items:1
});

$("#zoom2").elevateZoom({
        gallery:'gallery_02', 
        responsive : true,
        cursor: 'crosshair',
        zoomType : 'inner'
    
    });
</script>
