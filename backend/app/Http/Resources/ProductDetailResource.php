<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'desc' => $this->short_description,
            'price' => '€' . number_format($this->price, 0),
            'priceNum' => (float) $this->price,
            'tag' => $this->tag,
            'color' => $this->color_hex,

            // Extended product details
            'weight' => $this->weight,
            'mill' => $this->mill,
            'composition' => $this->composition,
            'construction' => $this->construction,
            'treatment' => $this->treatment,
            'sanforization' => $this->sanforization,

            'full_description' => $this->full_description,
            'sizing_info' => $this->sizing_info,
            'shipping_info' => $this->shipping_info,
            'care_instructions' => $this->care_instructions,

            'sizes' => $this->sizes_available,
            'stock_quantity' => $this->stock_quantity,

            // Images
            'images' => $this->images->map(function ($image) {
                return [
                    'url' => asset('storage/' . $image->image_path),
                    'type' => $image->image_type,
                    'alt' => $image->alt_text ?? $this->name,
                ];
            }),

            'categories' => $this->categories->pluck('name'),
        ];
    }
}
