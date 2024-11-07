<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Order;
use App\Models\RestaurantStripeConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        $companyId = $request->input('data.object.metadata.company_id');
        // Log::info('$companyId', ['$companyId' => $companyId]);
        $stripeConfig = RestaurantStripeConfig::where('company_id', $companyId)->first();
        
        if (!$stripeConfig) {
            return response()->json(['error' => 'Client not found'], 400);
        }

        // Use client-specific webhook secret
        $webhookSecret = $stripeConfig->stripe_webhook_secret;

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
            
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the checkout.session.completed event
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            // Retrieve metadata to find order ID
            $orderId = $session->metadata->order_id;

            // Find the order and update the status to 'Paid'
            $order = Order::find($orderId);
            if ($order) {
                $order->payment_status = 1;
                // $order->stripe_payment_intent_id = $paymentIntentId;
                $order->save();
            }
        }

        return response()->json(['status' => 'success']);
    }
}
