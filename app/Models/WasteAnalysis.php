<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'material_name',
        'category',
        'confidence_rate',
        'contamination_rate',
        'estimated_price_per_kg',
        'weight_kg',
        'has_scale_photo',
        'total_estimated_value',
        'recommendation',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'confidence_rate' => 'integer',
            'contamination_rate' => 'integer',
            'estimated_price_per_kg' => 'decimal:2',
            'weight_kg' => 'decimal:2',
            'has_scale_photo' => 'boolean',
            'total_estimated_value' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
