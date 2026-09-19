<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketplaceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'items',
        'shipping_address',
        'subtotal',
        'ppn_amount',
        'shipping_fee',
        'grand_total',
        'payment_method',
        'payment_status',
        'snap_token',
        'payment_type',
        'transaction_id',
        'payment_response',
        'co2_saved_kg',
        'points_earned',
        'shipping_status',
        'courier_name',
        'tracking_number',
        'estimated_delivery_date',
        'delivered_at',
        'completed_at',
        'recipient_name',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'subtotal' => 'decimal:2',
            'ppn_amount' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'co2_saved_kg' => 'decimal:2',
            'points_earned' => 'integer',
            'payment_response' => 'array',
            'estimated_delivery_date' => 'date',
            'delivered_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function isPaid(): bool
    {
        return in_array($this->payment_status, ['paid', 'settlement', 'capture']);
    }

    public function isPending(): bool
    {
        return in_array($this->payment_status, ['pending', 'unpaid']);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
