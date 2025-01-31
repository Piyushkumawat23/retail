<?php

namespace App\Models;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $products = Product::where('published', 1)
            ->with('stocks')
            ->get();
/*            foreach($products as $key => $product){
               $img_url = uploaded_asset($product->thumbnail_img); 
            }*/
            
            //echo '<pre>';print_r($img_url);die;
        $productStocks = collect();

        foreach ($products as $product) {
            //foreach ($product->stocks as $stock) {
                $purchasePrice = $product->purchase_price? $product->purchase_price : $product->unit_price ;
                $slug = $product->slug; // Generate the slug
                $url = route('product', $slug); // Construct the URL
                $img_url = uploaded_asset($product->thumbnail_img);
                $unitPriceFormatted = number_format($product->unit_price, 2) . " USD"; // Format the unit price
                //echo '<pre>';print_r($product);die;

               
                $productStocks->push([
                    'id' => $product->id,
                    'title' => $product->name,
                    'description' => strip_tags($product->description),
                    'availability' => 'in stock',
                    'condition' => 'new',
                    //'variant' => $stock->variant,
                    'price' => $unitPriceFormatted,
                    //'purchase_price' => $purchasePrice,
                    //'unit' => $product->unit,
                    //'current_stock' => $stock->qty,
                    //'meta_title' => $product->meta_title,
                    //'meta_description' => $product->meta_description,
                    'link' => $url, // Add the URL to the array,
                    'image_link' => $img_url // Add the URL to the array

                ]);
            //}
        }

        return $productStocks;
    }

    public function headings(): array
    {
        return [
            'id',
            'title',
            'description',
            'availability',
            'condition',
            //'variant',
            'price',
            //'purchase_price',
            //'unit',
            //'current_stock',
            //'meta_title',
            //'meta_description',
            'link', // Add the URL to the headings,
            'image_link' // Add the URL to the headings
        ];
    }
}

   /* public function map($product): array
    {
        if (is_array($product)) {
            $product = Product::find($product['id']);
        }
        $availability = 'in stock';
        $condition = 'new';
        $rows = [];
        if ($product->stocks) {
            foreach ($product->stocks as $stock) {
                $rows[] = [
                    $product->id,
                    $product->name,
                    $product->description,
                    $availability,
                    $condition,
                    $stock->variant, // <--- Use the variant from each stock
                    $product->unit_price,
                    $product->purchase_price,
                    $product->unit,
                    $stock->qty, // <--- Use the qty from each stock
                    $product->meta_title,
                    $product->meta_description,
                ];
            }
        }
        return $rows;
    }*/
    /*public function map($product): array
    {
        if (is_array($product)) {
            $product = Product::find($product['id']);
        }
        //echo '<pre>stocks';print_r($product->stocks);die;
        $availability = 'in stock';
        $condition = 'new';
        $qty = 0;
        //$variant = '';
        $rows = [];
        if ($product->stocks) {
            $variant = $product->stocks[0]->variant;
            foreach ($product->stocks as $key => $stock) {
                $qty += $stock->qty;
            }
        }
        return [
            $product->id,
            $product->name,
            $product->description,
            $availability,
            $condition,
            $variant,
            /*$product->added_by,
            $product->user_id,
            $product->category_id,
            $product->brand_id,
            $product->video_provider,
            $product->video_link,
            $product->unit_price,
            $product->purchase_price,
            $product->unit,
            //$product->current_stock,
            $qty,
            $product->meta_title,
            $product->meta_description,
            //$qty,
        ];
    }*/

