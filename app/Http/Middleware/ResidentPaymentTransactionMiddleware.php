<?php

namespace App\Http\Middleware;

use App\Models\PaymentTransaction;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResidentPaymentTransactionMiddleware
{
    /**
     * Resolve the payment token and confirm it belongs to the logged-in resident.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->route('token');

        abort_unless(is_string($token) && $token !== '', 404);

        // The token is the secure public identifier used in payment links.
        $transaction = PaymentTransaction::with(['bill.flat.building', 'resident'])
            ->where('payment_token', $token)
            ->firstOrFail();

        // A resident may only open their own payment transaction link.
        abort_unless($request->user()?->role === 'resident', 403);
        abort_unless($transaction->resident_id === $request->user()->id, 403);

        // Store the transaction for later payment middleware and the controller.
        $request->attributes->set('paymentTransaction', $transaction);

        return $next($request);
    }
}
