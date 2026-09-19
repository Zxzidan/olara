# OLARA — Product Requirements Document (PRD)

> **Tagline: “Olah Kembali dan Jaga Bumi.”**
>
> Versi yang disempurnakan dengan product scope yang lebih terstruktur dan Design System/UI specification untuk memastikan implementasi visual konsisten, modern, dan menghindari AI-slop.

## Gambaran Umum Produk

**OLARA** adalah aplikasi ekosistem pengelolaan sampah berbasis teknologi yang mengintegrasikan **AI Waste Scanner, layanan penjemputan sampah, direktori drop-off, Eco-Point, statistik dampak lingkungan, marketplace bahan baku daur ulang, dan membership** dalam satu platform.

Tujuan utama OLARA adalah membantu masyarakat mengelola sampah dengan lebih mudah, terukur, dan bernilai ekonomis.

Pengguna dapat:

- Memfoto dan mengidentifikasi sampah menggunakan AI.
- Mengetahui jenis material, tingkat kontaminasi, estimasi harga, dan potensi nilai sampah.
- Mengumpulkan Eco-Point dari aktivitas pengelolaan sampah.
- Memilih antara layanan pickup atau mengantar sampah secara mandiri ke mitra.
- Menukarkan Eco-Point menjadi berbagai reward.
- Melihat statistik kontribusi lingkungan secara personal.
- Membeli bahan baku hasil daur ulang melalui marketplace.
- Menggunakan layanan sesuai paket membership Lite atau Premium.

### Arah Produk

OLARA harus terasa seperti **produk digital modern yang benar-benar dapat digunakan**, bukan sekadar aplikasi edukasi.

Pengalaman pengguna harus:

- sederhana,
- cepat,
- informatif,
- transparan,
- terpercaya,
- memiliki unsur gamifikasi tetapi tidak terasa seperti game berlebihan.

Gamifikasi digunakan untuk mendorong kebiasaan positif melalui **Eco-Point, Recycler Level, progress, achievement, dan reward**.

---

# Masalah yang Ingin Diselesaikan

Pengelolaan sampah di tingkat masyarakat menghadapi beberapa masalah:

1. Pengguna sering tidak mengetahui jenis sampah yang mereka miliki.
2. Pengguna sulit mengetahui apakah sampah tersebut masih memiliki nilai ekonomis.
3. Pemilahan sampah membutuhkan pengetahuan mengenai material dan tingkat kebersihannya.
4. Akses terhadap pengepul atau bank sampah tidak selalu mudah.
5. Proses penjemputan sampah belum terintegrasi secara praktis.
6. Pengguna tidak memiliki sistem yang jelas untuk melihat kontribusi lingkungan mereka.
7. Insentif ekonomi dari aktivitas daur ulang masih belum terintegrasi dengan pengalaman pengguna.
8. Bahan baku daur ulang berkualitas sulit ditemukan oleh pihak yang membutuhkan material tersebut.
9. Informasi mengenai harga, berat, kualitas, dan transaksi sampah dapat menjadi tidak transparan.
10. Aktivitas memilah sampah sering kali tidak memiliki mekanisme yang cukup menarik untuk membangun kebiasaan jangka panjang.

OLARA mencoba menyelesaikan masalah tersebut dengan menghubungkan **pengguna → AI → pengelolaan sampah → mitra → Eco-Point → reward → dampak lingkungan → marketplace** dalam satu ekosistem.

---

# Konsep Inti Aplikasi

## 1. Eco-Point sebagai Sistem Insentif

Setiap aktivitas yang memenuhi syarat dapat menghasilkan Eco-Point.

Contoh aktivitas:

- Registrasi akun.
- Menyelesaikan onboarding edukasi.
- Memindai sampah.
- Melakukan setoran sampah.
- Melakukan pickup.
- Drop-off ke mitra.
- Membeli bahan baku daur ulang.
- Mengikuti program lingkungan tertentu.

Eco-Point dapat digunakan untuk:

- E-Wallet.
- Voucher.
- Donasi lingkungan.
- Reward lainnya.

---

# Sistem atau Aturan Tertentu

## Registrasi

Jika pengguna berhasil membuat akun baru:

**Trigger:** Registrasi berhasil
**Output:** Pengguna mendapatkan **50 Eco-Point welcome bonus**.

Bonus hanya diberikan satu kali untuk setiap akun.

---

## AI Waste Scanner

Jika pengguna mengambil atau mengunggah foto sampah:

**Trigger:** Foto sampah dikirim ke AI
**Output:** Sistem memberikan hasil analisis:

- Jenis material.
- Nama/kategori sampah.
- Tingkat confidence.
- Estimasi tingkat kontaminasi.
- Estimasi kebersihan.
- Estimasi harga material/kg.
- Rekomendasi pemilahan.

Contoh:

> Plastik PET
> Confidence: 94%
> Kontaminasi: 12%
> Estimasi nilai: RpX/kg

Hasil AI merupakan **estimasi**, bukan keputusan final transaksi.

---

## Verifikasi Berat

Pengguna dapat memasukkan berat secara manual atau mengunggah foto timbangan digital.

Jika pengguna mengunggah bukti timbangan:

**Trigger:** Foto timbangan berhasil diverifikasi
**Output:** Sistem dapat memberikan bonus Eco-Point sesuai aturan yang ditentukan.

Berat final untuk transaksi dapat tetap membutuhkan validasi mitra.

---

## Setoran Sampah

Jika pengguna melakukan setoran sampah:

**Trigger:** Sampah diterima dan berat/material diverifikasi
**Output:**

- Transaksi tercatat.
- Eco-Point ditambahkan.
- Total sampah pengguna diperbarui.
- Statistik dampak lingkungan diperbarui.
- Recycler Level dapat mengalami peningkatan.

---

## Recycler Level

Level pengguna ditentukan berdasarkan akumulasi aktivitas atau Eco-Point.

Contoh:

**Recycler Level**

- Level 1 — Starter
- Level 2 — Recycler
- Level 3 — Eco Builder
- Level 4 — Eco Champion
- Level 5 — Eco Guardian

Nama level dapat disesuaikan pada tahap desain.

Setiap level memiliki progress menuju level berikutnya.

---

# User Flow Dasar

## A. Onboarding

1. Pengguna membuka OLARA.
2. Sistem menampilkan onboarding edukatif.
3. Pengguna mempelajari:
   - cara memilah sampah,
   - nilai ekonomis sampah,
   - cara menggunakan OLARA.
4. Pengguna melakukan registrasi atau login.
5. Jika registrasi baru berhasil → +50 Eco-Point.
6. Pengguna masuk ke Home.

---

# B. Home / Dashboard

Home menjadi pusat aktivitas pengguna.

Informasi utama:

- Nama pengguna.
- Recycler Level.
- Total Eco-Point.
- Progress menuju level berikutnya.
- Shortcut fitur.
- Aktivitas terbaru.
- Banner membership.

Shortcut utama:

**Foto Sampah Sekarang**
→ AI Waste Scanner

**Jemput Sampah**
→ Pickup

**Antar Sendiri**
→ Drop-off Map

**Tukar Poin**
→ Rewards

**Beli Bahan Baku**
→ Marketplace

---

# C. AI Waste Scanner

1. Pengguna membuka Kamera AI.
2. Pengguna mengambil foto sampah.
3. AI melakukan analisis.
4. Sistem menampilkan hasil:
   - material,
   - confidence,
   - contamination,
   - estimasi harga.
5. Pengguna dapat memasukkan berat.
6. Pengguna dapat mengunggah foto timbangan.
7. Sistem menampilkan estimasi nilai.
8. Pengguna memilih:
   - Pickup.
   - Drop-off.
   - Simpan hasil analisis.

---

# D. Pickup

1. Pengguna memilih kategori sampah:
   - Plastik.
   - Kertas.
   - Organik.
   - Logam.
   - Kaca.
   - E-Waste.
2. Pengguna menentukan perkiraan volume/berat.
3. Pengguna memilih jadwal.
4. Sistem menghitung biaya.
5. Sistem menampilkan breakdown:
   - biaya dasar,
   - surcharge volume,
   - biaya operasional,
   - biaya jarak.
6. Pengguna melakukan konfirmasi.
7. Mitra kurir menerima permintaan.
8. Pengguna dapat melihat tracking.
9. Sistem menampilkan:
   - posisi kurir,
   - ETA,
   - nama kurir,
   - nomor kendaraan,
   - status pickup.
10. Setelah sampah diterima → transaksi diselesaikan dan Eco-Point diperbarui.

---

# E. Drop-off

1. Pengguna membuka peta.
2. Sistem menampilkan pengepul/bank sampah terdekat.
3. Pengguna dapat melihat:
   - alamat,
   - jarak,
   - jam operasional,
   - kategori mitra.
4. Pengguna memilih lokasi.
5. Sistem menyediakan navigasi.
6. Pengguna melakukan setoran langsung.
7. Mitra melakukan validasi.
8. Eco-Point diperbarui.

Mitra tertentu dapat memiliki label:

**Premium Partner**

Premium Partner dapat memberikan bonus Eco-Point tambahan sesuai aturan program.

---

# F. Rewards

Pengguna membuka dompet Eco-Point.

Kategori:

- Semua.
- E-Wallet.
- Voucher.
- Donasi.

Contoh reward:

### E-Wallet

- GoPay.
- OVO.

### Voucher

- Tokopedia.
- Solaria.
- Timezone.
- Optik Seis.
- Cinema XXI.
- Medical Check Up.

### Donasi

- Donasi Reboisasi.
- Program lingkungan lainnya.

Setiap reward memiliki:

- harga Eco-Point,
- deskripsi,
- syarat,
- status ketersediaan.

---

# G. Statistik & Eco Impact

Dashboard statistik menampilkan:

### Total Sampah

Contoh:

**Total Sampah Terpilah**

> 127.5 kg

Breakdown:

- Plastik: 65 kg
- Kertas: 32 kg
- Logam: 18 kg
- Kaca: 12.5 kg

### Carbon Impact

Menampilkan estimasi:

**CO₂e yang berhasil dihindari**

> XXX kg CO₂e

### Weekly Report

Grafik:

- total sampah per minggu,
- rata-rata kontaminasi,
- kategori sampah dominan.

### Personalized Recommendation

Sistem memberikan saran berdasarkan aktivitas pengguna.

Contoh:

> "Kontaminasi plastikmu minggu ini meningkat. Coba bilas kemasan sebelum disetorkan."

---

# H. Marketplace

Marketplace menyediakan bahan baku daur ulang.

Contoh produk:

- PET Flakes.
- Bubur Kertas Putih.
- Ingot Aluminium.
- Kain Perca.
- HDPE Regrind.
- Kardus Press.

Setiap produk menampilkan:

- Nama material.
- Foto.
- Grade.
- Harga.
- Minimum order.
- Spesifikasi.
- Ketersediaan.

---

## Shopping Flow

1. Pengguna memilih material.
2. Pengguna menentukan jumlah.
3. Produk masuk keranjang.
4. Pengguna memilih alamat.
5. Sistem menghitung:
   - subtotal,
   - ongkir,
   - biaya layanan,
   - PPN 10%.
6. Pengguna memilih metode pembayaran.
7. Pembayaran diproses.
8. Sistem membuat order.
9. Pengguna menerima bukti transaksi.
10. Sistem menampilkan:
    - estimasi pengiriman,
    - Eco-Point yang diperoleh,
    - estimasi dampak emisi yang dihemat.

---

# I. Membership

## Lite — Gratis

Fitur:

- Laporan dasar bulanan.
- 1x pickup per bulan.
- Limit penampungan 1.000 Eco-Point.
- 5 kredit AI Scanner per bulan.

---

## Premium — Berbayar

Fitur:

- Laporan jejak karbon mendalam.
- Prioritas antrean kurir.
- 5x pickup gratis per bulan.
- Limit Eco-Point lebih tinggi.
- AI Scanner tanpa batas.
- Kontribusi otomatis ke program reboisasi.

Membership harus dirancang agar pengguna tetap mendapatkan pengalaman inti OLARA tanpa harus membayar.

---

# Arsitektur Fitur Utama

Ekosistem OLARA dapat digambarkan sebagai:

**USER**

↓

**AI WASTE SCANNER**

↓

**WASTE IDENTIFICATION**

↓

**ESTIMATED VALUE**

↓

**PICKUP / DROP-OFF**

↓

**VERIFICATION**

↓

**ECO-POINT**

↓

**REWARDS / DONATION**

↓

**ECO IMPACT**

Sementara marketplace berjalan sebagai ekosistem tambahan:

**RECYCLED MATERIAL**

→ **MARKETPLACE**

→ **BUYER**

→ **RECYCLED PRODUCTION**

---

# Eksplorasi Fitur Tambahan

Fitur tambahan harus diprioritaskan berdasarkan manfaat dan kompleksitas implementasi.

## 1. Achievement

Contoh:

- First Scan.
- First Deposit.
- 10 KG Recycled.
- 100 KG Recycled.
- 10 Successful Pickups.

Achievement dapat memberikan badge dan/atau Eco-Point.

---

## 2. Daily / Weekly Challenge

Contoh:

> Pilah minimal 2 kg sampah minggu ini.

Reward:

> +100 Eco-Point

Tujuan fitur ini adalah membangun kebiasaan, bukan menjadikan aplikasi seperti game sepenuhnya.

---

## 3. Smart Waste Tips

AI dapat memberikan edukasi berdasarkan hasil scanner.

Contoh:

> "Botol PET ini sebaiknya dikosongkan, dibilas, dan dipisahkan dari tutup sebelum disetor."

---

## 4. Personal Waste Goal

Pengguna dapat menentukan target:

> Target bulan ini: 20 kg sampah terpilah.

Dashboard kemudian menampilkan progress.

---

## 5. Family / Household Account

Satu akun dapat membuat household sehingga beberapa anggota keluarga dapat berkontribusi pada satu target lingkungan.

Fitur ini dapat ditunda setelah MVP.

---

## 6. Community Impact

Menampilkan statistik agregat:

> OLARA Community
> 12.450 kg sampah telah dipilah bulan ini.

Data harus ditampilkan secara agregat dan tidak mengekspos informasi pribadi pengguna.

---

# Potensi Monetisasi

OLARA dapat menggunakan beberapa sumber revenue:

### 1. Premium Membership

Pendapatan recurring dari pengguna Premium.

### 2. Pickup Service Fee

Biaya layanan dari transaksi penjemputan.

### 3. Marketplace Commission

Komisi dari transaksi bahan baku daur ulang.

### 4. B2B Partnership

Kerja sama dengan:

- perusahaan,
- manufaktur,
- bank sampah,
- pengepul,
- brand,
- organisasi lingkungan.

### 5. Premium Partner

Mitra dapat memperoleh fitur atau exposure tambahan berdasarkan paket kerja sama.

---

# Scope MVP

## WAJIB ADA

### Authentication

- Register.
- Login.
- Google Login jika tersedia.
- Onboarding dasar.
- Welcome bonus 50 Eco-Point.

### Home

- Profil.
- Eco-Point.
- Recycler Level.
- Progress.
- Shortcut fitur.
- Aktivitas terbaru.

### AI Scanner

- Upload/take photo.
- Identifikasi material.
- Confidence.
- Estimasi kontaminasi.
- Estimasi harga.
- Input berat.

### Pickup

- Pilihan kategori.
- Pilihan jadwal.
- Kalkulasi biaya.
- Order confirmation.
- Status order.

### Drop-off

- Daftar/peta mitra.
- Detail mitra.
- Navigasi eksternal.

### Rewards

- Wallet Eco-Point.
- Katalog reward.
- Penukaran poin.

### Statistics

- Total berat sampah.
- Breakdown kategori.
- Eco-Impact dasar.

### Membership

- Lite.
- Premium.
- Perbandingan benefit.

---

# FITUR YANG DAPAT DITUNDA

Untuk menghindari MVP terlalu kompleks:

- Real-time courier tracking.
- Marketplace lengkap.
- Payment gateway multi-provider.
- Facebook/Apple login.
- Advanced carbon calculation.
- Household account.
- Community leaderboard.
- Advanced AI recommendation.
- Automatic reforestation contribution.
- OCR timbangan otomatis.
- Advanced partner analytics.
- Sistem logistics management kompleks.

Fitur-fitur tersebut dapat dikembangkan setelah core product tervalidasi.

---

# Asumsi dan Batasan Teknis

## AI

AI Waste Scanner pada MVP dapat menggunakan model computer vision/API eksternal.

Hasil AI harus diperlakukan sebagai **estimasi**, bukan verifikasi transaksi final.

Untuk tahap awal, jumlah kategori sampah dapat dibatasi agar model lebih konsisten.

---

## Location

Pickup dan Drop-off membutuhkan layanan lokasi/map API.

Untuk MVP, navigasi dapat diarahkan ke aplikasi peta eksternal daripada membangun sistem navigasi sendiri.

---

## Payment

MVP dapat menggunakan payment gateway pihak ketiga.

Sistem tidak perlu menyimpan data kartu pengguna secara langsung.

---

## Courier Tracking

Real-time GPS tracking merupakan fitur kompleks.

MVP dapat menggunakan status sederhana:

**Requested → Confirmed → Driver Assigned → On The Way → Arrived → Completed**

Real-time tracking dapat dikembangkan kemudian.

---

## Marketplace

Marketplace MVP dapat menggunakan katalog produk sederhana.

Sistem inventory, supplier management, shipping integration, dan settlement kompleks dapat ditunda.

---

# Prinsip UX/UI

OLARA harus memiliki visual yang:

- modern,
- bersih,
- mudah dipahami,
- environmentally oriented,
- tidak terlalu ramai,
- tidak terlihat seperti dashboard enterprise yang kompleks.

Gamifikasi harus terasa sebagai **lapisan motivasi**, bukan mengubah aplikasi menjadi game.

Prioritaskan:

**Action → Feedback → Progress → Reward**

Contoh:

User memindai sampah
↓
AI memberikan hasil
↓
User mengetahui nilai sampah
↓
User melakukan setoran
↓
Eco-Point bertambah
↓
Progress level meningkat
↓
User mendapatkan reward.

---

# Prioritas Produk

Prioritas utama OLARA adalah:

1. **Waste Scanner**
2. **Waste Deposit / Pickup**
3. **Eco-Point**
4. **Rewards**
5. **Waste Impact Statistics**
6. **Drop-off Directory**
7. **Membership**
8. **Marketplace**

Fitur yang tidak berkontribusi langsung terhadap validasi alur utama sebaiknya tidak menjadi prioritas MVP.

---


---

# Design System & UI/UX Direction

## Tujuan Visual

OLARA harus terlihat seperti **produk digital consumer yang matang**, bukan template SaaS, dashboard enterprise, atau hasil generator UI otomatis.

Arah visual:
- modern
- clean
- human
- eco-oriented tanpa menjadi "serba daun"
- premium tetapi tetap approachable
- fokus pada aksi utama
- memiliki hierarchy yang kuat
- nyaman dipakai satu tangan di mobile
- konsisten antara mobile dan desktop/web

### Prinsip Anti-AI-Slop

Hindari:
- gradient neon yang terlalu mencolok
- glassmorphism berlebihan
- kartu dengan shadow besar di setiap elemen
- terlalu banyak ikon dekoratif
- ilustrasi 3D generik bertema daun/bumi
- blob/background abstrak di setiap section
- penggunaan emoji sebagai ikon utama
- typography terlalu futuristik
- semua komponen dibuat berbentuk pill
- dashboard dengan puluhan angka yang sama-sama ditonjolkan
- warna hijau pada seluruh permukaan aplikasi
- CTA berlebihan pada satu layar
- copywriting yang terlalu marketing atau terdengar generik

Gunakan visual hierarchy yang tenang: **1 primary action, 1 secondary action, informasi pendukung seperlunya.**

---

## Visual Identity

### Warna Utama

Gunakan hijau sebagai identitas utama, tetapi jangan menjadikan seluruh UI berwarna hijau.

**Primary Green**
- `#168A5B` — aksi utama, tombol utama, active state
- `#0F6B47` — pressed/hover state
- `#0B4F38` — dark green untuk heading tertentu atau emphasis

**Supporting Green**
- `#DDF4E8` — surface hijau lembut
- `#EEF9F2` — background section ringan
- `#BFE7D0` — border/soft highlight

**Neutral**
- `#F7F8F6` — background utama
- `#FFFFFF` — card/surface
- `#1B211E` — primary text
- `#66716B` — secondary text
- `#DDE3DF` — border/divider

**Semantic**
- Success: hijau yang konsisten dengan brand
- Warning: amber/orange yang muted
- Error: red yang tidak terlalu saturated
- Info: blue yang muted

Warna hijau harus menjadi **accent**, bukan wallpaper.

### Gradient

Gradient boleh digunakan secara terbatas untuk:
- hero/welcome card
- Eco-Point highlight
- membership Premium
- achievement/impact highlight

Gunakan gradient yang subtle, misalnya:
`#168A5B → #2FAF76`

Hindari gradient multiwarna, neon, rainbow, atau gradient yang membuat teks sulit dibaca.

---

## Typography

Gunakan font sans-serif yang modern dan mudah dibaca.

Rekomendasi:
- Inter
- Plus Jakarta Sans
- Manrope

Hierarchy:
- Display: 28–36 px, semibold/bold
- H1: 24–28 px, semibold
- H2: 20–22 px, semibold
- H3: 16–18 px, semibold
- Body: 14–16 px, regular
- Caption: 12–13 px

Jangan menggunakan terlalu banyak weight dalam satu layar.

---

## Layout System

Gunakan spacing berbasis kelipatan 4/8 px.

Contoh:
- 4 px — micro spacing
- 8 px — icon/text gap
- 12 px — compact component
- 16 px — default spacing
- 24 px — section spacing
- 32 px — major section
- 48 px — page-level separation

Radius:
- 8 px — input/small control
- 12 px — card
- 16 px — prominent card
- 20 px — hero/feature surface

Gunakan shadow secara hemat. Sebagian besar card sebaiknya dibedakan melalui **surface, border, dan spacing**, bukan shadow.

---

# UI/UX Screen Specification

## 1. Onboarding

Tujuan: menjelaskan value OLARA dalam waktu singkat.

Layout:
- visual sederhana di bagian atas
- headline maksimal 2 baris
- short explanation
- progress indicator
- CTA "Mulai"
- secondary action "Lewati"

Onboarding tidak boleh terasa seperti presentasi panjang.

---

## 2. Home

Home adalah pusat aktivitas.

### Struktur

**Top bar**
- avatar
- greeting
- notification

**Eco-Point summary**
- total Eco-Point
- Recycler Level
- progress level berikutnya

**Primary action**
- tombol/card "Scan Sampah"

**Quick actions**
- Jemput Sampah
- Antar ke Mitra
- Tukar Poin
- Statistik

**Recent activity**
- aktivitas terbaru
- perubahan Eco-Point
- status transaksi

**Membership banner**
- hanya satu banner yang relevan
- tidak boleh mengambil terlalu banyak ruang

### Layout principle

Jangan membuat Home seperti dashboard admin.

Gunakan:
`Summary → Primary Action → Quick Actions → Activity`

---

## 3. AI Waste Scanner

Scanner adalah salah satu fitur paling penting sehingga UI harus fokus.

### Camera Screen

Elemen:
- camera preview besar
- scanning frame sederhana
- tombol shutter besar
- upload from gallery
- flash
- hint singkat

Hindari overlay dekoratif yang tidak membantu proses scanning.

### Processing State

Tampilkan:
- preview foto
- progress/loading
- teks "Menganalisis material..."
- jangan menampilkan loading animation berlebihan

### Result Screen

Gunakan hierarchy:

**Material**
> Plastik PET

**Confidence**
> 94%

**Condition**
> Kontaminasi rendah

**Estimated Value**
> Estimasi Rp X/kg

**Recommendation**
> Kosongkan dan bilas sebelum disetor.

**Primary CTA**
> Setor Sekarang

**Secondary CTA**
> Simpan Hasil

Tambahkan disclaimer kecil:
"Hasil AI merupakan estimasi dan dapat berbeda dari hasil verifikasi mitra."

---

## 4. Pickup

Gunakan flow bertahap agar pengguna tidak merasa mengisi form panjang.

Step:
1. Jenis sampah
2. Estimasi berat/volume
3. Alamat
4. Jadwal
5. Ringkasan biaya
6. Konfirmasi

Gunakan sticky bottom CTA:
**Konfirmasi Pickup**

Breakdown biaya harus transparan:
- biaya dasar
- biaya operasional
- surcharge volume
- biaya jarak
- total

---

## 5. Drop-off Map

Map menjadi visual utama.

Bottom sheet menampilkan:
- nama mitra
- jarak
- kategori
- jam operasional
- status buka/tutup
- Premium Partner jika relevan

CTA:
**Lihat Detail**
**Navigasi**

Jangan memenuhi map dengan terlalu banyak marker.

---

## 6. Rewards / Eco-Point Wallet

Bagian atas:
- saldo Eco-Point
- tombol riwayat

Kategori:
- Semua
- E-Wallet
- Voucher
- Donasi

Reward card:
- logo/thumbnail
- nama reward
- harga poin
- availability
- CTA "Tukar"

Gunakan empty state yang informatif jika reward tidak tersedia.

---

## 7. Statistics & Eco Impact

Jangan membuat halaman statistik seperti BI dashboard.

Gunakan hierarchy:
1. Total sampah
2. Dampak CO₂e
3. Breakdown material
4. Weekly trend
5. Recommendation

Contoh hero metric:
**127,5 kg**
Sampah terpilah

Lalu:
**XX kg CO₂e**
Estimasi emisi yang dihindari

Chart harus sederhana dan mudah dibaca. Hindari chart 3D.

---

## 8. Marketplace

Marketplace harus terasa seperti marketplace material B2B ringan, bukan toko e-commerce fashion.

Product card:
- foto material
- nama
- grade
- harga/kg
- minimum order
- availability

Filter:
- material
- grade
- price
- minimum order

Detail produk harus menonjolkan spesifikasi material dan minimum order.

---

## 9. Membership

Gunakan perbandingan Lite vs Premium yang mudah dipindai.

Lite:
- surface neutral

Premium:
- green accent yang lebih kuat
- subtle gradient hanya pada header/card utama

Jangan membuat Premium terlihat seperti iklan agresif.

CTA:
**Upgrade ke Premium**

Tetap tampilkan benefit inti yang tersedia untuk pengguna Lite.

---

## 10. Profile

Struktur:
- avatar + nama
- Recycler Level
- Eco-Point
- membership
- personal waste goal
- transaction history
- settings
- help/support

Profile tidak perlu memiliki banyak card dekoratif.

---

# Navigation

Untuk mobile, gunakan bottom navigation maksimal 5 item:

1. Home
2. Scan
3. Aktivitas
4. Rewards
5. Profile

Scanner boleh dibuat sebagai primary/floating action hanya jika tetap menjaga hierarchy dan accessibility.

Untuk desktop/tablet, gunakan sidebar ringan atau top navigation.

---

# Component Rules

## Button

Primary:
- green filled
- high contrast
- 44 px minimum touch target

Secondary:
- neutral/outline

Destructive:
- hanya untuk aksi yang benar-benar destruktif

Jangan membuat semua tombol pill. Gunakan radius konsisten 10–12 px.

## Card

Card digunakan untuk mengelompokkan informasi, bukan membungkus setiap elemen.

Gunakan card hanya ketika:
- ada boundary informasi
- ada interaksi
- ada summary
- ada status

## Icon

Gunakan satu icon family yang konsisten.

Icon harus:
- sederhana
- 20–24 px
- tidak terlalu dekoratif
- memiliki tooltip/label bila konteks tidak jelas

Jangan menggunakan emoji sebagai pengganti icon UI utama.

---

# Responsive Behavior

## Mobile

Prioritas:
- one-handed use
- bottom navigation
- sticky CTA
- vertical content
- camera-first scanner
- map bottom sheet

## Tablet

- gunakan grid 2 kolom jika membantu
- jangan sekadar memperbesar mobile UI

## Desktop

- content max-width sekitar 1200–1280 px
- sidebar/navigation dapat digunakan
- dashboard tetap sederhana
- scanner dan map dapat menggunakan split layout

---

# Accessibility

Minimum requirement:
- contrast teks memenuhi standar WCAG yang relevan
- touch target minimal 44 × 44 px
- jangan menyampaikan status hanya melalui warna
- semua icon action memiliki accessible label
- form memiliki label yang jelas
- error message menjelaskan cara memperbaiki masalah
- loading state tidak membuat pengguna mengira aplikasi macet

---

# Motion & Interaction

Motion digunakan untuk memberikan feedback, bukan dekorasi.

Gunakan:
- 150–250 ms untuk micro interaction
- 250–350 ms untuk transition halaman
- subtle success animation ketika Eco-Point bertambah
- progress animation ketika level berubah

Hindari:
- bounce berlebihan
- parallax dekoratif
- page transition panjang
- animasi yang menghambat task completion

---

# Empty, Loading, Error & Success States

Setiap fitur utama wajib memiliki empat kondisi:

### Loading
Tampilkan skeleton atau progress yang relevan.

### Empty
Jelaskan:
- apa yang belum tersedia
- kenapa kosong
- tindakan yang dapat dilakukan

### Error
Gunakan bahasa manusia:
"Foto belum cukup jelas. Coba ambil foto dengan pencahayaan lebih baik."

Hindari error teknis mentah seperti stack trace.

### Success
Berikan feedback langsung:
"Pickup berhasil dibuat."
"50 Eco-Point berhasil ditambahkan."

---

# Core Product Loop

Loop utama harus selalu terasa jelas:

**Scan → Understand → Act → Verify → Earn → Impact → Repeat**

Contoh:

1. User scan sampah.
2. AI menjelaskan material dan estimasi nilai.
3. User memilih pickup/drop-off.
4. Mitra memverifikasi material dan berat.
5. Eco-Point diberikan.
6. Impact diperbarui.
7. User melihat progress dan reward.
8. User terdorong melakukan aktivitas berikutnya.

Setiap layar utama harus mendukung minimal satu tahap dari loop ini.

---

# Product Quality Gate

Sebelum fitur dianggap selesai, cek:

- Apakah tujuan layar jelas dalam 3 detik?
- Apakah primary CTA mudah ditemukan?
- Apakah informasi penting lebih menonjol daripada dekorasi?
- Apakah pengguna tahu apa yang terjadi setelah menekan CTA?
- Apakah loading/error/empty state tersedia?
- Apakah layout tetap masuk akal di mobile?
- Apakah warna hijau digunakan sebagai identitas, bukan memenuhi seluruh layar?
- Apakah UI terlihat seperti produk nyata dan bukan template/generator?
- Apakah komponen konsisten?
- Apakah fitur benar-benar mendukung core loop?

---

# Updated Product Priority

Prioritas MVP:

**P0 — Core**
1. Authentication
2. Home
3. AI Waste Scanner
4. Waste Deposit/Pickup
5. Eco-Point
6. Rewards
7. Basic Impact Statistics

**P1 — Supporting**
8. Drop-off Directory
9. Membership
10. Activity History

**P2 — Expansion**
11. Marketplace
12. Achievement
13. Challenges
14. Smart Waste Tips
15. Personal Waste Goal

**P3 — Future**
16. Household Account
17. Community Impact
18. Real-time Courier Tracking
19. Advanced Carbon Calculation
20. Automated Reforestation
21. Advanced Partner Analytics

Urutan ini menjaga agar OLARA tidak menjadi aplikasi yang memiliki banyak fitur tetapi core loop belum tervalidasi.

---

# Final Design Direction

OLARA harus memiliki kesan:

> **"Eco utility yang modern dan terpercaya — Olah Kembali dan Jaga Bumi."**

Bukan:
- aplikasi kampanye lingkungan,
- game,
- dashboard enterprise,
- marketplace generik,
- atau template AI.

Visual utama menggunakan **neutral background + white surfaces + green accent**, dengan gradient hanya pada area yang memang membutuhkan emphasis.

Jika sebuah elemen tidak membantu pengguna:
**memahami → memilih → melakukan → mendapatkan feedback**, elemen tersebut sebaiknya dihilangkan.

---


# Output yang Diharapkan

PRD final harus menghasilkan dokumentasi yang:

- terstruktur,
- mudah dibaca,
- dapat dipahami developer,
- dapat diterjemahkan menjadi UI/UX oleh designer,
- memiliki user flow yang jelas,
- memiliki definisi MVP yang realistis,
- tidak over-engineered,
- memisahkan MVP dan future development,
- menjelaskan hubungan antarfitur,
- menjelaskan aturan Eco-Point,
- menjelaskan alur AI,
- menjelaskan alur transaksi,
- menjelaskan membership,
- dan menjelaskan potensi monetisasi.

Fokus utama bukan membuat sebanyak mungkin fitur, tetapi memastikan **core loop OLARA dapat berjalan dengan jelas dan dapat divalidasi.**