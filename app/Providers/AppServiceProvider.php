<?php

namespace App\Providers;

use App\Models\Bill;
use App\Models\Complaint;
use App\Models\Document;
use App\Models\FacilityBooking;
use App\Models\PaymentProof;
use App\Models\VisitorRequest;
use App\Models\WorkOrder;
use App\Policies\BillPolicy;
use App\Policies\ComplaintPolicy;
use App\Policies\DocumentPolicy;
use App\Policies\FacilityBookingPolicy;
use App\Policies\PaymentProofPolicy;
use App\Policies\VisitorRequestPolicy;
use App\Policies\WorkOrderPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register application services before the app is booted.
     */
    public function register(): void
    {
        //
    }

    /**
     * Connect models to policies so controllers can call $this->authorize().
     */
    public function boot(): void
    {
        // Policies centralize access rules for bills, documents, complaints, visitors, and work orders.
        Gate::policy(Bill::class, BillPolicy::class);
        Gate::policy(Complaint::class, ComplaintPolicy::class);
        Gate::policy(Document::class, DocumentPolicy::class);
        Gate::policy(FacilityBooking::class, FacilityBookingPolicy::class);
        Gate::policy(PaymentProof::class, PaymentProofPolicy::class);
        Gate::policy(VisitorRequest::class, VisitorRequestPolicy::class);
        Gate::policy(WorkOrder::class, WorkOrderPolicy::class);
    }
}
