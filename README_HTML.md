# Sistem Dashboard PT Wayan Jaya Abadi - Versi HTML

Sistem dashboard berbasis HTML murni dengan JavaScript menggunakan localStorage untuk penyimpanan data.

## Fitur

1. **Dashboard** - Menampilkan statistik dan transaksi terbaru
2. **Data Barang** - CRUD (Create, Read, Update, Delete) data barang
3. **Data Customer** - CRUD data customer
4. **Data Transaksi** - CRUD data transaksi
5. **Laporan Penjualan** - Laporan penjualan dengan filter tanggal dan CRUD transaksi

## Cara Menggunakan

### 1. Buka File HTML

Tidak perlu server! Buka langsung file HTML di browser:
- `index.html` - Halaman Dashboard
- `data_barang.html` - Halaman Data Barang
- `data_customer.html` - Halaman Data Customer
- `data_transaksi.html` - Halaman Data Transaksi
- `laporan_penjualan.html` - Halaman Laporan Penjualan

### 2. Penyimpanan Data

- Data disimpan di **localStorage** browser
- Data akan tetap ada selama tidak menghapus cache browser
- Data berbeda untuk setiap browser

### 3. Data Sample

Saat pertama kali membuka aplikasi, data sample akan otomatis dibuat:
- 3 sample barang
- 3 sample customer
- 3 sample transaksi

## Struktur File

```
wayan/
├── app.js                      # JavaScript untuk mengelola data (localStorage)
├── style.css                   # File CSS untuk styling (dark theme)
├── index.html                  # Halaman Dashboard
├── data_barang.html            # Halaman Data Barang
├── data_customer.html          # Halaman Data Customer
├── data_transaksi.html         # Halaman Data Transaksi
├── laporan_penjualan.html      # Halaman Laporan Penjualan
└── README_HTML.md              # Dokumentasi ini
```

## Fitur CRUD

### Data Barang
- ✅ Tambah barang baru
- ✅ Lihat daftar barang
- ✅ Edit data barang
- ✅ Hapus barang
- ⚠️ Peringatan stok rendah (warna merah jika stok ≤ 5)

### Data Customer
- ✅ Tambah customer baru
- ✅ Lihat daftar customer
- ✅ Edit data customer
- ✅ Hapus customer

### Data Transaksi
- ✅ Tambah transaksi baru
- ✅ Lihat daftar transaksi
- ✅ Edit transaksi (termasuk status)
- ✅ Hapus transaksi

### Laporan Penjualan
- ✅ Filter laporan berdasarkan tanggal
- ✅ Statistik penjualan
- ✅ Laporan per bulan
- ✅ Top 10 customer terbaik
- ✅ Detail transaksi dengan CRUD

## Catatan Penting

1. **LocalStorage**: Data hanya tersimpan di browser yang digunakan
2. **Tidak ada Database**: Tidak perlu MySQL atau database server
3. **Bisa dibuka langsung**: Tidak perlu XAMPP atau server web
4. **Data Sample**: Otomatis dibuat saat pertama kali dibuka
5. **Dark Theme**: Semua halaman menggunakan dark theme

## Kelebihan Versi HTML

- ✅ Tidak perlu server (XAMPP/PHP)
- ✅ Tidak perlu database MySQL
- ✅ Bisa dibuka langsung di browser
- ✅ Mudah di-deploy ke hosting statis
- ✅ Semua fitur CRUD tetap berfungsi

## Kekurangan Versi HTML

- ⚠️ Data hanya tersimpan di browser (localStorage)
- ⚠️ Data hilang jika cache browser dihapus
- ⚠️ Data tidak bisa diakses dari browser/device lain
- ⚠️ Tidak cocok untuk penggunaan multi-user

## Rekomendasi

- Untuk **demo/pengujian**: Gunakan versi HTML ini
- Untuk **produksi/multi-user**: Gunakan versi PHP dengan MySQL

