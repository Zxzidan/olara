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

        // Sample marketplace products for B2B showcase
        $sampleProducts = MarketplaceProduct::take(4)->get();

        // Key ecological & community statistics
        $stats = [
            'total_waste_kg' => 24850,
            'co2_avoided_kg' => 42120,
            'trees_saved' => 1850,
            'active_partners' => DropoffPartner::count() ?: 12,
            'total_rewards' => Reward::count() ?: 9,
            'community_members' => 12400,
        ];

        return view('landing.index', compact('user', 'sampleProducts', 'stats'));
    }
}
