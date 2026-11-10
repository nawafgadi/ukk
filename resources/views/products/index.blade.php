@extends('layouts.app')

@section('title', 'Data Produk - Toko Maksum')

@section('content')
<div class="container-fluid px-3 px-md-5">

    <!-- Header Actions -->
    <div class="card-custom p-4 mb-4 bg-white">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-success text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manajemen Produk</li>
                    </ol>
                </nav>
                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-boxes text-success"></i> Inventaris Produk Toko Maksum
                </h4>
                <p class="text-muted small mb-0">Kelola katalog barang, pantau ketersediaan stok fisik, dan perbarui harga jual.</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill px-3 d-inline-flex align-items-center gap-2 fw-semibold">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('products.create') }}" class="btn btn-green rounded-pill px-4 d-inline-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Produk Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Notifications -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show card-custom border-success-subtle bg-success-subtle text-success-emphasis p-3 mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show card-custom p-3 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2 mb-1 fw-bold text-danger">
                <i class="bi bi-exclamation-octagon-fill"></i> Terdapat kesalahan pengisian data:
            </div>
            <ul class="mb-0 ps-4 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search & Filter Card -->
    <div class="card-custom mb-4 p-3 bg-white">
        <div class="row g-2 align-items-center">
            <div class="col-md-6 col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Cari nama produk..." onkeyup="filterProducts()">
                </div>
            </div>
            <div class="col-md-6 col-lg-7 text-md-end text-muted small">
                <span>Total: <strong id="productCount" class="text-dark">{{ count($products) }}</strong> item terdaftar</span>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card-custom overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table table-maksum" id="productTable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th class="text-center" style="width: 70px;">Foto</th>
                        <th>Nama Produk / Barang</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-center">Sisa Stok</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>                                                                                                                       
        @forelse ($products as $product)                                                                                          
            <tr class="product-row">                                                                                              
                <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>                                            
                                                                                                                                  
                <!-- ⬅️ TAMBAH BLOK INI: UNTUK TAMPILKAN FOTO -->                                                                 
                <td class="text-center">                                                                                          
                    @if ($product->foto)                                                                                          
                        <img src="{{ asset('storage/' . $product->foto) }}"                                                       
                             alt="{{ $product->nama_produk }}"                                                                    
                             class="rounded-3 border shadow-sm"                                                                   
                             style="width: 48px; height: 48px; object-fit: cover;">                                               
                    @else                                                                                                         
                        <div class="rounded-3 bg-light border d-inline-flex align-items-center justify-content-center text-muted" 
                             style="width: 48px; height: 48px; font-size: 1.2rem;">                                               
                            <i class="bi bi-image"></i>                                                                           
                        </div>                                                                                                    
                    @endif                                                                                                        
                </td>                                                                                                             
                                                                                                                                  
                <td>                                                                                                              
                    <div class="fw-bold text-dark product-name">{{ $product->nama_produk }}</div>                                 
                    <small class="text-muted">Kode ID: #PRD-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</small>             
                </td>               
                            <td class="text-end">
                                <span class="fw-bold text-success fs-6">
                                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold fs-6">{{ $product->stok }}</span> <small class="text-muted">unit</small>
                            </td>
                            <td class="text-center">
                                @if($product->stok <= 0)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1">
                                        <i class="bi bi-x-circle me-1"></i> Habis
                                    </span>
                                @elseif($product->stok <= 5)
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2 py-1">
                                        <i class="bi bi-exclamation-triangle me-1"></i> Menipis
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                        <i class="bi bi-check-circle me-1"></i> Tersedia
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-2 py-1 fw-semibold" title="Edit Produk">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>

                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk [{{ $product->nama_produk }}]? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1 fw-semibold" title="Hapus Produk">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="7" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-box-seam text-muted display-4 d-block mb-3"></i>
                                    <h6 class="fw-bold text-dark">Katalog Produk Masih Kosong</h6>
                                    <p class="text-muted small mb-3">Tambahkan item barang pertama Anda untuk mulai mengelola penjualan kasir.</p>
                                    <a href="{{ route('products.create') }}" class="btn btn-green btn-sm px-4">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah Produk Sekarang
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

<script>
    function filterProducts() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.product-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.querySelector('.product-name').textContent.toLowerCase();
            if (name.includes(query)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('productCount').textContent = visibleCount;
    }
</script>
@endsection
