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

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by tag
        if ($request->has('tag')) {
            $query->where('tag', $request->tag);
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
}
