<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80'],
            'email' => [
                'required', 'string', 'email', 'max:120',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
            'no_whatsapp' => ['required', 'string', 'max:20', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,11}$/'],
            'alamat' => ['required', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'hapus_foto' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email belum benar.',
            'email.unique' => 'Email ini sudah dipakai akun lain.',
            'no_whatsapp.required' => 'Nomor WhatsApp wajib diisi agar pengurus RT bisa menghubungimu.',
            'no_whatsapp.regex' => 'Nomor WhatsApp belum benar. Contoh: 0812 3456 7890.',
            'alamat.required' => 'Alamat wajib diisi untuk memudahkan pengecekan lokasi.',
            'foto.image' => 'Berkas harus berupa foto.',
            'foto.mimes' => 'Foto harus berformat JPG, PNG, atau WebP.',
            'foto.max' => 'Ukuran foto maksimal 1 MB.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama lengkap',
            'email' => 'email',
            'no_whatsapp' => 'nomor WhatsApp',
            'alamat' => 'alamat',
            'foto' => 'foto profil',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => is_string($this->name) ? trim($this->name) : $this->name,
            'email' => is_string($this->email) ? trim(mb_strtolower($this->email)) : $this->email,
            'alamat' => is_string($this->alamat) ? trim($this->alamat) : $this->alamat,
            'no_whatsapp' => is_string($this->no_whatsapp)
                ? preg_replace('/[\s\-\.]/', '', $this->no_whatsapp)
                : $this->no_whatsapp,
        ]);
    }
}
