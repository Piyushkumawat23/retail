<?php

namespace App\Models;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        //return Product::all()->where('published', 1);
        //return Product::where('published', 1)->get();
        $products = Product::where('published', 1)
        ->with('stocks')
        ->get();

        $productStocks = collect();

        foreach ($products as $product) {
            foreach ($product->stocks as $stock) {
                $productStocks->push([
                    'id' => $product->id,
                    'title' => $product->name,
                    'description' => $product->description,
                    'availability' => 'in stock',
                    'condition' => 'new',
                    'unit_price' => $product->unitPrice,
                    'purchase_price' => $product->purchasePrice,
                    'unit' => $product->unit,
                    'current_stock' => $stock->qty,
                    'meta_title' => $product->metaTitle,
                    'meta_description' => $product->metaDescription,
                    'variant' => $stock->variant,
                ]);
            }
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
            'variant',
            /*'added_by',
            'user_id',
            'category_id',
            'brand_id',
            'video_provider',
            'video_link',*/
            'unit_price',
            'purchase_price',
            'unit',
            'current_stock',
            'meta_title',
            'meta_description',
        ];
    }

    /**
    * @var Product $product
    */
    
    public function map($product): array
    {
        if (is_array($product)) {
            $product = Product::find($product['id']);
        }
        $availability = 'in stock';
        $condition = 'new';
        $rows = [];
        $seenVariants = []; // Keep track of seen variants
        if ($product->stocks) {
            foreach ($product->stocks as $stock) {
                if (!in_array($stock->variant, $seenVariants)) {
                    $rows[] = [
                        $product->id,
                        $product->name,
                        $product->description,
                        $availability,
                        $condition,
                        $stock->variant,
                        $product->unit_price,
                        $product->purchase_price,
                        $product->unit,
                        $stock->qty,
                        $product->meta_title,
                        $product->meta_description,
                    ];
                    $seenVariants[] = $stock->variant; // Mark variant as seen
                }
            }
        }
        //echo '<pre>';print_r($seenVariants);
        //echo '<pre>';print_r($rows);die;
        return $rows;

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
}
