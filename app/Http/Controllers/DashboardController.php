<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalStokBuku = Buku::sum('stok');
        $totalAnggota = Anggota::count();
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $totalKembali = Peminjaman::where('status', 'kembali')->count();

        // 5 Transaksi Peminjaman Terbaru
        $peminjamanTerbaru = Peminjaman::with(['buku', 'anggota'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalBuku',
            'totalStokBuku',
            'totalAnggota',
            'totalDipinjam',
            'totalKembali',
            'peminjamanTerbaru'
        ));
    }
}
