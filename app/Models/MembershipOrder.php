<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembershipOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'tier',
        'plan_type',
        'amount',
        'payment_status',
        'payment_method',
        'payment_type',
        'snap_token',
        'transaction_id',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool
    {
        return in_array($this->payment_status, ['paid', 'settlement', 'capture']);
    }

    public function isPending(): bool
    {
        return in_array($this->payment_status, ['pending', 'unpaid']);
    }
}
