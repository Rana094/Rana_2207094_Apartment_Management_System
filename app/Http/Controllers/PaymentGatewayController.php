<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\PaymentTransaction;
use App\Services\NotificationService;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentGatewayController extends Controller
{
    public function show(Request $request, string $token): View
    {
        $transaction = $this->resolveTransaction($request, $token);

        return view('payments.gateway', [
            'transaction' => $transaction,
            'bill' => $transaction->bill,
            'resident' => $transaction->resident,
        ]);
    }

    public function qr(Request $request, string $token): Response
    {
        $transaction = $this->resolveTransaction($request, $token);

        $builder = new Builder(
            writer: new SvgWriter,
            writerOptions: [
                SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => true,
                SvgWriter::WRITER_OPTION_COMPACT => true,
            ],
            validateResult: false,
            data: $this->paymentUrl($transaction),
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 260,
            margin: 12,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $result = $builder->build();

        return response($result->getString(), 200, [
            'Content-Type' => $result->getMimeType(),
            'Cache-Control' => 'private, max-age=300',
        ]);
    }

    public function confirm(Request $request, string $token): RedirectResponse
    {
        $transaction = $this->resolveTransaction($request, $token);

        DB::transaction(function () use ($request, $transaction) {
            $transaction->update([
                'status' => 'paid',
                'paid_at' => now(),
                'expires_at' => now(),
            ]);

            $transaction->bill->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            app(NotificationService::class)->toRole(
                'manager',
                'payment_transaction_paid',
                'Bill paid through Nestora Pay',
                $request->user()->name.' paid bill '.$transaction->bill->bill_number.'.',
                route('manager.bills.index', absolute: false)
            );

            app(NotificationService::class)->toUser(
                $transaction->resident_id,
                'payment_transaction_paid',
                'Payment successful',
                'Your payment for '.$transaction->bill->bill_number.' has been completed.',
                route('resident.bills.show', $transaction->bill, absolute: false)
            );
        });

        return redirect()
            ->route('payments.success', $transaction->payment_token)
            ->with('status', 'Payment completed successfully.');
    }

    public function success(Request $request, string $token): View
    {
        $transaction = $this->resolveTransaction($request, $token);

        return view('payments.success', [
            'transaction' => $transaction,
            'bill' => $transaction->bill,
        ]);
    }

    public function unavailable(Request $request, string $token): View
    {
        $transaction = $this->resolveTransaction($request, $token);

        return view('payments.unavailable', [
            'transaction' => $transaction,
            'bill' => $transaction->bill,
        ]);
    }

    public static function sessionForBill(Bill $bill): ?PaymentTransaction
    {
        if ($bill->status === 'paid') {
            return $bill->latestPaymentTransaction;
        }

        return $bill->activePaymentTransaction()->first()
            ?? PaymentTransaction::create([
                'bill_id' => $bill->id,
                'resident_id' => $bill->resident_id,
                'transaction_number' => PaymentTransaction::generateTransactionNumber(),
                'payment_token' => PaymentTransaction::generateToken(),
                'amount' => $bill->amount,
                'method' => 'nestora_pay',
                'status' => 'pending',
                'expires_at' => now()->addDays(14),
            ]);
    }

    private function resolveTransaction(Request $request, string $token): PaymentTransaction
    {
        $middlewareTransaction = $request->attributes->get('paymentTransaction');

        if ($middlewareTransaction instanceof PaymentTransaction) {
            return $middlewareTransaction;
        }

        $transaction = PaymentTransaction::with(['bill.flat.building', 'resident'])
            ->where('payment_token', $token)
            ->firstOrFail();

        abort_unless($request->user()?->role === 'resident', 403);
        abort_unless($transaction->resident_id === $request->user()->id, 403);

        return $transaction;
    }

    private function paymentUrl(PaymentTransaction $transaction): string
    {
        return rtrim((string) config('app.url'), '/').route('payments.show', $transaction->payment_token, false);
    }
}
