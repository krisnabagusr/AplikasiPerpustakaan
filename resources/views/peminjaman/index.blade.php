@extends('layouts.app')

@section('title', 'Transaksi Peminjaman')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-arrow-left-right me-2 text-primary"></i>Sirkulasi Peminjaman & Pengembalian</h3>
        <p class="text-muted mb-0">Kelola transaksi peminjaman buku oleh siswa dan anggota.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Catat Peminjaman Baru
        </a>
    </div>
</div>

<!-- Filter & Search -->
<div class="card shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('peminjaman.index') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="kembali" {{ request('status') === 'kembali' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                </select>
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" 
                           placeholder="Cari nama anggota, NIS, atau judul buku..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-filter me-1"></i> Filter</button>
                @if(request('status') || request('search'))
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Anggota (Peminjam)</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th class="text-center">Status</th>
                        <th class="text-end" style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjaman as $index => $item)
                        <tr>
                            <td class="text-center text-muted small">
                                {{ $peminjaman->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->anggota->nama ?? '[Anggota Dihapus]' }}</div>
                                <div class="text-muted small">NIS: {{ $item->anggota->nis ?? '-' }} | Kelas: {{ $item->anggota->kelas ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark text-truncate" style="max-width: 250px;" title="{{ $item->buku->judul ?? '-' }}">
                                    {{ $item->buku->judul ?? '[Buku Dihapus]' }}
                                </div>
                                <div class="text-muted small font-monospace">{{ $item->buku->kode_buku ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-calendar-event me-1"></i>{{ $item->tanggal_pinjam->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                @if($item->tanggal_kembali)
                                    <span class="badge bg-light text-success border border-success-subtle">
                                        <i class="bi bi-calendar-check me-1"></i>{{ $item->tanggal_kembali->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-muted small fst-italic">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->status === 'dipinjam')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hourglass-split me-1"></i>Dipinjam
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Kembali
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($item->status === 'dipinjam')
                                    <form action="{{ route('peminjaman.kembalikan', $item->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Proses pengembalian buku ini? Stok akan otomatis bertambah 1.');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success py-1 px-2" title="Kembalikan Buku">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>Kembalikan
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('peminjaman.destroy', $item->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus catatan transaksi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Hapus Transaksi">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data transaksi peminjaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if ($peminjaman->hasPages())
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-end">
                {{ $peminjaman->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
