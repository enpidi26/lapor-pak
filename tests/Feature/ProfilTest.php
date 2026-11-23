<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengguna_dapat_membuka_dan_memperbarui_profil(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'email' => 'lama@laporpak.id',
        ]);

        $this->actingAs($user)->get(route('profil.edit'))->assertOk()->assertSee('Profil saya');

        $this->actingAs($user)
            ->put(route('profil.update'), [
                'name' => 'Nama Baru',
                'email' => 'baru@laporpak.id',
                'no_whatsapp' => '0812-3456-7890',
                'alamat' => 'Jl. Baru No. 10',
            ])
            ->assertRedirect(route('profil.edit'))
            ->assertSessionHas('sukses');

        $user->refresh();

        $this->assertSame('Nama Baru', $user->name);
        $this->assertSame('baru@laporpak.id', $user->email);
        $this->assertSame('081234567890', $user->no_whatsapp);
        $this->assertSame('Jl. Baru No. 10', $user->alamat);
    }

    public function test_profil_menolak_nomor_whatsapp_tidak_valid(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('profil.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'no_whatsapp' => '12345',
                'alamat' => 'Jl. Baru',
            ])
            ->assertSessionHasErrors('no_whatsapp');
    }

    public function test_pengguna_dapat_mengganti_password(): void
    {
        $user = User::factory()->create(['password' => 'passwordlama1']);

        $this->actingAs($user)
            ->put(route('profil.password'), [
                'password_saat_ini' => 'passwordlama1',
                'password' => 'passwordbaru2',
                'password_confirmation' => 'passwordbaru2',
            ])
            ->assertRedirect(route('profil.edit'))
            ->assertSessionHas('sukses');

        $this->assertTrue(Hash::check('passwordbaru2', $user->fresh()->password));
    }

    public function test_password_saat_ini_harus_benar(): void
    {
        $user = User::factory()->create(['password' => 'passwordlama1']);

        $this->actingAs($user)
            ->put(route('profil.password'), [
                'password_saat_ini' => 'salah',
                'password' => 'passwordbaru2',
                'password_confirmation' => 'passwordbaru2',
            ])
            ->assertSessionHasErrors('password_saat_ini');

        $this->assertTrue(Hash::check('passwordlama1', $user->fresh()->password));
    }

    public function test_pengunjung_tidak_bisa_membuka_profil(): void
    {
        $this->get(route('profil.edit'))->assertRedirect(route('login'));
    }
}
