<?php

namespace App\Http\Controllers;

use App\Models\WasteAnalysis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AiScannerController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $recentAnalyses = $user ? $user->wasteAnalyses()->take(5)->get() : collect();

        // Sample preset materials for demo testing
        $presetMaterials = [
            [
                'name' => 'Plastik Botol Minuman PET',
                'category' => 'Plastik',
                'confidence' => 98,
                'contamination' => 4,
                'price_per_kg' => 4800,
                'recommendation' => 'Kategori Sangat Bersih. Lepaskan tutup botol dan label plastik tipis untuk menaikkan harga jual.',
                'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Kardus Cokelat Gelombang (OCC)',
                'category' => 'Kertas',
                'confidence' => 96,
                'contamination' => 6,
                'price_per_kg' => 2100,
                'recommendation' => 'Kering dan padat. Pastikan bebas dari noda minyak atau sisa makanan sebelum ditumpuk rapat.',
                'image' => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Kaleng Minuman Bersoda (Aluminium UBC)',
                'category' => 'Logam',
                'confidence' => 99,
                'contamination' => 2,
                'price_per_kg' => 18500,
                'recommendation' => 'Kadar kemurnian tinggi. Injak atau pipihkan kaleng agar tidak memakan ruang penyimpanan.',
                'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Jerigen Sabun & Botol Shampo HDPE',
                'category' => 'Plastik',
                'confidence' => 95,
                'contamination' => 7,
                'price_per_kg' => 9800,
                'recommendation' => 'Bilas bersih sisa sabun dengan sedikit air dan biarkan mengering.',
                'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Botol Kaca Kecap & Sirup Bening',
                'category' => 'Kaca',
                'confidence' => 97,
                'contamination' => 5,
                'price_per_kg' => 1200,
                'recommendation' => 'Pisahkan tutup logam dan botol kaca, jangan sampai retak atau pecah berceceran.',
                'image' => 'https://images.unsplash.com/photo-1605600659908-0ef719419d41?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Kabel Tembaga & Perangkat Elektronik Rusak',
                'category' => 'E-Waste',
                'confidence' => 93,
                'contamination' => 8,
                'price_per_kg' => 25000,
                'recommendation' => 'E-waste bernilai tinggi. Simpan di tempat sejuk terlindung dari paparan hujan.',
                'image' => 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?w=600&auto=format&fit=crop&q=80',
            ],
        ];

        return view('scanner.index', compact('user', 'recentAnalyses', 'presetMaterials'));
    }

    public function saveAnalysis(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'material_name' => ['required', 'string'],
            'category' => ['required', 'string'],
            'confidence_rate' => ['required', 'integer'],
            'contamination_rate' => ['required', 'integer'],
            'estimated_price_per_kg' => ['required', 'numeric'],
            'weight_kg' => ['required', 'numeric', 'min:0.1'],
            'has_scale_photo' => ['nullable'],
            'recommendation' => ['nullable', 'string'],
            'action_type' => ['nullable', 'string'], // 'pickup', 'dropoff', or 'save'
        ]);

        $hasScale = $request->boolean('has_scale_photo');
        $weight = (float) $validated['weight_kg'];
        $price = (float) $validated['estimated_price_per_kg'];
        $totalVal = round($weight * $price, 2);

        $user = Auth::user();

        $analysis = WasteAnalysis::create([
            'user_id' => $user?->id,
            'material_name' => $validated['material_name'],
            'category' => $validated['category'],
            'confidence_rate' => (int) $validated['confidence_rate'],
            'contamination_rate' => (int) $validated['contamination_rate'],
            'estimated_price_per_kg' => $price,
            'weight_kg' => $weight,
            'has_scale_photo' => $hasScale,
            'total_estimated_value' => $totalVal,
            'recommendation' => $validated['recommendation'] ?? 'Pastikan material kering dan bersih.',
            'status' => 'analyzed',
        ]);

        // Award +10 points bonus if digital scale verification photo was submitted
        if ($hasScale && $user) {
            $user->addPoints(10, 'scan_bonus', 'Bonus Verifikasi Timbangan Digital: '.$validated['material_name']);
        }

        if ($request->input('action_type') === 'pickup') {
            return redirect()->route('pickup.index', [
                'category' => $validated['category'],
                'weight' => $weight,
            ])->with('success', 'Hasil analisis disimpan! Lanjutkan pemesanan penjemputan sampah Anda.');
        }

        if ($request->input('action_type') === 'dropoff') {
            return redirect()->route('dropoff.index', [
                'category' => $validated['category'],
            ])->with('success', 'Hasil analisis disimpan! Temukan bank sampah atau mitra terdekat.');
        }

        $msg = 'Hasil pemindaian AI berhasil disimpan ke riwayat Anda.';
        if ($hasScale) {
            $msg .= ' Anda mendapatkan bonus +10 Eco-Point karena melampirkan foto timbangan!';
        }

        return redirect()->route('scanner.index')->with('success', $msg);
    }
}
