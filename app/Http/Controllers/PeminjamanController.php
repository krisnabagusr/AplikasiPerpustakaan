<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan daftar transaksi peminjaman & pengembalian.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $peminjaman = Peminjaman::with(['buku', 'anggota'])
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('buku', function ($qb) use ($search) {
                        $qb->where('judul', 'like', "%{$search}%")
                           ->orWhere('kode_buku', 'like', "%{$search}%");
                    })
                    ->orWhereHas('anggota', function ($qa) use ($search) {
                        $qa->where('nama', 'like', "%{$search}%")
                           ->orWhere('nis', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('peminjaman.index', compact('peminjaman', 'status', 'search'));
    }

    /**
     * Menampilkan form transaksi peminjaman buku baru.
     */
    public function create()
    {
        $anggota = Anggota::orderBy('nama', 'asc')->get();
        // Hanya tampilkan buku yang memiliki stok > 0
        $buku = Buku::where('stok', '>', 0)->orderBy('judul', 'asc')->get();

        return view('peminjaman.create', compact('anggota', 'buku'));
    }

    /**
     * Menyimpan transaksi peminjaman dan mengurangi stok buku otomatis (-1).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'buku_id' => 'required|exists:buku,id',
            'anggota_id' => 'required|exists:anggota,id',
            'tanggal_pinjam' => 'required|date',
        ], [
            'buku_id.required' => 'Pilihan buku wajib dipilih.',
            'buku_id.exists' => 'Buku yang dipilih tidak valid.',
            'anggota_id.required' => 'Pilihan anggota wajib dipilih.',
            'anggota_id.exists' => 'Anggota yang dipilih tidak valid.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_pinjam.date' => 'Format tanggal pinjam tidak valid.',
        ]);

        return DB::transaction(function () use ($validated) {
            $buku = Buku::lockForUpdate()->findOrFail($validated['buku_id']);

            // Validasi ketersediaan stok
            if ($buku->stok <= 0) {
                return back()
                    ->withInput()
                    ->with('error', "Stok buku '{$buku->judul}' sedang habis, peminjaman tidak dapat diproses.");
            }

            // 1. Simpan Transaksi Peminjaman
            $peminjaman = Peminjaman::create([
                'buku_id' => $buku->id,
                'anggota_id' => $validated['anggota_id'],
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_kembali' => null,
                'status' => 'dipinjam',
            ]);

            // 2. Kurangi stok buku sebesar 1
            $buku->decrement('stok', 1);

            return redirect()->route('peminjaman.index')
                ->with('success', "Transaksi peminjaman berhasil dicatat! Stok buku '{$buku->judul}' tersisa {$buku->stok}.");
        });
    }

    /**
     * Memproses pengembalian buku: status diubah 'kembali', tanggal_kembali otomatis diisi, stok buku bertambah (+1).
     */
    public function kembalikan(Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'kembali') {
            return redirect()->route('peminjaman.index')
                ->with('warning', 'Transaksi ini sudah berstatus kembali sebelumnya.');
        }

        DB::transaction(function () use ($peminjaman) {
            // 1. Update Status & Tanggal Kembali
            $peminjaman->update([
                'status' => 'kembali',
                'tanggal_kembali' => Carbon::now()->toDateString(),
            ]);

            // 2. Tambah kembali stok buku sebesar 1
            $peminjaman->buku()->increment('stok', 1);
        });

        return redirect()->route('peminjaman.index')
            ->with('success', "Buku '{$peminjaman->buku->judul}' berhasil dikembalikan! Stok buku bertambah 1.");
    }

    /**
     * Menghapus catatan transaksi peminjaman.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        DB::transaction(function () use ($peminjaman) {
            // Jika transaksi dihapus saat status masih dipinjam, kembalikan stoknya
            if ($peminjaman->status === 'dipinjam') {
                $peminjaman->buku()->increment('stok', 1);
            }
            $peminjaman->delete();
        });

        return redirect()->route('peminjaman.index')
            ->with('success', 'Catatan transaksi peminjaman berhasil dihapus.');
    }
}
