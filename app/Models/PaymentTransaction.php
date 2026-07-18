<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resident_id');
    }

    public static function generateToken(): string
    {
        do {
            $token = 'pay_'.Str::lower(Str::random(48));
        } while (self::where('payment_token', $token)->exists());

        return $token;
    }

    public static function generateTransactionNumber(): string
    {
        do {
            $number = 'NSTPAY-'.now()->format('Ymd').'-'.Str::upper(Str::random(8));
        } while (self::where('transaction_number', $number)->exists());

        return $number;
    }
}
