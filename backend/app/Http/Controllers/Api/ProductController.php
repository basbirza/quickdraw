<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductDetailResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * GET /api/products
     * Filters: ?category=denim&tag=NEW&featured=1&per_page=12
     */
    public function index(Request $request)
    {
        $query = Product::with(['images', 'categories'])
            ->active();

        // Filter by category (includes child categories)
        if ($request->has('category')) {
            $category = \App\Models\Category::where('slug', $request->category)->first();

            if ($category) {
                // Get category and all its children slugs
                $categorySlugs = [$category->slug];
                if ($category->children()->exists()) {
                    $childSlugs = $category->children()->pluck('slug')->toArray();
                    $categorySlugs = array_merge($categorySlugs, $childSlugs);
                }

                // Query products in category or any of its children
                $query->whereHas('categories', function ($q) use ($categorySlugs) {
                    $q->whereIn('slug', $categorySlugs);
                });
            }
        }

        // Filter by tag
        if ($request->has('tag')) {
            $query->where('tag', $request->tag);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by size availability
        if ($request->has('size')) {
            $query->whereJsonContains('sizes_available', $request->size);
        }

        // Filter featured
        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Sorting - whitelist to prevent SQL injection
        $allowedSort = ['created_at', 'price', 'name', 'stock_quantity'];
        $sortBy = in_array($request->get('sort'), $allowedSort)
            ? $request->get('sort')
            : 'created_at';

        $sortOrder = in_array($request->get('order'), ['asc', 'desc'])
            ? $request->get('order')
            : 'desc';

        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        return ProductResource::collection($products);
    }

    /**
     * GET /api/products/{slug}
     */
    public function show($slug)
    {
        $product = Product::with(['images', 'categories'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        return new ProductDetailResource($product);
    }

    /**
     * GET /api/products/search?q=query&limit=10
     * Search products by name, description, specs
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
            'limit' => 'sometimes|integer|min:1|max:50'
        ]);

        $query = trim($request->input('q'));
        $limit = $request->input('limit', 10);

        // Search across multiple fields with LIKE
        $products = Product::with(['images', 'categories'])
            ->active()
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('short_description', 'LIKE', "%{$query}%")
                  ->orWhere('full_description', 'LIKE', "%{$query}%")
                  ->orWhere('composition', 'LIKE', "%{$query}%")
                  ->orWhere('mill', 'LIKE', "%{$query}%")
                  ->orWhere('weight', 'LIKE', "%{$query}%")
                  ->orWhere('construction', 'LIKE', "%{$query}%");
            })
            ->take($limit)
            ->get();

        return response()->json([
            'success' => true,
            'query' => $query,
            'results' => $products->count(),
            'data' => ProductResource::collection($products)->resolve()
        ]);
    }
}
