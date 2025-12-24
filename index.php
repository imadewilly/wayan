<?php
// Halaman Dashboard
require_once 'config.php';

// Query untuk mendapatkan statistik
$query_barang = "SELECT COUNT(*) as total FROM barang";
$result_barang = mysqli_query($conn, $query_barang);
$data_barang = mysqli_fetch_assoc($result_barang);

$query_customer = "SELECT COUNT(*) as total FROM customer";
$result_customer = mysqli_query($conn, $query_customer);
$data_customer = mysqli_fetch_assoc($result_customer);

$query_transaksi = "SELECT COUNT(*) as total FROM transaksi";
$result_transaksi = mysqli_query($conn, $query_transaksi);
$data_transaksi = mysqli_fetch_assoc($result_transaksi);

$query_total_penjualan = "SELECT SUM(total_harga) as total FROM transaksi WHERE status = 'selesai'";
$result_total = mysqli_query($conn, $query_total_penjualan);
$data_total = mysqli_fetch_assoc($result_total);
$total_penjualan = $data_total['total'] ?? 0;

include 'header.php';
?>

<h1 class="page-title">Dashboard</h1>

<!-- Statistik Cards -->
<div class="stats-container">
    <div class="stat-card blue">
        <h3>Data Jumlah Barang</h3>
        <div class="number"><?php echo $data_barang['total']; ?></div>
        <a href="data_barang.php" style="color: #3498db; text-decoration: none;">More info →</a>
    </div>
    
    <div class="stat-card green">
        <h3>Total Customer</h3>
        <div class="number"><?php echo $data_customer['total']; ?></div>
        <a href="data_customer.php" style="color: #27ae60; text-decoration: none;">More info →</a>
    </div>
    
    <div class="stat-card yellow">
        <h3>Total Transaksi</h3>
        <div class="number"><?php echo $data_transaksi['total']; ?></div>
        <a href="data_transaksi.php" style="color: #f39c12; text-decoration: none;">More info →</a>
    </div>
    
    <div class="stat-card red">
        <h3>Total Penjualan</h3>
        <div class="number">Rp <?php echo number_format($total_penjualan, 0, ',', '.'); ?></div>
        <a href="laporan_penjualan.php" style="color: #e74c3c; text-decoration: none;">More info →</a>
    </div>
</div>

<!-- Tabel Transaksi Terbaru -->
<div class="table-container">
    <h2 style="margin-bottom: 15px;">Transaksi Terbaru</h2>
    <table>
        <thead>
            <tr>
                <th>No Transaksi</th>
                <th>Customer</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT t.*, c.nama as nama_customer 
                      FROM transaksi t 
                      JOIN customer c ON t.customer_id = c.id 
                      ORDER BY t.tanggal_transaksi DESC 
                      LIMIT 5";
            $result = mysqli_query($conn, $query);
            
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $status_class = ($row['status'] == 'selesai') ? 'btn-success' : 'btn-warning';
                    echo "<tr>";
                    echo "<td>" . $row['no_transaksi'] . "</td>";
                    echo "<td>" . $row['nama_customer'] . "</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['tanggal_transaksi'])) . "</td>";
                    echo "<td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                    echo "<td><span class='btn $status_class' style='padding: 5px 10px; font-size: 12px;'>" . ucfirst($row['status']) . "</span></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align: center;'>Tidak ada data transaksi</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>

