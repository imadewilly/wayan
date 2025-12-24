<?php
// Halaman Data Transaksi
require_once 'config.php';

$message = '';
$message_type = '';

// Proses Tambah Transaksi
if (isset($_POST['tambah'])) {
    $no_transaksi = mysqli_real_escape_string($conn, $_POST['no_transaksi']);
    $customer_id = $_POST['customer_id'];
    $tanggal_transaksi = $_POST['tanggal_transaksi'];
    $total_harga = $_POST['total_harga'];
    $status = $_POST['status'];
    
    $query = "INSERT INTO transaksi (no_transaksi, customer_id, tanggal_transaksi, total_harga, status) 
              VALUES ('$no_transaksi', $customer_id, '$tanggal_transaksi', $total_harga, '$status')";
    
    if (mysqli_query($conn, $query)) {
        $message = "Data transaksi berhasil ditambahkan!";
        $message_type = "success";
    } else {
        $message = "Error: " . mysqli_error($conn);
        $message_type = "error";
    }
}

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
    $result = mysqli_query($conn, $query);
    $edit_data = mysqli_fetch_assoc($result);
}

// Query untuk mendapatkan daftar customer
$query_customer = "SELECT * FROM customer ORDER BY nama";
$result_customer = mysqli_query($conn, $query_customer);

// Query untuk menampilkan semua transaksi dengan nama customer
$query = "SELECT t.*, c.nama as nama_customer 
          FROM transaksi t 
          JOIN customer c ON t.customer_id = c.id 
          ORDER BY t.tanggal_transaksi DESC";
$result = mysqli_query($conn, $query);

include 'header.php';
?>

<h1 class="page-title">Data Transaksi</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<!-- Form Tambah/Edit Transaksi -->
<div class="table-container" style="margin-bottom: 20px;">
    <h2><?php echo $edit_data ? 'Edit Transaksi' : 'Tambah Transaksi Baru'; ?></h2>
    <form method="POST" action="">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>No Transaksi:</label>
            <input type="text" name="no_transaksi" required 
                   value="<?php echo $edit_data ? $edit_data['no_transaksi'] : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Customer:</label>
            <select name="customer_id" required>
                <option value="">Pilih Customer</option>
                <?php
                while ($customer = mysqli_fetch_assoc($result_customer)) {
                    $selected = ($edit_data && $edit_data['customer_id'] == $customer['id']) ? 'selected' : '';
                    echo "<option value='" . $customer['id'] . "' $selected>" . $customer['nama'] . "</option>";
                }
                mysqli_data_seek($result_customer, 0); // Reset pointer
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Tanggal Transaksi:</label>
            <input type="date" name="tanggal_transaksi" required 
                   value="<?php echo $edit_data ? $edit_data['tanggal_transaksi'] : date('Y-m-d'); ?>">
        </div>
        
        <div class="form-group">
            <label>Total Harga:</label>
            <input type="number" name="total_harga" step="0.01" required 
                   value="<?php echo $edit_data ? $edit_data['total_harga'] : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Status:</label>
            <select name="status" required>
                <option value="pending" <?php echo ($edit_data && $edit_data['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="selesai" <?php echo ($edit_data && $edit_data['status'] == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                <option value="batal" <?php echo ($edit_data && $edit_data['status'] == 'batal') ? 'selected' : ''; ?>>Batal</option>
            </select>
        </div>
        
        <?php if ($edit_data): ?>
            <button type="submit" name="edit" class="btn btn-warning">Update Transaksi</button>
            <a href="data_transaksi.php" class="btn btn-danger">Batal</a>
        <?php else: ?>
            <button type="submit" name="tambah" class="btn btn-primary">Tambah Transaksi</button>
        <?php endif; ?>
    </form>
</div>

<!-- Tabel Data Transaksi -->
<div class="table-container">
    <h2>Daftar Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
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
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $status_class = ($row['status'] == 'selesai') ? 'btn-success' : 
                                   (($row['status'] == 'pending') ? 'btn-warning' : 'btn-danger');
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['no_transaksi'] . "</td>";
                    echo "<td>" . $row['nama_customer'] . "</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['tanggal_transaksi'])) . "</td>";
                    echo "<td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                    echo "<td><span class='btn $status_class' style='padding: 5px 10px; font-size: 12px;'>" . ucfirst($row['status']) . "</span></td>";
                    echo "<td>";
                    echo "<a href='data_transaksi.php?edit=" . $row['id'] . "' class='btn btn-warning' style='padding: 5px 10px; font-size: 12px;'>Edit</a> ";
                    echo "<a href='data_transaksi.php?hapus=" . $row['id'] . "' class='btn btn-danger' style='padding: 5px 10px; font-size: 12px;' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' style='text-align: center;'>Tidak ada data transaksi</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>

