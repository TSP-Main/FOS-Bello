<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\TemporaryOrder;
use App\Models\TemporaryOrderDetail;


class PaymentController extends Controller
{  
    public function charge(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_API_KEY'));
    
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'phone' => 'required|string',
                'email' => 'nullable|string|email',
                'address' => 'nullable|string',
                'cartItems' => 'required|array',
                'cartTotal' => 'required|numeric',
                'orderType' => 'required|string',
                'paymentOption' => 'required|in:cash,online',
                'paymentMethodId' => 'required_if:paymentOption,online|string',
            ]);
    
            $amount = $validatedData['cartTotal'] * 100;
    
            $paymentIntent = null;
            if ($validatedData['paymentOption'] === 'online') {
                $paymentIntent = PaymentIntent::create([
                    'amount' => $amount,
                    'currency' => 'usd',
                    'payment_method' => $validatedData['paymentMethodId'],
                    'confirm' => true,
                    'automatic_payment_methods' => ['enabled' => true],
                ]);
            }
    
            // Store order in temporary_orders
            $temporaryOrder = new TemporaryOrder();
            $temporaryOrder->company_id = 1;
            $temporaryOrder->name = $validatedData['name'];
            $temporaryOrder->email = $validatedData['email'];
            $temporaryOrder->phone = $validatedData['phone'];
            $temporaryOrder->address = $validatedData['address'];
            $temporaryOrder->total = $validatedData['cartTotal'];
            $temporaryOrder->order_type = $validatedData['orderType'];
            $temporaryOrder->payment_option = $validatedData['paymentOption'];
            $temporaryOrder->status = 'pending'; // Set status to pending
            $temporaryOrder->save();
            $temporaryOrderId = $temporaryOrder->id;
    
            // Store transaction if online payment
            if ($paymentIntent) {
                Transaction::create([
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'amount' => $validatedData['cartTotal'],
                    'currency' => 'usd',
                    'status' => $paymentIntent->status,
                    'order_id' => $temporaryOrderId,
                ]);
            }
    
            foreach ($validatedData['cartItems'] as $item) {
                $orderDetail = new TemporaryOrderDetail();
                $orderDetail->temporary_order_id = $temporaryOrderId;
                $orderDetail->product_id = $item['productId'];
                $orderDetail->product_title = $item['productTitle'];
                $orderDetail->product_price = $item['productPrice'];
                $orderDetail->quantity = $item['quantity'];
                $orderDetail->sub_total = $item['rowTotal'];
                $orderDetail->options = implode(',', $item['optionNames']);
                $orderDetail->save();
            }
    
            return response()->json([
                'status' => 'success',
                'message' => 'Order Placed Successfully',
                'orderId' => $temporaryOrderId,
                'clientSecret' => $paymentIntent ? $paymentIntent->client_secret : null
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Payment failed. Please try again.'], 500);
        }
    }

}