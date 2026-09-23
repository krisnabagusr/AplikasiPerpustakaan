@extends('layouts.app')

@section('title', 'Catat Peminjaman')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-plus-circle me-2 text-primary"></i>Catat Transaksi Peminjaman</h5>
                <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('peminjaman.store') }}" method="POST">
                    @csrf
                    
                    <!-- Pilihan Anggota -->
                    <div class="mb-3">
                        <label for="anggota_id" class="form-label fw-semibold">Pilih Anggota / Siswa Peminjam <span class="text-danger">*</span></label>
                        <select name="anggota_id" id="anggota_id" class="form-select @error('anggota_id') is-invalid @enderror" required autofocus>
                            <option value="">-- Pilih Siswa / Anggota --</option>
                            @foreach ($anggota as $item)
                                <option value="{{ $item->id }}" {{ old('anggota_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nis }} - {{ $item->nama }} (Kelas: {{ $item->kelas }})
                                </option>
                            @endforeach
                        </select>
                        @error('anggota_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($anggota->isEmpty())
                            <small class="text-danger d-block mt-1">Belum ada data anggota. <a href="{{ route('anggota.create') }}">Tambah anggota terlebih dahulu</a>.</small>
                        @endif
                    </div>

                    <!-- Pilihan Buku -->
                    <div class="mb-3">
                        <label for="buku_id" class="form-label fw-semibold">Pilih Buku yang Dipinjam <span class="text-danger">*</span></label>
                        <select name="buku_id" id="buku_id" class="form-select @error('buku_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Judul Buku --</option>
                            @foreach ($buku as $item)
                                <option value="{{ $item->id }}" {{ old('buku_id') == $item->id ? 'selected' : '' }}>
                                    [{{ $item->kode_buku }}] {{ $item->judul }} (Tersedia: {{ $item->stok }} eks)
                                </option>
                            @endforeach
                        </select>
                        @error('buku_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($buku->isEmpty())
                            <small class="text-danger d-block mt-1">Tidak ada stok buku yang tersedia saat ini. <a href="{{ route('buku.create') }}">Tambah buku baru</a>.</small>
                        @endif
                    </div>

                    <!-- Tanggal Pinjam -->
                    <div class="mb-3">
                        <label for="tanggal_pinjam" class="form-label fw-semibold">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                               id="tanggal_pinjam" name="tanggal_pinjam" 
                               value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                        @error('tanggal_pinjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info d-flex align-items-center gap-2 small my-3" role="alert">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                        <div>
                            <strong>Perhatian:</strong> Menyimpan peminjaman ini akan langsung <u>mengurangi stok buku sebanyak 1 eksemplar</u> di database.
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary px-4" {{ $buku->isEmpty() || $anggota->isEmpty() ? 'disabled' : '' }}>
                            <i class="bi bi-check-circle me-1"></i> Simpan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
