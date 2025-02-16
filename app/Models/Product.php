<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Product extends Model
{

    protected $fillable = [
        'name', 'added_by', 'user_id', 'category_id', 'brand_id', 'photos', 'thumbnail_img','hover_img','hover_video', 'video_provider', 'video_link', 
        'tags', 'description', 'unit_price', 'purchase_price', 'variant_product', 'attributes', 'choice_options', 'unit', 'slug', 
        'approved', 'choice_options', 'colors', 'variations', 'todays_deal', 'published', 'approved', 'stock_visibility_state', 
        'cash_on_delivery', 'featured', 'seller_featured', 'current_stock', 'unit', 'min_qty', 'low_stock_quantity', 
        'discount', 'discount_type', 'discount_start_date', 'discount_end_date', 'shipping_type', 'shipping_cost', 'is_quantity_multiplied',
        'est_shipping_days', 'meta_title', 'meta_description','meta_img', 'pdf', 'slug', 'rating', 'barcode', 'digital', 'external_link', 
        'external_link_btn','refundable','earn_point','product_group_id','gemstone_size','gemstone_weight','product_weight','is_group_main_product','is_parent','is_price_show','is_new_arrival','made_to_order','list_product','videos','img_alt_text'
    ];

    protected $with = ['product_translations', 'taxes', 'related_products'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $product_translations = $this->product_translations->where('lang', $lang)->first();
        return $product_translations != null ? $product_translations->$field : $this->$field;
    }

    public function product_translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 1);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function taxes()
    {
        return $this->hasMany(ProductTax::class);
    }
    public function related_products()
    {
        return $this->hasMany(RelatedProduct::class);
    }
    public function product_variant_image()
    {
        return $this->hasMany(ProductVariantImage::class);
    }

    public function flash_deal_product()
    {
        return $this->hasOne(FlashDealProduct::class);
    }

    public function bids()
    {
        return $this->hasMany(AuctionProductBid::class);
    }

    public function scopePhysical($query)
    {
        return $query->where('digital', 0);
    }
	
	public function check_is_main_group_product($postData, $result){
		
		//$result = array();
		
		if($postData['product_action_type'] == "edit"){
			$total_grouped_main_products = Product::select('id')->where('product_group_id',$postData['product_group_id'])->where('id','!=',$postData['product_id'])->where('is_group_main_product',1)->count();
			
			if($total_grouped_main_products == 0){
				$result['status'] = 2;
				$result['msg'] = 'please make another product main before change this';
			}else{
				$result['status'] = 1;
				$result['msg'] = 'you can change this status';
			}
		}
		
		return $result;
	}
}
