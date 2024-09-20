<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\User;
use App\Models\Order;
use App\Models\Company;
use Stripe\PaymentIntent;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Events\OrderReceived;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;
        $orders = Order::where('company_id', $companyId)->orderBy('id', 'desc')->get();
        
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
        
        return view('orders.detail', $data);
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

            if ($request['paymentOption'] === 'online') {
                // Handle online payments
                Stripe::setApiKey(env('STRIPE_SECRET'));
                $amount = $request['cartTotal'] * 100; // Convert to cents
    
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
                        $this->sendEmail($request->email, null, $orderId);
                    }

                    // Add transaction entry
                    Transaction::create([
                        'stripe_payment_intent_id' => $paymentIntent->id,
                        'amount' => $request['cartTotal'],
                        'currency' => 'gbp',
                        'status' => $paymentIntent->status,
                        'order_id' => $orderId,
                    ]);
                }
            }
            else if($request['paymentOption'] === 'cash'){
                
                $request['payment_method_id'] = null;
                $orderId = $this->createOrder($request, $companyId);

                if($request->email){
                    $this->sendEmail($request->email, null, $orderId);
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
                    'clientSecret' => $paymentIntent->client_secret,
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
        // Save order detail
        $order = new Order();

        $order->company_id      = $companyId;
        $order->name            = $postData->name;
        $order->email           = $postData->email;
        $order->phone           = $postData->phone;
        $order->address         = $postData->address;
        $order->total           = $postData->cartTotal;
        $order->order_type      = $postData->orderType;
        $order->payment_option  = $postData->paymentOption;
        $order->order_note      = $postData->orderNote;

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

        if ($request->has('delivery_time')) {
            // Accept order
            $order->order_status = config('constants.ACCEPTED');
            $order->deliver_time = $request->input('delivery_time');
        } elseif ($request->has('reject')) {
            // Reject order
            $order->order_status = config('constants.REJECTED');
        } elseif ($request->has('deliver')) {
            // Delivered
            $order->order_status = config('constants.DELIVERED');
        } elseif ($request->has('cancel')) {
            // Cancel
            $order->order_status = config('constants.CANCELED');
        }

        $order->save();

        $orderStatus = config('constants.ORDER_STATUS')[$order->order_status];

        // Send mail to user if email is entered
        // if ($order->email) {
        //     $data = ['name' => "Lana Desert"];
    
        //     Mail::send([], $data, function($message) use ($order, $orderStatus, $id) {
        //         $message->to($order->email, 'User')
        //                 ->subject('Order Status')
        //                 ->text('Your Order is '. $orderStatus . '. Your Order Id is: ' . $id);
        //         $message->from('sales@lanadessert.co.uk', 'Lana Desert');
        //     });
        // }

        if($order->email){
            $this->sendEmail($order->email, $orderStatus, $id);
        }

        // if($order->order_status == config('constants.ACCEPTED')){
        //     // Generate PDF receipt
        //     $pdf = PDF::loadView('orders.reciept', ['order' => $order]);
        
        //     // Define the path to store the PDF
        //     $pdfPath = 'receipts/order_' . $order->id . '.pdf';
        
        //     // Store the PDF in the storage directory
        //     Storage::put($pdfPath, $pdf->output());
        // }
        
        $company = Company::find(Auth::user()->company_id);
        $data['company'] = [
            'name' => $company->name,
            'address' => $company->address,
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
        ];

        return view('orders.print', $data)->render();
    }

    public function sendEmail($email, $orderStatus = null, $orderId)
    {
        // Send mail to user if email is entered
        $data = ['name' => "Lana Desert"];

        if($orderStatus){
            $subject = 'Order Status';
            $text = 'Your Order is '. $orderStatus . '. Your Order Id is: ' . $orderId;
        }
        else{
            $subject = 'Order Received';
            $text = 'We received your order. Your Order Id is: ' . $orderId;
        }

        Mail::send([], $data, function($message) use ($email, $orderStatus, $orderId, $subject, $text) {
            $message->to($email, 'User')
                    ->subject($subject)
                    ->text($text);
            $message->from('sales@lanadessert.co.uk', 'Lana Desert');
        });

        return true;
    }
}
