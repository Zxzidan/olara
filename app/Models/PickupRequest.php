<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickupRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'pickup_code',
        'user_id',
        'categories',
        'scheduled_date',
        'scheduled_slot',
        'address',
        'notes',
        'estimated_weight',
        'base_fee',
        'volume_surcharge',
        'distance_km',
        'distance_fee',
        'service_fee',
        'total_fee',
        'payment_status',
        'payment_method',
        'snap_token',
        'transaction_id',
        'status',
        'courier_name',
        'courier_plate',
        'courier_phone',
    ];

    protected function casts(): array
    {
        return [
            'categories' => 'array',
            'scheduled_date' => 'date',
            'estimated_weight' => 'decimal:2',
            'base_fee' => 'decimal:2',
            'volume_surcharge' => 'decimal:2',
            'distance_km' => 'decimal:2',
            'distance_fee' => 'decimal:2',
            'service_fee' => 'decimal:2',
            'total_fee' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
