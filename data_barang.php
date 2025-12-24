<?php
// Halaman Data Barang
require_once 'config.php';

$message = '';
$message_type = '';

// Proses Tambah Barang
if (isset($_POST['tambah'])) {
    $kode_barang = mysqli_real_escape_string($conn, $_POST['kode_barang']);
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    
    // Cek apakah kode barang sudah ada
    $cek = "SELECT * FROM barang WHERE kode_barang = '$kode_barang'";
    $result_cek = mysqli_query($conn, $cek);
    
    if (mysqli_num_rows($result_cek) > 0) {
        $message = "Kode barang sudah ada! Gunakan kode yang berbeda.";
        $message_type = "error";
    } else {
        $query = "INSERT INTO barang (kode_barang, nama_barang, harga, stok, kategori) 
                  VALUES ('$kode_barang', '$nama_barang', $harga, $stok, '$kategori')";
        
        if (mysqli_query($conn, $query)) {
            $message = "Data barang berhasil ditambahkan!";
            $message_type = "success";
        } else {
            $message = "Error: " . mysqli_error($conn);
            $message_type = "error";
        }
    }
}

// Proses Edit Barang
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $kode_barang = mysqli_real_escape_string($conn, $_POST['kode_barang']);
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    
    // Cek apakah kode barang sudah ada (kecuali untuk barang yang sedang diedit)
    $cek = "SELECT * FROM barang WHERE kode_barang = '$kode_barang' AND id != $id";
    $result_cek = mysqli_query($conn, $cek);
    
    if (mysqli_num_rows($result_cek) > 0) {
        $message = "Kode barang sudah ada! Gunakan kode yang berbeda.";
        $message_type = "error";
    } else {
        $query = "UPDATE barang SET 
                  kode_barang='$kode_barang', nama_barang='$nama_barang', 
                  harga=$harga, stok=$stok, kategori='$kategori' 
                  WHERE id=$id";
        
        if (mysqli_query($conn, $query)) {
            $message = "Data barang berhasil diupdate!";
            $message_type = "success";
        } else {
            $message = "Error: " . mysqli_error($conn);
            $message_type = "error";
        }
    }
}

// Proses Hapus Barang
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Cek apakah barang digunakan di transaksi
    $cek_transaksi = "SELECT * FROM detail_transaksi WHERE barang_id = $id";
    $result_cek = mysqli_query($conn, $cek_transaksi);
    
    if (mysqli_num_rows($result_cek) > 0) {
        $message = "Barang tidak dapat dihapus karena sudah digunakan dalam transaksi!";
        $message_type = "error";
    } else {
        $query = "DELETE FROM barang WHERE id=$id";
        
        if (mysqli_query($conn, $query)) {
            $message = "Data barang berhasil dihapus!";
            $message_type = "success";
        } else {
            $message = "Error: " . mysqli_error($conn);
            $message_type = "error";
        }
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM barang WHERE id=$id";
    $result = mysqli_query($conn, $query);
    $edit_data = mysqli_fetch_assoc($result);
}

// Query untuk menampilkan semua barang
$query = "SELECT * FROM barang ORDER BY id DESC";
$result = mysqli_query($conn, $query);

include 'header.php';
?>

<h1 class="page-title">Data Barang</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<!-- Form Tambah/Edit Barang -->
<div class="table-container" style="margin-bottom: 20px;">
    <h2><?php echo $edit_data ? 'Edit Barang' : 'Tambah Barang Baru'; ?></h2>
    <form method="POST" action="">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>Kode Barang:</label>
            <input type="text" name="kode_barang" required 
                   value="<?php echo $edit_data ? $edit_data['kode_barang'] : ''; ?>"
                   placeholder="Contoh: BRG001">
        </div>
        
        <div class="form-group">
            <label>Nama Barang:</label>
            <input type="text" name="nama_barang" required 
                   value="<?php echo $edit_data ? $edit_data['nama_barang'] : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Harga:</label>
            <input type="number" name="harga" step="0.01" required 
                   value="<?php echo $edit_data ? $edit_data['harga'] : ''; ?>"
                   placeholder="Contoh: 1000000">
        </div>
        
        <div class="form-group">
            <label>Stok:</label>
            <input type="number" name="stok" required 
                   value="<?php echo $edit_data ? $edit_data['stok'] : '0'; ?>"
                   min="0">
        </div>
        
        <div class="form-group">
            <label>Kategori:</label>
            <input type="text" name="kategori" 
                   value="<?php echo $edit_data ? $edit_data['kategori'] : ''; ?>"
                   placeholder="Contoh: Elektronik, Aksesoris">
        </div>
        
        <?php if ($edit_data): ?>
            <button type="submit" name="edit" class="btn btn-warning">Update Barang</button>
            <a href="data_barang.php" class="btn btn-danger">Batal</a>
        <?php else: ?>
            <button type="submit" name="tambah" class="btn btn-primary">Tambah Barang</button>
        <?php endif; ?>
    </form>
</div>

<!-- Tabel Data Barang -->
<div class="table-container">
    <h2>Daftar Barang</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $stok_class = ($row['stok'] <= 5) ? 'style="color: red; font-weight: bold;"' : '';
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['kode_barang'] . "</td>";
                    echo "<td>" . $row['nama_barang'] . "</td>";
                    echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                    echo "<td $stok_class>" . $row['stok'] . "</td>";
                    echo "<td>" . $row['kategori'] . "</td>";
                    echo "<td>";
                    echo "<a href='data_barang.php?edit=" . $row['id'] . "' class='btn btn-warning' style='padding: 5px 10px; font-size: 12px;'>Edit</a> ";
                    echo "<a href='data_barang.php?hapus=" . $row['id'] . "' class='btn btn-danger' style='padding: 5px 10px; font-size: 12px;' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' style='text-align: center;'>Tidak ada data barang</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>

