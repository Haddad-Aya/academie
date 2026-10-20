<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    public const PENDING = 'pending';
    public const PAID = 'paid';
    public const FAILED = 'failed';
    public const METHOD_CARD = 'card';
    public const METHOD_ONSITE = 'onsite';

    // Le statut ne se modifie que via PaymentService (webhook ou validation admin).
    protected $fillable = ['registration_id', 'provider', 'amount', 'currency', 'billing_month', 'payment_method'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:3',
            'paid_at' => 'datetime',
            'payload' => 'array',
        ];
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}
