<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WasteAnalysis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AiScannerController extends Controller
{
    public function index(): View
    {
        if (! Auth::check()) {
            $defaultUser = User::where('email', 'zidan@olara.id')->first() ?: User::first();
            if ($defaultUser) {
                Auth::login($defaultUser);
            }
        }

        $user = Auth::user();
        $recentAnalyses = $user ? $user->wasteAnalyses()->take(5)->get() : collect();

        $presetMaterials = self::getWasteMaterialsList();
        $wasteMaterials = $presetMaterials;

        return view('scanner.index', compact('user', 'recentAnalyses', 'presetMaterials', 'wasteMaterials'));
    }

    public static function getWasteMaterialsList(): array
    {
        return [
            ['id' => 1, 'name' => 'Kardus', 'category' => 'Kertas', 'price_per_kg' => 1000, 'contamination' => 5, 'confidence' => 96, 'recommendation' => 'Kering dan padat. Pastikan bebas noda minyak dan dilipat rapi.', 'image' => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?w=600&auto=format&fit=crop&q=80'],
            ['id' => 2, 'name' => 'Duplek', 'category' => 'Kertas', 'price_per_kg' => 400, 'contamination' => 6, 'confidence' => 94, 'recommendation' => 'Kertas karton duplek kering tanpa laminasi tebal.', 'image' => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?w=600&auto=format&fit=crop&q=80'],
            ['id' => 3, 'name' => 'Arsip (HVS) Putih', 'category' => 'Kertas', 'price_per_kg' => 1000, 'contamination' => 4, 'confidence' => 97, 'recommendation' => 'Kertas dokumen putih bersih tanpa staples atau klip.', 'image' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?w=600&auto=format&fit=crop&q=80'],
            ['id' => 4, 'name' => 'Buku Cetak Bersih Tanpa Kulit', 'category' => 'Kertas', 'price_per_kg' => 1000, 'contamination' => 5, 'confidence' => 95, 'recommendation' => 'Buku cetak kertas dengan cover tebal atau jilid lakban dilepas.', 'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600&auto=format&fit=crop&q=80'],
            ['id' => 5, 'name' => 'Besi', 'category' => 'Logam', 'price_per_kg' => 2500, 'contamination' => 4, 'confidence' => 98, 'recommendation' => 'Besi tua dan potongan logam padat, simpan di tempat kering.', 'image' => 'https://images.unsplash.com/photo-1535813547-99c456a41d4a?w=600&auto=format&fit=crop&q=80'],
            ['id' => 6, 'name' => 'Rongsok', 'category' => 'Logam', 'price_per_kg' => 1500, 'contamination' => 8, 'confidence' => 92, 'recommendation' => 'Campuran rongsokan logam ringan dan barang bekas.', 'image' => 'https://images.unsplash.com/photo-1535813547-99c456a41d4a?w=600&auto=format&fit=crop&q=80'],
            ['id' => 7, 'name' => 'Kaleng', 'category' => 'Logam', 'price_per_kg' => 1800, 'contamination' => 3, 'confidence' => 98, 'recommendation' => 'Kaleng biskuit atau susu kaleng, pipihkan agar hemat tempat.', 'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80'],
            ['id' => 8, 'name' => 'Alma Panci', 'category' => 'Logam', 'price_per_kg' => 8000, 'contamination' => 3, 'confidence' => 96, 'recommendation' => 'Aluminium tebal dari panci dan peralatan dapur bersih.', 'image' => 'https://images.unsplash.com/photo-1584990347449-3891ec26d7f9?w=600&auto=format&fit=crop&q=80'],
            ['id' => 9, 'name' => 'Alma Kaleng', 'category' => 'Logam', 'price_per_kg' => 7000, 'contamination' => 2, 'confidence' => 99, 'recommendation' => 'Kaleng minuman bersoda aluminium (UBC), bersihkan dan injak pipih.', 'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80'],
            ['id' => 10, 'name' => 'Seng', 'category' => 'Logam', 'price_per_kg' => 500, 'contamination' => 10, 'confidence' => 90, 'recommendation' => 'Lembaran seng atap atau talang sisa bangunan.', 'image' => 'https://images.unsplash.com/photo-1535813547-99c456a41d4a?w=600&auto=format&fit=crop&q=80'],
            ['id' => 11, 'name' => 'Pet Bening Mix +Blues', 'category' => 'Plastik', 'price_per_kg' => 2500, 'contamination' => 4, 'confidence' => 95, 'recommendation' => 'Botol PET bening dan semburat kebiruan, lepas label.', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 12, 'name' => 'Pet Biru Mix', 'category' => 'Plastik', 'price_per_kg' => 500, 'contamination' => 5, 'confidence' => 94, 'recommendation' => 'Botol plastik PET warna biru campur.', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 13, 'name' => 'Pet Bening', 'category' => 'Plastik', 'price_per_kg' => 3000, 'contamination' => 3, 'confidence' => 98, 'recommendation' => 'Botol plastik PET bening murni tanpa tutup dan label.', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 14, 'name' => 'Pet Warna', 'category' => 'Plastik', 'price_per_kg' => 500, 'contamination' => 5, 'confidence' => 93, 'recommendation' => 'Botol plastik PET warna hijau, merah, atau warna lain.', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 15, 'name' => 'Lasegar', 'category' => 'Plastik', 'price_per_kg' => 800, 'contamination' => 5, 'confidence' => 92, 'recommendation' => 'Botol plastik kemasan larutan penyegar.', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 16, 'name' => 'Ember Warna', 'category' => 'Plastik', 'price_per_kg' => 1200, 'contamination' => 6, 'confidence' => 94, 'recommendation' => 'Pecahan baskom atau ember plastik PP/HDPE berwarna.', 'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80'],
            ['id' => 17, 'name' => 'Aqua Gelas PP A', 'category' => 'Plastik', 'price_per_kg' => 3400, 'contamination' => 3, 'confidence' => 98, 'recommendation' => 'Gelas plastik PP bersih bening tanpa ring penutup dan sisa air.', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 18, 'name' => 'Aqua Gelas PP B', 'category' => 'Plastik', 'price_per_kg' => 2400, 'contamination' => 5, 'confidence' => 95, 'recommendation' => 'Gelas plastik PP bersih dengan sedikit sablon atau ring.', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 19, 'name' => 'P. Campur', 'category' => 'Plastik', 'price_per_kg' => 500, 'contamination' => 10, 'confidence' => 90, 'recommendation' => 'Plastik aneka jenis campur yang belum terpilah.', 'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80'],
            ['id' => 20, 'name' => 'Mountea PP SABLON/ Ale - Ale', 'category' => 'Plastik', 'price_per_kg' => 1500, 'contamination' => 5, 'confidence' => 94, 'recommendation' => 'Gelas plastik bersablon tebal (Mountea, Ale-Ale, dsb).', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 22, 'name' => 'Jelantah', 'category' => 'Minyak & Khusus', 'price_per_kg' => 3400, 'contamination' => 5, 'confidence' => 96, 'recommendation' => 'Minyak goreng bekas pakai, disaring dari remah makanan dan simpan dalam jerigen.', 'image' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=600&auto=format&fit=crop&q=80'],
            ['id' => 23, 'name' => 'Multilayer', 'category' => 'Plastik', 'price_per_kg' => 150, 'contamination' => 12, 'confidence' => 90, 'recommendation' => 'Bungkus sachet kopi, detergen, atau snack multilayer.', 'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80'],
            ['id' => 24, 'name' => 'Beling / Kaca', 'category' => 'Kaca', 'price_per_kg' => 150, 'contamination' => 5, 'confidence' => 97, 'recommendation' => 'Botol kecap, bir, atau pecahan kaca beling.', 'image' => 'https://images.unsplash.com/photo-1605600659908-0ef719419d41?w=600&auto=format&fit=crop&q=80'],
            ['id' => 25, 'name' => 'Karung PP', 'category' => 'Plastik', 'price_per_kg' => 400, 'contamination' => 7, 'confidence' => 93, 'recommendation' => 'Karung anyaman plastik PP bekas beras atau pakan ternak.', 'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80'],
            ['id' => 26, 'name' => 'Karung Pet', 'category' => 'Plastik', 'price_per_kg' => 150, 'contamination' => 8, 'confidence' => 91, 'recommendation' => 'Karung berbahan serat plastik PET.', 'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80'],
            ['id' => 27, 'name' => 'Botol Infus LDPE', 'category' => 'Plastik', 'price_per_kg' => 3000, 'contamination' => 3, 'confidence' => 96, 'recommendation' => 'Botol infus plastik LDPE steril non-infeksius.', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 28, 'name' => 'Jerigen Hemodialisa HDPE', 'category' => 'Plastik', 'price_per_kg' => 2000, 'contamination' => 4, 'confidence' => 96, 'recommendation' => 'Jerigen plastik HDPE tebal bekas cairan HD yang telah dibilas.', 'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80'],
            ['id' => 29, 'name' => 'Spruit PP', 'category' => 'Plastik', 'price_per_kg' => 800, 'contamination' => 5, 'confidence' => 93, 'recommendation' => 'Bahan spruit plastik PP bening/putih.', 'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80'],
            ['id' => 30, 'name' => 'Botol Kemasan B3', 'category' => 'Plastik', 'price_per_kg' => 1000, 'contamination' => 8, 'confidence' => 91, 'recommendation' => 'Botol kemasan plastik bekas bahan kimia, bilas aman.', 'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80'],
            ['id' => 31, 'name' => 'Tutup Galon', 'category' => 'Plastik', 'price_per_kg' => 2000, 'contamination' => 4, 'confidence' => 97, 'recommendation' => 'Tutup galon plastik air mineral warna biru atau warna lain.', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 32, 'name' => 'Tabung Gas', 'category' => 'Logam & Khusus', 'price_per_kg' => 150000, 'contamination' => 2, 'confidence' => 99, 'recommendation' => 'Tabung gas LPG besi kosong dan utuh.', 'image' => 'https://images.unsplash.com/photo-1535813547-99c456a41d4a?w=600&auto=format&fit=crop&q=80'],
            ['id' => 33, 'name' => 'Tali Plastik', 'category' => 'Plastik', 'price_per_kg' => 150, 'contamination' => 9, 'confidence' => 92, 'recommendation' => 'Tali rafia atau strapping band plastik pengepakan.', 'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80'],
            ['id' => 34, 'name' => 'Sepatu Bekas', 'category' => 'Lainnya', 'price_per_kg' => 220, 'contamination' => 10, 'confidence' => 90, 'recommendation' => 'Sepatu atau alas kaki bekas campur layak sortir.', 'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&auto=format&fit=crop&q=80'],
            ['id' => 35, 'name' => 'Sepatu Safety Bekas', 'category' => 'Lainnya', 'price_per_kg' => 2500, 'contamination' => 6, 'confidence' => 94, 'recommendation' => 'Sepatu safety boots proyek dengan plat besi/baja.', 'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&auto=format&fit=crop&q=80'],
            ['id' => 36, 'name' => 'Plastik Mika', 'category' => 'Plastik', 'price_per_kg' => 150, 'contamination' => 7, 'confidence' => 91, 'recommendation' => 'Plastik mika transparan wadah kue atau makanan.', 'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80'],
            ['id' => 37, 'name' => 'PVC & Banner Spanduk', 'category' => 'Plastik', 'price_per_kg' => 100, 'contamination' => 10, 'confidence' => 90, 'recommendation' => 'Pipa PVC dan spanduk banner flexi bekas.', 'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80'],
            ['id' => 38, 'name' => 'Sedotan / Pipet Bening', 'category' => 'Plastik', 'price_per_kg' => 500, 'contamination' => 6, 'confidence' => 93, 'recommendation' => 'Sedotan plastik bening kering bebas sisa minuman.', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 39, 'name' => 'Mainan', 'category' => 'Plastik', 'price_per_kg' => 750, 'contamination' => 8, 'confidence' => 91, 'recommendation' => 'Mainan plastik keras anak-anak yang rusak.', 'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80'],
            ['id' => 40, 'name' => 'PCB TV', 'category' => 'E-Waste', 'price_per_kg' => 3000, 'contamination' => 5, 'confidence' => 95, 'recommendation' => 'Papan sirkuit elektronik PCB televisi atau perangkat tabung.', 'image' => 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?w=600&auto=format&fit=crop&q=80'],
            ['id' => 41, 'name' => 'Kabel', 'category' => 'E-Waste', 'price_per_kg' => 500, 'contamination' => 8, 'confidence' => 92, 'recommendation' => 'Kabel listrik tembaga bekas instalasi atau elektronik.', 'image' => 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?w=600&auto=format&fit=crop&q=80'],
            ['id' => 42, 'name' => 'Koran', 'category' => 'Kertas', 'price_per_kg' => 1800, 'contamination' => 4, 'confidence' => 97, 'recommendation' => 'Koran bekas rapi tidak basah atau sobek kotor.', 'image' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?w=600&auto=format&fit=crop&q=80'],
            ['id' => 43, 'name' => 'Kornis Roll', 'category' => 'Kertas', 'price_per_kg' => 200, 'contamination' => 8, 'confidence' => 91, 'recommendation' => 'Gulungan roll kertas tebal atau kornis.', 'image' => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?w=600&auto=format&fit=crop&q=80'],
            ['id' => 45, 'name' => 'Baju Bekas', 'category' => 'Tekstil', 'price_per_kg' => 120, 'contamination' => 10, 'confidence' => 90, 'recommendation' => 'Kain pakaian tekstil bekas kering untuk serat lap.', 'image' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=600&auto=format&fit=crop&q=80'],
            ['id' => 46, 'name' => 'Ban Bekas', 'category' => 'Karet', 'price_per_kg' => 130, 'contamination' => 8, 'confidence' => 92, 'recommendation' => 'Ban luar karet motor atau mobil bekas.', 'image' => 'https://images.unsplash.com/photo-1535813547-99c456a41d4a?w=600&auto=format&fit=crop&q=80'],
            ['id' => 47, 'name' => 'Sedotan pipet Warna', 'category' => 'Plastik', 'price_per_kg' => 400, 'contamination' => 6, 'confidence' => 92, 'recommendation' => 'Sedotan plastik warna warni kering.', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 48, 'name' => 'Pet mix kotor', 'category' => 'Plastik', 'price_per_kg' => 500, 'contamination' => 15, 'confidence' => 90, 'recommendation' => 'Botol plastik PET belum dicuci atau tercampur kotoran.', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 49, 'name' => 'Gelas PP Mix Kotor', 'category' => 'Plastik', 'price_per_kg' => 500, 'contamination' => 15, 'confidence' => 90, 'recommendation' => 'Gelas plastik PP campur sisa air/tanah.', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 50, 'name' => 'Botol Soya', 'category' => 'Plastik', 'price_per_kg' => 1500, 'contamination' => 5, 'confidence' => 94, 'recommendation' => 'Botol plastik susu kedelai atau soya bersih.', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 51, 'name' => 'Botol Milku', 'category' => 'Plastik', 'price_per_kg' => 500, 'contamination' => 5, 'confidence' => 93, 'recommendation' => 'Botol plastik minuman susu Milku bersih.', 'image' => 'https://images.unsplash.com/photo-1595278069441-2cf29f8005a4?w=600&auto=format&fit=crop&q=80'],
            ['id' => 52, 'name' => 'AKRILIK', 'category' => 'Plastik', 'price_per_kg' => 500, 'contamination' => 6, 'confidence' => 93, 'recommendation' => 'Lembaran atau potongan akrilik mika keras.', 'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80'],
            ['id' => 53, 'name' => 'micca', 'category' => 'Plastik', 'price_per_kg' => 100, 'contamination' => 8, 'confidence' => 90, 'recommendation' => 'Plastik mika tipis sisa kemasan.', 'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80'],
            ['id' => 54, 'name' => 'Kerasan / Hitaman', 'category' => 'Plastik', 'price_per_kg' => 500, 'contamination' => 8, 'confidence' => 92, 'recommendation' => 'Plastik keras warna hitam pekat.', 'image' => 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?w=600&auto=format&fit=crop&q=80'],
            ['id' => 55, 'name' => 'Jasa pengangkutan, pemilahan, pengolahan', 'category' => 'Jasa', 'price_per_kg' => 4500, 'contamination' => 0, 'confidence' => 99, 'recommendation' => 'Tarif jasa pengangkutan, pemilahan, dan pengolahan per kg.', 'image' => 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=600&auto=format&fit=crop&q=80'],
            ['id' => 56, 'name' => 'Sampah Campur', 'category' => 'Campuran', 'price_per_kg' => 2500, 'contamination' => 25, 'confidence' => 95, 'recommendation' => 'Tarif jasa pemilahan sampah anorganik campur per kg.', 'image' => 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=600&auto=format&fit=crop&q=80'],
        ];
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

        if (! Auth::check()) {
            $defaultUser = User::where('email', 'zidan@olara.id')->first() ?: User::first();
            if ($defaultUser) {
                Auth::login($defaultUser);
            }
        }

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

        // Award Eco-Points on saving AI waste scan: 10 Eco-Points
        $pointsEarned = 10;

        if ($user) {
            $desc = "Hasil Scan AI Sampah: {$validated['material_name']} ({$weight} kg)";
            if ($hasScale) {
                $desc .= ' [+Bonus Timbangan]';
            }
            $user->addPoints($pointsEarned, 'scan_bonus', $desc);
        }

        $pointNotice = "+{$pointsEarned} Eco-Points ditambahkan ke dompet Anda!";

        if ($request->input('action_type') === 'pickup') {
            return redirect()->route('pickup.index', [
                'category' => $validated['category'],
                'weight' => $weight,
            ])->with('success', "Hasil analisis disimpan ({$pointNotice})! Lanjutkan pemesanan penjemputan sampah Anda.");
        }

        if ($request->input('action_type') === 'dropoff') {
            return redirect()->route('dropoff.index', [
                'category' => $validated['category'],
            ])->with('success', "Hasil analisis disimpan ({$pointNotice})! Temukan bank sampah atau mitra terdekat.");
        }

        $msg = "Hasil pemindaian AI berhasil disimpan! {$pointNotice}";
        if ($hasScale) {
            $msg .= ' (Termasuk bonus verifikasi timbangan digital).';
        }

        return redirect()->route('scanner.index')->with('success', $msg);
    }
}
