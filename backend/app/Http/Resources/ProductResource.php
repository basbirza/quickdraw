<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'desc' => $this->short_description, // Frontend expects "desc"
            'price' => '€' . number_format($this->price, 0),
            'priceNum' => (float) $this->price,
            'tag' => $this->tag,
            'color' => $this->color_hex,
            'color_hex' => $this->color_hex, // For SVG fallback
            'image' => $this->main_image_url,
            'link' => $this->slug . '.html', // Frontend links
            'sizes' => $this->sizes_available,
            'in_stock' => $this->stock_quantity > 0,

            // Include images array for frontend consistency
            'images' => $this->images->map(function ($image) {
                return [
                    'url' => asset('storage/' . $image->image_path),
                    'type' => $image->image_type,
                    'alt' => $image->alt_text ?? $this->name,
                ];
            }),
        ];
    }
}
