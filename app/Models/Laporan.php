<?php

namespace App\Models;

use Database\Factories\LaporanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['kategori', 'judul', 'detail', 'tanggal', 'foto'])]
class Laporan extends Model
{
    /** @use HasFactory<LaporanFactory> */
    use HasFactory;

    /** Status laporan, berurutan sesuai alur penanganan. */
    public const STATUSES = [
        'pending' => 'Menunggu',
        'proses' => 'Diproses',
        'selesai' => 'Selesai',
    ];

    /** Penjelasan status dengan bahasa yang mudah dipahami warga. */
    public const STATUS_DESKRIPSI = [
        'pending' => 'Laporan masuk antrean dan belum ditinjau pengurus.',
        'proses' => 'Pengurus RT sedang menindaklanjuti laporanmu.',
        'selesai' => 'Masalah sudah ditangani dan hasilnya dicatat.',
    ];

    /** Jenis masalah yang bisa dilaporkan warga. */
    public const KATEGORI = [
        'kebersihan' => [
            'warna' => 'teal',
            'label' => 'Kebersihan & Sampah',
            'pendek' => 'Sampah',
            'icon' => 'trash',
            'contoh' => 'Sampah menumpuk, TPS penuh, atau bau tidak sedap',
        ],
        'penerangan' => [
            'warna' => 'orange',
            'label' => 'Lampu & Penerangan',
            'pendek' => 'Penerangan',
            'icon' => 'lamp',
            'contoh' => 'Lampu jalan mati, penerangan gang padam',
        ],
        'jalan' => [
            'warna' => 'purple',
            'label' => 'Jalan & Drainase',
            'pendek' => 'Jalan',
            'icon' => 'road',
            'contoh' => 'Jalan berlubang, got tersumbat, banjir',
        ],
        'keamanan' => [
            'warna' => 'blue',
            'label' => 'Keamanan & Ketertiban',
            'pendek' => 'Keamanan',
            'icon' => 'shield',
            'contoh' => 'Pos ronda rusak, aktivitas mencurigakan',
        ],
        'air' => [
            'warna' => 'pink',
            'label' => 'Air & Sanitasi',
            'pendek' => 'Air',
            'icon' => 'droplet',
            'contoh' => 'Air keruh, saluran air bermasalah',
        ],
        'lainnya' => [
            'warna' => 'gray',
            'label' => 'Lainnya',
            'pendek' => 'Lainnya',
            'icon' => 'dots',
            'contoh' => 'Fasilitas umum rusak atau masalah lain di lingkungan',
        ],
    ];

    protected $attributes = [
        'status' => 'pending',
        'kategori' => 'lainnya',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    public function isEditable(): bool
    {
        return $this->status === 'pending';
    }

    public function labelStatus(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function kode(): string
    {
        return 'LR-'.str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    /**
     * @return array{label: string, pendek: string, icon: string, contoh: string}
     */
    public function metaKategori(): array
    {
        return self::KATEGORI[$this->kategori] ?? self::KATEGORI['lainnya'];
    }

    public function labelKategori(): string
    {
        return $this->metaKategori()['label'];
    }

    public function scopeDicari(Builder $query, ?string $kata): void
    {
        $kata = trim((string) $kata);

        if ($kata === '') {
            return;
        }

        $pola = '%'.$kata.'%';

        $query->where(function (Builder $q) use ($pola) {
            $q->where('judul', 'like', $pola)
                ->orWhere('detail', 'like', $pola)
                ->orWhereHas('pelapor', fn (Builder $pelapor) => $pelapor
                    ->where('name', 'like', $pola)
                    ->orWhere('email', 'like', $pola));
        });
    }

    public function scopeBerstatus(Builder $query, ?string $status): void
    {
        if ($status !== null && array_key_exists($status, self::STATUSES)) {
            $query->where('status', $status);
        }
    }

    public function scopeBerkategori(Builder $query, ?string $kategori): void
    {
        if ($kategori !== null && array_key_exists($kategori, self::KATEGORI)) {
            $query->where('kategori', $kategori);
        }
    }

    public function scopeUrutkan(Builder $query, ?string $urut): void
    {
        match ($urut) {
            'lama' => $query->orderBy('created_at')->orderBy('id'),
            'terlama' => $query->orderBy('tanggal')->orderBy('id'),
            default => $query->latest('created_at')->latest('id'),
        };
    }
}
