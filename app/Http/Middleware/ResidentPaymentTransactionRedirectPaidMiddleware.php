<?php

namespace App\Http\Middleware;

use App\Models\PaymentTransaction;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResidentPaymentTransactionRedirectPaidMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $transaction = $request->attributes->get('paymentTransaction');

        abort_unless($transaction instanceof PaymentTransaction, 404);

        if ($transaction->status === 'paid' || $transaction->bill->status === 'paid') {
            return redirect()
                ->route('payments.unavailable', $transaction->payment_token)
                ->with('status', 'This payment token has already been used.');
        }

        if ($transaction->expires_at && $transaction->expires_at->isPast()) {
            return redirect()
                ->route('payments.unavailable', $transaction->payment_token)
                ->with('status', 'This payment token has expired.');
        }

        return $next($request);
    }
}
