<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnggotaController extends Controller
{
    /**
     * Menampilkan daftar data anggota dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $anggota = Anggota::query()
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('anggota.index', compact('anggota', 'search'));
    }

    /**
     * Menampilkan form tambah anggota.
     */
    public function create()
    {
        return view('anggota.create');
    }

    /**
     * Menyimpan data anggota baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:20|unique:anggota,nis',
            'nama' => 'required|string|max:100',
            'kelas' => 'required|string|max:20',
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah terdaftar untuk anggota lain.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'kelas.required' => 'Kelas wajib diisi.',
        ]);

        Anggota::create($validated);

        return redirect()->route('anggota.index')
            ->with('success', "Anggota '{$validated['nama']}' berhasil didaftarkan.");
    }

    /**
     * Menampilkan form edit anggota.
     */
    public function edit(Anggota $anggota)
    {
        return view('anggota.edit', compact('anggota'));
    }

    /**
     * Memperbarui data anggota di database.
     */
    public function update(Request $request, Anggota $anggota)
    {
        $validated = $request->validate([
            'nis' => [
                'required',
                'string',
                'max:20',
                Rule::unique('anggota', 'nis')->ignore($anggota->id),
            ],
            'nama' => 'required|string|max:100',
            'kelas' => 'required|string|max:20',
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah digunakan oleh anggota lain.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'kelas.required' => 'Kelas wajib diisi.',
        ]);

        $anggota->update($validated);

        return redirect()->route('anggota.index')
            ->with('success', "Data anggota '{$anggota->nama}' berhasil diperbarui.");
    }

    /**
     * Menghapus data anggota dari database.
     */
    public function destroy(Anggota $anggota)
    {
        // Cek jika anggota masih memiliki transaksi peminjaman aktif
        $masihMeminjam = $anggota->peminjaman()->where('status', 'dipinjam')->exists();
        if ($masihMeminjam) {
            return redirect()->route('anggota.index')
                ->with('error', "Anggota '{$anggota->nama}' tidak dapat dihapus karena masih meminjam buku.");
        }

        $nama = $anggota->nama;
        $anggota->delete();

        return redirect()->route('anggota.index')
            ->with('success', "Anggota '{$nama}' berhasil dihapus.");
    }
}
