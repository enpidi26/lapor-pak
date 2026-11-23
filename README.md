# Lapor Pak

Portal pengaduan warga untuk lingkungan RT. Warga mengirim laporan seperti sampah menumpuk, lampu jalan mati, atau jalan rusak. Pengurus RT menindaklanjuti lewat panel admin, dan warga bisa memantau laporannya sampai selesai.

Status laporan berjalan satu arah: **Menunggu → Diproses → Selesai**.

## Fitur

**Warga**

- Daftar dan masuk dengan email, nomor WhatsApp, dan alamat
- Membuat laporan: kategori, judul, deskripsi, tanggal kejadian, dan foto bukti
- Riwayat laporan dengan pencarian, filter status/kategori, urutan, dan pagination
- Mengubah atau menghapus laporan selama statusnya masih Menunggu
- Dashboard berisi statistik, grafik 7/14/30 hari, dan tingkat penyelesaian
- Mengubah profil dan password

**Pengurus (admin)**

- Ringkasan lingkungan: jumlah laporan per status, grafik laporan masuk, dan antrean tertua
- Daftar seluruh laporan dengan pencarian (judul, isi, nama, atau email pelapor) dan filter
- Menulis catatan tindak lanjut lalu memindahkan status satu langkah
- Melihat identitas pelapor: nama, email, WhatsApp, dan alamat

**Publik**

- Beranda yang menjelaskan cara melapor, jenis laporan, arti status, dan privasi
- Pusat bantuan dengan daftar isi dan kontak pengurus

## Teknologi

- PHP 8.3+ dan Laravel 13
- Blade sebagai templating, tanpa JavaScript framework
- SQLite untuk database (bisa diganti MySQL atau PostgreSQL)
- Lucide untuk ikon; font Plus Jakarta Sans dan DM Sans disajikan lokal
- PHPUnit untuk pengujian

## Persiapan

- PHP 8.3 atau lebih baru dengan ekstensi `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, dan `fileinfo`
- Composer

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

Setelah itu buka <http://127.0.0.1:8000>.

Perintah `migrate --seed` membuat akun admin dan, ketika `APP_ENV=local`, menambahkan data contoh lewat `DemoSeeder`.

## Konfigurasi

Nilai yang paling sering diubah ada di `.env`:

```env
APP_NAME="Lapor Pak"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite

RT_NAMA="RT 04 / RW 07"
RT_KELURAHAN="Kelurahan Sukamaju"
RT_WHATSAPP=6281234567890
RT_JAM_TINJAU=24

ADMIN_EMAIL=admin@laporpak.id
ADMIN_PASSWORD=

LAPOR_DEMO_AKTIF=false
DEMO_WARGA_EMAIL=budi@laporpak.id
DEMO_WARGA_PASSWORD=
```

`ADMIN_PASSWORD` wajib minimal 12 karakter dan memuat huruf besar, huruf kecil, angka, serta simbol. Akun admin dibuat dengan:

```bash
php artisan db:seed --class=AdminSeeder
```

Seeder tidak menimpa akun yang emailnya sudah terdaftar. Tombol masuk cepat di halaman login hanya berfungsi saat `APP_ENV=local` dan `LAPOR_DEMO_AKTIF=true`.

## Akun demo

Tersedia setelah `migrate --seed` di lingkungan lokal.

| Peran  | Email               | Password        |
| ------ | ------------------- | --------------- |
| Warga  | `budi@laporpak.id`  | `wargaLapor1`   |
| Admin  | `admin@laporpak.id` | `LaporPak#2025` |

## Pengujian

```bash
php artisan test
```

Pengujian mencakup pendaftaran, login dan logout, pembuatan dan validasi laporan, izin antarpengguna, unggah serta hapus foto, alur status admin, dan pembaruan profil.

## Struktur proyek

```text
app/
  Http/Controllers/   Logika halaman untuk warga, admin, dan auth
  Http/Requests/      Validasi form laporan dan profil
  Http/Middleware/    Pembatasan akses berdasarkan peran
  Models/             User dan Laporan
  Policies/           Aturan izin laporan
  Services/           Alur perpindahan status laporan
  Support/            Filter laporan, foto, statistik, format waktu
resources/views/
  layouts/            Kerangka halaman
  partials/           Bagian yang dipakai ulang
  components/         Komponen Blade
  auth/ warga/ admin/ Halaman per peran
public/               Aset statis: CSS, JS, font, favicon
database/             Migrasi, factory, seeder
tests/                Pengujian fitur
```

## Keamanan

- Warga hanya bisa membuka, mengubah, dan menghapus laporannya sendiri; admin bisa melihat semua
- Foto disimpan di disk non-publik dan diakses lewat route yang memeriksa izin
- `role`, `status`, dan `pelapor_id` tidak termasuk mass assignment
- Foto wajib JPG/PNG/WebP dengan batas ukuran, dan tanggal kejadian tidak boleh melewati hari ini
- Login dan pendaftaran punya pembatasan percobaan

## Dokumentasi

Tangkapan layar dan penjelasan proyek dalam bahasa nonteknis tersedia di [`dokumentasi/LAPORAN.md`](dokumentasi/LAPORAN.md).

## Lisensi

Dibuat untuk keperluan pembelajaran dan penggunaan internal RT.
