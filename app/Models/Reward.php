<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'provider',
        'points_required',
        'value_idr',
        'stock',
        'description',
        'badge_text',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'points_required' => 'integer',
            'value_idr' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(RewardRedemption::class);
    }
}
