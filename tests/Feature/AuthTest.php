<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengunjung_diarahkan_ke_halaman_masuk(): void
    {
        $this->get(route('beranda'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_halaman_publik_dapat_dibuka(): void
    {
        $this->get(route('home'))->assertOk()->assertSee('Lapor Pak');
        $this->get(route('login'))->assertOk()->assertSee('Selamat datang kembali');
        $this->get(route('register'))->assertOk()->assertSee('Buat akun');
        $this->get(route('bantuan'))->assertOk()->assertSee('Pusat bantuan');
    }

    public function test_warga_baru_bisa_mendaftar(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Rina Wulandari',
            'email' => 'Rina@Example.com',
            'no_whatsapp' => '0812-3456-7890',
            'alamat' => 'Jl. Mawar No. 8',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);

        $response->assertRedirect(route('beranda'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'rina@example.com',
            'role' => 'warga',
            'no_whatsapp' => '081234567890',
        ]);
    }

    public function test_pendaftaran_menolak_data_tidak_lengkap(): void
    {
        $this->post(route('register.store'), [])
            ->assertSessionHasErrors(['name', 'email', 'no_whatsapp', 'alamat', 'password']);

        $this->assertGuest();
    }

    public function test_pendaftaran_menolak_email_yang_sudah_dipakai(): void
    {
        User::factory()->create(['email' => 'sudah@laporpak.id']);

        $this->post(route('register.store'), [
            'name' => 'Warga Baru',
            'email' => 'sudah@laporpak.id',
            'no_whatsapp' => '081234567891',
            'alamat' => 'Jl. Mawar No. 8',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertSessionHasErrors('email');
    }

    public function test_warga_bisa_masuk_dan_diarahkan_ke_beranda(): void
    {
        $user = User::factory()->create([
            'email' => 'warga@laporpak.id',
            'password' => 'rahasia123',
        ]);

        $this->post(route('login.store'), [
            'email' => 'warga@laporpak.id',
            'password' => 'rahasia123',
        ])->assertRedirect(route('beranda'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_diarahkan_ke_ringkasan_setelah_masuk(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'pengurus@laporpak.id',
            'password' => 'rahasia123',
        ]);

        $this->post(route('login.store'), [
            'email' => 'pengurus@laporpak.id',
            'password' => 'rahasia123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_password_salah_menampilkan_pesan(): void
    {
        User::factory()->create([
            'email' => 'warga@laporpak.id',
            'password' => 'rahasia123',
        ]);

        $this->post(route('login.store'), [
            'email' => 'warga@laporpak.id',
            'password' => 'password-salah',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_pengguna_bisa_keluar(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_pengguna_yang_sudah_masuk_tidak_melihat_halaman_masuk(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('login'))
            ->assertRedirect(route('beranda'));
    }
}
