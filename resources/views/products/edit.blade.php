@extends('layouts.app')

@section('title', 'Perbarui Produk - Toko Maksum')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">

            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-success text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-success text-decoration-none">Produk</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Perbarui Produk</li>
                </ol>
            </nav>

            <div class="card-custom bg-white p-4 p-md-5">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div style="width: 48px; height: 48px; border-radius: 14px; background: #fefce8; color: #ca8a04; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-dark mb-0">Perbarui Data Produk</h4>
                        <small class="text-muted">Edit nama, harga jual, kuantitas stok, atau ganti foto produk.</small>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

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
                                   value="{{ old('nama_produk', $product->nama_produk) }}"
                                   maxlength="100"
                                   required autofocus>
                        </div>
                        @error('nama_produk')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Kategori Produk -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">
                            Kategori Produk <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-bookmark"></i></span>
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="Makanan" {{ old('kategori', $product->kategori) == 'Makanan' ? 'selected' : '' }}>🍛 Makanan</option>
                                <option value="Minuman" {{ old('kategori', $product->kategori) == 'Minuman' ? 'selected' : '' }}>🥤 Minuman</option>
                                <option value="Snack / Jajanan" {{ old('kategori', $product->kategori) == 'Snack / Jajanan' ? 'selected' : '' }}>🍪 Snack / Jajanan</option>
                                <option value="Alat Rumah Tangga" {{ old('kategori', $product->kategori) == 'Alat Rumah Tangga' ? 'selected' : '' }}>🧹 Alat Rumah Tangga</option>
                            </select>
                        </div>
                        @error('kategori')
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
                                   value="{{ old('harga', $product->harga) }}"
                                   min="0"
                                   required>
                        </div>
                        <div class="form-text small text-muted">Gunakan angka nominal tanpa titik atau koma.</div>
                        @error('harga')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Ketersediaan Stok Fisik -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">
                            Jumlah Stok Fisik <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-stack"></i></span>
                            <input type="number"
                                   name="stok"
                                   class="form-control @error('stok') is-invalid @enderror"
                                   value="{{ old('stok', $product->stok) }}"
                                   min="0"
                                   required>
                            <span class="input-group-text bg-light text-muted small">Unit / Pcs</span>
                        </div>
                        @error('stok')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Upload / Ganti Foto Produk -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark small">
                            Foto Produk <span class="text-muted small">(Biarkan kosong jika tidak ingin mengganti)</span>
                        </label>

                        @if ($product->foto)
                            <div class="mb-2 p-2 border rounded-3 bg-light d-flex align-items-center gap-3">
                                <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_produk }}" class="rounded border shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <small class="fw-bold text-dark d-block">Foto Saat Ini</small>
                                    <small class="text-muted" style="font-size: 0.75rem;">Pilih file baru di bawah ini jika ingin menggantinya.</small>
                                </div>
                            </div>
                        @endif

                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-image"></i></span>
                            <input type="file"
                                   name="foto"
                                   class="form-control @error('foto') is-invalid @enderror"
                                   accept="image/png, image/jpeg, image/jpg, image/webp"
                                   onchange="previewGambar(this)">
                        </div>
                        <div class="form-text small text-muted">Format: JPG, JPEG, PNG, WEBP (Maksimal 2MB).</div>
                        @error('foto')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror

                        <!-- Preview Gambar Baru -->
                        <div class="mt-3 text-center d-none" id="previewContainer">
                            <small class="text-muted d-block mb-1">Preview Foto Baru:</small>
                            <img id="previewImg" src="#" alt="Preview Foto Baru" class="rounded-3 border shadow-sm" style="max-height: 140px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 pt-2">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill px-4 flex-fill fw-semibold">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-green rounded-pill px-4 flex-fill d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-save-fill"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function previewGambar(input) {
        const preview = document.getElementById('previewImg');
        const container = document.getElementById('previewContainer');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
