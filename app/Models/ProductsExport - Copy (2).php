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
        return Product::where('published', 1)->get();
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
        //echo '<pre>stocks';print_r($product->stocks);
        $availability = 'in stock';
        $condition = 'new';
        $qty = 0;
        $variants = [];
        foreach ($product->stocks as $key => $stock) {
            $qty += $stock->qty;
            $variants[] = $stock->variant;
        }
        //$variant = $product->stocks->first()->variant ?? '';
        return [
            $product->id,
            $product->name,
            $product->description,
            $availability,
            $condition,
            implode(', ', $variants),
            /*$product->added_by,
            $product->user_id,
            $product->category_id,
            $product->brand_id,
            $product->video_provider,
            $product->video_link,*/
            $product->unit_price,
            $product->purchase_price,
            $product->unit,
            //$product->current_stock,
            $qty,
            $product->meta_title,
            $product->meta_description,
            //$qty,
        ];
    }
}
