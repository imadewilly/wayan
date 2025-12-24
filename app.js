// JavaScript untuk mengelola data dengan localStorage
// Simulasi database menggunakan localStorage

// Fungsi untuk mendapatkan data dari localStorage
function getData(key) {
    const data = localStorage.getItem(key);
    return data ? JSON.parse(data) : [];
}

// Fungsi untuk menyimpan data ke localStorage
function saveData(key, data) {
    localStorage.setItem(key, JSON.stringify(data));
}

// Fungsi untuk mendapatkan ID berikutnya
function getNextId(key) {
    const data = getData(key);
    if (data.length === 0) return 1;
    const maxId = Math.max(...data.map(item => item.id || 0));
    return maxId + 1;
}

// Inisialisasi data sample jika belum ada
function initSampleData() {
    if (getData('barang').length === 0) {
        const sampleBarang = [
            { id: 1, kode_barang: 'BRG001', nama_barang: 'Laptop ASUS', harga: 8500000, stok: 15, kategori: 'Elektronik' },
            { id: 2, kode_barang: 'BRG002', nama_barang: 'Mouse Logitech', harga: 250000, stok: 50, kategori: 'Aksesoris' },
            { id: 3, kode_barang: 'BRG003', nama_barang: 'Keyboard Mechanical', harga: 750000, stok: 30, kategori: 'Aksesoris' }
        ];
        saveData('barang', sampleBarang);
    }

    if (getData('customer').length === 0) {
        const sampleCustomer = [
            { id: 1, nama: 'Budi Santoso', email: 'budi@email.com', telepon: '081234567890', alamat: 'Jl. Merdeka No. 123, Jakarta', tanggal_daftar: '2023-01-15' },
            { id: 2, nama: 'Siti Nurhaliza', email: 'siti@email.com', telepon: '081234567891', alamat: 'Jl. Sudirman No. 456, Bandung', tanggal_daftar: '2023-02-20' },
            { id: 3, nama: 'Ahmad Fauzi', email: 'ahmad@email.com', telepon: '081234567892', alamat: 'Jl. Gatot Subroto No. 789, Surabaya', tanggal_daftar: '2023-03-10' }
        ];
        saveData('customer', sampleCustomer);
    }

    if (getData('transaksi').length === 0) {
        const sampleTransaksi = [
            { id: 1, no_transaksi: 'TRX001', customer_id: 1, tanggal_transaksi: '2023-06-01', total_harga: 8750000, status: 'selesai' },
            { id: 2, no_transaksi: 'TRX002', customer_id: 2, tanggal_transaksi: '2023-06-05', total_harga: 1000000, status: 'selesai' },
            { id: 3, no_transaksi: 'TRX003', customer_id: 1, tanggal_transaksi: '2023-06-10', total_harga: 3250000, status: 'selesai' }
        ];
        saveData('transaksi', sampleTransaksi);
    }
}

// Format angka ke Rupiah
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Format tanggal
function formatTanggal(tanggal) {
    const date = new Date(tanggal);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

// Tampilkan pesan alert
function showAlert(message, type) {
    let alertContainer = document.getElementById('alert-container');
    if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.id = 'alert-container';
        const mainContent = document.querySelector('.main-content');
        const pageTitle = mainContent.querySelector('.page-title');
        if (pageTitle) {
            pageTitle.parentNode.insertBefore(alertContainer, pageTitle.nextSibling);
        } else {
            mainContent.insertBefore(alertContainer, mainContent.firstChild);
        }
    }
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    alertContainer.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    initSampleData();
});

