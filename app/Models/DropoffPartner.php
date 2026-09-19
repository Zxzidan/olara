<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropoffPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'latitude',
        'longitude',
        'phone',
        'operating_hours',
        'is_open',
        'is_premium_partner',
        'bonus_points',
        'accepted_materials',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'is_open' => 'boolean',
            'is_premium_partner' => 'boolean',
            'bonus_points' => 'integer',
            'accepted_materials' => 'array',
        ];
    }
}
