<?php

namespace App\Services;

use App\Models\Color;
use App\Models\Product;
use App\Models\User;
use App\Utility\ProductUtility;
use Combinations;
use Illuminate\Support\Str;

class ProductService
{
    public function store(array $data)
    {
        //echo'<pre>store';print_r($data);die;
        $collection = collect($data);
        $collection['is_parent'] = 0;
        $collection['is_group_main_product'] = 1;
    
        if (isset($collection['parent_id'][0]) && !empty($collection['parent_id'][0])) {
            $collection['is_parent'] = 1;
            $collection['is_group_main_product'] = 0;
        }
    
        $approved = 1;
    
        if (auth()->user()->user_type == 'seller') {
            $user_id = auth()->user()->id;
    
            if (get_setting('product_approve_by_admin') == 1) {
                $approved = 0;
            }
        } else {
            $user_id = User::where('user_type', 'admin')->first()->id;
        }
    
        $tags = array();
    
        if ($collection['tags'][0] != null) {
            foreach (json_decode($collection['tags'][0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
    
        $collection['tags'] = implode(',', $tags);
    
        $discount_start_date = null;
        $discount_end_date = null;
    
        if ($collection['date_range'] != null) {
            $date_var = explode(" to ", $collection['date_range']);
            $discount_start_date = strtotime($date_var[0]);
            $discount_end_date = strtotime($date_var[1]);
        }
    
        unset($collection['date_range']);
    
        if ($collection['pdf']) {
            $collection['pdf'] = request()->pdf->store('uploads/products/pdf');
        }
    
        if ($collection['meta_title'] == null) {
            $collection['meta_title'] = $collection['name'];
        }
    
        if ($collection['meta_description'] == null) {
            $collection['meta_description'] = strip_tags($collection['description']);
        }
    
        if ($collection['meta_img'] == null) {
            $collection['meta_img'] = $collection['thumbnail_img'];
        }
    
        $shipping_cost = 0;
    
        if (isset($collection['shipping_type'])) {
            if ($collection['shipping_type'] == 'free') {
                $shipping_cost = 0;
            } elseif ($collection['shipping_type'] == 'flat_rate') {
                $shipping_cost = $collection['flat_shipping_cost'];
                unset($collection['flat_shipping_cost']);
            }
        }
    
        $slug = Str::slug($collection['name']);
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;
    
        $colors = json_encode(array());
    
        if (
            isset($collection['colors_active']) &&
            $collection['colors_active'] &&
            $collection['colors'] &&
            count($collection['colors']) > 0
        ) {
            $colors = json_encode($collection['colors']);
        }
    
        $options = ProductUtility::get_attribute_options($collection);
    
        $combinations = Combinations::makeCombinations($options);
    
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = ProductUtility::get_combination_string($combination, $collection);
    
                unset($collection['price_' . str_replace('.', '_', $str)]);
                unset($collection['sku_' . str_replace('.', '_', $str)]);
                unset($collection['qty_' . str_replace('.', '_', $str)]);
                unset($collection['img_' . str_replace('.', '_', $str)]);
            }
        }
    
        unset($collection['colors_active']);
    
        $choice_options = array();
    
        if (isset($collection['choice_no']) && $collection['choice_no']) {
            $str = '';
            $item = array();
    
            foreach ($collection['choice_no'] as $key => $no) {
                $str = 'choice_options_' . $no;
                $item['attribute_id'] = $no;
    
                $attribute_data = array();
    
                foreach ($collection[$str] as $key => $eachValue) {
                    array_push($attribute_data, $eachValue);
                }
    
                unset($collection[$str]);
    
                $item['values'] = $attribute_data;
                array_push($choice_options, $item);
            }
        }
    
        $choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);
        //$collection['brand_id'] = '';
        
        // Convert array to string for brand_id
        if (isset($collection['brand_id']) && is_array($collection['brand_id'])) {
            $collection['brand_id'] = implode(',', $collection['brand_id']);
        } elseif (!isset($collection['brand_id'])) {
            // Handle the case where 'brand_id' is not set in $collection
            // You might want to set a default value or handle this case based on your application logic
            $collection['brand_id'] = ''; // Or whatever default value you want to set
        }
        
        
        // Encode arrays into JSON strings
        $collection['choice_options'] = $choice_options;
        //echo '<pre>choice_options';print_r($collection);die;
        
        if (isset($collection['choice_no']) && $collection['choice_no']) {
            $attributes = json_encode($collection['choice_no']);
            unset($collection['choice_no']);
        } else {
            $attributes = json_encode(array());
        }
    
        $published = 1;
    
        if ($collection['button'] == 'unpublish' || $collection['button'] == 'draft') {
            $published = 0;
        }
    
        unset($collection['button']);
    
        $data = $collection->merge(compact(
            'user_id',
            'approved',
            'discount_start_date',
            'discount_end_date',
            'shipping_cost',
            'slug',
            'colors',
            'choice_options',
            'attributes',
            'published'
        ))->toArray();
        //echo'<pre>';print_r($data);die;
        return Product::create($data);
    }


    public function update(array $data, Product $product)
    {
		//echo'<pre>';print_r($data);die;
        $collection = collect($data);
		//echo '<pre>collection'; print_r($collection); die;
		
		/*if(isset($collection['is_group_main_product']) && !empty($collection['is_group_main_product']) && isset($collection['product_group_id']) && !empty($collection['product_group_id'])){
			
			$grouped_main_products = Product::select('id')->where('product_group_id',$collection['product_group_id'])->where('id','!=',$collection['id'])->where('is_group_main_product',1)->get();
			
			if(!empty($grouped_main_products)){
				foreach($grouped_main_products as $grouped_main_product){
					$product_grouped = Product::find($grouped_main_product->id);
					$product_grouped->is_group_main_product = 0;
					$product_grouped->save();
				}
			}
		}*/
		$collection['is_price_show'] = (isset($collection['is_price_show']) && !empty($collection['is_price_show'])) ? $collection['is_price_show'] : 0;
        $collection['is_new_arrival'] = (isset($collection['is_new_arrival']) && !empty($collection['is_new_arrival'])) ? $collection['is_new_arrival'] : 0;
        $collection['list_product'] = (isset($collection['list_product']) && !empty($collection['list_product'])) ? $collection['list_product'] : 0;
        $collection['made_to_order'] = (isset($collection['made_to_order']) && !empty($collection['made_to_order'])) ? $collection['made_to_order'] : 0;
        $collection['is_group_main_product'] = (isset($collection['is_group_main_product']) && !empty($collection['is_group_main_product'])) ? $collection['is_group_main_product'] : 0;
        $slug = Str::slug($collection['name']);
        $slug = $collection['slug'] ? Str::slug($collection['slug']) : Str::slug($collection['name']);
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count > 1 ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        if(addon_is_activated('refund_request') && !isset($collection['refundable'])){
            $collection['refundable'] = 0;
        }

        if(!isset($collection['is_quantity_multiplied'])){
            $collection['is_quantity_multiplied'] = 0;
        }

        if(!isset($collection['cash_on_delivery'])){
            $collection['cash_on_delivery'] = 0;
        }
        if(!isset($collection['featured'])){
            $collection['featured'] = 0;
        }
        if(!isset($collection['todays_deal'])){
            $collection['todays_deal'] = 0;
        }

        if ($collection['lang'] != env("DEFAULT_LANGUAGE")) {
            unset($collection['name']);
            unset($collection['unit']);
            unset($collection['description']);
        }
        unset($collection['lang']);

        $tags = array();
        if ($collection['tags'][0] != null) {
            foreach (json_decode($collection['tags'][0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $collection['tags'] = implode(',', $tags);
        $discount_start_date = null;
        $discount_end_date   = null;
        if ($collection['date_range'] != null) {
            $date_var               = explode(" to ", $collection['date_range']);
            $discount_start_date = strtotime($date_var[0]);
            $discount_end_date   = strtotime($date_var[1]);
        }
        unset($collection['date_range']);
        if ($collection['pdf']) {
            $collection['pdf'] = request()->pdf->store('uploads/products/pdf');
        }
        if ($collection['meta_title'] == null) {
            $collection['meta_title'] = $collection['name'];
        }
        if ($collection['meta_description'] == null) {
            $collection['meta_description'] = strip_tags($collection['description']);
        }

        if ($collection['meta_img'] == null) {
            $collection['meta_img'] = $collection['thumbnail_img'];
        }
		
		
        
        $shipping_cost = 0;
        if (isset($collection['shipping_type'])) {
            if ($collection['shipping_type'] == 'free') {
                $shipping_cost = 0;
            } elseif ($collection['shipping_type'] == 'flat_rate') {
                $shipping_cost = $collection['flat_shipping_cost'];
                unset($collection['flat_shipping_cost']);
            }
        }

        $colors = json_encode(array());
        if (
            isset($collection['colors_active']) && 
            $collection['colors_active'] &&
            $collection['colors'] &&
            count($collection['colors']) > 0
        ) {
            $colors = json_encode($collection['colors']);
        }

        $options = ProductUtility::get_attribute_options($collection);

        $combinations = Combinations::makeCombinations($options);
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = ProductUtility::get_combination_string($combination, $collection);

                unset($collection['price_' . str_replace('.', '_', $str)]);
                unset($collection['sku_' . str_replace('.', '_', $str)]);
                unset($collection['qty_' . str_replace('.', '_', $str)]);
                unset($collection['img_' . str_replace('.', '_', $str)]);
            }
        }

        unset($collection['colors_active']);

        $choice_options = array();
        if (isset($collection['choice_no']) && $collection['choice_no']) {
            $str = '';
            $item = array();
            foreach ($collection['choice_no'] as $key => $no) {
                $str = 'choice_options_' . $no;
                $item['attribute_id'] = $no;
                $attribute_data = array();
                // foreach (json_decode($request[$str][0]) as $key => $eachValue) {
                foreach ($collection[$str] as $key => $eachValue) {
                    // array_push($data, $eachValue->value);
                    array_push($attribute_data, $eachValue);
                }
                unset($collection[$str]);

                $item['values'] = $attribute_data;
                array_push($choice_options, $item);
            }
        }

        $choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);
        //$collection['brand_id'] = '';
        
        // Convert array to string for brand_id
        if (isset($collection['brand_id']) && is_array($collection['brand_id'])) {
            $collection['brand_id'] = implode(',', $collection['brand_id']);
        } elseif (!isset($collection['brand_id'])) {
            // Handle the case where 'brand_id' is not set in $collection
            // You might want to set a default value or handle this case based on your application logic
            $collection['brand_id'] = ''; // Or whatever default value you want to set
        }
            
            // Assign updated brand_id to the product
            $product->brand_id = $collection['brand_id'];


        
            
        if (isset($collection['choice_no']) && $collection['choice_no']) {
            $attributes = json_encode($collection['choice_no']);
            unset($collection['choice_no']);
        } else {
            $attributes = json_encode(array());
        }
        

            
        unset($collection['button']);
        
        $data = $collection->merge(compact(
            'discount_start_date',
            'discount_end_date',
            'shipping_cost',
            'slug',
            'colors',
            'choice_options',
            'attributes'
        ))->toArray();
        $product->update($data);

        return $product;
    }
}