<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RestoKita POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">🍽️ RestoKita POS</span>
    <div class="d-flex align-items-center gap-3">
        <span class="text-white">{{ Auth::user()->name }}</span>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-outline-light">Logout</button>
        </form>
    </div>
</nav>

<div class="container py-5">

    <h4 class="mb-4">Dashboard</h4>

    {{-- Kartu Metrik --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <h6 class="card-title">Total Produk</h6>
                    <h2>{{ $totalProduk }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <h6 class="card-title">Total Transaksi</h6>
                    <h2>{{ $totalTransaksi }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <h6 class="card-title">Total Pendapatan</h6>
                    <h2>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Navigasi --}}
    <div class="mb-4 d-flex gap-2">
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            📦 Kelola Produk
        </a>
        <a href="{{ route('kasir.index') }}" class="btn btn-success">
            🛒 Buka POS Kasir
        </a>
    </div>

    {{-- Riwayat Transaksi --}}
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            🧾 Riwayat Transaksi
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Diskon</th>
                        <th>Pajak</th>
                        <th>Total</th>
                        <th>Bayar</th>
                        <th>Kembalian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($semuaTransaksi as $trx)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $trx->tanggal }}</td>
                            <td>{{ $trx->user->name }}</td>
                            <td class="text-success">
                                Rp {{ number_format($trx->diskon, 0, ',', '.') }}
                            </td>
                            <td>Rp {{ number_format($trx->pajak, 0, ',', '.') }}</td>
                            <td class="fw-bold">
                                Rp {{ number_format($trx->total, 0, ',', '.') }}
                            </td>
                            <td>Rp {{ number_format($trx->bayar, 0, ',', '.') }}</td>
                            <td class="text-primary">
                                Rp {{ number_format($trx->kembalian, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
