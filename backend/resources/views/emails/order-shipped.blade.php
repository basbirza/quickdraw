<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Shipped</title>
    <style>
        body { margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #111; color: #ffffff; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 32px; letter-spacing: 0.15em; font-family: Georgia, serif; }
        .header p { margin: 8px 0 0; font-size: 11px; letter-spacing: 0.3em; color: #999; }
        .content { padding: 30px 20px; }
        .success-message { background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 16px; margin-bottom: 24px; }
        .success-message h2 { margin: 0 0 8px; color: #1e40af; font-size: 18px; }
        .success-message p { margin: 0; color: #1e3a8a; font-size: 14px; }
        .info-box { background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 16px; border-radius: 4px; margin: 16px 0; }
        .button { display: inline-block; background-color: #111; color: #ffffff !important; text-decoration: none; padding: 16px 32px; font-size: 13px; letter-spacing: 0.1em; border-radius: 2px; margin: 20px 0; }
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; font-size: 11px; color: #666; }
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
                <h2>📦 Your Order Has Shipped!</h2>
                <p>Your premium denim is on its way to you.</p>
            </div>

            <!-- Greeting -->
            <p style="font-size: 15px; margin-bottom: 8px;">
                Hi {{ $order->customer_first_name }},
            </p>

            <p style="font-size: 14px; color: #666; line-height: 1.6; margin-bottom: 24px;">
                Great news! Your order has been shipped and is on its way to you. We've carefully packaged your gear and handed it off to our shipping partner.
            </p>

            <!-- Order Details -->
            <div class="info-box">
                <p style="margin: 0 0 4px; font-size: 13px;"><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p style="margin: 0 0 4px; font-size: 13px;"><strong>Shipped:</strong> {{ now()->format('F j, Y') }}</p>
                <p style="margin: 0; font-size: 13px;"><strong>Estimated Delivery:</strong> {{ $order->shipping_country === 'Netherlands' ? '1-2 business days' : '3-5 business days' }}</p>
            </div>

            <!-- Shipping Address -->
            <div style="margin: 24px 0;">
                <p style="font-size: 12px; font-weight: bold; color: #666; margin-bottom: 8px;">SHIPPING TO:</p>
                <p style="font-size: 13px; line-height: 1.6; margin: 0;">
                    {{ $order->customer_first_name }} {{ $order->customer_last_name }}<br>
                    {{ $order->shipping_address_line1 ?: $order->billing_address_line1 }}<br>
                    @if($order->shipping_address_line2 ?: $order->billing_address_line2)
                        {{ $order->shipping_address_line2 ?: $order->billing_address_line2 }}<br>
                    @endif
                    {{ $order->shipping_postal_code ?: $order->billing_postal_code }}
                    {{ $order->shipping_city ?: $order->billing_city }}<br>
                    {{ $order->shipping_country ?: $order->billing_country }}
                </p>
            </div>

            <!-- CTA -->
            <div style="text-align: center; margin: 32px 0;">
                <a href="http://localhost:8001/account.html" class="button">VIEW ORDER STATUS</a>
            </div>

            <!-- Care Info -->
            <div style="background-color: #fef3c7; padding: 16px; border-radius: 4px; border-left: 3px solid #f59e0b;">
                <p style="margin: 0 0 8px; font-size: 12px; font-weight: bold; color: #92400e;">🌟 GETTING STARTED WITH RAW DENIM</p>
                <p style="margin: 0; font-size: 12px; color: #92400e; line-height: 1.5;">
                    If you ordered raw denim, wear it for 6+ months before the first wash for maximum fade development. Check our Denim Guide for detailed care instructions.
                </p>
            </div>

            <!-- Support -->
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <p style="margin: 0; font-size: 12px; color: #666;">
                    Questions about your shipment?
                    <a href="mailto:support@quickdraw.com" style="color: #111;">support@quickdraw.com</a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 12px; font-weight: bold; letter-spacing: 0.1em;">QUICKDRAW PRESSING CO.</p>
            <p style="margin: 0 0 12px;">Premium Selvedge Denim & Americana Wear</p>
            <p style="margin: 12px 0 0; font-size: 10px; color: #999;">
                © {{ date('Y') }} Quickdraw Pressing Co. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
