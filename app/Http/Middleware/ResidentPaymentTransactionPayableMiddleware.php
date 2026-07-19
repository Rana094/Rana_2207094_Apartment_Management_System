<?php

namespace App\Http\Middleware;

use App\Models\PaymentTransaction;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResidentPaymentTransactionPayableMiddleware
{
    /**
     * Allow payment confirmation only while the transaction is still payable.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $transaction = $request->attributes->get('paymentTransaction');

        // This middleware must run after ResidentPaymentTransactionMiddleware.
        abort_unless($transaction instanceof PaymentTransaction, 404);

        // Already-paid bills must not be paid a second time.
        if ($transaction->status === 'paid' || $transaction->bill->status === 'paid') {
            return redirect()
                ->route('payments.unavailable', $transaction->payment_token)
                ->with('status', 'This bill has already been paid.');
        }

        // Expired payment tokens are blocked even if the bill is unpaid.
        if ($transaction->expires_at && $transaction->expires_at->isPast()) {
            return redirect()
                ->route('payments.unavailable', $transaction->payment_token)
                ->with('status', 'This payment token has expired.');
        }

        return $next($request);
    }
}
