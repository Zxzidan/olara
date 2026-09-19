<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceOrder;
use App\Models\MarketplaceProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = MarketplaceProduct::query();

        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->input('search').'%');
        }

        $products = $query->get();
        $selectedCategory = $request->input('category', 'all');

        $userOrders = $user ? $user->marketplaceOrders()->latest()->take(5)->get() : collect();

        return view('marketplace.index', compact('products', 'user', 'selectedCategory', 'userOrders'));
    }

    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:marketplace_products,id'],
            'quantity_kg' => ['required', 'integer', 'min:1'],
            'shipping_address' => ['required', 'string', 'min:10'],
            'payment_method' => ['required', 'string'],
        ]);

        $product = MarketplaceProduct::findOrFail($validated['product_id']);
        $user = Auth::user();

        if ($validated['quantity_kg'] < $product->min_order_kg) {
            return back()->with('error', "Minimum pemesanan untuk {$product->name} adalah {$product->min_order_kg} kg.");
        }

        $qty = (int) $validated['quantity_kg'];
        $subtotal = round($qty * $product->price_per_kg, 2);
        $ppn = round($subtotal * 0.10, 2); // PPN 10%
        $shippingFee = 45000;
        $grandTotal = $subtotal + $ppn + $shippingFee;

        $co2Saved = round($qty * $product->co2_savings_per_kg, 2);
        $pointsEarned = (int) floor($subtotal / 10000); // 1 point per 10k purchase

        $orderNumber = 'ORD-'.date('Ym').'-'.strtoupper(Str::random(5));

        $order = MarketplaceOrder::create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'grade' => $product->grade,
                    'price' => $product->price_per_kg,
                    'qty_kg' => $qty,
                    'total' => $subtotal,
                ],
            ],
            'shipping_address' => $validated['shipping_address'],
            'subtotal' => $subtotal,
            'ppn_amount' => $ppn,
            'shipping_fee' => $shippingFee,
            'grand_total' => $grandTotal,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'paid',
            'co2_saved_kg' => $co2Saved,
            'points_earned' => $pointsEarned,
        ]);

        // Award cashback points
        if ($pointsEarned > 0) {
            $user->addPoints($pointsEarned, 'marketplace_purchase', "Cashback Poin Pesanan {$orderNumber}");
        }

        return redirect()->route('marketplace.orderDetail', $order->order_number)->with('success', "Pesanan {$orderNumber} berhasil dibuat dan lunas! Anda mendapatkan +{$pointsEarned} Eco-Points.");
    }

    public function orderDetail(string $orderNumber): View
    {
        $order = MarketplaceOrder::where('order_number', $orderNumber)->firstOrFail();
        $user = Auth::user();

        return view('marketplace.order_detail', compact('order', 'user'));
    }
}
