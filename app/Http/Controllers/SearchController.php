<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Search;
use App\Models\Product;
use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Shop;
use App\Models\Attribute;
use App\Models\AttributeCategory;
use App\Utility\CategoryUtility;
use App\Models\Faq;

class SearchController extends Controller
{
    public function index(Request $request, $category_id = null, $brand_id = null, $h1_tag = null)
    {
        $query = $request->keyword;
        $sort_by = $request->sort_by;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;
        $attributes = Attribute::all();
        $selected_attribute_values = [];
        $brand_values = [];
        $selected_shape_values = [];
        $colors = Color::all();
        $selected_color = null;
        $conditions = ['published' => 1];
        $faqs = array();
        $seo_content = '';        
        $brand_seo_content = '';
        $products = Product::where($conditions);

        if ($category_id !== null) {
            $category = Category::find($category_id);
            if ($category) {
                $seo_content = $category->seo_content;
                $category_ids = CategoryUtility::children_ids($category_id);
                $category_ids[] = $category_id;
                $products->whereIn('category_id', $category_ids);

                // Fetch the FAQs for the category
                $faqs = Faq::where('category', $category_id)->get();
            }
        }
        
 /*       $brand_seo_content = '';
        if ($brand_id === null && $request->brand !== null) {
            $brand_id = Brand::where('slug', $request->brand)->value('id');
        }*/
           // Fetch SEO content for brand
            if ($brand_id !== null) {
                $brand = Brand::find($brand_id);
                if ($brand) {
                    $brand_seo_content = $brand->seo_content;
                }
            } elseif ($request->brand !== null) {
                $brand = Brand::where('slug', $request->brand)->first();
                if ($brand) {
                    $brand_id = $brand->id;
                    $brand_seo_content = $brand->seo_content;
                }
            }



        if ($min_price && $max_price) {
            $products->whereBetween('unit_price', [$min_price, $max_price]);
        }

        if ($query) {
            $searchController = new SearchController;
            $searchController->store($request);

            $products->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%')
                        ->orWhereHas('product_translations', function ($q) use ($word) {
                            $q->where('name', 'like', '%' . $word . '%');
                        })
                        ->orWhereHas('stocks', function ($q) use ($word) {
                            $q->where('sku', 'like', '%' . $word . '%');
                        });
                }
            });
        }

        switch ($sort_by) {
            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $products->orderBy('created_at', 'asc');
                break;
            case 'price-asc':
                $products->orderBy('unit_price', 'asc');
                break;
            case 'price-desc':
                $products->orderBy('unit_price', 'desc');
                break;
            default:
                $products->orderBy('id', 'desc');
                break;
        }

        if ($request->has('color')) {
            $str = '"' . $request->color . '"';
            $products->where('colors', 'like', '%' . $str . '%');
            $selected_color = $request->color;
        }

        if ($request->has('selected_attribute_values')) {
            $selected_attribute_values = array_filter($request->selected_attribute_values);
            foreach ($selected_attribute_values as $value) {
                $str = '"' . $value . '"';
                $products->where('choice_options', 'like', '%' . $str . '%');
            }
        }

        if ($request->has('brand_values')) {
            $brand_values = array_filter($request->brand_values);
            $products->whereIn('brand_id', $brand_values);
        }

        if ($request->has('selected_shape_values')) {
            $selected_shape_values = array_filter($request->selected_shape_values);
            foreach ($selected_shape_values as $value) {
                $products->where('tags', 'like', '%' . $value . '%');
            }
        }

        if ($brand_id !== null) {
            $products->whereRaw('FIND_IN_SET(?, brand_id)', [$brand_id]);
        }

        $products = filter_products($products)->with('taxes')->paginate(12)->appends(request()->query());
          // Check if there are no products found and redirect to home
        /*if ($products->isEmpty()) {
            return redirect()->route('home');
        }*/
        $startProduct = ($products->currentPage() - 1) * $products->perPage() + 1;
        $endProduct = $startProduct + $products->count() - 1;
        $totalProducts = $products->total();

        return view('frontend.product_listing', compact(
            'products', 
            'query', 
            'category_id', 
            'h1_tag', 
            'brand_id', 
            'sort_by', 
            'seller_id', 
            'min_price', 
            'max_price', 
            'attributes', 
            'selected_attribute_values', 
            'selected_shape_values', 
            'colors', 
            'selected_color', 
            'brand_values', 
            'startProduct', 
            'endProduct', 
            'totalProducts',
            'faqs', // Pass the FAQs to the view
            'seo_content',  // Pass the SEO content to the view
            'brand_seo_content'  // Pass the SEO content to the view
        ));
    }

    public function listing(Request $request)
    {
        return $this->index($request);
    }

    public function listingByCategory(Request $request, $category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();
        if ($category != null) {
            return $this->index($request, $category->id, null, $category->h1_tag);
        }
        abort(404);
    }

    public function listingByBrand(Request $request, $brand_slug)
    {
        $brand = Brand::where('slug', $brand_slug)->first();
        if ($brand != null) {
            return $this->index($request, null, $brand->id, $brand->h1_tag);
        }
        abort(404);
    }


    //Suggestional Search
    public function ajax_search(Request $request)
    {
        $keywords = array();
        $query = $request->search;
        //echo'<pre>'; print_r($query); die; 

        $products = Product::where('published', 1)->where('tags', 'like', '%' . $query . '%')->get();
        foreach ($products as $key => $product) {
            foreach (explode(',', $product->tags) as $key => $tag) {
                if (stripos($tag, $query) !== false) {
                    if (sizeof($keywords) > 5) {
                        break;
                    } else {
                        if (!in_array(strtolower($tag), $keywords)) {
                            array_push($keywords, strtolower($tag));
                        }
                    }
                }
            }
        }

        $products = filter_products(Product::query());

        $products = $products->where('published', 1)
            ->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%')
                        ->orWhereHas('product_translations', function ($q) use ($word) {
                            $q->where('name', 'like', '%' . $word . '%');
                        })
                        ->orWhereHas('stocks', function ($q) use ($word) {
                            $q->where('sku', 'like', '%' . $word . '%');
                        });
                }
            })
            ->limit(3)
            ->get();

        $categories = Category::where('name', 'like', '%' . $query . '%')->get()->take(3);

        $shops = Shop::whereIn('user_id', verified_sellers_id())->where('name', 'like', '%' . $query . '%')->get()->take(3);

        if (sizeof($keywords) > 0 || sizeof($categories) > 0 || sizeof($products) > 0 || sizeof($shops) > 0) {
            return view('frontend.partials.search_content', compact('products', 'categories', 'keywords', 'shops'));
        }
        return '0';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $search = Search::where('query', $request->keyword)->first();
        if ($search != null) {
            $search->count = $search->count + 1;
            $search->save();
        } else {
            $search = new Search;
            $search->query = $request->keyword;
            $search->save();
        }
    }
}
