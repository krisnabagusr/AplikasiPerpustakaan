@extends('layouts.app')

@section('title', 'Data Buku')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-journal-bookmark me-2 text-primary"></i>Data Buku</h3>
        <p class="text-muted mb-0">Kelola katalog dan persediaan buku di perpustakaan.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('buku.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Buku Baru
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body p-3">
        <!-- Pencarian & Filter -->
        <form method="GET" action="{{ route('buku.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" 
                           placeholder="Cari judul, pengarang, penerbit, kode buku..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-filter me-1"></i> Cari Data</button>
                @if(request('search'))
                    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Kode Buku</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th class="text-center">Stok</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($buku as $index => $item)
                        <tr>
                            <td class="text-center text-muted small">
                                {{ $buku->firstItem() + $index }}
                            </td>
                            <td>
                                <span class="badge bg-secondary font-monospace">{{ $item->kode_buku }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->judul }}</div>
                            </td>
                            <td>{{ $item->pengarang }}</td>
                            <td>{{ $item->penerbit }}</td>
                            <td>{{ $item->tahun_terbit }}</td>
                            <td class="text-center">
                                @if($item->stok > 5)
                                    <span class="badge bg-success">{{ $item->stok }} eks</span>
                                @elseif($item->stok > 0)
                                    <span class="badge bg-warning text-dark">{{ $item->stok }} eks</span>
                                @else
                                    <span class="badge bg-danger">Habis (0)</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('buku.edit', $item->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2" title="Edit Data">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('buku.destroy', $item->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku \'{{ $item->judul }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Hapus Buku">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                                Tidak ada data buku yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if ($buku->hasPages())
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-end">
                {{ $buku->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
