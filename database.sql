-- Database untuk PT Wayan Jaya Abadi
-- Buat database terlebih dahulu: CREATE DATABASE pt_wayan_jaya_abadi;

USE pt_wayan_jaya_abadi;

-- Tabel Customer
CREATE TABLE IF NOT EXISTS customer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    telepon VARCHAR(20),
    alamat TEXT,
    tanggal_daftar DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Barang
CREATE TABLE IF NOT EXISTS barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(50) UNIQUE NOT NULL,
    nama_barang VARCHAR(100) NOT NULL,
    harga DECIMAL(15,2) NOT NULL,
    stok INT DEFAULT 0,
    kategori VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Transaksi
CREATE TABLE IF NOT EXISTS transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_transaksi VARCHAR(50) UNIQUE NOT NULL,
    customer_id INT NOT NULL,
    tanggal_transaksi DATE NOT NULL,
    total_harga DECIMAL(15,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customer(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Detail Transaksi
CREATE TABLE IF NOT EXISTS detail_transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaksi_id INT NOT NULL,
    barang_id INT NOT NULL,
    jumlah INT NOT NULL,
    harga_satuan DECIMAL(15,2) NOT NULL,
    subtotal DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE,
    FOREIGN KEY (barang_id) REFERENCES barang(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Data Sample Customer
INSERT INTO customer (nama, email, telepon, alamat, tanggal_daftar) VALUES
('Budi Santoso', 'budi@email.com', '081234567890', 'Jl. Merdeka No. 123, Jakarta', '2023-01-15'),
('Siti Nurhaliza', 'siti@email.com', '081234567891', 'Jl. Sudirman No. 456, Bandung', '2023-02-20'),
('Ahmad Fauzi', 'ahmad@email.com', '081234567892', 'Jl. Gatot Subroto No. 789, Surabaya', '2023-03-10'),
('Dewi Lestari', 'dewi@email.com', '081234567893', 'Jl. Thamrin No. 321, Medan', '2023-04-05'),
('Rudi Hartono', 'rudi@email.com', '081234567894', 'Jl. Diponegoro No. 654, Yogyakarta', '2023-05-12');

-- Insert Data Sample Barang
INSERT INTO barang (kode_barang, nama_barang, harga, stok, kategori) VALUES
('BRG001', 'Laptop ASUS', 8500000, 15, 'Elektronik'),
('BRG002', 'Mouse Logitech', 250000, 50, 'Aksesoris'),
('BRG003', 'Keyboard Mechanical', 750000, 30, 'Aksesoris'),
('BRG004', 'Monitor LG 24 inch', 2500000, 20, 'Elektronik'),
('BRG005', 'Webcam HD', 500000, 25, 'Aksesoris'),
('BRG006', 'Speaker JBL', 1200000, 18, 'Elektronik'),
('BRG007', 'Headset Wireless', 800000, 35, 'Aksesoris'),
('BRG008', 'Printer Canon', 2000000, 12, 'Elektronik');

-- Insert Data Sample Transaksi
INSERT INTO transaksi (no_transaksi, customer_id, tanggal_transaksi, total_harga, status) VALUES
('TRX001', 1, '2023-06-01', 8750000, 'selesai'),
('TRX002', 2, '2023-06-05', 1000000, 'selesai'),
('TRX003', 1, '2023-06-10', 3250000, 'selesai'),
('TRX004', 3, '2023-06-15', 500000, 'selesai'),
('TRX005', 4, '2023-06-20', 2000000, 'pending'),
('TRX006', 2, '2023-06-25', 1550000, 'selesai'),
('TRX007', 5, '2023-07-01', 8500000, 'selesai'),
('TRX008', 1, '2023-07-05', 750000, 'selesai');

-- Insert Data Sample Detail Transaksi
INSERT INTO detail_transaksi (transaksi_id, barang_id, jumlah, harga_satuan, subtotal) VALUES
(1, 1, 1, 8500000, 8500000),
(1, 2, 1, 250000, 250000),
(2, 7, 1, 800000, 800000),
(2, 5, 1, 500000, 500000),
(3, 4, 1, 2500000, 2500000),
(3, 3, 1, 750000, 750000),
(4, 5, 1, 500000, 500000),
(5, 8, 1, 2000000, 2000000),
(6, 6, 1, 1200000, 1200000),
(6, 2, 1, 250000, 250000),
(6, 5, 1, 500000, 500000),
(7, 1, 1, 8500000, 8500000),
(8, 3, 1, 750000, 750000);

