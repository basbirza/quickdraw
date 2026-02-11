<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DataSubjectController extends Controller
{
    /**
     * POST /api/data-subject/export
     * GDPR Article 15 - Right to Access
     *
     * Returns all personal data associated with the email address
     */
    public function export(Request $request): JsonResponse
    {
        // SECURITY: Verify user owns the email they're requesting (if authenticated)
        if ($request->user() && $request->user()->email !== $request->email) {
            return response()->json([
                'success' => false,
                'message' => 'You can only export your own data',
            ], 403);
        }

        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $email = $request->email;
            $data = [];

            // Newsletter subscription data
            $newsletter = NewsletterSubscriber::where('email', $email)->first();
            if ($newsletter) {
                $data['newsletter'] = [
                    'email' => $newsletter->email,
                    'status' => $newsletter->status,
                    'subscribed_at' => $newsletter->subscribed_at,
                    'unsubscribed_at' => $newsletter->unsubscribed_at,
                    'source' => $newsletter->source,
                ];
            }

            // Order history
            $orders = Order::where('customer_email', $email)
                ->with('items')
                ->get()
                ->map(function ($order) {
                    return [
                        'order_number' => $order->order_number,
                        'date' => $order->created_at->format('Y-m-d H:i:s'),
                        'customer_name' => $order->customer_first_name . ' ' . $order->customer_last_name,
                        'customer_phone' => $order->customer_phone,
                        'billing_address' => [
                            'line1' => $order->billing_address_line1,
                            'line2' => $order->billing_address_line2,
                            'city' => $order->billing_city,
                            'postal_code' => $order->billing_postal_code,
                            'country' => $order->billing_country,
                        ],
                        'total' => $order->total,
                        'status' => $order->status,
                        'payment_status' => $order->payment_status,
                        'items' => $order->items->map(fn($item) => [
                            'product' => $item->product_name,
                            'size' => $item->product_size,
                            'quantity' => $item->quantity,
                            'price' => $item->unit_price,
                        ]),
                    ];
                });

            $data['orders'] = $orders;
            $data['data_export_timestamp'] = now()->toIso8601String();
            $data['note'] = 'This is all personal data we hold about you as of ' . now()->format('F j, Y');

            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data export failed. Please contact privacy@quickdrawpressing.co',
            ], 500);
        }
    }

    /**
     * POST /api/data-subject/delete
     * GDPR Article 17 - Right to Erasure
     *
     * Deletes or anonymizes personal data
     * Note: Order data must be retained for 7 years (Dutch tax law)
     */
    public function delete(Request $request): JsonResponse
    {
        // SECURITY: Verify user owns the email they're deleting (if authenticated)
        if ($request->user() && $request->user()->email !== $request->email) {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete your own data',
            ], 403);
        }

        $request->validate([
            'email' => 'required|email',
            'confirm' => 'required|boolean|accepted', // Must explicitly confirm
        ]);

        try {
            $email = $request->email;

            // Delete newsletter subscription (can be fully deleted)
            NewsletterSubscriber::where('email', $email)->delete();

            // Anonymize order data (must retain for tax purposes)
            $orders = Order::where('customer_email', $email)->get();

            foreach ($orders as $order) {
                $order->update([
                    'customer_email' => 'deleted@anonymized.local',
                    'customer_first_name' => 'Deleted',
                    'customer_last_name' => 'User',
                    'customer_phone' => null,
                    'billing_address_line1' => 'ANONYMIZED',
                    'billing_address_line2' => null,
                    'billing_city' => 'ANONYMIZED',
                    'shipping_address_line1' => $order->shipping_same_as_billing ? 'ANONYMIZED' : $order->shipping_address_line1,
                    'shipping_address_line2' => null,
                    'shipping_city' => $order->shipping_same_as_billing ? 'ANONYMIZED' : $order->shipping_city,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Your personal data has been deleted. Order records have been anonymized for legal compliance.',
                'orders_anonymized' => $orders->count(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data deletion failed. Please contact privacy@quickdrawpressing.co',
            ], 500);
        }
    }
}
