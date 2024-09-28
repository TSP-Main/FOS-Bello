<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .receipt {
            width: 80mm;
            background: white;
            padding: 10mm;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            font-size: 16px;
            margin: 5px 0;
        }

        p {
            font-size: 12px;
            margin: 3px 0;
            line-height: 1.2;
        }

        table {
            width: 100%;
            margin: 10px 0;
            border-collapse: collapse;
        }

        table td {
            font-size: 12px;
            padding: 5px 0;
        }

        h2 {
            font-size: 14px;
            margin: 10px 0;
            color: #333;
        }

        table tr td:first-child {
            text-align: left;
        }

        table tr td:last-child {
            text-align: right;
        }

        strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <h1>Lana Deserts</h1>
        <p><strong>Delivery</strong></p>
        <p><strong>Due:</strong> {{ \Carbon\Carbon::now()->format('d-M H:i') }} at ASAP {{ $order->deliver_time }}</p>
        <p><strong>Order number:</strong> {{ $order->id }}</p>

        <!-- Dynamically list products -->
        <table>
            <tbody>
                @foreach($order->details as $detail)
                <tr>
                    <td>{{ $detail->quantity }} x</td>
                    <td>{{ $detail->product_title }}</td>
                    <td>£{{ number_format($detail->product_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p><strong>Subtotal:</strong> £{{ number_format($order->total, 2) }}</p>
        <p><strong>Restaurant discount:</strong> -£{{ number_format($order->discount ?? 0, 2) }}</p>
        <p><strong>Order total:</strong> £{{ number_format($order->total - ($order->discount ?? 0), 2) }}</p>
        <p><strong>Service charge:</strong> £{{ number_format($order->service_charge ?? 0.49, 2) }}</p>
        <p><strong>Delivery charge:</strong> £{{ number_format($order->delivery_charge ?? 3.00, 2) }}</p>
        <p><strong>Total Due:</strong> £{{ number_format(($order->total - ($order->discount ?? 0)) + ($order->service_charge ?? 0.49) + ($order->delivery_charge ?? 3.00), 2) }}</p>

        <p><strong>Important:</strong> For food allergen info, call the restaurant or check their menu</p>
        <h2>ORDER HAS BEEN PAID</h2>

        <p><strong>Customer ID:</strong> {{ $order->id }}</p>

        <p><strong>Customer details:</strong></p>
        <p>{{ $order->name }}</p>
        <p>{{ $order->address }}</p>

        <p>To contact customer call: {{ $order->phone }}</p>

        <p><strong>Order placed at:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('H:i d-M') }}</p>
        <p><strong>Order accepted at:</strong> {{ \Carbon\Carbon::now()->format('H:i d-M') }}</p>
    </div>
</body>
</html>
