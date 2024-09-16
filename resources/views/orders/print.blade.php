<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 80mm; /* Set width for receipt printers (80mm standard) */
            margin: 0;
            padding: 0;
        }
        .receipt-container {
            text-align: center;
            width: 100%;
        }
        .header {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .order-details {
            text-align: left;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            padding: 5px;
        }
        th {
            text-align: left;
            border-bottom: 1px dashed black;
        }
        .footer {
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header (e.g., Store Name, Address, etc.) -->
        <div class="header">
            <h3>{{ $company['name'] }}</h3>
            <p>{{ $company['address'] }}</p>
        </div>

        <!-- Order Details -->
        <div class="order-details">
            <p>Order #: {{ $order->id }}</p>
            <p>Date: {{ $order->created_at->format('Y-m-d H:i:s') }}</p>
            <p>Name: {{ $order->name }}</p>
            <p>Phone: {{ $order->phone }}</p>
            <p>Order Type: {{ Str::ucfirst($order->order_type) }}</p>
            <p>Payment: {{ Str::ucfirst($order->payment_option) }}</p>
            <hr>

            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->details as $item)
                        <tr>
                            <td>{{ $item->product_title }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>£{{ number_format($item->sub_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>
            <p>Total: £{{ number_format($order->total, 2) }}</p>
        </div>

        <!-- Footer (e.g., Thank You message) -->
        <div class="footer">
            <p>Thank you for your order!</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };

        window.onafterprint = function() {
            // Redirect after printing is finished
            window.location.href = "{{ route('orders.list') }}";
        };
    </script>
</body>
</html>
