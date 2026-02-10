<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * POST /api/orders
     * Creates order and processes payment
     */
    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();

        try {
            // Calculate totals
            $subtotal = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Check stock availability
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}. Only {$product->stock_quantity} available.");
                }

                $itemTotal = $product->price * $item['quantity'];
                $subtotal += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_description' => $product->short_description,
                    'product_size' => $item['size'],
                    'product_image' => $product->main_image_url,
                    'unit_price' => $product->price,
                    'quantity' => $item['quantity'],
                    'total_price' => $itemTotal,
                ];
            }

            // Calculate shipping (free over €150)
            $shippingCost = $subtotal >= 150 ? 0 : 9.95;
            $total = $subtotal + $shippingCost;

            // Create order
            $order = Order::create([
                'customer_email' => $request->customer_email,
                'customer_first_name' => $request->customer_first_name,
                'customer_last_name' => $request->customer_last_name,
                'customer_phone' => $request->customer_phone,
                'billing_address_line1' => $request->billing_address_line1,
                'billing_address_line2' => $request->billing_address_line2,
                'billing_city' => $request->billing_city,
                'billing_postal_code' => $request->billing_postal_code,
                'billing_country' => $request->billing_country,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
            ]);

            // Create order items
            $order->items()->createMany($orderItems);

            // Process payment
            $paymentResult = $this->paymentService->processPayment($order, $request->payment_token);

            if ($paymentResult['success']) {
                $order->update([
                    'payment_status' => 'paid',
                    'payment_transaction_id' => $paymentResult['transaction_id'],
                    'status' => 'processing',
                ]);

                // Reduce stock quantities for all items
                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $product->decrement('stock_quantity', $item['quantity']);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'order_number' => $order->order_number,
                    'message' => 'Order placed successfully! Stock updated.',
                ], 201);
            } else {
                throw new \Exception('Payment failed: ' . ($paymentResult['message'] ?? 'Unknown error'));
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Order creation failed. ' . $e->getMessage(),
            ], 500);
        }
    }
}
