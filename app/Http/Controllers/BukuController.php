<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar data buku dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $buku = Buku::query()
            ->when($search, function ($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('kode_buku', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%")
                    ->orWhere('penerbit', 'like', "%{$search}%")
                    ->orWhere('tahun_terbit', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('buku.index', compact('buku', 'search'));
    }

    /**
     * Menampilkan form tambah buku.
     */
    public function create()
    {
        return view('buku.create');
    }

    /**
     * Menyimpan data buku baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_buku' => 'required|string|max:20|unique:buku,kode_buku',
            'judul' => 'required|string|max:150',
            'pengarang' => 'required|string|max:100',
            'penerbit' => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|digits:4|min:1900|max:' . (date('Y') + 1),
            'stok' => 'required|integer|min:0',
        ], [
            'kode_buku.required' => 'Kode buku wajib diisi.',
            'kode_buku.unique' => 'Kode buku sudah digunakan, gunakan kode lain.',
            'judul.required' => 'Judul buku wajib diisi.',
            'pengarang.required' => 'Nama pengarang wajib diisi.',
            'penerbit.required' => 'Nama penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.digits' => 'Tahun terbit harus berupa 4 digit tahun.',
            'stok.required' => 'Stok buku wajib diisi.',
            'stok.min' => 'Stok buku minimal 0.',
        ]);

        Buku::create($validated);

        return redirect()->route('buku.index')
            ->with('success', "Buku '{$validated['judul']}' berhasil ditambahkan.");
    }

    /**
     * Menampilkan form edit buku.
     */
    public function edit(Buku $buku)
    {
        return view('buku.edit', compact('buku'));
    }

    /**
     * Memperbarui data buku di database.
     */
    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'kode_buku' => [
                'required',
                'string',
                'max:20',
                Rule::unique('buku', 'kode_buku')->ignore($buku->id),
            ],
            'judul' => 'required|string|max:150',
            'pengarang' => 'required|string|max:100',
            'penerbit' => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|digits:4|min:1900|max:' . (date('Y') + 1),
            'stok' => 'required|integer|min:0',
        ], [
            'kode_buku.required' => 'Kode buku wajib diisi.',
            'kode_buku.unique' => 'Kode buku sudah digunakan oleh buku lain.',
            'judul.required' => 'Judul buku wajib diisi.',
            'pengarang.required' => 'Nama pengarang wajib diisi.',
            'penerbit.required' => 'Nama penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'stok.required' => 'Stok buku wajib diisi.',
            'stok.min' => 'Stok buku minimal 0.',
        ]);

        $buku->update($validated);

        return redirect()->route('buku.index')
            ->with('success', "Data buku '{$buku->judul}' berhasil diperbarui.");
    }

    /**
     * Menghapus data buku dari database.
     */
    public function destroy(Buku $buku)
    {
        // Cek jika buku masih sedang dipinjam
        $masihDipinjam = $buku->peminjaman()->where('status', 'dipinjam')->exists();
        if ($masihDipinjam) {
            return redirect()->route('buku.index')
                ->with('error', "Buku '{$buku->judul}' tidak dapat dihapus karena masih tercatat dalam transaksi peminjaman aktif.");
        }

        $judul = $buku->judul;
        $buku->delete();

        return redirect()->route('buku.index')
            ->with('success', "Buku '{$judul}' berhasil dihapus.");
    }
}
