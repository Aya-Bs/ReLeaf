<?php

namespace App\Services\Payments;

use App\Models\Donation;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;

class StripePaymentService
{
    public function __construct()
    {
        $secret = config('services.stripe.secret');
        if ($secret) {
            Stripe::setApiKey($secret);
        }
    }

    /**
     * Create a Stripe Checkout Session for a donation.
     * Returns array with 'id' and 'url'.
     */
    public function createCheckoutSession(Donation $donation): array
    {
        $currency = strtolower($donation->currency ?: 'eur');
        // Stripe only supports certain currencies for card in test; EUR/USD ok.
        if (!in_array(strtoupper($currency), ['EUR', 'USD', 'GBP'])) {
            $currency = 'eur';
        }
        $amountInCents = (int) round($donation->amount * 100);

        $successUrl = route('donations.success', $donation);
        $cancelUrl = route('donations.create', $donation->event) . '?cancel=1';

        $session = CheckoutSession::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'customer_email' => $donation->donor_email,
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => 'Don pour ' . ($donation->event->title ?? 'événement'),
                        'description' => 'Donateur: ' . $donation->donor_name,
                    ],
                    'unit_amount' => $amountInCents,
                ],
                'quantity' => 1,
            ]],
            'metadata' => [
                'donation_id' => (string)$donation->id,
                'event_id' => (string)$donation->event_id,
                'type' => $donation->type,
            ],
            'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl,
        ]);

        $donation->update(['stripe_session_id' => $session->id]);

        return ['id' => $session->id, 'url' => $session->url];
    }

    /**
     * Verify webhook signature and return event or null.
     */
    public function constructEventFromWebhook(string $payload, string $sigHeader)
    {
        $secret = config('services.stripe.webhook_secret');
        if (!$secret) {
            Log::warning('Stripe webhook secret missing');
            return null;
        }
        try {
            return Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Throwable $e) {
            Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * When user returns to success page with a session_id, finalize donation without webhook.
     * Returns true if donation status updated to confirmed.
     */
    public function finalizeDonationFromSession(Donation $donation, string $sessionId): bool
    {
        try {
            // Expand payment_intent and its charges to get a receipt URL
            $session = Session::retrieve([
                'id' => $sessionId,
                'expand' => ['payment_intent.charges'],
            ]);
            if (!$session || ($session->id !== $donation->stripe_session_id)) {
                return false;
            }
            $paid = ($session->payment_status ?? null) === 'paid' || ($session->status ?? null) === 'complete';
            if (!$paid) return false;

            $paymentIntentId = $session->payment_intent ? (is_string($session->payment_intent) ? $session->payment_intent : $session->payment_intent->id) : null;
            $receiptUrl = null;
            if ($session->payment_intent && !is_string($session->payment_intent)) {
                $charges = $session->payment_intent->charges->data ?? [];
                if (!empty($charges)) {
                    $receiptUrl = $charges[0]->receipt_url ?? null;
                }
            } else if ($paymentIntentId) {
                // Fallback retrieve
                $pi = PaymentIntent::retrieve($paymentIntentId);
                $charges = $pi->charges->data ?? [];
                if (!empty($charges)) {
                    $receiptUrl = $charges[0]->receipt_url ?? null;
                }
            }

            $donation->update([
                'status' => 'confirmed',
                'stripe_payment_intent_id' => $paymentIntentId,
                'receipt_url' => $receiptUrl,
                'donated_at' => now(),
            ]);
            return true;
        } catch (\Throwable $e) {
            Log::warning('Stripe finalize from session failed: ' . $e->getMessage());
            return false;
        }
    }
}
