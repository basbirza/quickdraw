<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body { margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #111; color: #ffffff; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 32px; letter-spacing: 0.15em; font-family: Georgia, serif; }
        .header p { margin: 8px 0 0; font-size: 11px; letter-spacing: 0.3em; color: #999; }
        .content { padding: 30px 20px; }
        .success-message { background-color: #f0fdf4; border-left: 4px solid: #22c55e; padding: 16px; margin-bottom: 24px; }
        .success-message h2 { margin: 0 0 8px; color: #166534; font-size: 18px; }
        .success-message p { margin: 0; color: #15803d; font-size: 14px; }
        .section { margin-bottom: 30px; }
        .section-title { font-size: 12px; font-weight: bold; letter-spacing: 0.1em; color: #666; margin-bottom: 12px; }
        .info-box { background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 16px; border-radius: 4px; }
        .order-items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .order-items th { background-color: #f9fafb; padding: 12px; text-align: left; font-size: 11px; letter-spacing: 0.05em; color: #666; border-bottom: 1px solid #e5e7eb; }
        .order-items td { padding: 12px; border-bottom: 1px solid #f3f4f6; font-size: 13px; }
        .totals-table { width: 100%; max-width: 300px; margin-left: auto; }
        .totals-table td { padding: 8px 0; font-size: 13px; }
        .totals-table .total-row { font-weight: bold; font-size: 16px; padding-top: 12px; border-top: 2px solid #111; }
        .button { display: inline-block; background-color: #111; color: #ffffff; text-decoration: none; padding: 14px 28px; font-size: 12px; letter-spacing: 0.1em; border-radius: 2px; margin: 10px 10px 10px 0; }
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; font-size: 11px; color: #666; }
        .footer a { color: #111; text-decoration: none; }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>QUICKDRAW</h1>
            <p>PRESSING CO.</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Success Message -->
            <div class="success-message">
                <h2>✓ Order Confirmed!</h2>
                <p>Thank you for your order. We've received your payment and will begin processing shortly.</p>
            </div>

            <!-- Customer Greeting -->
            <p style="font-size: 15px; margin-bottom: 20px;">
                Hi {{ $order->customer_first_name }},
            </p>

            <p style="font-size: 14px; color: #666; line-height: 1.6; margin-bottom: 24px;">
                We're excited to confirm your order! Your premium selvedge denim and Americana wear will be carefully prepared and shipped to you soon.
            </p>

            <!-- Order Details -->
            <div class="section">
                <div class="section-title">ORDER DETAILS</div>
                <div class="info-box">
                    <p style="margin: 0 0 4px; font-size: 13px;"><strong>Order Number:</strong> {{ $order->order_number }}</p>
                    <p style="margin: 0; font-size: 13px;"><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
                </div>
            </div>

            <!-- Order Items -->
            <div class="section">
                <div class="section-title">ORDER ITEMS</div>
                <table class="order-items">
                    <thead>
                        <tr>
                            <th>PRODUCT</th>
                            <th>SIZE</th>
                            <th>QTY</th>
                            <th style="text-align: right;">PRICE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div style="font-weight: 500;">{{ $item->product_name }}</div>
                                <div style="font-size: 11px; color: #666;">{{ $item->product_description }}</div>
                            </td>
                            <td>{{ $item->size }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td style="text-align: right;">€{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Totals -->
                <table class="totals-table">
                    <tr>
                        <td>Subtotal:</td>
                        <td style="text-align: right;">€{{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Shipping:</td>
                        <td style="text-align: right;">
                            @if($order->shipping_cost > 0)
                                €{{ number_format($order->shipping_cost, 2) }}
                            @else
                                FREE
                            @endif
                        </td>
                    </tr>
                    @if($order->tax > 0)
                    <tr>
                        <td>Tax:</td>
                        <td style="text-align: right;">€{{ number_format($order->tax, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td>TOTAL:</td>
                        <td style="text-align: right;">€{{ number_format($order->total, 2) }}</td>
                    </tr>
                </table>
            </div>

            <!-- Shipping Address -->
            <div class="section">
                <div class="section-title">SHIPPING ADDRESS</div>
                <div class="info-box">
                    <p style="margin: 0; font-size: 13px; line-height: 1.6;">
                        <strong>{{ $order->customer_first_name }} {{ $order->customer_last_name }}</strong><br>
                        {{ $order->shipping_address_line1 ?: $order->billing_address_line1 }}<br>
                        @if($order->shipping_address_line2 ?: $order->billing_address_line2)
                            {{ $order->shipping_address_line2 ?: $order->billing_address_line2 }}<br>
                        @endif
                        {{ $order->shipping_postal_code ?: $order->billing_postal_code }}
                        {{ $order->shipping_city ?: $order->billing_city }}<br>
                        {{ $order->shipping_country ?: $order->billing_country }}
                    </p>
                </div>
            </div>

            <!-- Call to Action -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="http://localhost:8001/account.html" class="button">VIEW ORDER STATUS</a>
                <a href="http://localhost:8001/index.html" class="button" style="background-color: #fff; color: #111; border: 1px solid #111;">CONTINUE SHOPPING</a>
            </div>

            <!-- Support Info -->
            <div style="background-color: #f9fafb; padding: 16px; border-radius: 4px; margin-top: 30px;">
                <p style="margin: 0 0 8px; font-size: 12px; font-weight: bold; letter-spacing: 0.05em;">NEED HELP?</p>
                <p style="margin: 0; font-size: 12px; color: #666; line-height: 1.6;">
                    If you have any questions about your order, please contact us at
                    <a href="mailto:support@quickdraw.com" style="color: #111;">support@quickdraw.com</a>
                    or reply to this email.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 12px; font-weight: bold; letter-spacing: 0.1em;">QUICKDRAW PRESSING CO.</p>
            <p style="margin: 0 0 12px;">Premium Selvedge Denim & Americana Wear</p>
            <p style="margin: 0 0 12px;">
                <a href="http://localhost:8001/index.html">Shop</a> &nbsp;|&nbsp;
                <a href="http://localhost:8001/denim-guide.html">Denim Guide</a> &nbsp;|&nbsp;
                <a href="http://localhost:8001/account.html">My Account</a>
            </p>
            <p style="margin: 12px 0 0; font-size: 10px; color: #999;">
                © {{ date('Y') }} Quickdraw Pressing Co. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
