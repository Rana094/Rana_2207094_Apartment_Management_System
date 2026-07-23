<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    /**
     * Allow only approved users into protected portals.
     * Pending or rejected users are sent to the waiting approval page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Authentication runs before this middleware, but this guard keeps the check safe.
        if (! $user || ! $user->isApproved()) {
            return redirect()->route('approval.pending');
        }

        return $next($request);
    }
}
