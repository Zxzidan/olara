<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'grade',
        'category',
        'price_per_kg',
        'min_order_kg',
        'stock_kg',
        'description',
        'image_url',
        'co2_savings_per_kg',
    ];

    protected function casts(): array
    {
        return [
            'price_per_kg' => 'decimal:2',
            'min_order_kg' => 'integer',
            'stock_kg' => 'integer',
            'co2_savings_per_kg' => 'decimal:2',
        ];
    }
}
