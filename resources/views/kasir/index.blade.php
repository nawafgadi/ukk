@extends('layouts.app')

@section('title', 'Kasir POS - Toko Maksum')

@section('content')
<div class="container-fluid px-3 px-md-4">

    <!-- Flash Error Notification -->
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show card-custom p-3 mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">

        <!-- KIRI: Katalog Menu & Produk -->
        <div class="col-12 col-lg-7 col-xl-8">
            <div class="card-custom bg-white p-3 p-md-4 mb-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-grid text-success"></i> Menu & Produk Penjualan
                        </h5>
                        <small class="text-muted">Klik produk untuk memasukkan ke keranjang kasir.</small>
                    </div>

                    <!-- Search Input -->
                    <div class="input-group" style="max-width: 280px;">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" id="posSearchInput" class="form-control border-start-0 ps-0" placeholder="Cari menu produk..." onkeyup="filterPosProducts()">
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="row g-3" id="posProductGrid">
                    @forelse ($products as $product)
                        <div class="col-6 col-sm-4 col-md-4 col-xl-3 pos-product-item">
                            <div class="card-custom card-custom-hover h-100 p-2 p-sm-3 d-flex flex-column justify-content-between border">
                                <div>
                                    <!-- Foto Produk di Kasir -->
                                    @if ($product->foto)
                                        <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_produk }}" class="rounded-3 w-100 mb-2 border shadow-sm" style="height: 100px; object-fit: cover;">
                                    @else
                                        <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center text-muted w-100 mb-2" style="height: 100px; font-size: 1.8rem;">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="badge {{ $product->stok <= 5 ? 'bg-warning-subtle text-warning-emphasis' : 'bg-success-subtle text-success' }} rounded-pill px-2 py-1 small fw-semibold">
                                            Stok: {{ $product->stok }}
                                        </span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1 product-title text-truncate" title="{{ $product->nama_produk }}">
                                        {{ $product->nama_produk }}
                                    </h6>
                                    <div class="fw-bold text-success fs-6 mb-2">
                                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                                    </div>
                                </div>

                                <button type="button"
                                        class="btn btn-green-subtle btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1 rounded-pill"
                                        onclick="tambahKeKeranjang({{ $product->id }}, '{{ addslashes($product->nama_produk) }}', {{ $product->harga }}, {{ $product->stok }})">
                                    <i class="bi bi-plus-circle"></i> Tambah
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-box2 text-muted display-4 d-block mb-2"></i>
                            <h6 class="fw-bold text-dark">Stok Produk Kosong</h6>
                            <p class="text-muted small mb-0">Belum ada produk dengan stok tersedia saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- KANAN: Terminal Kasir & Ringkasan Transaksi -->
        <div class="col-12 col-lg-5 col-xl-4">
            <div class="card-custom bg-white p-3 p-md-4 sticky-top" style="top: 85px;">
                <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-3">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-cart3 text-success"></i> Keranjang Kasir
                    </h5>
                    <button type="button" class="btn btn-sm btn-link text-danger text-decoration-none p-0 fw-semibold" onclick="resetKeranjang()">
                        <i class="bi bi-trash"></i> Bersihkan
                    </button>
                </div>

                <form action="{{ route('kasir.store') }}" method="POST" id="formTransaksi">
                    @csrf

                    <!-- Tabel Item Keranjang -->
                    <div class="table-responsive mb-3" style="max-height: 250px; overflow-y: auto;">
                        <table class="table table-sm table-borderless align-middle mb-0">
                            <tbody id="isiKeranjang">
                                <tr id="keranjangKosong">
                                    <td colspan="4" class="text-center py-4 text-muted small">
                                        <i class="bi bi-bag text-muted fs-3 d-block mb-1"></i>
                                        Keranjang belanja masih kosong
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Incentive Hint Diskon Real-time -->
                    <div id="diskonNotice" class="alert alert-light border py-2 px-3 rounded-3 small mb-3 text-muted d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill text-success fs-6"></i>
                        <span id="diskonNoticeText">Belanja min. Rp 50.000 untuk dapat Diskon 10%!</span>
                    </div>

                    <!-- Rincian Kalkulasi Tagihan -->
                    <div class="p-3 rounded-3 mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Subtotal Belanja</span>
                            <span class="fw-semibold text-dark" id="txtSubtotal">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between small text-success mb-1">
                            <span>Diskon (<span id="txtPctDiskon">0</span>%)</span>
                            <span class="fw-bold" id="txtDiskon">- Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between small text-muted mb-2">
                            <span>Pajak Resto (10%)</span>
                            <span class="fw-semibold text-dark" id="txtPajak">Rp 0</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark fs-6">Total Tagihan</span>
                            <span class="fw-bold text-success fs-4" id="txtTotal">Rp 0</span>
                        </div>
                    </div>

                    <!-- Input Nominal Bayar -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small mb-1">Nominal Pembayaran Tunai (Rp)</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-light fw-bold text-success">Rp</span>
                            <input type="number" id="inputBayar" name="bayar"
                                   class="form-control form-control-lg fw-bold text-dark"
                                   placeholder="0" min="0" oninput="hitungKembalian()" required>
                        </div>

                        <!-- Quick Cash Shortcut Buttons -->
                        <div class="d-flex gap-1 flex-wrap mb-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0 small" onclick="setCashPas()">Uang Pas</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0 small" onclick="setCash(20000)">20rb</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0 small" onclick="setCash(50000)">50rb</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0 small" onclick="setCash(100000)">100rb</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0 small" onclick="setCash(200000)">200rb</button>
                        </div>
                    </div>

                    <!-- Kembalian Display Box -->
                    <div class="card p-3 mb-3 border-0" id="boxKembalian" style="background: #f1f5f9; border-radius: 12px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted fw-semibold d-block" id="labelKembalian">Uang Kembalian</small>
                                <span class="fw-bold fs-5 text-dark" id="txtKembalian">Rp 0</span>
                            </div>
                            <div id="iconKembalian" class="text-muted fs-4">
                                <i class="bi bi-wallet2"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Transaction Button -->
                    <button type="submit" id="btnSubmit" class="btn btn-green w-100 py-3 rounded-pill fw-bold fs-6 d-inline-flex align-items-center justify-content-center gap-2" disabled>
                        <i class="bi bi-check2-circle fs-5"></i> Selesaikan Transaksi
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    let keranjang = {};
    window.totalAkhir = 0;

    function formatRupiah(angka) {
        return 'Rp ' + Number(angka).toLocaleString('id-ID');
    }

    function filterPosProducts() {
        const query = document.getElementById('posSearchInput').value.toLowerCase();
        const items = document.querySelectorAll('.pos-product-item');
        items.forEach(item => {
            const title = item.querySelector('.product-title').textContent.toLowerCase();
            item.style.display = title.includes(query) ? '' : 'none';
        });
    }

    function tambahKeKeranjang(id, nama, harga, stok) {
        if (keranjang[id]) {
            if (keranjang[id].qty >= stok) {
                alert('Maksimal stok untuk ' + nama + ' hanya ' + stok + ' unit.');
                return;
            }
            keranjang[id].qty++;
        } else {
            if (stok < 1) {
                alert('Stok produk ini sedang habis.');
                return;
            }
            keranjang[id] = { id, nama, harga, stok, qty: 1 };
        }
        renderKeranjang();
    }

    function kurangiQty(id) {
        if (!keranjang[id]) return;
        keranjang[id].qty--;
        if (keranjang[id].qty <= 0) {
            delete keranjang[id];
        }
        renderKeranjang();
    }

    function tambahQty(id) {
        if (!keranjang[id]) return;
        if (keranjang[id].qty >= keranjang[id].stok) {
            alert('Stok tidak mencukupi.');
            return;
        }
        keranjang[id].qty++;
        renderKeranjang();
    }

    function hapusItem(id) {
        delete keranjang[id];
        renderKeranjang();
    }

    function resetKeranjang() {
        if (Object.keys(keranjang).length === 0) return;
        if (confirm('Kosongkan semua item di keranjang?')) {
            keranjang = {};
            renderKeranjang();
        }
    }

    function renderKeranjang() {
        const tbody = document.getElementById('isiKeranjang');
        tbody.innerHTML = '';

        // Bersihkan hidden inputs sebelumnya
        document.querySelectorAll('.item-input').forEach(el => el.remove());

        const form = document.getElementById('formTransaksi');
        const ids = Object.keys(keranjang);

        if (ids.length === 0) {
            tbody.innerHTML = `
                <tr id="keranjangKosong">
                    <td colspan="4" class="text-center py-4 text-muted small">
                        <i class="bi bi-bag text-muted fs-3 d-block mb-1"></i>
                        Keranjang belanja masih kosong
                    </td>
                </tr>`;
            hitungKalkulasi(0);
            return;
        }

        let subtotal = 0;
        let index = 0;

        ids.forEach(id => {
            const item = keranjang[id];
            const itemSubtotal = item.harga * item.qty;
            subtotal += itemSubtotal;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="width: 50%;">
                    <div class="fw-bold text-dark small text-truncate" style="max-width: 140px;">${item.nama}</div>
                    <small class="text-muted" style="font-size: 0.72rem;">${formatRupiah(item.harga)} x ${item.qty}</small>
                </td>
                <td class="text-center" style="width: 30%;">
                    <div class="d-inline-flex align-items-center border rounded-pill bg-light p-1">
                        <button type="button" class="btn btn-sm btn-link p-0 text-dark px-1" onclick="kurangiQty(${id})"><i class="bi bi-dash"></i></button>
                        <span class="px-2 small fw-bold">${item.qty}</span>
                        <button type="button" class="btn btn-sm btn-link p-0 text-dark px-1" onclick="tambahQty(${id})"><i class="bi bi-plus"></i></button>
                    </div>
                </td>
                <td class="text-end fw-bold small text-dark" style="width: 20%;">
                    ${formatRupiah(itemSubtotal)}
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-1" onclick="hapusItem(${id})" title="Hapus">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);

            // Tambahkan hidden input untuk controller
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = `items[${index}][id]`;
            inputId.value = item.id;
            inputId.className = 'item-input';
            form.appendChild(inputId);

            const inputQty = document.createElement('input');
            inputQty.type = 'hidden';
            inputQty.name = `items[${index}][qty]`;
            inputQty.value = item.qty;
            inputQty.className = 'item-input';
            form.appendChild(inputQty);

            index++;
        });

        hitungKalkulasi(subtotal);
    }

    function hitungKalkulasi(subtotal) {
        let pctDiskon = 0;
        let diskon = 0;
        const noticeEl = document.getElementById('diskonNoticeText');

        if (subtotal >= 100000) {
            pctDiskon = 15;
            diskon = Math.floor(subtotal * 0.15);
            noticeEl.innerHTML = '🌟 <strong>Selamat!</strong> Diskon maksimal 15% telah diterapkan!';
        } else if (subtotal >= 50000) {
            pctDiskon = 10;
            diskon = Math.floor(subtotal * 0.10);
            const kekurangan = 100000 - subtotal;
            noticeEl.innerHTML = `🎉 <strong>Diskon 10% aktif!</strong> Tambah ${formatRupiah(kekurangan)} lagi untuk dapat Diskon 15%!`;
        } else if (subtotal > 0) {
            const kekurangan = 50000 - subtotal;
            noticeEl.innerHTML = `Belanja <strong>${formatRupiah(kekurangan)}</strong> lagi untuk mendapatkan <strong>Diskon 10%</strong>!`;
        } else {
            noticeEl.innerHTML = 'Belanja min. Rp 50.000 untuk dapat Diskon 10%!';
        }

        const dpp = subtotal - diskon;
        const pajak = Math.floor(dpp * 0.10);
        const total = dpp + pajak;

        window.totalAkhir = total;

        document.getElementById('txtSubtotal').textContent = formatRupiah(subtotal);
        document.getElementById('txtPctDiskon').textContent = pctDiskon;
        document.getElementById('txtDiskon').textContent = '- ' + formatRupiah(diskon);
        document.getElementById('txtPajak').textContent = formatRupiah(pajak);
        document.getElementById('txtTotal').textContent = formatRupiah(total);

        hitungKembalian();
    }

    function hitungKembalian() {
        const inputBayar = document.getElementById('inputBayar');
        const bayar = parseInt(inputBayar.value) || 0;
        const total = window.totalAkhir || 0;
        const kembalian = bayar - total;

        const box = document.getElementById('boxKembalian');
        const txt = document.getElementById('txtKembalian');
        const label = document.getElementById('labelKembalian');
        const icon = document.getElementById('iconKembalian');
        const btn = document.getElementById('btnSubmit');

        const hasItems = Object.keys(keranjang).length > 0;

        if (total > 0 && bayar >= total && hasItems) {
            box.style.background = '#ecfdf5';
            box.style.border = '1.5px solid #a7f3d0';
            txt.className = 'fw-bold fs-4 text-success';
            txt.textContent = formatRupiah(kembalian);
            label.textContent = 'Kembalian Tunai';
            label.className = 'text-success fw-bold small d-block';
            icon.className = 'text-success fs-3';
            icon.innerHTML = '<i class="bi bi-check-circle-fill"></i>';
            btn.disabled = false;
        } else if (total > 0 && bayar > 0 && bayar < total) {
            box.style.background = '#fef2f2';
            box.style.border = '1.5px solid #fecaca';
            txt.className = 'fw-bold fs-5 text-danger';
            txt.textContent = 'Kurang ' + formatRupiah(total - bayar);
            label.textContent = 'Nominal Pembayaran Kurang';
            label.className = 'text-danger fw-bold small d-block';
            icon.className = 'text-danger fs-3';
            icon.innerHTML = '<i class="bi bi-x-circle-fill"></i>';
            btn.disabled = true;
        } else {
            box.style.background = '#f1f5f9';
            box.style.border = 'none';
            txt.className = 'fw-bold fs-5 text-dark';
            txt.textContent = formatRupiah(0);
            label.textContent = 'Uang Kembalian';
            label.className = 'text-muted fw-semibold small d-block';
            icon.className = 'text-muted fs-4';
            icon.innerHTML = '<i class="bi bi-wallet2"></i>';
            btn.disabled = true;
        }
    }

    function setCashPas() {
        if (!window.totalAkhir) return;
        document.getElementById('inputBayar').value = window.totalAkhir;
        hitungKembalian();
    }

    function setCash(nominal) {
        document.getElementById('inputBayar').value = nominal;
        hitungKembalian();
    }
</script>
@endsection
