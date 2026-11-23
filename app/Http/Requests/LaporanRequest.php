<?php

namespace App\Http\Requests;

use App\Models\Laporan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        $laporan = $this->route('laporan');

        if ($laporan instanceof Laporan) {
            return $this->user()->can('update', $laporan);
        }

        return $this->user()->can('create', Laporan::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'kategori' => ['required', Rule::in(array_keys(Laporan::KATEGORI))],
            'judul' => ['required', 'string', 'min:5', 'max:120'],
            'detail' => ['required', 'string', 'min:20', 'max:2000'],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:'.config('lapor.foto_maks_kb')],
            'hapus_foto' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kategori.required' => 'Pilih jenis masalah agar laporan diteruskan ke petugas yang tepat.',
            'kategori.in' => 'Jenis masalah belum dikenali. Pilih salah satu pilihan yang tersedia.',
            'judul.required' => 'Tuliskan judul singkat supaya pengurus RT langsung paham masalahnya.',
            'judul.min' => 'Judul terlalu pendek. Tulis minimal 5 karakter.',
            'judul.max' => 'Judul terlalu panjang. Maksimal 120 karakter.',
            'detail.required' => 'Ceritakan masalahnya sedikit lebih lengkap.',
            'detail.min' => 'Ceritakan sedikit lebih detail (minimal 20 karakter) agar mudah ditindaklanjuti.',
            'detail.max' => 'Deskripsi terlalu panjang. Maksimal 2.000 karakter.',
            'tanggal.required' => 'Isi tanggal kejadian.',
            'tanggal.date' => 'Format tanggal belum benar.',
            'tanggal.before_or_equal' => 'Tanggal kejadian tidak boleh melewati hari ini.',
            'foto.image' => 'Berkas harus berupa foto.',
            'foto.mimes' => 'Foto harus berformat JPG, PNG, atau WebP.',
            'foto.max' => 'Ukuran foto terlalu besar. Maksimal 2 MB.',
            'foto.uploaded' => 'Foto gagal diunggah. Coba pilih foto dengan ukuran lebih kecil.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'kategori' => 'jenis masalah',
            'judul' => 'judul laporan',
            'detail' => 'deskripsi',
            'tanggal' => 'tanggal kejadian',
            'foto' => 'foto',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'judul' => is_string($this->judul) ? trim($this->judul) : $this->judul,
            'detail' => is_string($this->detail) ? trim($this->detail) : $this->detail,
        ]);
    }
}
