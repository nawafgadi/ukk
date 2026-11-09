# Product Requirement Document (PRD)
## RestoKita — Point of Sale (POS) Web Application
**Skema:** UKK Pemrogram Junior (Junior Coder) / BNSP  
**Status:** In Development  

---

## 1. Ringkasan Eksekutif & Latar Belakang
Banyak usaha mikro/restoran kecil masih menggunakan pencatatan transaksi manual. Hal ini berisiko menimbulkan kesalahan perhitungan, kesulitan pemantauan stok secara *real-time*, dan tidak adanya riwayat transaksi yang terstruktur.

Aplikasi **RestoKita POS** dikembangkan sebagai solusi kasir berbasis web yang mempermudah pencatatan produk, kalkulasi transaksi otomatis (termasuk diskon bertingkat dan pajak 10%), pengurangan stok otomatis, serta penyajian riwayat penjualan dan struk digital.

---

## 2. Tujuan & Standar Penilaian UKK
### 2.1 Tujuan Produk
1. Mempermudah transaksi penjualan kasir.
2. Mengelola inventaris produk, harga, dan ketersediaan stok.
3. Mengotomatiskan perhitungan subtotal, diskon bertingkat, pajak 10%, dan kembalian.
4. Mencegah transaksi melebihi kapasitas stok barang.
5. Menyajikan riwayat transaksi dan struk belanja.

### 2.2 Standar Mutu Project (MUK BNSP)
Project ini dinilai berdasarkan 5 pilar utama:
* **Functional:** Semua fitur inti kasir dan CRUD berjalan tanpa eror.
* **Structured:** Pemisahan logika bersih (MVC/Services), struktur database berelasi normal.
* **Logical:** Implementasi percabangan (`if/else`), validasi stok, dan rumus kalkulasi tepat.
* **Documented:** Tersedia dokumentasi *DocBlock* pada class/method dan catatan fungsi program.
* **Tested:** Menjalankan Unit Testing (PHPUnit) dengan target **100% Passed**.

---

## 3. Pengguna & Hak Akses (User Roles)

| Role | Tanggung Jawab & Hak Akses |
| :--- | :--- |
| **Admin** | Mengelola data produk (CRUD), memantau stok, dan melihat dashboard serta riwayat transaksi. |
| **Kasir** | Mengoperasikan modul kasir, memilih pesanan, memproses pembayaran, dan mencetak struk. |

---

## 4. Fitur Utama (Core Features)

### 4.1 Autentikasi & Otorisasi
* Form login terproteksi untuk Admin dan Kasir.
* Pembatasan rute halaman berdasarkan role aktif (*middleware*).
* Tombol keluar (*logout*).

### 4.2 Dashboard Ringkasan
* Menampilkan metrik: Total Produk, Total Transaksi, dan Akumulasi Pendapatan.
* Menampilkan ringkasan ringkas transaksi terkini.

### 4.3 Manajemen Produk (CRUD)
* Menampilkan katalog produk/menu beserta harga dan stok.
* Tambah produk baru, edit data produk, dan hapus produk.
* Validasi input: Nama produk (string), Harga (min 0), Stok (integer, min 0).

### 4.4 Modul Transaksi Kasir (POS)
* **Katalog Kasir:** Tampilan daftar menu dengan stok > 0.
* **Keranjang Belanja:** Pemilihan item belanja dan penyesuaian jumlah (qty).
* **Validasi Stok:** Cegah penambahan item melebihi ketersediaan stok.
* **Kalkulasi Otomatis:**
  $$\text{Subtotal} = \sum (\text{Harga} \times \text{Jumlah})$$
  $$\text{Diskon Bertingkat} = \begin{cases} 
  15\% \times \text{Subtotal}, & \text{jika Subtotal} \ge 100.000 \\ 
  10\% \times \text{Subtotal}, & \text{jika } 50.000 \le \text{Subtotal} < 100.000 \\ 
  0, & \text{jika Subtotal} < 50.000 
  \end{cases}$$
  $$\text{Dasar Pengenaan Pajak (DPP)} = \text{Subtotal} - \text{Diskon}$$
  $$\text{Pajak 10\%} = 10\% \times \text{DPP}$$
  $$\text{Total Akhir} = \text{DPP} + \text{Pajak 10\%}$$
  $$\text{Kembalian} = \text{Uang Bayar} - \text{Total Akhir}$$
* **Penyelesaian Transaksi:**
  * Validasi pembayaran: Uang bayar tidak boleh kurang dari total akhir.
  * *Database Transaction* atomik untuk mencatat transaksi dan mengurangi stok produk.

### 4.5 Struk & Riwayat Penjualan
* Menampilkan rincian struk pembelian (nama warung/resto, tanggal, kasir, rincian barang, total, diskon, pajak, nominal bayar, dan kembalian).
* Halaman daftar riwayat transaksi untuk rekapitulasi data.

---

## 5. Batasan Sistem (Scope & Out-of-Scope)

### Dalam Cakupan (In-Scope):
* Autentikasi 2 role sederhana (Admin & Kasir).
* Manajemen inventori produk dasar.
* Modul POS kasir berbasis web responsif.
* Diskon bertingkat + Pajak 10%.
* Struk belanja HTML/Print view browser.
* Dokumentasi DocBlock & PHPUnit Test.

### Di Luar Cakupan (Out-of-Scope):
* Integrasi Payment Gateway / QRIS dinamis.
* Aplikasi mobile / native Android / iOS.
* Integrasi driver printer thermal langsung (cukup print bawaan browser).
* Multi-outlet / multi-cabang.
* Sistem akuntansi buku besar kompleks.

---

## 6. Perancangan Database (Schema)

* **Nama Database:** `db_warung_pos` (atau default DB Laravel)

### 6.1 Tabel `users`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (PK) | Primary Key Auto Increment |
| `nama` / `name` | VARCHAR(100) | Nama lengkap pengguna |
| `username` / `email` | VARCHAR(100) | Identitas login pengguna |
| `password` | VARCHAR(255) | Hash password terenkripsi |
| `role` | ENUM('admin','kasir') | Peran hak akses |

### 6.2 Tabel `products`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (PK) | Primary Key Auto Increment |
| `nama_produk` | VARCHAR(100) | Nama item menu/barang |
| `harga` | DECIMAL(12,2) / INT | Harga jual satuan |
| `stok` | INT | Ketersediaan stok fisik |

### 6.3 Tabel `transactions`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (PK) | Primary Key Auto Increment |
| `user_id` | BIGINT (FK) | Relasi ke `users.id` (kasir pencatat) |
| `tanggal` | DATETIME | Waktu transaksi |
| `total` | DECIMAL(12,2) / INT | Total tagihan akhir |
| `diskon` | DECIMAL(12,2) / INT | Potongan diskon bertingkat |
| `pajak` | DECIMAL(12,2) / INT | Nilai pajak 10% |
| `bayar` | DECIMAL(12,2) / INT | Uang yang diserahkan pelanggan |
| `kembalian` | DECIMAL(12,2) / INT | Uang kembalian |

### 6.4 Tabel `transaction_details`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT (PK) | Primary Key Auto Increment |
| `transaction_id` | BIGINT (FK) | Relasi ke `transactions.id` |
| `product_id` | BIGINT (FK) | Relasi ke `products.id` |
| `jumlah` | INT | Jumlah unit yang dibeli |
| `harga` | DECIMAL(12,2) / INT | Harga item saat transaksi terjadi |
| `subtotal` | DECIMAL(12,2) / INT | $\text{Harga} \times \text{Jumlah}$ |

---

## 7. Rencana Pengujian (Testing Plan - PHPUnit)

| ID | Skenario Uji | Kondisi Input | Hasil yang Diharapkan |
| :--- | :--- | :--- | :--- |
| **TC-01** | Uji Diskon Level 0 | Subtotal = Rp30.000 | Diskon = Rp0 |
| **TC-02** | Uji Diskon Level 1 (10%) | Subtotal = Rp58.000 | Diskon = Rp5.800 |
| **TC-03** | Uji Diskon Level 2 (15%) | Subtotal = Rp120.000 | Diskon = Rp18.000 |
| **TC-04** | Uji Perhitungan Pajak 10% | Subtotal = Rp58.000, Diskon = Rp5.800 | DPP = Rp52.200, Pajak = Rp5.220 |
| **TC-05** | Uji Total Akhir | DPP = Rp52.200, Pajak = Rp5.220 | Total Akhir = Rp57.420 |
| **TC-06** | Uji Kembalian Cukup | Bayar = Rp60.000, Total = Rp57.420 | Kembalian = Rp2.580 |
| **TC-07** | Validasi Bayar Kurang | Bayar < Total Akhir | Form checkout menolak proses transaksi |
| **TC-08** | Validasi Stok Berkurang | Beli Produk A sebanyak 2 unit (awal stok 10) | Stok Produk A sisa 8 unit |

---

## 8. Roadmap & Tahapan Pengerjaan

1. **Tahap 1:** Inisialisasi basis data (Migrations & Eloquent Models).
2. **Tahap 2:** Autentikasi pengguna & pembatasan hak akses (Admin & Kasir).
3. **Tahap 3:** Modul CRUD Manajemen Produk.
4. **Tahap 4:** Dashboard ringkasan metrik.
5. **Tahap 5:** Pembuatan Layanan Kalkulasi POS (Subtotal, Diskon, Pajak) & Antarmuka Kasir.
6. **Tahap 6:** Penyimpanan transaksi atomik (`DB::transaction`) dan pengurangan stok.
7. **Tahap 7:** Cetak struk pembelian & riwayat transaksi.
8. **Tahap 8:** Dokumentasi DocBlock & Eksekusi PHPUnit Test (Target 100% Passed).

buat report :

Fitur yang akan ada:                                                                                                            
                                                                                                                                  
  1. 🔐 Login (Admin & Kasir)                                                                                                     
  2. 📊 Dashboard metrik                                                                                                          
  3. 📦 CRUD Produk                                                                                                               
  4. 🛒 Modul POS Kasir + Keranjang                                                                                               
  5. 💾 Simpan Transaksi (stok otomatis berkurang)                                                                                
  6. 🧾 Struk & Riwayat                                                                                                           
  7. 🧪 Unit Testing                                                                                                              
                                                                                                                                  
  Kenapa Laravel? → Struktur rapi (MVC), fitur auth & validasi sudah bawaan, cocok untuk UKK karena standar industri.  

