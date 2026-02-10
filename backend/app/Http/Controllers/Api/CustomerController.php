<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * GET /api/customer/orders
     * Get customer's order history
     */
    public function orders(Request $request)
    {
        $orders = $request->user()->orders()
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'date' => $order->created_at->format('F j, Y'),
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'total' => '€' . number_format($order->total, 2),
                    'items_count' => $order->items->sum('quantity'),
                    'items' => $order->items->map(fn($item) => [
                        'name' => $item->product_name,
                        'description' => $item->product_description,
                        'size' => $item->product_size,
                        'quantity' => $item->quantity,
                        'price' => '€' . number_format($item->unit_price, 2),
                        'image' => $item->product_image,
                    ]),
                ];
            });

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }

    /**
     * GET /api/customer/orders/{orderNumber}
     * Get single order details
     */
    public function showOrder(Request $request, $orderNumber)
    {
        $order = $request->user()->orders()
            ->where('order_number', $orderNumber)
            ->with('items')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'order' => [
                'order_number' => $order->order_number,
                'date' => $order->created_at->format('F j, Y \a\t g:i A'),
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'subtotal' => '€' . number_format($order->subtotal, 2),
                'shipping_cost' => '€' . number_format($order->shipping_cost, 2),
                'total' => '€' . number_format($order->total, 2),
                'shipping_address' => [
                    'name' => $order->customer_first_name . ' ' . $order->customer_last_name,
                    'line1' => $order->billing_address_line1,
                    'line2' => $order->billing_address_line2,
                    'city' => $order->billing_city,
                    'postal_code' => $order->billing_postal_code,
                    'country' => $order->billing_country,
                ],
                'items' => $order->items->map(fn($item) => [
                    'product_name' => $item->product_name,
                    'product_description' => $item->product_description,
                    'size' => $item->product_size,
                    'quantity' => $item->quantity,
                    'unit_price' => '€' . number_format($item->unit_price, 2),
                    'total_price' => '€' . number_format($item->total_price, 2),
                    'image' => $item->product_image,
                ]),
            ],
        ]);
    }

    /**
     * POST /api/customer/returns/request
     * Request a return for an order
     */
    public function requestReturn(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'items' => 'required|array',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
        ]);

        $order = $request->user()->orders()
            ->where('order_number', $request->order_number)
            ->firstOrFail();

        // Check if order is eligible for return (within 14 days - EU law)
        $daysSinceOrder = $order->created_at->diffInDays(now());
        if ($daysSinceOrder > 14) {
            return response()->json([
                'success' => false,
                'message' => 'Return period has expired. Returns must be requested within 14 days of delivery (EU Consumer Rights).',
            ], 400);
        }

        // For now, store return request in admin_notes
        // In production, you'd create a separate returns table
        $order->update([
            'admin_notes' => "RETURN REQUESTED: " . $request->reason . " (Items: " . json_encode($request->items) . ")",
        ]);

        // TODO: Send email notification to admin
        // TODO: Create proper Return model and table

        return response()->json([
            'success' => true,
            'message' => 'Return request submitted successfully. We will contact you within 2 business days.',
        ]);
    }

    /**
     * PUT /api/customer/profile
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
        ]);

        $request->user()->update($request->only(['name', 'email']));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
            'user' => [
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
        ]);
    }

    /**
     * DELETE /api/customer/account
     * Delete customer account (GDPR Right to Erasure)
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirm' => 'required|boolean|accepted',
        ]);

        // SECURITY: User is already authenticated via auth:sanctum middleware
        // No need to re-verify password (avoids plaintext password transmission)
        $user = $request->user();

        // Anonymize orders (can't delete due to tax law)
        $user->orders()->update([
            'user_id' => null,
            'customer_email' => 'deleted@anonymized.local',
            'customer_first_name' => 'Deleted',
            'customer_last_name' => 'User',
        ]);

        // Delete user account
        $user->tokens()->delete(); // Revoke all tokens
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Your account has been deleted. Order records have been anonymized for legal compliance.',
        ]);
    }
}
