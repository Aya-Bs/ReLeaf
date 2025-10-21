<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Services\Payments\StripePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $service = new StripePaymentService();
        $event = $service->constructEventFromWebhook($payload, $sigHeader);
        if (!$event) {
            return response('invalid', 400);
        }

        // Handle successful payment
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $sessionId = $session->id;
            $paymentIntent = $session->payment_intent ?? null;
            $receiptUrl = null;
            try {
                // Stripe PHP 13: PaymentIntent is expandable via separate API if needed
                if ($paymentIntent) {
                    $intent = \Stripe\PaymentIntent::retrieve($paymentIntent);
                    $charges = $intent->charges->data ?? [];
                    if (!empty($charges)) {
                        $receiptUrl = $charges[0]->receipt_url ?? null;
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Stripe receipt fetch failed: ' . $e->getMessage());
            }

            $donation = Donation::where('stripe_session_id', $sessionId)->first();
            if ($donation) {
                $donation->update([
                    'status' => 'confirmed',
                    'stripe_payment_intent_id' => $paymentIntent,
                    'receipt_url' => $receiptUrl,
                    'donated_at' => now(),
                ]);

                // Notify organizer on confirmation
                try {
                    $organizer = $donation->event?->user;
                    if ($organizer && $organizer->email) {
                        Mail::to($organizer->email)->queue(new \App\Mail\OrganizerDonationAlert($donation));
                    }
                } catch (\Throwable $e) {
                    Log::warning('Organizer email failed: ' . $e->getMessage());
                }
            }
        }

        if ($event->type === 'checkout.session.expired' || $event->type === 'payment_intent.payment_failed') {
            $obj = $event->data->object;
            $sessionId = $obj->id ?? ($obj->metadata->checkout_session_id ?? null);
            if ($sessionId) {
                Donation::where('stripe_session_id', $sessionId)->update(['status' => 'failed']);
            }
        }

        return response('ok');
    }
}
