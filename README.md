# Sistem Dashboard PT Wayan Jaya Abadi

Sistem dashboard sederhana untuk mengelola data customer, transaksi, dan laporan penjualan menggunakan PHP dasar.

## Fitur

1. **Dashboard** - Menampilkan statistik dan transaksi terbaru
2. **Data Customer** - CRUD (Create, Read, Update, Delete) data customer
3. **Data Transaksi** - CRUD data transaksi
4. **Laporan Penjualan** - Laporan penjualan dengan filter tanggal

## Instalasi

### 1. Database Setup

1. Buka phpMyAdmin di browser: `http://localhost/phpmyadmin`
2. Buat database baru dengan nama: `pt_wayan_jaya_abadi`
3. Import file `database.sql` ke database tersebut
   - Klik tab "Import" di phpMyAdmin
   - Pilih file `database.sql`
   - Klik "Go" untuk mengimpor

### 2. Konfigurasi Database

Edit file `config.php` dan sesuaikan dengan pengaturan database Anda:

```php
$host = "localhost";
$username = "root";      // Sesuaikan jika berbeda
$password = "";           // Sesuaikan jika ada password
$database = "pt_wayan_jaya_abadi";
```

### 3. Akses Aplikasi

Buka browser dan akses:
```
http://localhost/wayan/index.php
```

## Struktur File

```
wayan/
├── config.php                 # File koneksi database
├── database.sql               # File SQL untuk membuat database
├── header.php                 # Header dan navigasi
├── footer.php                 # Footer
├── style.css                  # File CSS untuk styling
├── index.php                  # Halaman Dashboard
├── data_customer.php          # Halaman Data Customer
├── data_transaksi.php         # Halaman Data Transaksi
├── laporan_penjualan.php      # Halaman Laporan Penjualan
└── README.md                  # Dokumentasi
```

## Penggunaan

### Data Customer
- Tambah customer baru melalui form
- Edit data customer yang sudah ada
- Hapus data customer

### Data Transaksi
- Tambah transaksi baru
- Edit status dan data transaksi
- Hapus transaksi

### Laporan Penjualan
- Filter laporan berdasarkan tanggal
- Lihat statistik penjualan
- Lihat laporan per bulan
- Lihat top 10 customer terbaik
- Lihat detail semua transaksi

## Catatan

- Pastikan XAMPP sudah berjalan
- Pastikan MySQL service aktif
- Database harus dibuat dan diimport terlebih dahulu
- Semua kode menggunakan PHP dasar yang mudah dipahami

