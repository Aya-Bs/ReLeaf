# Stripe Checkout for Donations

Quick setup to accept card payments via Stripe Checkout on the donation form.

## 1) Set environment variables (.env)

Add the following entries (use test mode keys from dashboard.stripe.com):

STRIPE_PUBLISHABLE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx

Then clear config cache (optional in local):

php artisan config:clear

## 2) Webhook (optional in local, recommended)

Create a webhook endpoint pointing to:

POST https://YOUR_DOMAIN/stripe/webhook

Events to send (test mode):

-   checkout.session.completed
-   checkout.session.expired
-   payment_intent.payment_failed

Alternatively, in local use the Stripe CLI:

stripe login
stripe listen --forward-to "http://localhost:8000/stripe/webhook"

## 3) How it works

-   When a donation is created with payment method "card", we create a Stripe Checkout Session and redirect the user to Stripe.
-   On success, Stripe redirects back to our success page with session_id.
-   We finalize the donation either via webhook or immediately on the success page using the session_id (dev friendly).
-   Receipt URL is stored when available and shown on the success page.

## 4) Test a payment

Use these test card details on the Stripe page:

-   Card: 4242 4242 4242 4242
-   Date: any future date
-   CVC: any 3 digits
-   ZIP: any

## 5) Notes

-   Supported currencies for card in test are EUR/USD/GBP; we fallback to EUR if unsupported.
-   Ensure the donation form shows the payment method selector and the button label switches to "Payer par carte" when card is selected.
