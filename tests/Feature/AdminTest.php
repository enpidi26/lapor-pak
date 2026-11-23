<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_warga_tidak_bisa_masuk_ruang_pengurus(): void
    {
        $warga = User::factory()->create();

        $this->actingAs($warga)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($warga)->get(route('admin.laporan.index'))->assertForbidden();
    }

    public function test_admin_melihat_ringkasan_dan_semua_laporan(): void
    {
        $admin = User::factory()->admin()->create();
        $warga = User::factory()->create();

        Laporan::factory()->for($warga, 'pelapor')->create(['judul' => 'Lampu depan mati']);
        Laporan::factory()->for($warga, 'pelapor')->create(['judul' => 'Sampah menumpuk', 'status' => 'selesai']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Ringkasan lingkungan');

        $this->actingAs($admin)
            ->get(route('admin.laporan.index'))
            ->assertOk()
            ->assertSee('Lampu depan mati')
            ->assertSee('Sampah menumpuk');
    }

    public function test_admin_dapat_memindahkan_status_satu_langkah_dan_menyimpan_catatan(): void
    {
        $admin = User::factory()->admin()->create();
        $laporan = Laporan::factory()->create(['status' => 'pending']);

        $this->actingAs($admin)
            ->patch(route('admin.laporan.status', $laporan), [
                'status' => 'proses',
                'tanggapan' => 'Petugas sudah mengecek lokasi.',
            ])
            ->assertRedirect(route('admin.laporan.show', $laporan))
            ->assertSessionHas('sukses');

        $laporan->refresh();
        $this->assertSame('proses', $laporan->status);
        $this->assertSame('Petugas sudah mengecek lokasi.', $laporan->tanggapan);

        $this->actingAs($admin)
            ->patch(route('admin.laporan.status', $laporan), [
                'status' => 'selesai',
                'tanggapan' => 'Lampu sudah diganti.',
            ]);

        $this->assertSame('selesai', $laporan->fresh()->status);
    }

    public function test_status_tidak_boleh_dilompati(): void
    {
        $admin = User::factory()->admin()->create();
        $laporan = Laporan::factory()->create(['status' => 'pending']);

        $this->actingAs($admin)
            ->patch(route('admin.laporan.status', $laporan), ['status' => 'selesai'])
            ->assertSessionHasErrors('status');

        $this->assertSame('pending', $laporan->fresh()->status);
    }

    public function test_status_tidak_boleh_dikembalikan(): void
    {
        $admin = User::factory()->admin()->create();
        $laporan = Laporan::factory()->create(['status' => 'proses']);

        $this->actingAs($admin)
            ->patch(route('admin.laporan.status', $laporan), ['status' => 'pending'])
            ->assertSessionHasErrors('status');

        $this->assertSame('proses', $laporan->fresh()->status);
    }

    public function test_admin_dapat_menyimpan_catatan_tanpa_mengubah_status(): void
    {
        $admin = User::factory()->admin()->create();
        $laporan = Laporan::factory()->create(['status' => 'proses', 'tanggapan' => null]);

        $this->actingAs($admin)
            ->patch(route('admin.laporan.status', $laporan), [
                'status' => 'proses',
                'tanggapan' => 'Menunggu material datang.',
            ])
            ->assertRedirect(route('admin.laporan.show', $laporan));

        $laporan->refresh();
        $this->assertSame('proses', $laporan->status);
        $this->assertSame('Menunggu material datang.', $laporan->tanggapan);
    }

    public function test_status_tidak_dikenal_ditolak(): void
    {
        $admin = User::factory()->admin()->create();
        $laporan = Laporan::factory()->create(['status' => 'pending']);

        $this->actingAs($admin)
            ->patch(route('admin.laporan.status', $laporan), ['status' => 'entah'])
            ->assertSessionHasErrors('status');
    }

    public function test_warga_tidak_bisa_mengubah_status(): void
    {
        $warga = User::factory()->create();
        $laporan = Laporan::factory()->for($warga, 'pelapor')->create(['status' => 'pending']);

        $this->actingAs($warga)
            ->patch(route('admin.laporan.status', $laporan), ['status' => 'selesai'])
            ->assertForbidden();

        $this->assertSame('pending', $laporan->fresh()->status);
    }

    public function test_admin_dapat_membuka_detail_laporan_warga(): void
    {
        $admin = User::factory()->admin()->create();
        $laporan = Laporan::factory()->create(['judul' => 'Trotoar berlubang']);

        $this->actingAs($admin)
            ->get(route('admin.laporan.show', $laporan))
            ->assertOk()
            ->assertSee('Trotoar berlubang')
            ->assertSee('Tindak lanjut');
    }
}
