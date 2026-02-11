<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * GET /api/categories
     * Returns hierarchical category structure
     */
    public function index()
    {
        $categories = Category::with('children')
            ->main()
            ->active()
            ->orderBy('sort_order')
            ->get();

        return CategoryResource::collection($categories);
    }

    /**
     * GET /api/categories/{slug}
     */
    public function show($slug)
    {
        $category = Category::with(['children', 'products'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        return new CategoryResource($category);
    }
}
