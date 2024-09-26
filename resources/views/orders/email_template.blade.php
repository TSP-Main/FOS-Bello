<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        /* Add styling similar to the image you uploaded */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #003366;
            padding: 10px;
            color: #ffffff;
            text-align: center;
        }
        .order-id {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #f0f8ff;
        }
        .order-detail {
            padding: 10px 20px;
        }
        .delivery-date {
            padding: 0 20px;
        }
        .summary {
            padding: 0px 20px;
        }
        .order-head{
            text-align: center;
            font-size: xx-large;
            color: darkcyan;
        }
        .footer {
            background-color: #003366;
            padding: 5px;
            color: #ffffff;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ $restaurantName }}</h1>
        </div>

        <p class="order-head">{{ $statusHead }}!</p>
        <div>
            <p>
                Hi {{ $name }},<br> 
                {{ $statusMsg }}
            </p>
        </div>

        <div class="order-id">
            <h3>Order Confirmation No. <span style="float: inline-end;">{{ $orderId }}</span></h3>
        </div>
        <div class="order-detail">
            @foreach ($orderItems as $orderItem)
                <p>{{ $orderItem->product_title }} <span style="float: inline-end;">£{{ $orderItem->sub_total }}</span></p>
            @endforeach
        </div>
        <hr>

        @if ($isDelivery)
            <div class="order-detail">
                <p>Shipping <span style="float: inline-end;">£2.00</span></p>
            </div>
        <hr>
        @endif

        <div class="order-detail">
            <h4>Total <span style="float: inline-end;">£{{ number_format($orderTotal, 2)}}</span></h4>
        </div>
        <hr>

        @if ($isDelivery)
            <div class="delivery-date">
                <h3>Estimated Delivery Date <span style="float: inline-end;"></span></h3>
            </div>

            <div class="summary">
                <h3>Delivery Address</h3>
                <p>{{ $address }}</p>
            </div>
        @endif

        <div class="footer">
            <h4>Thank You For Your Order!</h4>
        </div>
    </div>
</body>
</html>