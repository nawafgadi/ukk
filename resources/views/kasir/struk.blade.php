<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #TRX-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }} - Toko Maksum</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .receipt-container {
            width: 100%;
            max-width: 420px;
        }

        .receipt-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 10px 30px -5px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            padding: 32px 28px;
            position: relative;
        }

        .receipt-mono {
            font-family: 'Space Mono', monospace;
        }

        .dashed-line {
            border-top: 1.5px dashed #cbd5e1;
            margin: 16px 0;
        }

        .brand-badge-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .receipt-card {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <div class="receipt-container">

        <div class="receipt-card">
            <!-- Header Brand -->
            <div class="text-center">
                <div class="brand-badge-circle">
                    <i class="bi bi-shop"></i>
                </div>
                <h4 class="fw-bold text-dark mb-0">TOKO MAKSUM</h4>
                <small class="text-muted d-block">Sistem Kasir Modern & Inventaris</small>
                <small class="text-muted" style="font-size: 0.75rem;">Jl. Raya Telkom No. 10 • Telp: (021) 789-0123</small>
            </div>

            <div class="dashed-line"></div>

            <!-- Meta Data Transaksi -->
            <div class="small receipt-mono text-muted mb-2">
                <div class="d-flex justify-content-between">
                    <span>No. Nota:</span>
                    <span class="text-dark fw-bold">#TRX-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Tanggal:</span>
                    <span class="text-dark">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Kasir:</span>
                    <span class="text-dark fw-bold">{{ $transaksi->user->name ?? 'Kasir' }}</span>
                </div>
            </div>

            <div class="dashed-line"></div>

            <!-- Rincian Produk -->
            <div class="mb-2">
                @php $subtotalMurni = 0; @endphp
                @foreach ($transaksi->details as $item)
                    @php $subtotalMurni += $item->subtotal; @endphp
                    <div class="mb-2">
                        <div class="fw-bold text-dark small">{{ $item->product->nama_produk ?? 'Item' }}</div>
                        <div class="d-flex justify-content-between small text-muted receipt-mono">
                            <span>{{ $item->jumlah }} x Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                            <span class="text-dark fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="dashed-line"></div>

            <!-- Rincian Kalkulasi & Diskon -->
            <div class="small receipt-mono">
                <div class="d-flex justify-content-between text-muted mb-1">
                    <span>Subtotal</span>
                    <span class="text-dark">Rp {{ number_format($subtotalMurni, 0, ',', '.') }}</span>
                </div>

                @if ($transaksi->diskon > 0)
                    <div class="d-flex justify-content-between text-success fw-bold mb-1">
                        <span>Diskon Khusus</span>
                        <span>- Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="d-flex justify-content-between text-muted mb-1">
                    <span>Pajak (10%)</span>
                    <span class="text-dark">Rp {{ number_format($transaksi->pajak, 0, ',', '.') }}</span>
                </div>

                <div class="dashed-line"></div>

                <div class="d-flex justify-content-between fs-6 fw-bold text-dark mb-2">
                    <span>TOTAL AKHIR</span>
                    <span class="text-success fs-5">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between text-muted mb-1">
                    <span>Tunai (Cash)</span>
                    <span class="text-dark">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between fw-bold text-dark">
                    <span>Kembalian</span>
                    <span class="text-primary">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="dashed-line"></div>

            <!-- Footer Message -->
            <div class="text-center small text-muted">
                <p class="mb-1 fw-bold text-dark">Terima Kasih Telah Berbelanja!</p>
                <p class="mb-0" style="font-size: 0.72rem;">Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan kecuali ada perjanjian.</p>
            </div>
        </div>

        <!-- Tombol Aksi (Tidak Ikut Tercetak) -->
        <div class="d-flex gap-2 mt-3 no-print">
            <button onclick="window.print()" class="btn btn-outline-dark rounded-pill py-2 flex-fill fw-semibold d-inline-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-printer-fill"></i> Cetak Struk
            </button>
            <a href="{{ route('kasir.index') }}" class="btn btn-success rounded-pill py-2 flex-fill fw-bold d-inline-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                <i class="bi bi-cart-plus"></i> Transaksi Baru
            </a>
        </div>

    </div>

</body>
</html>
