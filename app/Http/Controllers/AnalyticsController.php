<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // Carbon & Waste metrics
        $totalWeightKg = 148.5;
        $totalCo2AvoidedKg = 212.8;
        $treesSaved = 3.2;
        $energySavedKwh = 384.5;
        $waterSavedLiters = 1420;

        // Weekly waste trend data (Monday to Sunday)
        $weeklyTrend = [
            ['day' => 'Sen', 'weight' => 14.2, 'co2' => 20.4],
            ['day' => 'Sel', 'weight' => 18.5, 'co2' => 26.6],
            ['day' => 'Rab', 'weight' => 22.1, 'co2' => 31.8],
            ['day' => 'Kam', 'weight' => 16.8, 'co2' => 24.1],
            ['day' => 'Jum', 'weight' => 25.4, 'co2' => 36.5],
            ['day' => 'Sab', 'weight' => 32.0, 'co2' => 46.0],
            ['day' => 'Min', 'weight' => 19.5, 'co2' => 28.0],
        ];

        // Waste composition breakdown
        $composition = [
            ['name' => 'Plastik (PET & HDPE)', 'percent' => 52, 'weight' => 77.2, 'color' => '#168A5B'],
            ['name' => 'Kardus & Kertas', 'percent' => 28, 'weight' => 41.5, 'color' => '#10B981'],
            ['name' => 'Logam & Aluminium', 'percent' => 12, 'weight' => 17.8, 'color' => '#F59E0B'],
            ['name' => 'Kaca & E-Waste', 'percent' => 8, 'weight' => 12.0, 'color' => '#0284C7'],
        ];

        // Average contamination rate
        $avgContamination = 5.2; // percent (Grade: Sangat Bersih)
        $purityScore = 94.8; // percent

        // Data-driven personalized recommendations
        $recommendations = [
            [
                'title' => 'Tingkatkan Nilai Kardus Hingga 20%',
                'desc' => 'Lipat rata kardus box dan pastikan disimpan di tempat kering tanpa terkena minyak sisa makanan untuk mendapatkan harga penerimaan terbaik dari pengepul.',
                'tag' => 'Kertas & Kardus',
            ],
            [
                'title' => 'Lepas Ring Tutup Botol PET',
                'desc' => 'Sebagian besar pabrik daur ulang memotong harga jika ring leher botol masih terpasang. Memisahkannya menaikkan poin setoran Anda ke grade tertinggi.',
                'tag' => 'Plastik',
            ],
            [
                'title' => 'Jadwal Penjemputan Kolektif',
                'desc' => 'Menggabungkan setoran sampah dengan tetangga satu blok perumahan dapat menghemat biaya jarak dan mengurangi emisi operasional armada logistik.',
                'tag' => 'Komunitas',
            ],
        ];

        return view('analytics.index', compact(
            'user',
            'totalWeightKg',
            'totalCo2AvoidedKg',
            'treesSaved',
            'energySavedKwh',
            'waterSavedLiters',
            'weeklyTrend',
            'composition',
            'avgContamination',
            'purityScore',
            'recommendations'
        ));
    }
}
