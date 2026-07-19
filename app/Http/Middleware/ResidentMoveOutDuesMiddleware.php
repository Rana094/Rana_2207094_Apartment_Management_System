<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResidentMoveOutDuesMiddleware
{
    /**
     * Block move-out requests until the resident has paid all outstanding bills.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $resident = $request->user();

        abort_unless($resident?->role === 'resident', 403);

        // Only fully paid bills allow a resident to start the move-out process.
        $outstandingBills = $resident->bills()
            ->where('status', '!=', 'paid')
            ->orderBy('due_date')
            ->get(['id', 'bill_number', 'amount', 'due_date', 'status']);

        if ($outstandingBills->isNotEmpty()) {
            $totalDue = $outstandingBills->sum(fn ($bill) => (float) $bill->amount);

            return redirect()
                ->route('resident.move-out')
                ->withErrors([
                    'move_out_dues' => 'Please clear all outstanding dues before submitting a move-out request. Pending bills: '.$outstandingBills->count().', total due: Tk '.number_format($totalDue, 2).'.',
                ])
                ->withInput();
        }

        return $next($request);
    }
}
