<!DOCTYPE html>
<html>
<head>
    <title>Order Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 80mm; 
        }
        .receipt {
            width: 100%;
            padding: 5mm;
            box-sizing: border-box;
        }
        .receipt h1, .receipt p {
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .receipt table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .receipt table, .receipt th, .receipt td {
            border: 1px solid black;
        }
        .receipt th, .receipt td {
            padding: 5px;
            text-align: center;
        }
        .receipt th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <h2>Order Receipt</h2>
        <p><strong>Order ID:</strong> {{ $order->id }}</p>
        <p><strong>Name:</strong> {{ $order->name }}</p>
        <p><strong>Phone:</strong> {{ $order->phone }}</p>
        <p><strong>Address:</strong> {{ $order->address }}</p>
        <p><strong>Total:</strong>£{{ $order->total }}</p>
        <p><strong>Order Type:</strong> {{ $order->order_type }}</p>
        <p><strong>Payment Option:</strong> {{ $order->payment_option }}</p>
        <p><strong>Delivery Time:</strong> {{ $order->deliver_time }} minutes</p>
        <p><strong>Date and Time:</strong> {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</p>

        <!-- Order Details Table -->
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Sides</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->details as $detail)
                    <tr>
                        <td>{{ $detail->product_title }}</td>
                        <td>{{ $detail->options }}</td>
                        <td>£{{ $detail->product_price }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>£{{ $detail->sub_total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
