<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerpustakaanTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'admin@perpus.com',
            'password' => bcrypt('admin123'),
        ]);
    }

    public function test_guest_is_redirected_to_login_page()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'admin@perpus.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->user);
    }

    public function test_user_can_view_dashboard()
    {
        $response = $this->actingAs($this->user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Sistem Perpustakaan');
    }

    public function test_crud_buku_and_search()
    {
        // 1. Create Buku
        $response = $this->actingAs($this->user)->post('/buku', [
            'kode_buku' => 'BK-TEST',
            'judul' => 'Buku Belajar Laravel Test',
            'pengarang' => 'Penulis Test',
            'penerbit' => 'Penerbit Test',
            'tahun_terbit' => 2024,
            'stok' => 5,
        ]);

        $response->assertRedirect('/buku');
        $this->assertDatabaseHas('buku', [
            'kode_buku' => 'BK-TEST',
            'judul' => 'Buku Belajar Laravel Test',
        ]);

        $buku = Buku::where('kode_buku', 'BK-TEST')->first();

        // 2. Search Buku
        $searchResponse = $this->actingAs($this->user)->get('/buku?search=Laravel+Test');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Buku Belajar Laravel Test');

        // 3. Update Buku
        $updateResponse = $this->actingAs($this->user)->put("/buku/{$buku->id}", [
            'kode_buku' => 'BK-TEST',
            'judul' => 'Buku Belajar Laravel Test Updated',
            'pengarang' => 'Penulis Test',
            'penerbit' => 'Penerbit Test',
            'tahun_terbit' => 2024,
            'stok' => 10,
        ]);
        $updateResponse->assertRedirect('/buku');
        $this->assertDatabaseHas('buku', [
            'judul' => 'Buku Belajar Laravel Test Updated',
            'stok' => 10,
        ]);

        // 4. Delete Buku
        $deleteResponse = $this->actingAs($this->user)->delete("/buku/{$buku->id}");
        $deleteResponse->assertRedirect('/buku');
        $this->assertDatabaseMissing('buku', ['id' => $buku->id]);
    }

    public function test_crud_anggota()
    {
        // 1. Create Anggota
        $response = $this->actingAs($this->user)->post('/anggota', [
            'nis' => '99990001',
            'nama' => 'Siswa Pengujian',
            'kelas' => 'XII RPL 1',
        ]);
        $response->assertRedirect('/anggota');
        $this->assertDatabaseHas('anggota', [
            'nis' => '99990001',
            'nama' => 'Siswa Pengujian',
        ]);

        $anggota = Anggota::where('nis', '99990001')->first();

        // 2. Update Anggota
        $updateResponse = $this->actingAs($this->user)->put("/anggota/{$anggota->id}", [
            'nis' => '99990001',
            'nama' => 'Siswa Pengujian Update',
            'kelas' => 'XII RPL 2',
        ]);
        $updateResponse->assertRedirect('/anggota');
        $this->assertDatabaseHas('anggota', [
            'nama' => 'Siswa Pengujian Update',
            'kelas' => 'XII RPL 2',
        ]);

        // 3. Delete Anggota
        $deleteResponse = $this->actingAs($this->user)->delete("/anggota/{$anggota->id}");
        $deleteResponse->assertRedirect('/anggota');
        $this->assertDatabaseMissing('anggota', ['id' => $anggota->id]);
    }

    public function test_transaksi_peminjaman_reduces_stock_and_pengembalian_increases_stock()
    {
        $buku = Buku::create([
            'kode_buku' => 'BK-FLOW',
            'judul' => 'Buku Alur Sirkulasi',
            'pengarang' => 'Penulis Flow',
            'penerbit' => 'Penerbit Flow',
            'tahun_terbit' => 2023,
            'stok' => 5,
        ]);

        $anggota = Anggota::create([
            'nis' => '88880001',
            'nama' => 'Peminjam Alur',
            'kelas' => 'XII TKJ 1',
        ]);

        // 1. Pinjam Buku -> stok awal 5 menjadi 4
        $pinjamResponse = $this->actingAs($this->user)->post('/peminjaman', [
            'buku_id' => $buku->id,
            'anggota_id' => $anggota->id,
            'tanggal_pinjam' => '2026-09-23',
        ]);

        $pinjamResponse->assertRedirect('/peminjaman');
        $this->assertEquals(4, $buku->fresh()->stok);
        $this->assertDatabaseHas('peminjaman', [
            'buku_id' => $buku->id,
            'anggota_id' => $anggota->id,
            'status' => 'dipinjam',
            'tanggal_kembali' => null,
        ]);

        $peminjaman = Peminjaman::where('buku_id', $buku->id)->first();

        // 2. Kembalikan Buku -> stok kembali menjadi 5
        $kembaliResponse = $this->actingAs($this->user)->post("/peminjaman/{$peminjaman->id}/kembalikan");
        $kembaliResponse->assertRedirect('/peminjaman');

        $this->assertEquals(5, $buku->fresh()->stok);
        $this->assertEquals('kembali', $peminjaman->fresh()->status);
        $this->assertNotNull($peminjaman->fresh()->tanggal_kembali);
    }
}
