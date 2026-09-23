<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin / Petugas Perpustakaan
        User::updateOrCreate(
            ['email' => 'admin@perpus.com'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Data Master Buku
        $bukuList = [
            [
                'kode_buku' => 'BK-001',
                'judul' => 'Pemrograman Web dengan PHP & Laravel',
                'pengarang' => 'Budi Raharjo',
                'penerbit' => 'Informatika Bandung',
                'tahun_terbit' => 2023,
                'stok' => 8,
            ],
            [
                'kode_buku' => 'BK-002',
                'judul' => 'Basis Data Relasional & MySQL Modern',
                'pengarang' => 'Fathansyah',
                'penerbit' => 'Informatika',
                'tahun_terbit' => 2022,
                'stok' => 5,
            ],
            [
                'kode_buku' => 'BK-003',
                'judul' => 'Struktur Data dan Algoritma Dasar',
                'pengarang' => 'Rinaldi Munir',
                'penerbit' => 'Informatika Bandung',
                'tahun_terbit' => 2021,
                'stok' => 6,
            ],
            [
                'kode_buku' => 'BK-004',
                'judul' => 'Clean Code: Panduan Menulis Kode Rapi',
                'pengarang' => 'Robert C. Martin',
                'penerbit' => 'Prentice Hall',
                'tahun_terbit' => 2020,
                'stok' => 4,
            ],
            [
                'kode_buku' => 'BK-005',
                'judul' => 'UI/UX Design Essentials untuk Pemula',
                'pengarang' => 'Don Norman',
                'penerbit' => 'Elex Media Komputindo',
                'tahun_terbit' => 2023,
                'stok' => 7,
            ],
            [
                'kode_buku' => 'BK-006',
                'judul' => 'Jaringan Komputer dan Keamanan Siber',
                'pengarang' => 'Onno W. Purbo',
                'penerbit' => 'Andi Offset',
                'tahun_terbit' => 2022,
                'stok' => 3,
            ],
        ];

        foreach ($bukuList as $bukuData) {
            Buku::firstOrCreate(['kode_buku' => $bukuData['kode_buku']], $bukuData);
        }

        // 3. Data Master Anggota / Siswa
        $anggotaList = [
            [
                'nis' => '20241001',
                'nama' => 'Ahmad Maulana',
                'kelas' => 'XII RPL 1',
            ],
            [
                'nis' => '20241002',
                'nama' => 'Siti Nurhaliza',
                'kelas' => 'XII RPL 1',
            ],
            [
                'nis' => '20241003',
                'nama' => 'Rizky Pratama',
                'kelas' => 'XII RPL 2',
            ],
            [
                'nis' => '20241004',
                'nama' => 'Dewi Lestari',
                'kelas' => 'XII TKJ 1',
            ],
            [
                'nis' => '20241005',
                'nama' => 'Fajar Ramadhan',
                'kelas' => 'XII TKJ 2',
            ],
        ];

        foreach ($anggotaList as $anggotaData) {
            Anggota::firstOrCreate(['nis' => $anggotaData['nis']], $anggotaData);
        }

        // 4. Sample Data Transaksi Peminjaman
        $buku1 = Buku::where('kode_buku', 'BK-001')->first();
        $buku2 = Buku::where('kode_buku', 'BK-002')->first();
        $buku3 = Buku::where('kode_buku', 'BK-003')->first();

        $anggota1 = Anggota::where('nis', '20241001')->first();
        $anggota2 = Anggota::where('nis', '20241002')->first();
        $anggota3 = Anggota::where('nis', '20241003')->first();

        if ($buku1 && $anggota1 && Peminjaman::count() === 0) {
            // Transaksi 1: Masih dipinjam
            Peminjaman::create([
                'buku_id' => $buku1->id,
                'anggota_id' => $anggota1->id,
                'tanggal_pinjam' => Carbon::now()->subDays(3)->toDateString(),
                'tanggal_kembali' => null,
                'status' => 'dipinjam',
            ]);
            $buku1->decrement('stok', 1);

            // Transaksi 2: Masih dipinjam
            Peminjaman::create([
                'buku_id' => $buku2->id,
                'anggota_id' => $anggota2->id,
                'tanggal_pinjam' => Carbon::now()->subDays(1)->toDateString(),
                'tanggal_kembali' => null,
                'status' => 'dipinjam',
            ]);
            $buku2->decrement('stok', 1);

            // Transaksi 3: Sudah dikembalikan
            Peminjaman::create([
                'buku_id' => $buku3->id,
                'anggota_id' => $anggota3->id,
                'tanggal_pinjam' => Carbon::now()->subDays(10)->toDateString(),
                'tanggal_kembali' => Carbon::now()->subDays(3)->toDateString(),
                'status' => 'kembali',
            ]);
        }
    }
}
