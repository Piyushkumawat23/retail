<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\ProductTranslation;
use App\Models\Category;
use App\Models\ProductTax;
use App\Models\AttributeValue;
use App\Models\Cart;
use App\Models\ProductGroup;
use App\Models\ProductVariantImage;
use App\Models\RelatedProduct;
use App\Models\FrontRelatedProduct;
use App\Models\Brand;
use App\Models\BrandTranslation;
use App\Models\ProductStock;
use Carbon\Carbon;
use Combinations;
use CoreComponentRepository;
use Artisan;
use Cache;
use Str;
use App\Services\ProductService;
use App\Services\ProductTaxService;
use App\Services\ProductFlashDealService;
use App\Services\ProductStockService;
use App\Services\ProductRelatedService;
use App\Services\ProductVariantImageService;

class ProductController extends Controller
{
    protected $productService;
    protected $productTaxService;
    protected $productFlashDealService;
    protected $productStockService;
    protected $productRelatedService;
    protected $productVariantImageService;

    public function __construct(
        ProductService $productService,
        ProductTaxService $productTaxService,
        ProductFlashDealService $productFlashDealService,
        ProductStockService $productStockService,
        ProductRelatedService $productRelatedService,
        ProductVariantImageService $productVariantImageService
    ) {
        $this->productService = $productService;
        $this->productTaxService = $productTaxService;
        $this->productFlashDealService = $productFlashDealService;
        $this->productStockService = $productStockService;
        $this->productRelatedService = $productRelatedService;
        $this->productVariantImageService = $productVariantImageService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_products(Request $request)
    {

        //CoreComponentRepository::instantiateShopRepository();

        $type = 'In House';
        $col_name = null;
        $query = null;
        $sort_search = null;
        $is_parent = 3;
        $products = Product::where('added_by', 'admin')->where('auction_product', 0)->where('wholesale_product', 0);

        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }

        

        $products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate(15);

        return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'sort_search', 'is_parent'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function seller_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::where('added_by', 'seller')->where('auction_product', 0)->where('wholesale_product', 0);
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null) {
            $products = $products
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }

        $products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate(15);
        $type = 'Seller';

        return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }

    public function all_products(Request $request)
    {

        //echo "<pre>";print_r($_REQUEST);die;
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $is_parent = 3;
        $system_search = null;

        $brands = Brand::orderBy('name', 'asc');

        $products = Product::with('brand')->orderBy('created_at', 'desc')->where('auction_product', 0)->where('wholesale_product', 0);
        
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        
        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        /*echo '<pre>col_name';print_r($col_name);
        echo '<pre>sort_type';print_r($sort_type);
        echo '<pre>query';print_r($query);die;*/
        if ($request->is_parent != null) {
            $is_parent = $request->is_parent;
            if ($request->is_parent == 1) {
                $products = $products->where('is_parent', 0);
            }elseif ($request->is_parent == 2) {
                $products = $products->where('is_parent', 1);
            }
            
        }
        $products = $products->paginate(15);
        $type = 'All';

        return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search','is_parent'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        CoreComponentRepository::initializeCache();
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        
        $product_groups = ProductGroup::select(['id','name'])->orderBy('name','asc')->get();
    
        $stone_types = array('gemstone'=>'Gemstone','birthstone'=>'Birthstone');
        return view('backend.product.products.create', compact('categories','product_groups','stone_types'));
    }

    public function add_more_choice_option(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('attribute_id', $request->attribute_id)->orderByRaw('value+0 asc')->get();

        $html = '';
        //sort($all_attribute_values);
        
       // sort($all_attribute_values);
        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';
        }

        echo json_encode($html);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
  
        //echo "<pre>";print_r($_POST);
        //$brandIdsString = implode(',', $request->input('brand_id'));
        $product = $this->productService->store($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]));
        
        /*if(isset($_POST['product_group_id']) && !empty($_POST['product_group_id'])){
            
            if(isset($_POST['is_group_main_product']) && !empty($_POST['is_group_main_product'])){
                
                $grouped_main_products = Product::select('id')->where('product_group_id',$_POST['product_group_id'])->where('id','!=',$product->id)->where('is_group_main_product',1)->get();
                
                if(!empty($grouped_main_products)){
                    foreach($grouped_main_products as $grouped_main_product){
                        $product_grouped = Product::find($grouped_main_product->id);
                        $product_grouped->is_group_main_product = 0;
                        $product_grouped->save();
                    }
                }
                
                $updateProduct = Product::find($product->id);
                $updateProduct->is_group_main_product = 1;
                $updateProduct->save();
            }else{
                
                $grouped_main_products = Product::select('id')->where('product_group_id',$_POST['product_group_id'])->where('is_group_main_product',1)->count();
                
                if($grouped_main_products == 0){
                    
                    $updateProduct = Product::find($product->id);
                    $updateProduct->is_group_main_product = 1;
                    $updateProduct->save();
                    
                }
            }
        }*/
        
        $request->merge(['product_id' => $product->id]);

        //VAT & Tax
        if($request->tax_id) {
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }

        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);
        //Product Stock
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);
        //Product Related
        $this->productRelatedService->store($request->only([
            'parent_id','product_id'
        ]), $product);
        //Product Related
        $this->productVariantImageService->store($request->only([
            'variant_images','product_id'
        ]), $product);
        // Product Translations
        $request->merge(['lang' => env('DEFAULT_LANGUAGE')]);
        ProductTranslation::create($request->only([
            'lang', 'name', 'unit', 'description', 'product_id'
        ]));
        
        flash(translate('Product has been inserted successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return redirect()->route('products.admin');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function admin_product_edit(Request $request, $id)
    {
        
        //CoreComponentRepository::initializeCache();
        $selected = array();
        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
            return redirect('admin/digitalproducts/' . $id . '/edit');
        }

        foreach ($product->related_products as $key => $value) {
            $selected[$value->parent_id]=$value->parent_id;
        }

        
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        $allProduct = Product::select(['id','name'])->where('is_parent', 0)
            ->where('category_id', $product->category_id)
            ->get();
            
        $product_groups = ProductGroup::select(['id','name'])->orderBy('name','asc')->get();
        $stone_types = array('gemstone'=>'Gemstone','birthstone'=>'Birthstone');
        
        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang','product_groups','stone_types','allProduct','selected'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function seller_product_edit(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
            return redirect('digitalproducts/' . $id . '/edit');
        }
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        // $categories = Category::all();
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        //
        //Product
        $product = $this->productService->update($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);
        
        //echo '<pre>POST'; print_r($product); die;
        
        //Product Stock
        foreach ($product->stocks as $key => $stock) {
            $stock->delete();
        }
        
        $request->merge(['product_id' => $product->id]);
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        //VAT & Tax
        if ($request->tax_id) {
            ProductTax::where('product_id', $product->id)->delete();
            $request->merge(['product_id' => $product->id]);
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }


        //Product Related
        $this->productRelatedService->store($request->only([
            'parent_id','product_id'
        ]), $product);

        //Product Related
        $this->productVariantImageService->store($request->only([
            'variant_images','product_id'
        ]), $product);

        // Product Translations
        ProductTranslation::updateOrCreate(
            $request->only([
                'lang', 'product_id'
            ]),
            $request->only([
                'name', 'unit', 'description'
            ])
        );
        //echo '<pre>POST'; print_r($product); die;
        flash(translate('Product has been updated successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->product_translations()->delete();
        $product->stocks()->delete();
        $product->taxes()->delete();

        if (Product::destroy($id)) {
            Cart::where('product_id', $id)->delete();

            flash(translate('Product has been deleted successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return back();
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function bulk_product_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $product_id) {
                $this->destroy($product_id);
            }
        }

        return 1;
    }

    /**
     * Duplicates the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function duplicate(Request $request, $id)
    {
        $product = Product::find($id);

        $product_new = $product->replicate();
        $product_new->slug = $product_new->slug . '-' . Str::random(5);
        $product_new->save();
        
        //Product Stock
        $this->productStockService->product_duplicate_store($product->stocks, $product_new);

        //VAT & Tax
        $this->productTaxService->product_duplicate_store($product->taxes, $product_new);

        flash(translate('Product has been duplicated successfully'))->success();
        if ($request->type == 'In House')
            return redirect()->route('products.admin');
        elseif ($request->type == 'Seller')
            return redirect()->route('products.seller');
        elseif ($request->type == 'All')
            return redirect()->route('products.all');
    }

    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }

    public function updateTodaysDeal(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->todays_deal = $request->status;
        $product->save();
        Cache::forget('todays_deal_products');
        return 1;
    }

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription') && $request->status == 1) {
            $shop = $product->user->shop;
            if (
                $shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
            ) {
                return 0;
            }
        }

        $product->save();
        return 1;
    }

    public function updateProductApproval(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->approved = $request->approved;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription')) {
            $shop = $product->user->shop;
            if (
                $shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
            ) {
                return 0;
            }
        }

        $product->save();
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->featured = $request->status;
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    /*public function img_combination(Request $request)
    {
        
        $options = array();

        if ($request->has('choice_no')) {
            echo "<pre>";print_r($_REQUEST);die;
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                if($request['choice'][$key] == 'Materials'){

                    if (isset($request[$name])) {
                        foreach ($request[$name] as $key => $item) {
                            // array_push($data, $item->value);
                            array_push($data, $item);
                        }
                    }

                    array_push($options, $data);
                }

            }
        }

        $combinations = Combinations::makeCombinations($options);
        //echo "<pre>";print_r($options);die;
        return view('backend.product.products.img_combinations', compact('options'));
    }*/
    public function img_combination(Request $request)
    {
        
        $options = array();

        if ($request->has('choice_no')) {
            
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                if($request['choice'][$key] == 'Metals'){

                    if (isset($request[$name])) {
                        foreach ($request[$name] as $key => $item) {
                            // array_push($data, $item->value);
                            array_push($data, $item);
                        }
                    }

                    array_push($options, $data);
                }

            }
        }

        $combinations = Combinations::makeCombinations($options);
        //echo "<pre>";print_r($options);die;
        return view('backend.product.products.img_combinations', compact('options'));
    }

    public function img_combination_edit(Request $request)
    {
        $product = Product::with('product_variant_image')->findOrFail($request->id);
        $options = array();

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                if($request['choice'][$key] == 'Metals'){
                    foreach ($request[$name] as $key => $item) {
                        // array_push($data, $item->value);
                        array_push($data, $item);
                    }
                    array_push($options, $data);
                }

            }
        }

        $combinations = Combinations::makeCombinations($options);
        //$product_variant_image = ProductVariantImage::where('product_id', $request->id)->get();
        
        /*echo "<pre>product";print_r($product);
        echo "<pre>";print_r($options);die;*/
        return view('backend.product.products.img_combination_edit', compact('options','product'));
    }

    public function sku_combination(Request $request)
    {
      //  echo "<pre>";print_r($_POST);die;
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                /*echo "<pre>===>";print_r($_POST);
                echo "<pre>eeerdWWWW";print_r($name);
                echo "<pre>eeerd";print_r($request[$name]);*/
        
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                if (isset($request[$name])) {
                    foreach ($request[$name] as $key => $item) {
                          // array_push($data, $item->value);
                        array_push($data, $item);
                    }
                    array_push($options, $data);
                }
                
                
            }
        }
        //dump($options);
        //die;
        $combinations = Combinations::makeCombinations($options);

        $category_name = '';
        if (!empty($request->category_id)) {
            $category = \App\Models\Category::find($request->category_id);
            $category_name = $category ? substr($category->name, 0, 2) : ''; // Use empty string if not found
        }

        // Retrieve brand name based on the ID, or return an empty string if not selected
        $brand_name = '';
        if (!empty($request->brand_id) && is_array($request->brand_id) && isset($request->brand_id[0])) {
            $brand = \App\Models\Brand::find($request->brand_id[0]); // Assuming single brand
            $brand_name = $brand ? substr($brand->name, 0, 2) : ''; // Use empty string if not found
        }

       
       /* $lastProductId = \App\Models\Product::latest()->first()->id ?? 0; // Default to 0 if no products exist
        $newProductId = $lastProductId + 1;*/
        // Add color abbreviation (first 3 characters of the color)
    //dump($combinations);

        $sku_list = [];
        foreach ($combinations as $combination) {
           // Start building the SKU
            $sku = '';

            // Add category name if it exists
            if (!empty($category_name)) {
                $sku .= $category_name . '-';
            }

            // Generate unique 4-digit number
            $unique_number = mt_rand(1000, 9999);
            $sku .= $unique_number . '-';


            // Check if $combination[1] exists, otherwise check $combination[0]
            if (isset($combination[1]) && !is_numeric($combination[1])) {
                $color = $combination[1]; // Use the second element as the color
            } elseif (isset($combination[0]) && !is_numeric($combination[0])) {
                $color = $combination[0]; // If the second element is not the color, use the first element
            } else {
                $color = ''; // Set to null if no color is found
            }

            // Add color abbreviation if a valid color is found
            if ($color) {
                $color_abbreviation = substr($color, 0, 3); // Extract first 3 characters
                $sku .= $color_abbreviation . '-';
            }

            // Add brand name if it exists
            if (!empty($brand_name)) {
                $sku .= $brand_name . '-';
            }

            // Add combination details
            $sku .= implode('-', $combination);

            // Remove any trailing hyphens in case category_name or brand_name were empty
            $sku = rtrim($sku, '-');

            // Add the SKU to the list
            $sku_list[] = $sku;
        }
        //dump($sku_list);
       /* echo "<pre>options";print_r($options);
        echo "<pre>";print_r($combinations);die;*/
        return view('backend.product.products.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name','sku_list'));
    }

public function updateAllSkuCombinations()
{
    // Get all products
    $products = Product::all();

    foreach ($products as $product) {
        // Decode choice_options data from the product table
        $choice_options = json_decode($product->choice_options, true) ?? [];

        // Prepare options array, fetching attribute and attribute values
        $options = [];
        foreach ($choice_options as $choice) {
            $attribute_id = $choice['attribute_id'];
            $values = $choice['values'] ?? [];

            // Get attribute object
            $attribute = Attribute::find($attribute_id);
            if ($attribute) {
                // Get attribute values related to the attribute
                $attribute_values = $attribute->attribute_values()
                    ->whereIn('value', $values)
                    ->pluck('value')
                    ->toArray();

                // Only add to options if we have attribute values
                if (!empty($attribute_values)) {
                    $options[] = $attribute_values;
                }
            }
        }

        // Generate combinations based on options
        $combinations = Combinations::makeCombinations($options);
//dump($combinations);
        // Get category and brand abbreviations
        $category_name = $product->category ? substr($product->category->name, 0, 2) : '';
        $brand_name = $product->brand ? substr($product->brand->name, 0, 2) : '';

        // Fetch all ProductStock entries for the product where is_sku_updated is 0
        $productStocks = ProductStock::where('product_id', $product->id)
            ->where('is_sku_updated', 0)
            ->get();

        foreach ($productStocks as $index => $productStock) {
            // Check if we have a combination available for the current index
            if (!isset($combinations[$index])) break;

            $combination = $combinations[$index];

            // Prepare the base SKU
            $sku = $category_name ? $category_name . '-' : '';
            
            // Generate a unique random number for each SKU, ensuring uniqueness
            $uniqueNumber = mt_rand(1000, 9999) + $index; // Adding index for uniqueness
            $sku .= $uniqueNumber . '-';

            // Determine the color from the combination
            $color = '';
            foreach ($combination as $value) {
                if (!is_numeric($value)) {
                    $color = $value; // Get the color value if it's not numeric
                    break;
                }
            }

            // Append color to SKU
            $sku .= $color ? substr($color, 0, 3) . '-' : '';
            $sku .= $brand_name ? $brand_name . '-' : '';

            // Append each value from the combination
            foreach ($combination as $value) {
                $sku .= $value . '-';
            }

            $sku = rtrim($sku, '-'); // Trim trailing hyphens

            // Update the ProductStock entry with the unique SKU and set is_sku_updated to 1
            $productStock->sku = $sku;
            $productStock->is_sku_updated = 1;
            $productStock->save(); // Save the changes to the database
        }
    }

    return response()->json(['message' => 'test All SKUs updated successfully'], 200);
}





    public function sku_combination_edit(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $product_name = $request->name;
        $unit_price = $request->unit_price;

            // Handle choice options
            /*if ($request->has('choice_no')) {
                foreach ($request->choice_no as $key => $no) {
                    $name = 'choice_options_' . $no;
                    if ($request->has($name)) {
                        $options[] = $request[$name]; // Add choice options to options array
                    }
                }
            }*/

               // Handle choice options
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                if ($request->has($name)) {
                    $data = [];
                    foreach ($request[$name] as $item) {
                        array_push($data, $item); // Add choice options to array
                    }
                    array_push($options, $data); // Add choices array to options
                }
            }
        }

        $combinations = Combinations::makeCombinations($options);

            // Retrieve category and brand names based on the product
         /*   $category_name = $product->category ? substr($product->category->name, 0, 2) : 'Unknown';
            $brand_name = $product->brand ? substr($product->brand->name, 0, 2) : 'Unknown';
*/
            $category_name = '';
            if (!empty($product->category)) {
                $category_name = $product->category ? substr($product->category->name, 0, 2) : ''; // Use empty string if not found
            }

            // Retrieve brand name based on the product, or use an empty string if not found
            $brand_name = '';
            if (!empty($product->brand)) {
                $brand_name = $product->brand ? substr($product->brand->name, 0, 2) : ''; // Use empty string if not found
            }

            // Assuming the product ID does not change for editing
            //$newProductId = $product->id;

            // Generate SKU list
            $sku_list = [];
            foreach ($combinations as $combination) {


                // Start building the SKU
            $sku = '';

            // Add category name if it exists
            if (!empty($category_name)) {
                $sku .= $category_name . '-';
            }

            // Add product ID
            //$sku .= $newProductId . '-';

            // Generate unique 4-digit number
            $unique_number = mt_rand(1000, 9999);
            $sku .= $unique_number . '-';

            // Add color abbreviation if the combination contains colors
            if (isset($combination[1])) {
                $color = $combination[1]; // Color is the second element in the combination
                $color_abbreviation = substr($color, 0, 3); // First 3 letters of the color
                $sku .= $color_abbreviation . '-';
            }
            // Add brand name if it exists
            if (!empty($brand_name)) {
                $sku .= $brand_name . '-';
            }

            // Add combination details
            $sku .= implode('-', $combination);

            // Remove any trailing hyphens in case category_name or brand_name were empty
            $sku = rtrim($sku, '-');

            // Add the SKU to the list
            $sku_list[] = $sku;

            }
            //dump($sku_list);
        return view('backend.product.products.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product', 'sku_list'));
    }
    
    public function ajax_request(Request $request){
        $result = array();
        $result['status'] = 0;
        $result['msg'] = 'something went wrong';
        
        $action = (isset($request->action) && !empty($request->action)) ? $request->action : '';
        $product = new Product;
        /*if($action == "is_group_main_product"){
            
            $postData = array();
            $postData['product_group_id'] =  (isset($request->selected_product_group_id) && !empty($request->selected_product_group_id)) ? $request->selected_product_group_id : '';
            $postData['product_action_type'] =  (isset($request->product_action_type) && !empty($request->product_action_type)) ? $request->product_action_type : '';
            $postData['product_id'] =  (isset($request->product_id) && !empty($request->product_id)) ? $request->product_id : '';
            
            if(!empty($postData['product_group_id']) && !empty($postData['product_group_id']) && !empty($postData['product_group_id'])){
                $result = $product->check_is_main_group_product($postData,$result);
                
            }
            
        }*/
        
        echo json_encode($result); exit();
    }
    
    public function get_all_main_product(Request $request)
    {
        $products = Product::where('category_id', $request->category_id)->where('is_parent',0)->get();
        $html = '<option value="">'.translate("Select Product").'</option>';
        //echo "<pre>";print_r($products);die;
        foreach ($products as $product) {
            $html .= '<option value="' . $product->id . '">' . $product->name . '</option>';
        }
        
        echo json_encode($html);
    }


    /**
     * product_child the specified resource from storage.
     *
     * @param  int  $main_product_id
     * @return \Illuminate\Http\Response
     */

    public function product_child($main_product_id='')
    {
        $products =array();
        $subMain =array();
        if (!empty($main_product_id)) {
            $sub_products = RelatedProduct::select('product_id')->where('parent_id', $main_product_id)->get()->toArray();
            foreach ($sub_products as $value) {
                $subMain[$value['product_id']] = $value['product_id'];
            }
            //echo "<pre>";print_r($subMain);die;
            if (!empty($subMain)){
                $products = Product::whereIN('id', $subMain)->where('is_parent',1);
                $products = $products->paginate(15);
            }
            
        }
        
        
        return view('backend.product.products.product_child', compact('products'));
    }

    /**
     * product_child_detail the specified resource from storage.
     *
     * @param  int  $main_product_id
     * @return \Illuminate\Http\Response
     */

    public function product_child_detail(Request $request)
    {
        $products =array();
        $subMain =array();
        $mainid = $request->id;
        if (!empty($request->id)) {
            $sub_products = RelatedProduct::select('product_id')->where('parent_id', $request->id)->get()->toArray();
            foreach ($sub_products as $value) {
                $subMain[$value['product_id']] = $value['product_id'];
            }
            
            if (!empty($subMain)){
                $products = Product::whereIN('id', $subMain)->where('is_parent',1);
                $products = $products->paginate(15);
            }
            
        }
                
        /*$allProducts = Product::with(['related_products' => function($q) {
                        $q->select('product_id', 'parent_id', 'id');
                        $q->where('parent_id', '=', 0);
                    }])->where('is_group_main_product', 0)->where('is_parent',1)->get()->toArray();*/

        $allProducts = Product::select('name', 'id')->where('is_group_main_product', 0)->where('is_parent',1)->get()->toArray();
        $allProductsData = array();
        foreach ($allProducts as $key => $value) {
            $productsData = RelatedProduct::select('id')->where('product_id', $value['id'])->where('parent_id', 0)->get()->toArray();
            if (!empty($productsData)) {
                $allProductsData[]=$value;
            }
            
        }
        //echo "<pre>";print_r($allProductsData);die;

        return view('backend.product.products.product_child_detail', compact('products','mainid','allProductsData'));
    }

    /**
     * product_child_detail the specified resource from storage.
     *
     * @param  int  $main_product_id
     * @return \Illuminate\Http\Response
     */

    public function child_destroy(Request $request)
    {
        //echo "<pre>";print_r($_POST);die;
        if (!empty($request->id) && !empty($request->mainid)) {
            $product = RelatedProduct::where('product_id', $request->id)->where('parent_id', $request->mainid)->first();
            $product->parent_id = 0;
            if ($product->save()) {
                Artisan::call('view:clear');
                Artisan::call('cache:clear');
                return 1;
            }
        }

        return 0;
    }

    /**
     * child_add the specified resource from storage.
     *
     * @param  int  $main_product_id
     * @return \Illuminate\Http\Response
     */

    public function child_add(Request $request)
    {
        //echo "<pre>";print_r($_POST);die;
        if (isset($request->selected) && !empty($request->selected) && !empty($request->mainid)) {
            foreach ($request->selected as $key => $value) {
                
                $product = RelatedProduct::where('product_id', $value)->where('parent_id', 0)->first();
                if (!empty($product)) {
                    $product->parent_id = $request->mainid;
                    $product->save(); 
                }
                                              
            }

            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;

        }

        return 0;
    }


    /*public function front_related_products($product_id)
    {
        $allProducts = Product::select('name', 'id')->get()->toArray();
        $product_data = Product::select('name', 'id')->where('id', $product_id)->first();
        return view('backend.product.products.front_related_products', compact('product_data','allProductsData'));
    }*/

    /**
     * product_child_detail the specified resource from storage.
     *
     * @param  int  $main_product_id
     * @return \Illuminate\Http\Response
     */

    public function front_related_products(Request $request,$id)
    {
        $products =array();
        $product =array();
        $subMain =array();
        $mainid = $id;
        $category_id = $id;
        $is_related_product = 0;
        
        //CoreComponentRepository::initializeCache();

        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        $products = Product::where('id','!=',$mainid);

        if ($request->has('category_id') && $request->category_id != null) {
            $products = $products->where('category_id', $request->category_id);
            $category_id = $request->category_id;
        }
        if ($request->has('is_related_product') && $request->is_related_product != null) {
            if ($request->is_related_product == 1) {
                $is_related_product = 1;
            }
            
        }

        $products = $products->paginate(30);
        //echo "<pre>";print_r($products);die;
        foreach ($products as $key => $value) {
            $value['is_selected'] = $this->relatedProductIs($value->id,$mainid);
            if ($is_related_product == 1 && $value['is_selected'] == 0) {
                unset($products[$key]);
            }

        }
        


        $mainproduct = Product::select('id','name')->where('id', $mainid)->first();            
        
              
        return view('backend.product.products.front_related_products', compact('products','mainid','mainproduct','categories','category_id','is_related_product'));
    }  

    public function relatedProductIs($product_id,$parent_id)
    {
        $relatedproduct = FrontRelatedProduct::select('id')->where('parent_id', $parent_id)->where('product_id', $product_id)->first();
        if (!empty($relatedproduct)) {
            return 1;
        }
        return 0;
    }

    public function update_related(Request $request)
    {
        $mainproduct = Product::select('id','name')->where('id', $request->id)->first();

        if(!empty($mainproduct)){

            $relatedproduct = FrontRelatedProduct::select('id')->where('parent_id', $request->mainid)->where('product_id', $request->id)->first();
            if (!empty($relatedproduct)) {
                FrontRelatedProduct::where('id', $relatedproduct->id)->delete();
            }
            $frontrelatedProduct = new FrontRelatedProduct;                
            $frontrelatedProduct->parent_id = $request->mainid;                
            $frontrelatedProduct->product_id = $request->id;                
            $frontrelatedProduct->save();
            return 1;
            

            
        }

        return 0;

    }

    public function related_product_destroy($product_id,$mainid)
    {

        $relatedproduct = FrontRelatedProduct::select('id')->where('parent_id', $mainid)->where('product_id', $product_id)->first();
        if (!empty($relatedproduct)) {
            FrontRelatedProduct::where('id', $relatedproduct->id)->delete();
            flash(translate('Related Product Remove successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return back();
        }
        return back();

    }  

}
