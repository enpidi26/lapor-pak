<?php

namespace Database\Seeders;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Data contoh agar pratinjau langsung terasa hidup.
 * Hanya dijalankan di lingkungan lokal (lihat DatabaseSeeder).
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (Laporan::exists()) {
            $this->command?->warn('Data laporan sudah ada — DemoSeeder dilewati.');

            return;
        }

        $budi = User::firstOrCreate(
            ['email' => config('lapor.demo_warga_email')],
            [
                'name' => 'Budi Santoso',
                'password' => config('lapor.demo_warga_password'),
                'no_whatsapp' => '081234567890',
                'alamat' => 'Jl. Melati No. 12, RT 04/RW 07',
            ]
        );

        $siti = User::firstOrCreate(
            ['email' => 'siti@laporpak.id'],
            [
                'name' => 'Siti Rahayu',
                'password' => config('lapor.demo_warga_password'),
                'no_whatsapp' => '081298765432',
                'alamat' => 'Jl. Kenanga No. 5, RT 04/RW 07',
            ]
        );

        $agus = User::firstOrCreate(
            ['email' => 'agus@laporpak.id'],
            [
                'name' => 'Agus Prasetyo',
                'password' => config('lapor.demo_warga_password'),
                'no_whatsapp' => '081377788899',
                'alamat' => 'Jl. Anggrek No. 21, RT 02/RW 07',
            ]
        );

        $dewi = User::firstOrCreate(
            ['email' => 'dewi@laporpak.id'],
            [
                'name' => 'Dewi Lestari',
                'password' => config('lapor.demo_warga_password'),
                'no_whatsapp' => '081355566677',
                'alamat' => 'Jl. Dahlia No. 8, RT 04/RW 07',
            ]
        );

        $laporan = [
            [
                'user' => $budi,
                'kategori' => 'penerangan',
                'judul' => 'Lampu jalan di Gang Melati mati sejak Sabtu',
                'detail' => 'Lampu penerangan di depan rumah Bu Sri (Gang Melati, RT 04) mati sejak Sabtu malam. Gang jadi gelap dan warga yang pulang malam kesulitan melihat jalan. Mohon dicek apakah lampunya putus atau ada gangguan listrik.',
                'status' => 'proses',
                'dibuat' => 2,
                'tanggal' => 5,
                'tanggapan' => 'Terima kasih laporannya. Petugas sudah mengecek, lampu menunggu penggantian bohlam dari kelurahan. Perkiraan 2–3 hari lagi.',
            ],
            [
                'user' => $budi,
                'kategori' => 'kebersihan',
                'judul' => 'Sampah menumpuk di dekat pos ronda',
                'detail' => 'Sudah tiga hari sampah tidak diangkut dan menumpuk di dekat pos ronda RT 04. Baunya mulai mengganggu, terutama sore hari. Mohon dijadwalkan pengangkutan kembali.',
                'status' => 'pending',
                'dibuat' => 1,
                'tanggal' => 2,
            ],
            [
                'user' => $budi,
                'kategori' => 'jalan',
                'judul' => 'Saluran air tersumbat, jalan tergenang',
                'detail' => 'Saluran air di depan gang RT 04 tersumbat daun dan lumpur. Setiap hujan jalan tergenang setinggi mata kaki dan sulit dilewati motor.',
                'status' => 'selesai',
                'dibuat' => 12,
                'tanggal' => 13,
                'tanggapan' => 'Saluran sudah dikeruk bersama warga pada Minggu pagi. Genangan sudah surut. Terima kasih sudah melapor!',
            ],
            [
                'user' => $budi,
                'kategori' => 'keamanan',
                'judul' => 'Atap pos kamling RT 02 bocor',
                'detail' => 'Atap pos kamling di RT 02 bocor cukup parah. Saat hujan, air masuk ke area jaga sehingga petugas ronda tidak bisa berjaga dengan nyaman.',
                'status' => 'proses',
                'dibuat' => 8,
                'tanggal' => 9,
                'tanggapan' => 'Sudah masuk daftar perbaikan. Material sudah dibeli, pengerjaan dijadwalkan minggu ini.',
            ],
            [
                'user' => $budi,
                'kategori' => 'jalan',
                'judul' => 'Trotoar berlubang dekat sekolah',
                'detail' => 'Trotoar di depan SD negeri berlubang dan cukup dalam. Anak-anak sering tersandung saat pulang sekolah. Mohon segera ditutup agar aman.',
                'status' => 'pending',
                'dibuat' => 3,
                'tanggal' => 4,
            ],
            [
                'user' => $budi,
                'kategori' => 'air',
                'judul' => 'Air PDAM keruh sejak Kamis',
                'detail' => 'Air PDAM di RT 04 keruh dan berbau sejak Kamis pagi. Warga terpaksa membeli air galon untuk memasak dan mandi.',
                'status' => 'selesai',
                'dibuat' => 20,
                'tanggal' => 21,
                'tanggapan' => 'Sudah dikoordinasikan dengan PDAM. Pipa di Jalan Kenanga diperbaiki dan air kembali jernih sejak Jumat.',
            ],
            [
                'user' => $siti,
                'kategori' => 'penerangan',
                'judul' => 'Penerangan taman RW padam',
                'detail' => 'Dua titik lampu taman RW tidak menyala sejak seminggu terakhir. Taman jadi gelap dan kurang nyaman untuk warga yang berolahraga sore.',
                'status' => 'selesai',
                'dibuat' => 15,
                'tanggal' => 16,
                'tanggapan' => 'Lampu taman sudah diganti petugas kelurahan. Silakan dicek kembali saat malam.',
            ],
            [
                'user' => $siti,
                'kategori' => 'kebersihan',
                'judul' => 'Bau tidak sedap dari TPS sementara',
                'detail' => 'TPS sementara di ujung Jalan Kenanga mengeluarkan bau tidak sedap, terutama siang hari. Warga sekitar sudah mulai terganggu.',
                'status' => 'proses',
                'dibuat' => 4,
                'tanggal' => 5,
                'tanggapan' => 'Kami sudah meminta petugas kebersihan menambah frekuensi pengangkutan sore hari.',
            ],
            [
                'user' => $agus,
                'kategori' => 'lainnya',
                'judul' => 'Pohon tumbang menutup akses jalan',
                'detail' => 'Ada pohon tumbang menutup setengah badan jalan di RT 02 sejak hujan deras tadi malam. Kendaraan roda dua masih bisa lewat bergantian.',
                'status' => 'selesai',
                'dibuat' => 18,
                'tanggal' => 18,
                'tanggapan' => 'Pohon sudah dipotong dan dibersihkan bersama warga serta petugas kelurahan. Jalan kembali normal.',
            ],
            [
                'user' => $agus,
                'kategori' => 'jalan',
                'judul' => 'Jembatan kecil di RT 03 retak',
                'detail' => 'Jembatan kecil penghubung RT 03 dan RT 04 retak di bagian tengah. Cukup berbahaya jika dilalui motor saat ramai.',
                'status' => 'pending',
                'dibuat' => 6,
                'tanggal' => 7,
            ],
            [
                'user' => $dewi,
                'kategori' => 'penerangan',
                'judul' => 'Lampu gang Blok C tidak menyala',
                'detail' => 'Lampu di gang Blok C mati sejak dua hari lalu. Gang jadi gelap dan warga khawatir soal keamanan saat malam.',
                'status' => 'pending',
                'dibuat' => 1,
                'tanggal' => 1,
            ],
            [
                'user' => $dewi,
                'kategori' => 'jalan',
                'judul' => 'Got mampet di depan rumah Bu Ani',
                'detail' => 'Got di depan rumah Bu Ani mampet dan air menggenang. Bau mulai muncul dan banyak jentik nyamuk.',
                'status' => 'proses',
                'dibuat' => 6,
                'tanggal' => 6,
                'tanggapan' => 'Petugas sudah survei. Pengerukan dijadwalkan akhir pekan ini.',
            ],
        ];

        foreach ($laporan as $data) {
            $dibuat = now()->subDays($data['dibuat'])->setTime(9, 15);
            $diperbarui = $data['status'] === 'pending'
                ? $dibuat
                : now()->subDays(max(0, $data['dibuat'] - 1))->setTime(15, 30);

            $item = new Laporan([
                'kategori' => $data['kategori'],
                'judul' => $data['judul'],
                'detail' => $data['detail'],
                'tanggal' => now()->subDays($data['tanggal'])->toDateString(),
            ]);

            $item->pelapor()->associate($data['user']);
            $item->status = $data['status'];
            $item->tanggapan = $data['tanggapan'] ?? null;
            $item->created_at = $dibuat;
            $item->updated_at = $diperbarui;
            $item->save();
        }

        $this->command?->info('Data contoh: '.count($laporan).' laporan dari 4 warga.');
    }
}
