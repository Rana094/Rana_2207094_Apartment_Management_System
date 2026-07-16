@extends('layouts.dashboard')

@section('title', 'Bill Details #B-9872 — Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <a href="{{ url('/resident/bills') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 0.85rem; height: 0.85rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
            Back to Bills List
        </a>
        <h1 class="db-title" style="font-size: 1.5rem;">Invoice #B-9872</h1>
    </div>
    
    <div style="display: flex; gap: 0.75rem;">
        <button type="button" class="btn btn-outline btn-sm" onclick="window.print();">
            <!-- Printer SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1rem; height: 1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.82l-.24 4.54m0 0l-3-3m3 3h10.5m-1.5-12.75h-7.5c-.621 0-1.125.504-1.125 1.125v10.125a1.125 1.125 0 001.125 1.125h9.75a1.125 1.125 0 001.125-1.125V8.25m-3-5.25v5.25" />
            </svg>
            Print Invoice
        </button>
        <a href="{{ url('/resident/bills/9872/upload') }}" class="btn btn-primary btn-sm">Pay Bill</a>
    </div>
</div>

<!-- Invoice Card Layout -->
<div class="card-static" style="background-color: #ffffff; padding: 3rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);">
    <!-- Invoice Brand Info -->
    <div style="display: flex; justify-content: space-between; border-bottom: 2px solid var(--border-color); padding-bottom: 2rem; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="logo" style="margin-bottom: 0.5rem;">
                Nestora<span>.</span>
            </h2>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0;">Smart Apartment Management Made Simple</p>
        </div>
        <div style="text-align: right;">
            <div style="font-weight: 800; font-size: 1.25rem; color: var(--text-primary);">INVOICE</div>
            <div style="font-size: 0.9rem; color: var(--text-secondary); margin-top: 0.25rem;">Invoice No: #B-9872</div>
            <div style="margin-top: 0.5rem;"><span class="badge badge-unpaid">unpaid</span></div>
        </div>
    </div>

    <!-- Invoice Billing Details metadata -->
    <div class="grid grid-2" style="margin-bottom: 2.5rem; gap: 2rem;">
        <div>
            <h4 style="text-transform: uppercase; font-size: 0.75rem; color: var(--text-muted); letter-spacing: 0.05em; margin-bottom: 0.5rem;">Billed To:</h4>
            <div style="font-weight: 700; font-size: 1rem; color: var(--text-primary);">ullas</div>
            <div style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem;">
                Flat 3B, Building A (Tower 1)<br>
                Nestora Residential Complex<br>
                Dhanmondi, Dhaka, Bangladesh
            </div>
        </div>
        <div style="text-align: right; justify-self: end;">
            <h4 style="text-transform: uppercase; font-size: 0.75rem; color: var(--text-muted); letter-spacing: 0.05em; margin-bottom: 0.5rem;">Invoice Dates:</h4>
            <table style="font-size: 0.875rem; color: var(--text-secondary); margin-left: auto;">
                <tr>
                    <td style="padding: 0.25rem 1rem 0.25rem 0; text-align: right;">Billing Date:</td>
                    <td style="font-weight: 600; text-align: left;">July 01, 2026</td>
                </tr>
                <tr>
                    <td style="padding: 0.25rem 1rem 0.25rem 0; text-align: right; color: var(--color-rejected);">Due Date:</td>
                    <td style="font-weight: 700; text-align: left; color: var(--color-rejected);">July 10, 2026</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Itemized List Table -->
    <table class="db-table" style="margin-bottom: 2rem;">
        <thead>
            <tr>
                <th>Item Description</th>
                <th style="text-align: right;">Unit Price</th>
                <th style="text-align: right;">Qty</th>
                <th style="text-align: right;">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 600;">Monthly Flat Service Charge<div style="font-weight: normal; font-size: 0.8rem; color: var(--text-muted);">Includes garbage pickup, cleaning of common areas</div></td>
                <td style="text-align: right;"><span class="money"><x-taka />3,000</span></td>
                <td style="text-align: right;">1</td>
                <td style="font-weight: 600; text-align: right;"><span class="money"><x-taka />3,000</span></td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Security Levy Charge<div style="font-weight: normal; font-size: 0.8rem; color: var(--text-muted);">24/7 gate security guards payroll share</div></td>
                <td style="text-align: right;"><span class="money"><x-taka />500</span></td>
                <td style="text-align: right;">1</td>
                <td style="font-weight: 600; text-align: right;"><span class="money"><x-taka />500</span></td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Standby Generator Surcharge<div style="font-weight: normal; font-size: 0.8rem; color: var(--text-muted);">Common utility area power fuel consumption share</div></td>
                <td style="text-align: right;"><span class="money"><x-taka />1,000</span></td>
                <td style="text-align: right;">1</td>
                <td style="font-weight: 600; text-align: right;"><span class="money"><x-taka />1,000</span></td>
            </tr>
        </tbody>
    </table>

    <!-- Totals Area -->
    <div style="display: flex; justify-content: flex-end;">
        <div style="width: 100%; max-width: 340px;">
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; font-size: 0.9rem;">
                <span class="text-muted">Subtotal:</span>
                <span class="money" style="font-weight: 600;"><x-taka />4,500</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; font-size: 0.9rem; border-bottom: 1px solid var(--border-color);">
                <span class="text-muted">Taxes & Levies (0%):</span>
                <span class="money" style="font-weight: 600;"><x-taka />0</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; font-size: 1.15rem; border-bottom: 2px double var(--border-color); margin-bottom: 1.5rem;">
                <span style="font-weight: 700; color: var(--text-primary);">Total Due:</span>
                <span class="money" style="font-weight: 800; color: var(--primary-color);"><x-taka />4,500</span>
            </div>
        </div>
    </div>

    <!-- Notes & Bank Info -->
    <div style="border-top: 1px solid var(--border-color); padding-top: 1.5rem; font-size: 0.8rem; color: var(--text-secondary); line-height: 1.6;">
        <h5 style="margin-bottom: 0.25rem; font-size: 0.85rem; color: var(--text-primary);">Payment Instructions:</h5>
        <p style="margin-bottom: 0.5rem;">Please transfer the total invoice amount to the society bank account details listed below. After completing the payment, upload a screenshot of your transfer proof to get approved.</p>
        <div style="background-color: var(--bg-main); padding: 1rem; border-radius: var(--radius-md); font-family: monospace;">
            <strong>Bank Name:</strong> Dhaka Bank PLC<br>
            <strong>Account Title:</strong> Nestora Flat Owners Association<br>
            <strong>Account Number:</strong> 215-100-8809223<br>
            <strong>Routing Number:</strong> 060260269
        </div>
    </div>
</div>
@endsection
