<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentSetting;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StripePaymentController extends Controller
{
    public function checkout(Request $request, SubscriptionPlan $plan)
    {
        $user = Auth::user();
        
        // Get active payment settings
        $paymentSettings = PaymentSetting::active()->first();
        
        if (!$paymentSettings) {
            return back()->with('error', 'Payment gateway is not configured. Please contact support.');
        }
        
        return view('checkout', compact('plan', 'user', 'paymentSettings'));
    }
    
    public function createPaymentIntent(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);
        
        $plan = SubscriptionPlan::findOrFail($validated['plan_id']);
        $user = Auth::user();
        
        // Get payment settings
        $paymentSettings = PaymentSetting::active()->first();
        
        if (!$paymentSettings) {
            return response()->json(['error' => 'Payment gateway not configured'], 500);
        }
        
        try {
            // Initialize Stripe
            \Stripe\Stripe::setApiKey($paymentSettings->stripe_secret_key);
            
            // Create payment intent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $plan->price * 100, // Convert to cents
                'currency' => strtolower($paymentSettings->currency),
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                ],
            ]);
            
            // Create payment record
            Payment::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'payment_id' => $paymentIntent->id,
                'amount' => $plan->price,
                'currency' => $paymentSettings->currency,
                'status' => 'pending',
                'description' => "Subscription to {$plan->name}",
            ]);
            
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Stripe payment intent creation failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function webhook(Request $request)
    {
        $paymentSettings = PaymentSetting::active()->first();
        
        if (!$paymentSettings) {
            return response()->json(['error' => 'Payment gateway not configured'], 500);
        }
        
        \Stripe\Stripe::setApiKey($paymentSettings->stripe_secret_key);
        
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $paymentSettings->stripe_webhook_secret
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Webhook signature verification failed'], 400);
        }
        
        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSuccess($event->data->object);
                break;
                
            case 'payment_intent.payment_failed':
                $this->handlePaymentFailure($event->data->object);
                break;
                
            default:
                Log::info('Unhandled Stripe event: ' . $event->type);
        }
        
        return response()->json(['status' => 'success']);
    }
    
    protected function handlePaymentSuccess($paymentIntent)
    {
        $payment = Payment::where('payment_id', $paymentIntent->id)->first();
        
        if (!$payment) {
            Log::error('Payment not found for payment intent: ' . $paymentIntent->id);
            return;
        }
        
        // Update payment status
        $payment->update([
            'status' => 'succeeded',
            'paid_at' => now(),
            'receipt_url' => $paymentIntent->charges->data[0]->receipt_url ?? null,
        ]);
        
        // Create or update user subscription
        $subscription = UserSubscription::where('user_id', $payment->user_id)
            ->where('status', 'active')
            ->first();
        
        if ($subscription) {
            // Extend existing subscription
            $subscription->update([
                'expires_at' => now()->addMonth(),
            ]);
        } else {
            // Create new subscription
            UserSubscription::create([
                'user_id' => $payment->user_id,
                'subscription_plan_id' => $payment->subscription_plan_id,
                'status' => 'active',
                'tokens_used' => 0,
                'tokens_remaining' => $payment->subscriptionPlan->tokens_limit,
                'starts_at' => now(),
                'expires_at' => now()->addMonth(),
                'auto_renew' => true,
            ]);
        }
        
        Log::info('Payment succeeded and subscription updated for user: ' . $payment->user_id);
    }
    
    protected function handlePaymentFailure($paymentIntent)
    {
        $payment = Payment::where('payment_id', $paymentIntent->id)->first();
        
        if (!$payment) {
            return;
        }
        
        $payment->update([
            'status' => 'failed',
            'failure_reason' => $paymentIntent->last_payment_error->message ?? 'Unknown error',
        ]);
        
        Log::warning('Payment failed for user: ' . $payment->user_id);
    }
}
