<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\RewardRedemption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RewardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $selectedCategory = $request->input('category', 'all');

        $query = Reward::query();
        if ($selectedCategory !== 'all') {
            $query->where('category', $selectedCategory);
        }
        $rewards = $query->get();

        $myRedemptions = $user ? $user->rewardRedemptions()->with('reward')->take(10)->get() : collect();
        $pointTransactions = $user ? $user->pointTransactions()->take(10)->get() : collect();

        // Total trees planted from community donations
        $totalTreesDonated = 12450;

        return view('rewards.index', compact('user', 'rewards', 'selectedCategory', 'myRedemptions', 'pointTransactions', 'totalTreesDonated'));
    }

    public function redeem(int $id, Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login')->with('error', 'Silakan masuk untuk menukarkan poin.');
        }

        $reward = Reward::findOrFail($id);

        if ($user->eco_points < $reward->points_required) {
            return back()->with('error', 'Saldo Eco-Point Anda tidak mencukupi untuk reward ini. Saldo Anda: '.number_format($user->eco_points).' Pts, dibutuhkan: '.number_format($reward->points_required).' Pts.');
        }

        if ($reward->stock <= 0) {
            return back()->with('error', 'Maaf, stok reward ini sedang habis. Silakan pilih reward lainnya.');
        }

        // Deduct points
        $user->deductPoints(
            $reward->points_required,
            'reward_redeem',
            'Penukaran Hadiah: '.$reward->title
        );

        $reward->decrement('stock');

        // Generate voucher code
        $prefix = match ($reward->provider) {
            'GoPay' => 'GOPAY',
            'OVO' => 'OVO',
            'Tokopedia' => 'TKPD',
            'Cinema XXI' => 'XXI',
            'Solaria' => 'SLR',
            default => 'OLARA',
        };
        $code = $prefix.'-'.strtoupper(Str::random(6));

        $redemption = RewardRedemption::create([
            'user_id' => $user->id,
            'reward_id' => $reward->id,
            'points_spent' => $reward->points_required,
            'redemption_code' => $code,
            'status' => 'active',
        ]);

        $msg = 'Selamat! '.$reward->title.' berhasil ditukarkan. Kode Anda: '.$code;
        if ($reward->category === 'donasi') {
            $msg = 'Terima kasih atas kontribusi Anda! 1 bibit pohon mangrove telah ditanam atas nama Anda.';
        }

        return redirect()->route('rewards.index')->with('success', $msg);
    }
}
