@extends('layouts.app')

@section('title', 'Data Anggota')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-people me-2 text-success"></i>Data Anggota</h3>
        <p class="text-muted mb-0">Kelola data siswa dan anggota perpustakaan sekolah.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('anggota.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Anggota Baru
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('anggota.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" 
                           placeholder="Cari nama anggota, NIS, atau kelas..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-success"><i class="bi bi-filter me-1"></i> Cari Data</button>
                @if(request('search'))
                    <a href="{{ route('anggota.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;" class="text-center">No</th>
                        <th>NIS</th>
                        <th>Nama Lengkap</th>
                        <th>Kelas / Jurusan</th>
                        <th>Terdaftar Sejak</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($anggota as $index => $item)
                        <tr>
                            <td class="text-center text-muted small">
                                {{ $anggota->firstItem() + $index }}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">{{ $item->nis }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->nama }}</div>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $item->kelas }}</span>
                            </td>
                            <td class="text-muted small">
                                {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('anggota.edit', $item->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2" title="Edit Anggota">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('anggota.destroy', $item->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data anggota \'{{ $item->nama }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Hapus Anggota">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2"></i>
                                Tidak ada data anggota yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if ($anggota->hasPages())
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-end">
                {{ $anggota->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
