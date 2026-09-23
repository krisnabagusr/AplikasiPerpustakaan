@extends('layouts.app')

@section('title', 'Tambah Anggota')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-person-plus-fill me-2 text-success"></i>Daftarkan Anggota Baru</h5>
                <a href="{{ route('anggota.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('anggota.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="nis" class="form-label fw-semibold">Nomor Induk Siswa (NIS) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nis') is-invalid @enderror" 
                               id="nis" name="nis" value="{{ old('nis') }}" 
                               placeholder="Contoh: 20241001" required autofocus>
                        @error('nis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label fw-semibold">Nama Lengkap Siswa / Anggota <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" name="nama" value="{{ old('nama') }}" 
                               placeholder="Contoh: Ahmad Maulana" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kelas" class="form-label fw-semibold">Kelas / Rombel <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kelas') is-invalid @enderror" 
                               id="kelas" name="kelas" value="{{ old('kelas') }}" 
                               placeholder="Contoh: XII RPL 1 / XII TKJ 2" required>
                        @error('kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('anggota.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-save me-1"></i> Simpan Anggota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
