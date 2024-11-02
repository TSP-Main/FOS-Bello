<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Log::info('Stripe webhook received:', ['payload' => $request->all()]);
        
        // Verify the webhook signature
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET'); // Get this from your Stripe dashboard

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object; // Contains the payment information
            $orderId = $session->metadata->order_id; // Get order ID from metadata

            // Update the order status in your database
            $order = Order::find($orderId);
            if ($order) {
                $order->payment_status = 1; // Assuming '3' is for 'Paid' status
                $order->save();
            } else {
                Log::warning('Order not found for ID: ' . $orderId);
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
