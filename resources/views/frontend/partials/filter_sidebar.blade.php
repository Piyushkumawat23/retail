<div class="filter_sidebar">
    <div class="filter_close">
        <div class="filter_text">
            <h3>{{ translate('Filters') }}</h3>
        </div>
        <div class="filter_sidebar_close">
            <a href="javascript:void(0)"><i class="ion-android-close"></i></a>
        </div>
    </div>

    <div class="sidebar_widget">
        <div class="widget_list widget_categories">
            <h2>{{ translate('Categories')}}</h2>
            <ul>
                @if (!isset($category_id))
                    @foreach (\App\Models\Category::where('level', 0)->get() as $category)
                        <li>
                            <a href="{{ route('products.category', $category->slug) }}">{{ $category->getTranslation('name') }}</a>
                        </li>
                    @endforeach
                @else
                    <li>
                        <a href="{{ route('search') }}">
                            <i class="las la-angle-left"></i>
                            {{ translate('All Categories')}}
                        </a>
                    </li>
                    @if (\App\Models\Category::find($category_id)->parent_id != 0)
                        <li>
                            <a href="{{ route('products.category', \App\Models\Category::find(\App\Models\Category::find($category_id)->parent_id)->slug) }}">
                                <i class="las la-angle-left"></i>
                                {{ \App\Models\Category::find(\App\Models\Category::find($category_id)->parent_id)->getTranslation('name') }}</a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('products.category', \App\Models\Category::find($category_id)->slug) }}">
                            <i class="las la-angle-left"></i>
                            {{ \App\Models\Category::find($category_id)->getTranslation('name') }}</a>
                    </li>
                    @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category_id) as $key => $id)
                        <li>
                            <a href="{{ route('products.category', \App\Models\Category::find($id)->slug) }}">
                                {{ \App\Models\Category::find($id)->getTranslation('name') }}</a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>

        @if (isset(Auth::user()->id))
            <div class="widget_list widget_filter">
                <h2>{{ translate('Price range')}}</h2>
                <form action="#">
                    <div id="slider-range"
                        data-range-value-min="@if(\App\Models\Product::count() < 1) 0 @else {{ \App\Models\Product::min('unit_price') }} @endif"
                        data-range-value-max="@if(\App\Models\Product::count() < 1) 0 @else {{ \App\Models\Product::max('unit_price') }} @endif">
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <span class="range-slider-value value-low fs-14 fw-600 opacity-70"
                                @if (isset($min_price))
                                    data-range-value-low="{{ $min_price }}"
                                @elseif($products->min('unit_price') > 0)
                                    data-range-value-low="{{ $products->min('unit_price') }}"
                                @else
                                    data-range-value-low="0"
                                @endif
                                id="input-slider-range-value-low">
                            </span>
                        </div>
                        <div class="col-6 text-right">
                            <span class="range-slider-value value-high fs-14 fw-600 opacity-70"
                                @if (isset($max_price))
                                    data-range-value-high="{{ $max_price }}"
                                @elseif($products->max('unit_price') > 0)
                                    data-range-value-high="{{ $products->max('unit_price') }}"
                                @else
                                    data-range-value-high="0"
                                @endif
                                id="input-slider-range-value-high">
                            </span>
                        </div>
                    </div>
                    <input class="price_range_filter" type="text" name="text" id="amount" />
                    <button class="price_range_filter_button" type="submit">{{ translate('Filter') }}</button>
                </form>
            </div>
        @endif

        <?php $is_ring = 0; ?>
        @if(isset($category_id))
            <?php
                $attribute_category = \App\Models\Category::find($category_id);
                if(!empty($attribute_category) && $attribute_category->name == 'Rings'){
                    $is_ring = 1;
                }
            ?>
        @endif

        @foreach ($attributes as $attribute)
            <?php $attr_name = $attribute->getTranslation('name'); ?>
            @if($is_ring == 1 && $attr_name == 'Sizes')
                <div class="widget_list widget_brands">
                    <h2>{{ $attribute->getTranslation('name') }}</h2>
                    <div class="aiz-checkbox-list">
                        @foreach ($attribute->attribute_values as $attribute_value)
                            <label class="aiz-checkbox">
                                <input
                                    type="checkbox"
                                    name="selected_attribute_values[]"
                                    value="{{ $attribute_value->value }}" @if (in_array($attribute_value->value, $selected_attribute_values)) checked @endif
                                    onchange="filter()"
                                >
                                <span class="aiz-square-check"></span>
                                <span class="checkbox_filter">{{ $attribute_value->value }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @elseif($attr_name != 'Sizes')
                <div class="widget_list widget_brands">
                    <h2>{{ $attribute->getTranslation('name') }}</h2>
                    <div class="aiz-checkbox-list">
                        @foreach ($attribute->attribute_values as $attribute_value)
                            <label class="aiz-checkbox">
                                <input
                                    type="checkbox"
                                    name="selected_attribute_values[]"
                                    value="{{ $attribute_value->value }}" @if (in_array($attribute_value->value, $selected_attribute_values)) checked @endif
                                    onchange="filter()"
                                >
                                <span class="aiz-square-check"></span>
                                <span class="checkbox_filter">{{ $attribute_value->value }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        <div class="widget_list widget_brands">
            <h2>{{ translate('Gemstones')}}</h2>
            <div class="aiz-checkbox-list">
                @foreach (\App\Models\Brand::where('active',1)->orderBy('name', 'asc')->get() as $brand)
                    <?php
                        if(isset($category_id)){
                            $brand_product = \App\Models\Product::where('brand_id',$brand->id)->where('published',1)->where('category_id',$category_id)->count();
                            if(!empty($brand_product) && $brand_product > 0){
                    ?>
                        <label class="aiz-checkbox">
                            <input
                                type="checkbox"
                                name="brand_values[]"
                                value="{{ $brand->id }}" @if (in_array($brand->id, $brand_values)) checked @endif onchange="filter()">
                            <span class="aiz-square-check"></span>
                            <span class="checkbox_filter">{{ $brand->getTranslation('name') }}</span>
                        </label>
                    <?php
                            }
                        }
                    ?>
                @endforeach
            </div>
        </div>
    </div>
</div>
