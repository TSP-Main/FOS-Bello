<?php

namespace App\Http\Controllers;

use Stripe\Price;
use Carbon\Carbon;
use Stripe\Stripe;
use App\Models\User;
use App\Models\Order;
use App\Models\Company;
use App\Models\Product;
use Stripe\PaymentLink;
use Stripe\SetupIntent;
use App\Models\Category;
use App\Models\Discount;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Events\OrderReceived;
use App\Models\RestaurantEmail;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use App\Models\RestaurantStripeConfig;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;
        $orders = Order::where('company_id', $companyId)->orderBy('id', 'desc')->get();
        $data['currencySymbol'] = Company::where('id', $companyId)->pluck('currency_symbol')->first();
        
        $data['incomingOrders'] = $orders->filter(function ($value) {
            return $value->order_status == 0;
        });

        $data['acceptedOrders'] = $orders->filter(function ($value) {
            return $value->order_status == 1;
        });

        $data['rejectedOrders'] = $orders->filter(function ($value) {
            return $value->order_status == 2;
        });

        $data['deliveredOrders'] = $orders->filter(function ($value) {
            return $value->order_status == 3;
        });

        $data['canceledOrders'] = $orders->filter(function ($value) {
            return $value->order_status == 4;
        });

        return view('orders.list', $data);
    }

    public function detail($id)
    {
        $companyId = Auth::user()->company_id;
        
        $orderId = base64_decode($id);
        $data['orderDetails'] = Order::where('company_id', $companyId)->with('details')->find($orderId);
        
        if($data['orderDetails']){
            $this->deleteNotification($orderId);

            return view('orders.detail', $data);
        }
        else{
            return redirect()->route('dashboard')->with('error', 'No Order Found!');
        }
    }

    public function send_mail()
    {
        $data = ['name' => "Lana Desert"];
 
        Mail::send([], $data, function($message) {
            $message->to('usmandiljan@gmail.com', 'User')
                    ->subject('Order Status')
                    ->text('This is a test email.');
            $message->from('usman@tahqeeqotajzia.com','Lana Desert');
        });

        echo "Basic Email Sent. Check your inbox.";
    }

    public function check_incoming_orders(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $incomingOrders = Order::where('company_id', $companyId)->where('order_status', 0)->orderBy('id', 'desc')->get();
        return response()->json(['incomingOrders' => $incomingOrders]);
    }

    public function store_incoming_order(Request $request)
    {
        // Store Incoming Orders
        $response = validate_token($request->header('Authorization'));
        $responseData = $response->getData();
        $companyId = $responseData->company->id;

        $stripeConfig = RestaurantStripeConfig::where('company_id', $companyId)->first();

        if($responseData->status == 'success'){
            $request->validate([
                'name'          => 'required|string',
                'phone'         => 'required|string',
                'email'         => 'nullable|string|email',
                'address'       => 'nullable|string',
                'cartItems'     => 'required|array',
                'cartTotal'     => 'required|numeric',
                'orderType'     => 'required|in:delivery,pickup',
                'paymentOption' => 'required|in:cash,online',
                'payment_method_id' => 'nullable|string',
            ]);

            if ($request['paymentOption'] === 'onlsdfasdfasine') {
                // this is old method will be deleted in future after tsting orf new code
                // Handle online payments
                Stripe::setApiKey($stripeConfig->stripe_secret);
                if($request['orderType'] == 'delivery'){
                    // temporary delivery charges
                    $amount = (2 + $request['cartTotal']) * 100; // Convert to cents
                }
                else{
                    $amount = $request['cartTotal'] * 100; // Convert to cents
                }
    
                $paymentIntent = PaymentIntent::create([
                    'amount' => $amount,
                    'currency' => 'gbp',
                    'payment_method' => $request['payment_method_id'],
                    'confirm' => true,
                    'automatic_payment_methods' => [
                        'enabled' => true,
                        'allow_redirects' => 'never'
                    ],
                ]);

                if($paymentIntent->status == 'succeeded'){

                    $orderId = $this->createOrder($request, $companyId);
                    if($request->email){
                        $this->sendEmail($request->email, null, $orderId, $companyId);
                    }

                    // Add transaction entry
                    Transaction::create([
                        'stripe_payment_intent_id' => $paymentIntent->id,
                        'amount' => $amount,
                        'currency' => 'gbp',
                        'status' => $paymentIntent->status,
                        'order_id' => $orderId,
                    ]);
                }
            }
            else if ($request['paymentOption'] === 'online' && $request['payment_method_id']) {
                Stripe::setApiKey(Crypt::decrypt($stripeConfig->stripe_secret));

                try{
                    $setupIntent = SetupIntent::create([
                        'payment_method' => $request['payment_method_id'],
                        'confirm' => true,
                        'automatic_payment_methods' => [
                            'enabled' => true,
                            'allow_redirects' => 'never'
                        ],
                    ]);

                    if($setupIntent->status == 'succeeded'){
                        $paymentMethodId = $request['payment_method_id'];
                        $orderId = $this->createOrder($request, $companyId); 
                        $order = Order::find($orderId);
                        $order->payment_method_id = $paymentMethodId;
                        $order->save();
            
                        if ($request->email) {
                            $this->sendEmail($request->email, null, $orderId, $companyId);
                        }
                    }
                    else{
                        return response()->json(['error' => 'Payment not successful. Please try again.'], 400);
                    }
                }
                catch(\Exception $e){
                    return response()->json(['error' => 'Payment processing error: ' . $e->getMessage()], 500);
                }
            }
            else if($request['paymentOption'] === 'cash'){
                
                $request['payment_method_id'] = null;
                $orderId = $this->createOrder($request, $companyId);

                if($request->email){
                    $this->sendEmail($request->email, null, $orderId, $companyId);
                }
            }
            else{
                return response()->json(['status' => 'Payment Method', 'message' => 'Payment method is not valid'], 401);
            }

            // Fetch admin users for the same company
            $admins = User::where('role', 2)
                ->where('company_id', $companyId)
                ->get();

            // Database notification
            Notification::send($admins, new NewOrderNotification($orderId, $companyId));

            // pusher notification
            $data['msg'] = 'order received';
            $data['url'] = route('orders.detail', base64_encode($orderId));
            event(new OrderReceived($data, $data['url'], $companyId));

            $orderDetails = Order::with('details')->find($orderId);
    
            if($request['paymentOption'] === 'online'){
                // Return the client secret to the frontend
                return response()->json([
                    'status' => 'success',
                    'message' => 'Order placed successfully',
                    'orderDetails' => $orderDetails,
                    // 'clientSecret' => $paymentIntent->client_secret,
                ], 200);
            }
            else{
                return response()->json(['status' => 'success', 'message' => 'Order placed successfully', 'orderDetails' => $orderDetails], 200);
            }
        } 
        else {
            return response()->json(['status' => $responseData->status, 'message' => $responseData->message], 401);
        }
    }

    public function createOrder($postData, $companyId)
    {
        $restaurantData = Company::find($companyId);
        $discountData = $this->calculateDiscount($postData->discountCode, $postData);

        if($postData->orderType == 'delivery'){
            // temporary delivery charges
            // free shiping over specific amount
            if($postData->cartTotal > $restaurantData->free_shipping_amount){
                // $orderTotal = $postData->cartTotal;
                $orderTotal = $discountData['orderTotal'];
                $originalBill = $postData->cartTotal;
            }
            else{
                // $orderTotal = $postData->cartTotal + 2;
                $orderTotal = $discountData['orderTotal'] + 2;
                $originalBill = $postData->cartTotal + 2;
            }
        }
        else{
            // $orderTotal = $postData->cartTotal;
            $orderTotal = $discountData['orderTotal'];
            $originalBill = $postData->cartTotal;
        }

        $order = new Order();

        if($postData->paymentOption == 'online'){
            $stripeConfig = RestaurantStripeConfig::where('company_id', $companyId)->first();
            Stripe::setApiKey(Crypt::decrypt($stripeConfig->stripe_secret));

            try {
                $customer = \Stripe\Customer::create([
                    'name' => $postData->name,
                    'phone' => $postData->phone,
                    'email' => $postData->email ?? null,
                ]);
                $order->customer_stripe_id = $customer->id;
            } catch (\Exception $e) {
                return 'Failed to create Stripe customer: ' . $e->getMessage();
            }
        }

        $order->company_id      = $companyId;
        $order->name            = $postData->name;
        $order->email           = $postData->email;
        $order->phone           = $postData->phone;
        $order->address         = $postData->address;
        $order->city            = $postData->city;
        $order->postcode        = $postData->postcode;
        $order->total           = $orderTotal;
        $order->order_type      = $postData->orderType;
        $order->payment_option  = $postData->paymentOption;
        $order->order_note      = $postData->orderNote;
        $order->original_bill   = $originalBill;
        $order->discount_code   = $postData->discountCode ? strtoupper($postData->discountCode) : NULL;
        $order->discount_amount = $discountData['discountAmount'];

        $order->save();
        $orderId = $order->id;

        if($orderId){
            $orderItems = $postData->cartItems;

            foreach($orderItems as $orderItem){
                $orderDetail = new OrderDetail();

                $orderDetail->order_id      = $orderId;
                $orderDetail->product_id    = $orderItem['productId'];
                $orderDetail->product_title = $orderItem['productTitle'];
                $orderDetail->product_price = $orderItem['productPrice'];
                $orderDetail->quantity      = $orderItem['quantity'];
                $orderDetail->sub_total     = $orderItem['rowTotal'];
                $orderDetail->options       = $orderItem['optionNames'] ? implode(',', $orderItem['optionNames']) : NULL;
                $orderDetail->item_instruction = $orderItem['productInstruction'];

                $orderDetail->save();
            }

            return $orderId;
        }
        else{
            return 'Order not saved';
        }
    }

    public function update(Request $request, $id)
    {
        $id = base64_decode($id);
        $order = Order::with('details')->find($id);
        $currency = Company::where('id', $order->company_id)->pluck('currency')->first();
        
        if ($request->has('delivery_time')) {
            
            // Accept order
            $order->order_status = config('constants.ACCEPTED');
            $order->deliver_time = $request->input('delivery_time');

            if ($order->payment_method_id) {
                $order->payment_status = 1; 
                
                // Handle Stripe Payment on Order Acceptance
                $stripeConfig = RestaurantStripeConfig::where('company_id', $order->company_id)->first();
                Stripe::setApiKey(Crypt::decrypt($stripeConfig->stripe_secret));

                $customerStripeId = $order->customer_stripe_id;
            
                // Attach the payment method to the customer
                try {
                    $paymentMethod = PaymentMethod::retrieve($order->payment_method_id);
                    $paymentMethod->attach(['customer' => $customerStripeId]);
                } catch (\Exception $e) {
                    return redirect()->route('orders.list')->with('error', 'Payment method attachment failed: ' . $e->getMessage());
                }
    
                // $amount = ($order->order_type == 'delivery') ? (2 + $order->total) * 100 : $order->total * 100;

                try {
                    $paymentIntent = PaymentIntent::create([
                        'amount' => $order->total * 100,
                        'currency' => strtolower($currency),
                        'customer' => $customerStripeId,
                        'payment_method' => $order->payment_method_id,
                        'confirm' => true,
                        'automatic_payment_methods' => [
                            'enabled' => true,
                            'allow_redirects' => 'never',
                        ],
                    ]);

                    // Add transaction entry
                    Transaction::create([
                        'stripe_payment_intent_id' => $paymentIntent->id,
                        'amount' => $order->total * 100,
                        'currency' => strtolower($currency),
                        'status' => $paymentIntent->status,
                        'order_id' => $order->id,
                    ]);

                    $order->payment_method_id = null;
                    $order->customer_stripe_id = null;
                } catch (\Exception $e) {
                    return $e->getMessage();
                    // Handle failed payment
                    return redirect()->route('orders.list')->with('error', 'Payment failed: ' . $e->getMessage());
                }
            }
        } 
        elseif ($request->has('reject')) {
            // Reject order
            $order->order_status = config('constants.REJECTED');
            $order->payment_method_id = null;
            $order->customer_stripe_id = null;
            $order->reject_reason = $request->reject_reason;
        }
        elseif ($request->has('deliver')) {
            // Delivered
            $order->order_status = config('constants.DELIVERED');
        }
        elseif ($request->has('cancel')) {
            // Cancel
            $order->order_status = config('constants.CANCELED');
        }

        $order->save();

        $this->deleteNotification($id);

        $orderStatus = config('constants.ORDER_STATUS')[$order->order_status];

        if($order->email){
            $res = $this->sendEmail($order->email, $orderStatus, $id, Auth::user()->company_id);
            // return $res;
        }

        $company = Company::find(Auth::user()->company_id);
        $data['company'] = [
            'name' => $company->name,
            'address' => $company->address,
            'freeShippingAmount' => $company->free_shipping_amount,
            'currency' => $company->currency
        ];

        // Redirect to print route if order is accepted
        if ($order->order_status == config('constants.ACCEPTED')) {
            $data['order'] = $order;
            return view('orders.print', $data);
        }

        return redirect()->route('orders.list')->with('success', 'Order status updated successfully.');
    }

    public function print($id)
    {
        $id = base64_decode($id);
        $data['order'] = Order::with('details')->findOrFail($id);

        $company = Company::find(Auth::user()->company_id);
        $data['company'] = [
            'name' => $company->name,
            'address' => $company->address,
            'freeShippingAmount' => $company->free_shipping_amount,
            'currency' => $company->currency
        ];

        return view('orders.print', $data)->render();
    }

    public function sendEmail($email, $orderStatus = null, $orderId, $companyId)
    {
        // Send mail to user if email is entered
        $mailConfig = RestaurantEmail::where('company_id', $companyId)->first();
        $company = Company::find($companyId);
        
        $data = ['name' => $mailConfig->name];
        $from = $mailConfig->address;

        $orderData = Order::with('details')->find($orderId);
        
        if($orderData->order_status == 1){
            $statusHead = 'Order Accepted';
            $statusMsg = 'Pleased to inform you that your order has been accepted.';
        } elseif($orderData->order_status == 2){
            $statusHead = 'Order Rejected'; 
            $statusMsg = 'We regret to inform you that your recent order at Lana Dessert has been rejected. We appreciate your understanding and look forward to serving you soon again.'; 
        } elseif($orderData->order_status == 3){
            $statusHead = 'Order Delivered'; 
            $statusMsg = 'Your order has been delivered.'; 
        } else {
            $statusHead = 'Order Received';
            $statusMsg = 'We have received your order! ';
        }
        
        $orderDetails = [
            'name' => $orderData->name,
            'statusHead' => $statusHead,
            'statusMsg' => $statusMsg,
            'orderId' => $orderId,
            'isDelivery' => $orderData->order_type == 'delivery' ? true : false,
            'orderTotal' => $orderData->total,
            'orderItems' => $orderData->details,
            'address' => $orderData->address,
            'restaurantName' => $mailConfig->name,
            'freeShippingAmount' => $company->free_shipping_amount,
            'currencySymbol' => $company->currency_symbol,
        ];

        // Dynamically configure the mail settings
        config([
            'mail.default'                  => $mailConfig->mailer,
            'mail.mailers.smtp.host'        => $mailConfig->host,
            'mail.mailers.smtp.port'        => $mailConfig->port,
            'mail.mailers.smtp.encryption'  => $mailConfig->encryption,
            'mail.mailers.smtp.username'    => $mailConfig->username,
            'mail.mailers.smtp.password'    => Crypt::decrypt($mailConfig->password),
            'mail.from.address'             => $mailConfig->address,
            'mail.from.name'                => $mailConfig->name,
        ]);

        try{
            Mail::send('orders.email_template', $orderDetails, function ($message) use ($email, $from,  $mailConfig) {
                $message->to($email, 'User')
                        ->subject('Order Information');
                $message->from($from, $mailConfig->name);
            });
            return true;
        }
        catch(\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteNotification($orderId)
    {
        // \Log::info("Attempting to delete notification for order_id: {$orderId}");
    
        // Fetch notifications with the specific order_id to see if they exist
        $notifications = DB::table('notifications')
            ->whereRaw("JSON_EXTRACT(data, '$.order_id') = ?", [$orderId])
            ->get();

        // Log the notifications found
        // \Log::info("Found notifications: ", (array) $notifications);

        // Attempt to delete the notification using a raw query
        $deletedCount = DB::table('notifications')
            ->whereRaw("JSON_EXTRACT(data, '$.order_id') = ?", [$orderId])
            ->delete();
        
        // \Log::info("Deleted notifications: {$deletedCount}");
    }

    public function calculateDiscount($code, $orderData)
    {
        $discountDetail = Discount::where('code', $code)->first();
        $orderTotal = $orderData->cartTotal;
        $discountAmount = 0;

        if($discountDetail){
            $minimumAmount = $discountDetail->minimum_amount ?? 0.00;
            $discountRate = $discountDetail->rate;

            if ($orderTotal > $minimumAmount) {
                if ($discountDetail->type == 1) {
                    // percentage discount calculation
                    $discountAmount = round(($orderTotal * ($discountRate / 100)), 2);
                } elseif ($discountDetail->type == 2) {
                    // fixed amount discount calculation
                    $discountAmount = $discountRate;
                }
            
                // Apply discount if valid
                if (isset($discountAmount) && $orderTotal > $discountAmount) {
                    $orderTotal -= $discountAmount;
                }
            }
        }

        return ['orderTotal' => $orderTotal, 'discountAmount' => $discountAmount];
    }

    public function ordersList()
    {
        $companyId = Auth::user()->company_id;
        $data['orders'] = Order::where('company_id', $companyId)->orderBy('id', 'desc')->get();
        $data['currencySymbol'] = Company::where('id', $companyId)->pluck('currency_symbol')->first();

        return view('orders.orders_list', $data);
    }

    public function ordersFilter(Request $request)
    {
        $companyId = Auth::user()->company_id; 
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Order::where('company_id', $companyId);

        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $orders = $query->get();
        
        return response()->json($orders);
    }

    public function walkin_order_form ()
    {
        $companyId = Auth::user()->company_id;
        $data['companyDetail'] = Company::where('id', $companyId)->first();
        $data['categories'] = Category::where('company_id', $companyId)->get();
        $data['products'] = Product::where('company_id', $companyId)->get();
        $data['currencySymbol'] = $data['companyDetail']->currency_symbol;

        // return $data;
        return view('orders.walkin_order_form', $data);
    }

    public function store_walkin_order(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $company = Company::find($companyId);

        $stripeConfig = RestaurantStripeConfig::where('company_id', $companyId)->first();

        $order = new Order();

        $order->company_id      = $companyId;
        $order->name            = 'Walk In Customer';
        $order->total           = $request->data['total'];
        $order->original_bill   = $request->data['total'];
        $order->discount_amount = '0.00';
        $order->order_type      = 'Walk In';
        $order->payment_option  = 'QR Code';
        $order->order_status  = 1;
        
        $order->save();
        $orderId = $order->id;

        if($orderId){
            $orderItems = $request->data['items'];

            foreach($orderItems as $orderItem){
                $orderDetail = new OrderDetail();

                $orderDetail->order_id      = $orderId;
                $orderDetail->product_id    = $orderItem['id'];
                $orderDetail->product_title = $orderItem['title'];
                $orderDetail->product_price = $orderItem['price'];
                $orderDetail->quantity      = $orderItem['quantity'];
                $orderDetail->sub_total     = $orderItem['price'] * $orderItem['quantity'];
                $orderDetail->options       = $orderItem['options'];

                $orderDetail->save();
            }

            // Create Stripe Payment Link
            Stripe::setApiKey(Crypt::decrypt($stripeConfig->stripe_secret));

            // Create a Price object dynamically based on the order total
            $price = Price::create([
                'unit_amount' => $request->data['total'] * 100,
                'currency' => $company->currency,
                'product_data' => [
                    'name' => 'Walk-In Order',
                ],
            ]);

            $paymentLink = PaymentLink::create([
                'line_items' => [[
                    'price' => $price->id,
                    'quantity' => 1,
                ]],
                'metadata' => [
                    'order_id' => $orderId,
                    'company_id' => $companyId
                ],
            ]);

            $order->stripe_payment_link  = $paymentLink->url;
            $order->save();

            $printUrl = route('orders.print', ['id' => base64_encode($orderId)]);
            return response()->json(['print_url' => $printUrl]);
        }
    }
}
