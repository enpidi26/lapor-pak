<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'no_whatsapp', 'alamat', 'foto'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $attributes = [
        'role' => 'warga',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function laporans(): HasMany
    {
        return $this->hasMany(Laporan::class, 'pelapor_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function dashboardRoute(): string
    {
        return $this->isAdmin() ? 'admin.dashboard' : 'beranda';
    }

    public function inisial(): string
    {
        $kata = preg_split('/\s+/', trim($this->name)) ?: [];

        $inisial = collect($kata)
            ->take(2)
            ->map(fn (string $k) => mb_strtoupper(mb_substr($k, 0, 1)))
            ->implode('');

        return $inisial !== '' ? $inisial : 'W';
    }

    public function fotoUrl(): ?string
    {
        if (! $this->foto) {
            return null;
        }

        return route('profil.avatar').'?v='.($this->updated_at?->timestamp ?? 0);
    }

    public function nomorWhatsappTampil(): string
    {
        $nomor = preg_replace('/\D+/', '', (string) $this->no_whatsapp) ?? '';

        if (str_starts_with($nomor, '62')) {
            $nomor = '0'.substr($nomor, 2);
        }

        return trim(chunk_split($nomor, 4, ' '));
    }
}
