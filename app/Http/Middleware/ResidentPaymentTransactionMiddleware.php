<?php

namespace App\Http\Middleware;

use App\Models\PaymentTransaction;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResidentPaymentTransactionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->route('token');

        abort_unless(is_string($token) && $token !== '', 404);

        $transaction = PaymentTransaction::with(['bill.flat.building', 'resident'])
            ->where('payment_token', $token)
            ->firstOrFail();

        abort_unless($request->user()?->role === 'resident', 403);
        abort_unless($transaction->resident_id === $request->user()->id, 403);

        $request->attributes->set('paymentTransaction', $transaction);

        return $next($request);
    }
}
