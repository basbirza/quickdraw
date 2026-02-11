<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_number', 'customer_email', 'customer_first_name', 'customer_last_name',
        'customer_phone', 'billing_address_line1', 'billing_address_line2', 'billing_city',
        'billing_state', 'billing_postal_code', 'billing_country', 'shipping_same_as_billing',
        'shipping_address_line1', 'shipping_address_line2', 'shipping_city', 'shipping_state',
        'shipping_postal_code', 'shipping_country', 'subtotal', 'shipping_cost', 'tax',
        'discount', 'total', 'payment_method', 'payment_status', 'payment_transaction_id',
        'status', 'customer_notes', 'admin_notes'
    ];

    protected $casts = [
        // Financial data
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'shipping_same_as_billing' => 'boolean',

        // Encrypted PII (GDPR Article 32 - Security of Processing)
        'customer_email' => 'encrypted',
        'customer_phone' => 'encrypted',
        'billing_address_line1' => 'encrypted',
        'billing_address_line2' => 'encrypted',
        'billing_city' => 'encrypted',
        'billing_postal_code' => 'encrypted',
        'shipping_address_line1' => 'encrypted',
        'shipping_address_line2' => 'encrypted',
        'shipping_city' => 'encrypted',
        'shipping_postal_code' => 'encrypted',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Generate unique order number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'QD-' . strtoupper(Str::random(10));
            }
        });
    }
}
