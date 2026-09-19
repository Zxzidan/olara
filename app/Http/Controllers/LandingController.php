<?php

namespace App\Http\Controllers;

use App\Models\DropoffPartner;
use App\Models\MarketplaceProduct;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LandingController extends Controller
{
    /**
     * Display the Softly-themed Olara Landing Page.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        try {
            $sampleProducts = MarketplaceProduct::take(4)->get();
            $partnerCount = DropoffPartner::count() ?: 12;
            $rewardCount = Reward::count() ?: 9;
        } catch (\Throwable $e) {
            $sampleProducts = collect();
            $partnerCount = 12;
            $rewardCount = 9;
        }

        // Key ecological & community statistics
        $stats = [
            'total_waste_kg' => 24850,
            'co2_avoided_kg' => 42120,
            'trees_saved' => 1850,
            'active_partners' => $partnerCount,
            'total_rewards' => $rewardCount,
            'community_members' => 12400,
        ];

        return view('landing.index', compact('user', 'sampleProducts', 'stats'));
    }
}
