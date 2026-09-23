@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-8">
        <h3 class="fw-bold text-dark mb-1">Selamat Datang di Sistem Perpustakaan</h3>
        <p class="text-muted mb-0">Kelola katalog buku, data siswa/anggota, serta sirkulasi peminjaman dengan mudah.</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Transaksi Peminjaman
        </a>
    </div>
</div>

<!-- Statistik Ringkasan -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card primary h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Judul Buku</span>
                    <h3 class="fw-bold text-primary my-1">{{ number_format($totalBuku) }}</h3>
                    <span class="small text-muted">Total Stok: <strong>{{ number_format($totalStokBuku) }}</strong> eks</span>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle fs-3">
                    <i class="bi bi-book"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card success h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Total Anggota</span>
                    <h3 class="fw-bold text-success my-1">{{ number_format($totalAnggota) }}</h3>
                    <span class="small text-muted">Siswa & Guru Terdaftar</span>
                </div>
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle fs-3">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card warning h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Sedang Dipinjam</span>
                    <h3 class="fw-bold text-warning my-1">{{ number_format($totalDipinjam) }}</h3>
                    <span class="small text-muted">Buku belum dikembalikan</span>
                </div>
                <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle fs-3">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card info h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Sudah Dikembalikan</span>
                    <h3 class="fw-bold text-info my-1">{{ number_format($totalKembali) }}</h3>
                    <span class="small text-muted">Riwayat transaksi selesai</span>
                </div>
                <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle fs-3">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Aksi Cepat & Transaksi Terbaru -->
<div class="row g-4">
    <!-- Tabel Peminjaman Terbaru -->
    <div class="col-lg-8">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>5 Transaksi Peminjaman Terbaru</span>
                <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Anggota</th>
                                <th>Buku</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($peminjamanTerbaru as $pinjam)
                                <tr>
                                    <td>
                                        <div class="small fw-semibold">{{ $pinjam->tanggal_pinjam->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $pinjam->anggota->nama ?? '-' }}</div>
                                        <div class="text-muted small">{{ $pinjam->anggota->kelas ?? '' }} (NIS: {{ $pinjam->anggota->nis ?? '-' }})</div>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $pinjam->buku->judul ?? '-' }}">
                                            {{ $pinjam->buku->judul ?? '-' }}
                                        </div>
                                        <div class="text-muted small font-monospace">{{ $pinjam->buku->kode_buku ?? '' }}</div>
                                    </td>
                                    <td>
                                        @if($pinjam->status === 'dipinjam')
                                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Dipinjam</span>
                                        @else
                                            <span class="badge bg-success"><i class="bi bi-check-lg me-1"></i>Kembali</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($pinjam->status === 'dipinjam')
                                            <form action="{{ route('peminjaman.kembalikan', $pinjam->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success py-1 px-2" title="Kembalikan Buku" onclick="return confirm('Konfirmasi pengembalian buku ini?')">
                                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Kembalikan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">{{ $pinjam->tanggal_kembali?->format('d M Y') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-1"></i>
                                        Belum ada transaksi peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Navigation -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <span class="fw-bold text-dark"><i class="bi bi-lightning-charge me-2 text-warning"></i>Aksi Cepat</span>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('peminjaman.create') }}" class="btn btn-primary text-start d-flex align-items-center justify-content-between p-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-plus-square-fill fs-5"></i>
                        <div>
                            <div class="fw-semibold">Catat Peminjaman Baru</div>
                            <small class="text-white-50">Input transaksi pinjam buku</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </a>

                <a href="{{ route('buku.create') }}" class="btn btn-outline-primary text-start d-flex align-items-center justify-content-between p-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-journal-plus fs-5"></i>
                        <div>
                            <div class="fw-semibold text-dark">Tambah Buku Baru</div>
                            <small class="text-muted">Tambahkan judul atau stok buku</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>

                <a href="{{ route('anggota.create') }}" class="btn btn-outline-success text-start d-flex align-items-center justify-content-between p-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-plus-fill fs-5"></i>
                        <div>
                            <div class="fw-semibold text-dark">Daftarkan Anggota Baru</div>
                            <small class="text-muted">Input data siswa atau anggota</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0 bg-primary text-white p-3">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-info-circle fs-1"></i>
                <div>
                    <h6 class="fw-bold mb-1">Informasi Aturan Pinjam</h6>
                    <p class="small text-white-50 mb-0">Stok buku akan otomatis berkurang 1 saat dipinjam dan bertambah 1 ketika dikembalikan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
