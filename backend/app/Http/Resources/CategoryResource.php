<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
            'description' => $this->description,
            'banner_image' => $this->banner_image ? asset('storage/' . $this->banner_image) : null,
            'product_count' => $this->products()->count(),
            'children' => CategoryResource::collection($this->whenLoaded('children')),
        ];
    }
}
