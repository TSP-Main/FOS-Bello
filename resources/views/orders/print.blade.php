<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <style>
        body {
            font-family: 'Verdana';
            width: 80mm; /* Set width for receipt printers (80mm standard) */
            margin: 0;
            padding: 0;
        }
        .receipt-container {
            text-align: center;
            width: 100%;
        }
        .header {
            /* font-size: 14px; */
            margin-bottom: 10px;
        }
        .order-details {
            text-align: left;
            font-size: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 18px;
        }
        .address {
            font-size: 20px;
        }
        .order-type{
            text-align: center;
            font-size: 25px;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header (e.g., Store Name, Address, etc.) -->
        <div class="header">
            <h1>{{ $company['name'] }}</h1>
            <div class="address">
                <p>{{ $company['address'] }}</p>
            </div>
        </div>
        <hr style="border: 1px dotted black;">

        <div class="order-type">
            <span style="font-weight: bold">
                {{ Str::ucfirst($order->order_type) }}
            </span>
            <br>
            <span>Order number: {{ $order->id }}</span>
        </div>
        <hr style="border: 1px dotted black;">

        <!-- Order Details -->
        <div class="order-details">
            <table>
                <thead>
                    <th></th>
                    <th></th>
                    <th>{{ $company['currency'] }}</th>
                </thead>
                <tbody>
                    @php
                        $subtotalSum = 0;
                    @endphp
                    @foreach($order->details as $item)
                        <tr>
                            <td>{{ $item->quantity }} x #</td>
                            <td>{{ $item->product_title }}</td>
                            <td>{{ number_format($item->sub_total, 2) }}</td>
                        </tr>
                        @php
                            $subtotalSum +=  $item->sub_total;
                        @endphp
                    @endforeach
                </tbody>
            </table>
            <br>
            
            <div style="display: flex; justify-content: space-between;">
                <span>Subtotal</span>
                <span>{{ number_format($subtotalSum, 2) }}</span> 
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Restaurant discount</span>
                <span>{{ number_format($order->discount_amount, 2) }}</span> 
            </div>
            <br>

            <div style="display: flex; justify-content: space-between;">
                <span>Order total</span>
                <span>{{ number_format($subtotalSum - $order->discount_amount , 2) }}</span> 
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Service charge</span>
                <span>0.00</span> 
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Delivery charge</span>
                <span>
                    @if ($order->order_type == 'delivery' && ($subtotalSum < $company['freeShippingAmount']))
                        2.00
                    @else
                        0.00
                    @endif    
                </span> 
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span></span>
                <span>-----</span> 
            </div>
            <br>

            <div style="display: flex; justify-content: space-between; font-weight:bold">
                <span>Total Due</span>
                <span>{{ number_format($order->total, 2) }}</span> 
            </div>
            <br>

            <div>
                <span>{{ $order->payment_option == 'cash' ? 'Pay by:' : 'Paid by:'}}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>{{ $order->payment_option == 'cash' ? 'Cash' : 'Card'}}</span>
                <span>{{ number_format($order->total, 2) }}</span> 
            </div>
            <hr style="border: 1px dotted black;">
            
            <div style="text-align: center">
                <span style="font-weight: bold">IMPORTANT: FOR FOOD ALLERGIAN INFO</span><br>
                <span>Call the restaurant or check their menu</span>
            </div>
            <hr style="border: 1px dotted black;">

            <div style="text-align: center">
                <span style="font-size: 25px; font-weight: bold">{{ $order->payment_option == 'cash' ? 'ORDER HAS NOT BEEN PAID' : 'ORDER HAS BEEN PAID'}}</span>
            </div>
            <hr style="border: 1px dotted black;">

            <div>
                <span>Customer details:</span><br>
                <span style="font-size: 22px; font-weight: bold">{{ $order->name }}</span><br>
                <span style="font-size: 22px; font-weight: bold">{{ $order->address }}</span><br>
            </div>
            <br>

            <div>
                <span>To contact customer call:</span><br>
                <span style="font-size: 22px; font-weight: bold">{{ $order->phone }}</span><br>
            </div>
            <br>
            <hr style="border: 1px dotted black;">

            <div style="display: flex; justify-content: space-between;">
                <span>Order placed at</span>
                <span>{{ $order->created_at->format('H:i d-M') }}</span> 
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Order accepted at</span>
                <span>{{ $order->updated_at->format('H:i d-M') }}</span> 
            </div>
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
