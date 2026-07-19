<?php

namespace App\Http\Middleware;

use App\Models\PaymentTransaction;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResidentPaymentTransactionRedirectPaidMiddleware
{
    /**
     * Redirect unusable payment links before showing the payment page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $transaction = $request->attributes->get('paymentTransaction');

        // This middleware depends on the transaction loaded by ResidentPaymentTransactionMiddleware.
        abort_unless($transaction instanceof PaymentTransaction, 404);

        // A used payment link should show the unavailable page, not the payment form.
        if ($transaction->status === 'paid' || $transaction->bill->status === 'paid') {
            return redirect()
                ->route('payments.unavailable', $transaction->payment_token)
                ->with('status', 'This payment token has already been used.');
        }

        // Expired links are also redirected before rendering the payment screen.
        if ($transaction->expires_at && $transaction->expires_at->isPast()) {
            return redirect()
                ->route('payments.unavailable', $transaction->payment_token)
                ->with('status', 'This payment token has expired.');
        }

        return $next($request);
    }
}
