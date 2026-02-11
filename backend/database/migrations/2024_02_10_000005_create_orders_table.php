<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();

            // Customer info
            $table->string('customer_email');
            $table->string('customer_first_name');
            $table->string('customer_last_name');
            $table->string('customer_phone')->nullable();

            // Billing address
            $table->string('billing_address_line1');
            $table->string('billing_address_line2')->nullable();
            $table->string('billing_city');
            $table->string('billing_state')->nullable();
            $table->string('billing_postal_code');
            $table->string('billing_country');

            // Shipping address
            $table->boolean('shipping_same_as_billing')->default(true);
            $table->string('shipping_address_line1')->nullable();
            $table->string('shipping_address_line2')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_country')->nullable();

            // Order totals
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            // Payment
            $table->string('payment_method'); // "stripe", "mollie", "paypal"
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('payment_transaction_id')->nullable();

            // Order status
            $table->string('status')->default('pending'); // pending, processing, shipped, delivered, cancelled
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamps();

            $table->index('order_number');
            $table->index('customer_email');
            $table->index(['status', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
