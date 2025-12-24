<?php
// Halaman Laporan Penjualan
require_once 'config.php';

$message = '';
$message_type = '';

// Proses Edit Transaksi
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $no_transaksi = mysqli_real_escape_string($conn, $_POST['no_transaksi']);
    $customer_id = $_POST['customer_id'];
    $tanggal_transaksi = $_POST['tanggal_transaksi'];
    $total_harga = $_POST['total_harga'];
    $status = $_POST['status'];
    
    $query = "UPDATE transaksi SET 
              no_transaksi='$no_transaksi', customer_id=$customer_id, 
              tanggal_transaksi='$tanggal_transaksi', total_harga=$total_harga, status='$status' 
              WHERE id=$id";
    
    if (mysqli_query($conn, $query)) {
        $message = "Data transaksi berhasil diupdate!";
        $message_type = "success";
    } else {
        $message = "Error: " . mysqli_error($conn);
        $message_type = "error";
    }
}

// Proses Hapus Transaksi
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM transaksi WHERE id=$id";
    
    if (mysqli_query($conn, $query)) {
        $message = "Data transaksi berhasil dihapus!";
        $message_type = "success";
    } else {
        $message = "Error: " . mysqli_error($conn);
        $message_type = "error";
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM transaksi WHERE id=$id";
    $result_edit = mysqli_query($conn, $query);
    $edit_data = mysqli_fetch_assoc($result_edit);
}

// Filter berdasarkan tanggal
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01');
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-d');

// Query untuk statistik laporan (hitung ulang setelah filter tanggal)
$query_total = "SELECT SUM(total_harga) as total FROM transaksi 
                WHERE status = 'selesai' 
                AND tanggal_transaksi BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
$result_total = mysqli_query($conn, $query_total);
$data_total = mysqli_fetch_assoc($result_total);
$total_penjualan = $data_total['total'] ?? 0;

$query_jumlah = "SELECT COUNT(*) as jumlah FROM transaksi 
                 WHERE status = 'selesai' 
                 AND tanggal_transaksi BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
$result_jumlah = mysqli_query($conn, $query_jumlah);
$data_jumlah = mysqli_fetch_assoc($result_jumlah);

$query_rata = "SELECT AVG(total_harga) as rata FROM transaksi 
               WHERE status = 'selesai' 
               AND tanggal_transaksi BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
$result_rata = mysqli_query($conn, $query_rata);
$data_rata = mysqli_fetch_assoc($result_rata);
$rata_rata = $data_rata['rata'] ?? 0;

// Query untuk laporan per bulan
$query_bulanan = "SELECT DATE_FORMAT(tanggal_transaksi, '%Y-%m') as bulan, 
                  COUNT(*) as jumlah_transaksi, 
                  SUM(total_harga) as total_penjualan 
                  FROM transaksi 
                  WHERE status = 'selesai' 
                  AND tanggal_transaksi BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                  GROUP BY DATE_FORMAT(tanggal_transaksi, '%Y-%m')
                  ORDER BY bulan DESC";
$result_bulanan = mysqli_query($conn, $query_bulanan);

// Query untuk laporan per customer
$query_customer = "SELECT c.nama, COUNT(t.id) as jumlah_transaksi, 
                   SUM(t.total_harga) as total_penjualan 
                   FROM transaksi t 
                   JOIN customer c ON t.customer_id = c.id 
                   WHERE t.status = 'selesai' 
                   AND t.tanggal_transaksi BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                   GROUP BY c.id, c.nama 
                   ORDER BY total_penjualan DESC 
                   LIMIT 10";
$result_customer = mysqli_query($conn, $query_customer);

// Query untuk detail transaksi (tampilkan semua status untuk bisa di-edit)
$query_detail = "SELECT t.*, c.nama as nama_customer 
                 FROM transaksi t 
                 JOIN customer c ON t.customer_id = c.id 
                 WHERE t.tanggal_transaksi BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                 ORDER BY t.tanggal_transaksi DESC";
$result_detail = mysqli_query($conn, $query_detail);

// Query untuk mendapatkan daftar customer (untuk form edit)
$query_customer = "SELECT * FROM customer ORDER BY nama";
$result_customer = mysqli_query($conn, $query_customer);

include 'header.php';
?>

<h1 class="page-title">Laporan Penjualan</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<!-- Form Edit Transaksi -->
<?php if ($edit_data): ?>
<div class="table-container" style="margin-bottom: 20px; background-color: #1c2128; border-left: 4px solid #d29922;">
    <h2 style="color: #c9d1d9;">Edit Transaksi</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
        
        <div class="form-group">
            <label>No Transaksi:</label>
            <input type="text" name="no_transaksi" required 
                   value="<?php echo $edit_data['no_transaksi']; ?>">
        </div>
        
        <div class="form-group">
            <label>Customer:</label>
            <select name="customer_id" required>
                <option value="">Pilih Customer</option>
                <?php
                mysqli_data_seek($result_customer, 0);
                while ($customer = mysqli_fetch_assoc($result_customer)) {
                    $selected = ($edit_data['customer_id'] == $customer['id']) ? 'selected' : '';
                    echo "<option value='" . $customer['id'] . "' $selected>" . $customer['nama'] . "</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Tanggal Transaksi:</label>
            <input type="date" name="tanggal_transaksi" required 
                   value="<?php echo $edit_data['tanggal_transaksi']; ?>">
        </div>
        
        <div class="form-group">
            <label>Total Harga:</label>
            <input type="number" name="total_harga" step="0.01" required 
                   value="<?php echo $edit_data['total_harga']; ?>">
        </div>
        
        <div class="form-group">
            <label>Status:</label>
            <select name="status" required>
                <option value="pending" <?php echo ($edit_data['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="selesai" <?php echo ($edit_data['status'] == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                <option value="batal" <?php echo ($edit_data['status'] == 'batal') ? 'selected' : ''; ?>>Batal</option>
            </select>
        </div>
        
        <button type="submit" name="edit" class="btn btn-warning">Update Transaksi</button>
        <a href="laporan_penjualan.php?tanggal_awal=<?php echo $tanggal_awal; ?>&tanggal_akhir=<?php echo $tanggal_akhir; ?>" class="btn btn-danger">Batal</a>
    </form>
</div>
<?php endif; ?>

<!-- Filter Tanggal -->
<div class="table-container" style="margin-bottom: 20px;">
    <h2>Filter Laporan</h2>
    <form method="GET" action="" style="display: flex; gap: 15px; align-items: end;">
        <div class="form-group" style="flex: 1;">
            <label>Tanggal Awal:</label>
            <input type="date" name="tanggal_awal" value="<?php echo $tanggal_awal; ?>" required>
        </div>
        <div class="form-group" style="flex: 1;">
            <label>Tanggal Akhir:</label>
            <input type="date" name="tanggal_akhir" value="<?php echo $tanggal_akhir; ?>" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="laporan_penjualan.php" class="btn btn-danger">Reset</a>
        </div>
    </form>
</div>

<!-- Statistik Laporan -->
<div class="stats-container" style="margin-bottom: 30px;">
    <div class="stat-card blue">
        <h3>Total Penjualan</h3>
        <div class="number">Rp <?php echo number_format($total_penjualan, 0, ',', '.'); ?></div>
    </div>
    
    <div class="stat-card green">
        <h3>Jumlah Transaksi</h3>
        <div class="number"><?php echo $data_jumlah['jumlah']; ?></div>
    </div>
    
    <div class="stat-card yellow">
        <h3>Rata-rata per Transaksi</h3>
        <div class="number">Rp <?php echo number_format($rata_rata, 0, ',', '.'); ?></div>
    </div>
</div>

<!-- Laporan Per Bulan -->
<div class="table-container" style="margin-bottom: 20px;">
    <h2>Laporan Penjualan Per Bulan</h2>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Jumlah Transaksi</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result_bulanan) > 0) {
                while ($row = mysqli_fetch_assoc($result_bulanan)) {
                    $bulan = date('F Y', strtotime($row['bulan'] . '-01'));
                    $jumlah_transaksi = isset($row['jumlah_transaksi']) ? $row['jumlah_transaksi'] : 0;
                    $total_penjualan = isset($row['total_penjualan']) ? $row['total_penjualan'] : 0;
                    echo "<tr>";
                    echo "<td>" . $bulan . "</td>";
                    echo "<td>" . $jumlah_transaksi . "</td>";
                    echo "<td>Rp " . number_format($total_penjualan, 0, ',', '.') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3' style='text-align: center;'>Tidak ada data untuk periode ini</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Laporan Per Customer -->
<div class="table-container" style="margin-bottom: 20px;">
    <h2>Top 10 Customer Terbaik</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Customer</th>
                <th>Jumlah Transaksi</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result_customer) > 0) {
                while ($row = mysqli_fetch_assoc($result_customer)) {
                    $jumlah_transaksi = isset($row['jumlah_transaksi']) ? $row['jumlah_transaksi'] : 0;
                    $total_penjualan = isset($row['total_penjualan']) ? $row['total_penjualan'] : 0;
                    echo "<tr>";
                    echo "<td>" . ($row['nama'] ?? '-') . "</td>";
                    echo "<td>" . $jumlah_transaksi . "</td>";
                    echo "<td>Rp " . number_format($total_penjualan, 0, ',', '.') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3' style='text-align: center;'>Tidak ada data untuk periode ini</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Detail Transaksi -->
<div class="table-container">
    <h2>Detail Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th>No Transaksi</th>
                <th>Customer</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result_detail) > 0) {
                while ($row = mysqli_fetch_assoc($result_detail)) {
                    $status_class = ($row['status'] == 'selesai') ? 'btn-success' : 
                                   (($row['status'] == 'pending') ? 'btn-warning' : 'btn-danger');
                    echo "<tr>";
                    echo "<td>" . $row['no_transaksi'] . "</td>";
                    echo "<td>" . $row['nama_customer'] . "</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['tanggal_transaksi'])) . "</td>";
                    echo "<td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                    echo "<td><span class='btn $status_class' style='padding: 5px 10px; font-size: 12px;'>" . ucfirst($row['status']) . "</span></td>";
                    echo "<td>";
                    echo "<a href='laporan_penjualan.php?edit=" . $row['id'] . "&tanggal_awal=" . $tanggal_awal . "&tanggal_akhir=" . $tanggal_akhir . "' class='btn btn-warning' style='padding: 5px 10px; font-size: 12px;'>Edit</a> ";
                    echo "<a href='laporan_penjualan.php?hapus=" . $row['id'] . "&tanggal_awal=" . $tanggal_awal . "&tanggal_akhir=" . $tanggal_akhir . "' class='btn btn-danger' style='padding: 5px 10px; font-size: 12px;' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align: center;'>Tidak ada data transaksi untuk periode ini</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>

