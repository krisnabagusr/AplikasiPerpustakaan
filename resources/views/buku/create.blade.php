@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Buku Baru</h5>
                <a href="{{ route('buku.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('buku.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="kode_buku" class="form-label fw-semibold">Kode Buku <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_buku') is-invalid @enderror" 
                                   id="kode_buku" name="kode_buku" value="{{ old('kode_buku') }}" 
                                   placeholder="Contoh: BK-001" required autofocus>
                            @error('kode_buku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="stok" class="form-label fw-semibold">Jumlah Stok <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control @error('stok') is-invalid @enderror" 
                                   id="stok" name="stok" value="{{ old('stok', 1) }}" required>
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="judul" class="form-label fw-semibold">Judul Buku <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                   id="judul" name="judul" value="{{ old('judul') }}" 
                                   placeholder="Contoh: Pemrograman Web dengan Laravel & Bootstrap" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="pengarang" class="form-label fw-semibold">Pengarang / Penulis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('pengarang') is-invalid @enderror" 
                                   id="pengarang" name="pengarang" value="{{ old('pengarang') }}" 
                                   placeholder="Contoh: Budi Raharjo" required>
                            @error('pengarang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="penerbit" class="form-label fw-semibold">Penerbit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('penerbit') is-invalid @enderror" 
                                   id="penerbit" name="penerbit" value="{{ old('penerbit') }}" 
                                   placeholder="Contoh: Informatika Bandung" required>
                            @error('penerbit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="tahun_terbit" class="form-label fw-semibold">Tahun Terbit <span class="text-danger">*</span></label>
                            <input type="number" min="1900" max="{{ date('Y') + 1 }}" 
                                   class="form-control @error('tahun_terbit') is-invalid @enderror" 
                                   id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit', date('Y')) }}" required>
                            @error('tahun_terbit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('buku.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
