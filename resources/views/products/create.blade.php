@extends('layouts.app')

@section('title', 'Tambah Produk Baru - Toko Maksum')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">

            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-success text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-success text-decoration-none">Produk</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Produk</li>
                </ol>
            </nav>

            <div class="card-custom bg-white p-4 p-md-5">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div style="width: 48px; height: 48px; border-radius: 14px; background: #ecfdf5; color: var(--primary-green-dark); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-dark mb-0">Tambah Produk Baru</h4>
                        <small class="text-muted">Masukkan data inventaris barang ke katalog Toko Maksum.</small>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf

                    <!-- Nama Produk -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">
                            Nama Produk / Menu <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-tag"></i></span>
                            <input type="text"
                                   name="nama_produk"
                                   class="form-control @error('nama_produk') is-invalid @enderror"
                                   placeholder="Contoh: Beras Ramos 5kg / Kopi Susu Aren"
                                   value="{{ old('nama_produk') }}"
                                   maxlength="100"
                                   required autofocus>
                        </div>
                        @error('nama_produk')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Harga Jual -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">
                            Harga Satuan (Rp) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold text-success">Rp</span>
                            <input type="number"
                                   name="harga"
                                   class="form-control @error('harga') is-invalid @enderror"
                                   placeholder="0"
                                   value="{{ old('harga') }}"
                                   min="0"
                                   required>
                        </div>
                        <div class="form-text small text-muted">Gunakan angka nominal tanpa titik atau koma.</div>
                        @error('harga')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Ketersediaan Stok Fisik -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark small">
                            Jumlah Stok Fisik Awal <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-stack"></i></span>
                            <input type="number"
                                   name="stok"
                                   class="form-control @error('stok') is-invalid @enderror"
                                   placeholder="0"
                                   value="{{ old('stok', 0) }}"
                                   min="0"
                                   required>
                            <span class="input-group-text bg-light text-muted small">Unit / Pcs</span>
                        </div>
                        @error('stok')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 pt-2">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill px-4 flex-fill fw-semibold">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-green rounded-pill px-4 flex-fill d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-check-lg"></i> Simpan Produk
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
