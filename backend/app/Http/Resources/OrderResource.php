<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'customer_email' => $this->customer_email,
            'customer_name' => $this->customer_first_name . ' ' . $this->customer_last_name,
            'subtotal' => '€' . number_format($this->subtotal, 2),
            'shipping_cost' => '€' . number_format($this->shipping_cost, 2),
            'total' => '€' . number_format($this->total, 2),
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'items' => $this->items->map(function ($item) {
                return [
                    'product_name' => $item->product_name,
                    'product_size' => $item->product_size,
                    'quantity' => $item->quantity,
                    'unit_price' => '€' . number_format($item->unit_price, 2),
                    'total_price' => '€' . number_format($item->total_price, 2),
                ];
            }),
        ];
    }
}
