<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroImage;

class HeroImageController extends Controller
{
    /**
     * GET /api/hero-images
     * Returns active hero images for homepage slideshow
     */
    public function index()
    {
        $images = HeroImage::active()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => asset('storage/' . $image->image_path),
                    'alt' => $image->alt_text ?? 'Quickdraw Pressing Co.',
                    'order' => $image->sort_order,
                ];
            });

        return response()->json([
            'success' => true,
            'images' => $images,
        ]);
    }
}
