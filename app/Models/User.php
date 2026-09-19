<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'origin',
        'email',
        'password',
        'eco_points',
        'recycler_level',
        'membership_tier',
        'phone',
        'address',
        'avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'eco_points' => 'integer',
        ];
    }

    public function pointTransactions(): HasMany
    {
        return $this->hasMany(EcoPointTransaction::class)->latest();
    }

    public function wasteAnalyses(): HasMany
    {
        return $this->hasMany(WasteAnalysis::class)->latest();
    }

    public function pickupRequests(): HasMany
    {
        return $this->hasMany(PickupRequest::class)->latest();
    }

    public function rewardRedemptions(): HasMany
    {
        return $this->hasMany(RewardRedemption::class)->latest();
    }

    public function marketplaceOrders(): HasMany
    {
        return $this->hasMany(MarketplaceOrder::class)->latest();
    }

    /**
     * Add Eco-Points to user balance and log transaction.
     */
    public function addPoints(int $amount, string $type, string $description): EcoPointTransaction
    {
        $multiplier = ($this->membership_tier === 'premium') ? 1.2 : 1.0;
        $adjustedAmount = (int) round($amount * $multiplier);

        $this->increment('eco_points', $adjustedAmount);
        $this->refresh();
        $this->updateRecyclerLevel();

        return $this->pointTransactions()->create([
            'amount' => $adjustedAmount,
            'type' => $type,
            'description' => $description,
            'balance_after' => $this->eco_points,
        ]);
    }

    /**
     * Deduct Eco-Points from user balance and log transaction.
     */
    public function deductPoints(int $amount, string $type, string $description): ?EcoPointTransaction
    {
        if ($this->eco_points < $amount) {
            return null;
        }

        $this->decrement('eco_points', $amount);

        return $this->pointTransactions()->create([
            'amount' => -$amount,
            'type' => $type,
            'description' => $description,
            'balance_after' => $this->eco_points,
        ]);
    }

    /**
     * Update recycler level based on points accumulation.
     */
    public function updateRecyclerLevel(): void
    {
        $points = $this->eco_points;

        $newLevel = match (true) {
            $points >= 2500 => 'Level 5 — Eco Guardian',
            $points >= 1500 => 'Level 4 — Eco Champion',
            $points >= 800 => 'Level 3 — Eco Builder',
            $points >= 300 => 'Level 2 — Recycler',
            default => 'Level 1 — Starter',
        };

        if ($this->recycler_level !== $newLevel) {
            $this->update(['recycler_level' => $newLevel]);
        }
    }
}
