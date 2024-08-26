<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemporaryOrder;
use App\Models\TemporaryOrderDetail;
use App\Models\Transaction;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class TemporaryOrderController extends Controller
{
    public function process(Request $request)
    {
        try {
            // Validate request
            $validatedData = $request->validate([
                'name' => 'required|string',
                'phone' => 'required|string',
                'email' => 'nullable|string|email',
                'address' => 'nullable|string',
                'cartItems' => 'required|array',
                'cartTotal' => 'required|numeric',
                'orderType' => 'required|string',
                'paymentOption' => 'required|in:cash,online',
                'payment_method_id' => 'nullable|string',
            ]);
    
            // Validate token and get company ID
            $response = validate_token($request->header('Authorization'));
            $responseData = $response->getData();

            if ($responseData->status !== 'success') {
                return response()->json(['status' => $responseData->status, 'message' => $responseData->message], 401);
            }

            $companyId = $responseData->company->id;

            if ($validatedData['paymentOption'] === 'online') {
                // Handle online payments
                Stripe::setApiKey(env('STRIPE_SECRET'));
                $amount = $validatedData['cartTotal'] * 100; // Convert to cents
    
                $paymentIntent = PaymentIntent::create([
                    'amount' => $amount,
                    'currency' => 'gbp',
                    'payment_method' => $validatedData['payment_method_id'],
                    'confirm' => true,
                    'automatic_payment_methods' => [
                        'enabled' => true,
                        'allow_redirects' => 'never'
                    ],
                ]);

                if($paymentIntent->status == 'succeeded'){

                    $temporaryOrderId = $this->createTemporaryOrder($validatedData, $companyId);

                    // Add transaction entry
                    Transaction::create([
                        'stripe_payment_intent_id' => $paymentIntent->id,
                        'amount' => $validatedData['cartTotal'],
                        'currency' => 'usd',
                        'status' => $paymentIntent->status,
                        'order_id' => $temporaryOrderId,
                    ]);
                }
            }
            else{
                $validatedData['payment_method_id'] = null;
                $temporaryOrderId = $this->createTemporaryOrder($validatedData, $companyId);
            }
    
            // Fetch admin users for the same company
            $admins = User::where('role', 2)
                ->where('company_id', $companyId)
                ->get();

            // Notify all relevant admins
            Notification::send($admins, new NewOrderNotification($temporaryOrderId, $companyId));
    
            if($validatedData['paymentOption'] === 'online'){
                // Return the client secret to the frontend
                return response()->json([
                    'status' => 'success',
                    'message' => 'Order placed successfully',
                    'orderId' => $temporaryOrderId,
                    'clientSecret' => $paymentIntent->client_secret,
                ], 200);
            }
            else{
                return response()->json(['status' => 'success', 'message' => 'Order placed successfully', 'orderId' => $temporaryOrderId], 200);
            }
    
        } catch (\Exception $e) {
            Log::error('Order Processing Error:', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function createTemporaryOrder($validatedData, $companyId)
    {
        // Create temporary order
        $temporaryOrder = TemporaryOrder::create([
            'company_id' => $companyId,
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'total' => $validatedData['cartTotal'],
            'order_type' => $validatedData['orderType'],
            'payment_option' => $validatedData['paymentOption'],
            'status' => 'pending',
        ]);

        $temporaryOrderId = $temporaryOrder->id;

        foreach ($validatedData['cartItems'] as $item) {
            TemporaryOrderDetail::create([
                'temporary_order_id' => $temporaryOrderId,
                'product_id' => $item['productId'],
                'product_title' => $item['productTitle'],
                'product_price' => $item['productPrice'],
                'quantity' => $item['quantity'],
                'sub_total' => $item['rowTotal'],
                'options' => implode(',', $item['optionNames']),
                'item_instruction' => $item['productInstruction'],
            ]);
        }

        return $temporaryOrderId;
    }
}
