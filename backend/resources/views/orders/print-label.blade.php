<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20mm;
            background: white;
        }

        .label-container {
            width: 100mm;
            height: 150mm;
            border: 3px solid #000;
            padding: 5mm;
            page-break-after: always;
            background: white;
        }

        .logo-section {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 3mm;
            margin-bottom: 3mm;
        }

        .logo-section h1 {
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .logo-section .tagline {
            font-size: 8pt;
            color: #666;
            margin-top: 1mm;
        }

        .section {
            margin-bottom: 4mm;
        }

        .section-title {
            font-size: 8pt;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 2mm;
            letter-spacing: 0.5px;
        }

        .from-address {
            font-size: 9pt;
            line-height: 1.4;
        }

        .to-address {
            border: 2px solid #000;
            padding: 4mm;
            background: #f9f9f9;
            min-height: 40mm;
        }

        .to-address .name {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 3mm;
            text-transform: uppercase;
        }

        .to-address .address {
            font-size: 11pt;
            line-height: 1.5;
        }

        .order-info {
            text-align: center;
            margin-top: 4mm;
            padding: 3mm;
            background: #000;
            color: white;
        }

        .order-info .order-number {
            font-size: 18pt;
            font-weight: bold;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
        }

        .order-info .date {
            font-size: 8pt;
            margin-top: 2mm;
        }

        .items-summary {
            margin-top: 3mm;
            font-size: 8pt;
            text-align: center;
            color: #666;
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }

            .label-container {
                border: 3px solid #000;
                margin: 0;
            }

            .no-print {
                display: none;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 30px;
            background: #000;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .print-button:hover {
            background: #333;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">🖨️ Print Label</button>

    <div class="label-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <h1>QUICKDRAW PRESSING CO.</h1>
            <div class="tagline">Premium Selvedge Denim & Americana</div>
        </div>

        <!-- From Address -->
        <div class="section">
            <div class="section-title">From</div>
            <div class="from-address">
                <strong>Quickdraw Pressing Co.</strong><br>
                123 Heritage Lane<br>
                Amsterdam, 1012 AB<br>
                Netherlands<br>
                +31 20 123 4567
            </div>
        </div>

        <!-- To Address -->
        <div class="section">
            <div class="section-title">Ship To</div>
            <div class="to-address">
                <div class="name">
                    {{ $order->customer_first_name }} {{ $order->customer_last_name }}
                </div>
                <div class="address">
                    {{ $order->shipping_address_line1 ?: $order->billing_address_line1 }}<br>
                    @if($order->shipping_address_line2 ?: $order->billing_address_line2)
                        {{ $order->shipping_address_line2 ?: $order->billing_address_line2 }}<br>
                    @endif
                    {{ $order->shipping_postal_code ?: $order->billing_postal_code }}
                    {{ $order->shipping_city ?: $order->billing_city }}<br>
                    {{ $order->shipping_state ?: $order->billing_state }}
                    {{ $order->shipping_country ?: $order->billing_country }}
                </div>
            </div>
        </div>

        <!-- Order Info -->
        <div class="order-info">
            <div class="order-number">{{ $order->order_number }}</div>
            <div class="date">{{ $order->created_at->format('d M Y') }}</div>
        </div>

        <!-- Items Summary -->
        <div class="items-summary">
            {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }} •
            Weight: {{ number_format($order->items->sum('quantity') * 0.5, 1) }}kg (est.)
        </div>
    </div>

    <script>
        // Auto-open print dialog after a short delay
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });

        // Close window after printing or canceling
        window.addEventListener('afterprint', function() {
            setTimeout(function() {
                window.close();
            }, 500);
        });
    </script>
</body>
</html>
