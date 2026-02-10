<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * GET /api/wishlist
     * Get user's wishlist
     */
    public function index(Request $request)
    {
        $wishlist = $request->user()->wishlists()
            ->with('product.images')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product' => [
                        'id' => $item->product->id,
                        'name' => $item->product->name,
                        'slug' => $item->product->slug,
                        'price' => '€' . number_format($item->product->price, 0),
                        'image' => $item->product->main_image_url,
                        'in_stock' => $item->product->stock_quantity > 0,
                    ],
                    'added_at' => $item->created_at->format('M j, Y'),
                ];
            });

        return response()->json([
            'success' => true,
            'wishlist' => $wishlist,
        ]);
    }

    /**
     * POST /api/wishlist/add
     * Add product to wishlist
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Added to wishlist',
        ]);
    }

    /**
     * DELETE /api/wishlist/remove/{productId}
     * Remove product from wishlist
     */
    public function remove(Request $request, $productId)
    {
        Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Removed from wishlist',
        ]);
    }
}
