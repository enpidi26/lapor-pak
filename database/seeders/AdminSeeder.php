<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) config('lapor.admin_email');
        $password = (string) config('lapor.admin_password');

        if ($password === '') {
            $this->command?->warn('ADMIN_PASSWORD belum diisi di .env — akun admin dilewati.');

            return;
        }

        try {
            Validator::make(
                compact('email', 'password'),
                [
                    'email' => ['required', 'email'],
                    'password' => [
                        'required',
                        'string',
                        Password::min(12)->letters()->mixedCase()->numbers()->symbols(),
                    ],
                ]
            )->validate();
        } catch (ValidationException $exception) {
            $this->command?->error('ADMIN_EMAIL / ADMIN_PASSWORD belum memenuhi syarat.');
            $this->command?->line($exception->getMessage());

            return;
        }

        if (User::where('email', $email)->exists()) {
            $this->command?->warn('Email admin sudah terdaftar — data tidak diubah.');

            return;
        }

        $admin = new User([
            'name' => 'Pengurus '.config('lapor.rt'),
            'email' => $email,
            'password' => $password,
            'no_whatsapp' => '',
            'alamat' => config('lapor.kelurahan'),
        ]);
        $admin->role = 'admin';
        $admin->save();

        $this->command?->info('Akun admin dibuat: '.$email);
    }
}
