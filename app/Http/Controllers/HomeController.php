<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        // Auto-login default user if not logged in for seamless demo experience
        if (! Auth::check()) {
            $defaultUser = User::where('email', 'zidan@olara.id')->first();
            if ($defaultUser) {
                Auth::login($defaultUser);
            }
        }

        $user = Auth::user();

        // Calculate progress to next tier
        $points = $user ? $user->eco_points : 0;
        $nextTierPoints = 2500;
        $currentTierBase = 1500;
        $tierName = 'Eco Champion';
        $nextTierName = 'Eco Guardian';

        if ($points < 300) {
            $nextTierPoints = 300;
            $currentTierBase = 0;
            $tierName = 'Starter';
            $nextTierName = 'Recycler';
        } elseif ($points < 800) {
            $nextTierPoints = 800;
            $currentTierBase = 300;
            $tierName = 'Recycler';
            $nextTierName = 'Eco Builder';
        } elseif ($points < 1500) {
            $nextTierPoints = 1500;
            $currentTierBase = 800;
            $tierName = 'Eco Builder';
            $nextTierName = 'Eco Champion';
        } elseif ($points >= 2500) {
            $nextTierPoints = 5000;
            $currentTierBase = 2500;
            $tierName = 'Eco Guardian';
            $nextTierName = 'Earth Legend';
        }

        $pointsInCurrentTier = max(0, $points - $currentTierBase);
        $pointsNeededForNext = max(1, $nextTierPoints - $currentTierBase);
        $tierProgressPercent = min(100, (int) round(($pointsInCurrentTier / $pointsNeededForNext) * 100));
        $pointsRemaining = max(0, $nextTierPoints - $points);

        // Recent activities & transactions
        try {
            $recentTransactions = $user ? $user->pointTransactions()->take(6)->get() : collect();
            $activePickup = $user ? $user->pickupRequests()->whereIn('status', ['confirmed', 'driver_assigned', 'on_the_way'])->first() : null;
            $recentAnalysis = $user ? $user->wasteAnalyses()->take(3)->get() : collect();
        } catch (\Throwable $e) {
            $recentTransactions = collect();
            $activePickup = null;
            $recentAnalysis = collect();
        }

        // Dynamic Eco impact metrics for dashboard (0.0 for new users)
        $totalWasteManaged = (float) ($user ? $user->wasteAnalyses()->sum('weight_kg') : 0);
        $totalCo2Avoided = round($totalWasteManaged * 1.43, 1);
        $treesEquivalent = round($totalWasteManaged * 0.021, 1);

        return view('home.index', compact(
            'user',
            'tierName',
            'nextTierName',
            'tierProgressPercent',
            'pointsRemaining',
            'recentTransactions',
            'activePickup',
            'recentAnalysis',
            'totalWasteManaged',
            'totalCo2Avoided',
            'treesEquivalent'
        ));
    }
}
