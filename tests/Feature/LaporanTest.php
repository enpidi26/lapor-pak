<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    private function dataLaporan(array $tambahan = []): array
    {
        return array_merge([
            'kategori' => 'penerangan',
            'judul' => 'Lampu jalan di Gang Melati mati',
            'detail' => 'Lampu di depan rumah Bu Sri mati sejak Sabtu. Gang menjadi gelap dan warga kesulitan melihat jalan saat pulang malam.',
            'tanggal' => now()->subDay()->toDateString(),
        ], $tambahan);
    }

    public function test_warga_dapat_melihat_halaman_daftar_dan_formulir(): void
    {
        $warga = User::factory()->create();

        $this->actingAs($warga)->get(route('laporan.index'))->assertOk()->assertSee('Laporan saya');
        $this->actingAs($warga)->get(route('laporan.create'))->assertOk()->assertSee('Buat laporan');
    }

    public function test_warga_dapat_mengirim_laporan_baru(): void
    {
        Storage::fake('public');

        $warga = User::factory()->create();

        $response = $this->actingAs($warga)->post(route('laporan.store'), $this->dataLaporan([
            'foto' => UploadedFile::fake()->image('bukti.jpg', 900, 600),
        ]));

        $laporan = Laporan::firstOrFail();

        $response->assertRedirect(route('laporan.show', $laporan));
        $response->assertSessionHas('sukses');

        $this->assertSame($warga->id, $laporan->pelapor_id);
        $this->assertSame('pending', $laporan->status);
        $this->assertSame('penerangan', $laporan->kategori);
        $this->assertNotNull($laporan->foto);
        Storage::disk('public')->assertExists($laporan->foto);
    }

    public function test_laporan_menolak_tanggal_di_masa_depan_dan_isi_kosong(): void
    {
        $warga = User::factory()->create();

        $this->actingAs($warga)->post(route('laporan.store'), [
            'kategori' => 'bukan-jenis',
            'judul' => '',
            'detail' => 'pendek',
            'tanggal' => now()->addDay()->toDateString(),
        ])->assertSessionHasErrors(['kategori', 'judul', 'detail', 'tanggal']);

        $this->assertDatabaseCount('laporans', 0);
    }

    public function test_warga_tidak_bisa_membuka_laporan_orang_lain(): void
    {
        $pemilik = User::factory()->create();
        $lain = User::factory()->create();
        $laporan = Laporan::factory()->for($pemilik, 'pelapor')->create();

        $this->actingAs($lain)
            ->get(route('laporan.show', $laporan))
            ->assertForbidden();

        $this->actingAs($lain)
            ->get(route('laporan.foto', $laporan))
            ->assertForbidden();
    }

    public function test_warga_dapat_mengubah_laporan_yang_masih_menunggu(): void
    {
        $warga = User::factory()->create();
        $laporan = Laporan::factory()->for($warga, 'pelapor')->create(['status' => 'pending']);

        $this->actingAs($warga)
            ->get(route('laporan.edit', $laporan))
            ->assertOk();

        $this->actingAs($warga)
            ->put(route('laporan.update', $laporan), $this->dataLaporan([
                'judul' => 'Judul yang sudah diperbaiki',
            ]))
            ->assertRedirect(route('laporan.show', $laporan));

        $this->assertSame('Judul yang sudah diperbaiki', $laporan->fresh()->judul);
        $this->assertSame('pending', $laporan->fresh()->status);
    }

    public function test_laporan_yang_sudah_ditinjau_tidak_bisa_diubah_atau_dihapus(): void
    {
        $warga = User::factory()->create();
        $laporan = Laporan::factory()->for($warga, 'pelapor')->create(['status' => 'proses']);

        $this->actingAs($warga)
            ->get(route('laporan.edit', $laporan))
            ->assertRedirect(route('laporan.show', $laporan))
            ->assertSessionHas('galat');

        $this->actingAs($warga)
            ->put(route('laporan.update', $laporan), $this->dataLaporan())
            ->assertForbidden();

        $this->actingAs($warga)
            ->delete(route('laporan.destroy', $laporan))
            ->assertForbidden();

        $this->assertDatabaseHas('laporans', ['id' => $laporan->id]);
    }

    public function test_warga_dapat_menghapus_laporan_beserta_fotonya(): void
    {
        Storage::fake('public');

        $warga = User::factory()->create();
        Storage::disk('public')->put('laporan/contoh.jpg', 'isi');

        $laporan = Laporan::factory()->for($warga, 'pelapor')->create([
            'status' => 'pending',
            'foto' => 'laporan/contoh.jpg',
        ]);

        $this->actingAs($warga)
            ->delete(route('laporan.destroy', $laporan))
            ->assertRedirect(route('laporan.index'));

        $this->assertDatabaseMissing('laporans', ['id' => $laporan->id]);
        Storage::disk('public')->assertMissing('laporan/contoh.jpg');
    }

    public function test_foto_hanya_bisa_dibuka_pelapor_dan_pengurus(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('laporan/bukti.jpg', 'isi-gambar');

        $pemilik = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $laporan = Laporan::factory()->for($pemilik, 'pelapor')->create([
            'foto' => 'laporan/bukti.jpg',
        ]);

        $this->actingAs($pemilik)->get(route('laporan.foto', $laporan))->assertOk();
        $this->actingAs($admin)->get(route('laporan.foto', $laporan))->assertOk();
    }

    public function test_daftar_laporan_hanya_menampilkan_milik_sendiri_dan_bisa_difilter(): void
    {
        $warga = User::factory()->create();
        $lain = User::factory()->create();

        Laporan::factory()->for($warga, 'pelapor')->create([
            'judul' => 'Lampu depan rumah mati',
            'status' => 'pending',
        ]);
        Laporan::factory()->for($warga, 'pelapor')->create([
            'judul' => 'Got tersumbat',
            'status' => 'selesai',
        ]);
        Laporan::factory()->for($lain, 'pelapor')->create([
            'judul' => 'Laporan milik orang lain',
            'status' => 'pending',
        ]);

        $this->actingAs($warga)
            ->get(route('laporan.index'))
            ->assertOk()
            ->assertViewHas('laporans', fn ($paginator) => $paginator->total() === 2);

        $this->actingAs($warga)
            ->get(route('laporan.index', ['status' => 'selesai']))
            ->assertOk()
            ->assertViewHas('laporans', fn ($paginator) => $paginator->total() === 1);
    }

    public function test_admin_tidak_bisa_mengirim_laporan(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('laporan.store'), $this->dataLaporan())
            ->assertForbidden();
    }
}
