<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $kader = User::create([
            'name' => 'Kader Aini',
            'email' => 'kader@sindu.id',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'kader',
        ]);

        $parent = User::create([
            'name' => 'Siti Aminah (Orang Tua)',
            'email' => 'orangtua@sindu.id',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
        ]);

        // 2. Seed Children
        $budi = \App\Models\Child::create([
            'nama' => 'Budi Pratama',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => now()->subMonths(14)->format('Y-m-d'),
            'nama_ibu' => 'Siti Aminah',
            'user_id' => $parent->id, // Linked to the parent account!
        ]);

        $siti = \App\Models\Child::create([
            'nama' => 'Siti Aisyah',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => now()->subMonths(24)->format('Y-m-d'),
            'nama_ibu' => 'Dewi Lestari',
        ]);

        $daffa = \App\Models\Child::create([
            'nama' => 'Daffa Al-Fatih',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => now()->subMonths(12)->format('Y-m-d'),
            'nama_ibu' => 'Rina Wati',
        ]);

        // 2. Seed Growth Records for Budi
        $budi_records = [
            ['umur' => 0, 'berat' => 3.3, 'tinggi' => 49.5, 'status' => 'Gizi Baik', 'keluhan' => null, 'solusi' => null],
            ['umur' => 2, 'berat' => 5.6, 'tinggi' => 58.0, 'status' => 'Gizi Baik', 'keluhan' => null, 'solusi' => null],
            ['umur' => 4, 'berat' => 7.0, 'tinggi' => 63.8, 'status' => 'Gizi Baik', 'keluhan' => null, 'solusi' => null],
            ['umur' => 6, 'berat' => 7.9, 'tinggi' => 67.6, 'status' => 'Gizi Baik', 'keluhan' => null, 'solusi' => null],
            ['umur' => 8, 'berat' => 8.6, 'tinggi' => 70.6, 'status' => 'Gizi Baik', 'keluhan' => null, 'solusi' => null],
            ['umur' => 10, 'berat' => 9.2, 'tinggi' => 73.3, 'status' => 'Gizi Baik', 'keluhan' => 'Demam ringan pasca imunisasi DPT', 'solusi' => 'Berikan paracetamol drop sesuai dosis, kompres air hangat, perbanyak ASI.'],
            ['umur' => 12, 'berat' => 9.6, 'tinggi' => 75.7, 'status' => 'Gizi Baik', 'keluhan' => null, 'solusi' => null],
            ['umur' => 14, 'berat' => 10.1, 'tinggi' => 78.0, 'status' => 'Gizi Baik', 'keluhan' => 'Nafsu makan menurun sedikit karena tumbuh gigi', 'solusi' => 'Berikan makanan dengan tekstur lebih lembut/dingin, tetap berikan stimulan buah segar.'],
        ];
        foreach ($budi_records as $r) {
            $budi->records()->create([
                'tanggal_periksa' => now()->subMonths(14 - $r['umur'])->format('Y-m-d'),
                'umur_bulan' => $r['umur'],
                'berat_badan' => $r['berat'],
                'tinggi_badan' => $r['tinggi'],
                'status_gizi' => $r['status'],
                'keluhan' => $r['keluhan'],
                'solusi' => $r['solusi'],
            ]);
        }

        // 3. Seed Growth Records for Siti
        $siti_records = [
            ['umur' => 0, 'berat' => 3.2, 'tinggi' => 49.0, 'status' => 'Gizi Baik', 'keluhan' => null, 'solusi' => null],
            ['umur' => 3, 'berat' => 5.8, 'tinggi' => 59.8, 'status' => 'Gizi Baik', 'keluhan' => null, 'solusi' => null],
            ['umur' => 6, 'berat' => 7.3, 'tinggi' => 65.7, 'status' => 'Gizi Baik', 'keluhan' => null, 'solusi' => null],
            ['umur' => 9, 'berat' => 8.2, 'tinggi' => 70.1, 'status' => 'Gizi Baik', 'keluhan' => null, 'solusi' => null],
            ['umur' => 12, 'berat' => 8.9, 'tinggi' => 74.0, 'status' => 'Gizi Baik', 'keluhan' => 'Batuk pilek ringan', 'solusi' => 'Jemur pagi hari, berikan ASI eksklusif/cairan hangat, hindari ruangan ber-AC dingin.'],
            ['umur' => 15, 'berat' => 9.6, 'tinggi' => 77.5, 'status' => 'Gizi Baik', 'keluhan' => null, 'solusi' => null],
            ['umur' => 18, 'berat' => 10.2, 'tinggi' => 80.7, 'status' => 'Gizi Baik', 'keluhan' => null, 'solusi' => null],
            ['umur' => 21, 'berat' => 10.9, 'tinggi' => 83.7, 'status' => 'Gizi Baik', 'keluhan' => null, 'solusi' => null],
            ['umur' => 24, 'berat' => 11.5, 'tinggi' => 86.4, 'status' => 'Gizi Baik', 'keluhan' => 'Sembelit/susah buang air besar', 'solusi' => 'Tingkatkan asupan serat dari buah naga/pepaya, perbanyak minum air putih.'],
        ];
        foreach ($siti_records as $r) {
            $siti->records()->create([
                'tanggal_periksa' => now()->subMonths(24 - $r['umur'])->format('Y-m-d'),
                'umur_bulan' => $r['umur'],
                'berat_badan' => $r['berat'],
                'tinggi_badan' => $r['tinggi'],
                'status_gizi' => $r['status'],
                'keluhan' => $r['keluhan'],
                'solusi' => $r['solusi'],
            ]);
        }

        // 4. Seed Growth Records for Daffa (Underweight)
        $daffa_records = [
            ['umur' => 0, 'berat' => 3.1, 'tinggi' => 49.0, 'status' => 'Gizi Baik', 'keluhan' => null, 'solusi' => null],
            ['umur' => 3, 'berat' => 5.0, 'tinggi' => 58.0, 'status' => 'Gizi Baik', 'keluhan' => 'Nafsu makan menurun sejak beralih MPASI', 'solusi' => 'Modifikasi variasi MPASI, berikan makanan porsi kecil tapi sering, konsultasikan metode feeding rule.'],
            ['umur' => 6, 'berat' => 6.2, 'tinggi' => 64.0, 'status' => 'Gizi Kurang', 'keluhan' => 'Nafsu makan sangat rendah, berat badan stagnan', 'solusi' => 'Berikan biskuit PMT Pemulihan 1 keping/hari, perbanyak protein hewani (telur, hati ayam), jadwalkan kunjungan Puskesmas.'],
            ['umur' => 9, 'berat' => 6.8, 'tinggi' => 68.0, 'status' => 'Gizi Kurang', 'keluhan' => 'Sering lemas dan batuk kronis', 'solusi' => 'Pantau suhu tubuh dan lakukan skrining TB paru anak ke Puskesmas, teruskan PMT dan suplemen mikronutrien.'],
            ['umur' => 12, 'berat' => 7.2, 'tinggi' => 71.0, 'status' => 'Gizi Buruk', 'keluhan' => 'Badan sangat kurus, diare berulang, rambut kemerahan', 'solusi' => 'Rujuk SEGERA ke Fasilitas Kesehatan Tingkat Pertama (FKTP/Puskesmas) untuk tata laksana Gizi Buruk rawat jalan.'],
        ];
        foreach ($daffa_records as $r) {
            $daffa->records()->create([
                'tanggal_periksa' => now()->subMonths(12 - $r['umur'])->format('Y-m-d'),
                'umur_bulan' => $r['umur'],
                'berat_badan' => $r['berat'],
                'tinggi_badan' => $r['tinggi'],
                'status_gizi' => $r['status'],
                'keluhan' => $r['keluhan'],
                'solusi' => $r['solusi'],
            ]);
        }
    }
}
