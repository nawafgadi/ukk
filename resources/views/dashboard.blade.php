@extends('layouts.app')

@section('title', 'Dashboard - Toko Maksum')

@section('content')
<div class="container-fluid px-3 px-md-5">

    <!-- Welcome Hero Banner -->
    <div class="card-custom p-4 p-md-4 mb-4" style="background: linear-gradient(135deg, #ecfdf5 0%, #ffffff 100%); border-left: 5px solid var(--primary-green);">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="badge bg-success-subtle text-success border border-success-subtle mb-2 px-3 py-1 rounded-pill fw-semibold">
                    <i class="bi bi-clock-history me-1"></i> Panel Admin Toko Maksum
                </span>
                <h3 class="fw-bold text-dark mb-1">Selamat Datang, {{ Auth::user()->name }} 👋</h3>
                <p class="text-muted mb-0 small">Pantau inventaris barang, ringkasan omset penjualan, dan kelola operasional kasir secara real-time.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('products.index') }}" class="btn btn-green-subtle d-inline-flex align-items-center gap-2">
                    <i class="bi bi-box-seam"></i> Kelola Produk
                </a>
                <a href="{{ route('kasir.index') }}" class="btn btn-green d-inline-flex align-items-center gap-2">
                    <i class="bi bi-cart-plus-fill"></i> Buka Kasir POS
                </a>
            </div>
        </div>
    </div>

    <!-- Metrics Stat Cards -->
    <div class="row g-3 g-md-4 mb-4">
        <!-- Card 1: Total Produk -->
        <div class="col-12 col-md-4">
            <div class="card-custom p-4 h-100 card-custom-hover">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Total Menu & Produk</span>
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #ecfdf5; color: var(--primary-green-dark); display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="fw-bold text-dark mb-0">{{ $totalProduk }}</h2>
                    <span class="text-muted small fw-medium">Item terdaftar</span>
                </div>
                <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center small">
                    <span class="text-muted">Ketersediaan Stok</span>
                    <a href="{{ route('products.index') }}" class="text-success text-decoration-none fw-semibold">
                        Lihat Katalog <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Transaksi -->
        <div class="col-12 col-md-4">
            <div class="card-custom p-4 h-100 card-custom-hover">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Frekuensi Transaksi</span>
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="fw-bold text-dark mb-0">{{ $totalTransaksi }}</h2>
                    <span class="text-muted small fw-medium">Penjualan selesai</span>
                </div>
                <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center small">
                    <span class="text-muted">Status Kasir</span>
                    <span class="badge bg-primary-subtle text-primary fw-semibold rounded-pill">Aktif Terhubung</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Omset -->
        <div class="col-12 col-md-4">
            <div class="card-custom p-4 h-100 card-custom-hover">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Akumulasi Pendapatan</span>
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #fefce8; color: #ca8a04; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="fw-bold text-dark mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
                </div>
                <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center small">
                    <span class="text-muted">Metode Kasir</span>
                    <span class="badge bg-warning-subtle text-warning-emphasis fw-semibold rounded-pill">Tunai (Cash POS)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table Section -->
    <div class="card-custom overflow-hidden">
        <div class="p-4 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 bg-white">
            <div>
                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-success"></i> Riwayat Transaksi Toko Maksum
                </h5>
                <small class="text-muted">Log keseluruhan data transaksi penjualan pelanggan, diskon, dan kalkulasi pajak.</small>
            </div>
            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small fw-semibold">
                Total: {{ count($semuaTransaksi) }} Data
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-maksum">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Waktu Transaksi</th>
                        <th>Petugas Kasir</th>
                        <th class="text-end">Diskon</th>
                        <th class="text-end">Pajak (10%)</th>
                        <th class="text-end">Total Akhir</th>
                        <th class="text-end">Tunai Bayar</th>
                        <th class="text-end">Kembalian</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($semuaTransaksi as $trx)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d M Y, H:i') }}</div>
                                <small class="text-muted" style="font-size: 0.75rem;">ID: #TRX-{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</small>
                            </td>
                            <td>
                                <span class="d-inline-flex align-items-center gap-1 fw-medium text-dark">
                                    <i class="bi bi-person-fill text-muted"></i> {{ $trx->user->name ?? 'Kasir' }}
                                </span>
                            </td>
                            <td class="text-end">
                                @if($trx->diskon > 0)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill fw-semibold">
                                        - Rp {{ number_format($trx->diskon, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-muted small">Rp 0</span>
                                @endif
                            </td>
                            <td class="text-end text-muted">
                                Rp {{ number_format($trx->pajak, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                <span class="fw-bold text-success fs-6">
                                    Rp {{ number_format($trx->total, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-end text-dark">
                                Rp {{ number_format($trx->bayar, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                <span class="fw-semibold text-primary">
                                    Rp {{ number_format($trx->kembalian, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('kasir.struk', $trx->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1" target="_blank" title="Cetak Ulang Struk">
                                    <i class="bi bi-printer"></i> Struk
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-inbox text-muted display-4 d-block mb-3"></i>
                                    <h6 class="fw-bold text-dark">Belum Ada Transaksi Tercatat</h6>
                                    <p class="text-muted small mb-3">Silakan buka modul POS Kasir untuk memulai transaksi penjualan pertama.</p>
                                    <a href="{{ route('kasir.index') }}" class="btn btn-green btn-sm px-4">
                                        <i class="bi bi-cart-plus me-1"></i> Buka Kasir POS Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
