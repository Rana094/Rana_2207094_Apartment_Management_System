<?php

use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\EnsureUserIsApproved;
use App\Http\Middleware\ResidentFacilityBookingMiddleware;
use App\Http\Middleware\ResidentMoveOutDuesMiddleware;
use App\Http\Middleware\ResidentPaymentTransactionMiddleware;
use App\Http\Middleware\ResidentPaymentTransactionNoCacheMiddleware;
use App\Http\Middleware\ResidentPaymentTransactionPayableMiddleware;
use App\Http\Middleware\ResidentPaymentTransactionRedirectPaidMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'approved' => EnsureUserIsApproved::class,
            'residentfacilitybookingmiddleware' => ResidentFacilityBookingMiddleware::class,
            'residentmoveoutduesmiddleware' => ResidentMoveOutDuesMiddleware::class,
            'residentpaymenttransactionmiddleware' => ResidentPaymentTransactionMiddleware::class,
            'residentpaymenttransactionnocachemiddleware' => ResidentPaymentTransactionNoCacheMiddleware::class,
            'residentpaymenttransactionpayablemiddleware' => ResidentPaymentTransactionPayableMiddleware::class,
            'residentpaymenttransactionredirectpaidmiddleware' => ResidentPaymentTransactionRedirectPaidMiddleware::class,
            'role' => EnsureUserHasRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
