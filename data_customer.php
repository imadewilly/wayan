<?php
// Halaman Data Customer
require_once 'config.php';

$message = '';
$message_type = '';

// Proses Tambah Customer
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $tanggal_daftar = $_POST['tanggal_daftar'];
    
    $query = "INSERT INTO customer (nama, email, telepon, alamat, tanggal_daftar) 
              VALUES ('$nama', '$email', '$telepon', '$alamat', '$tanggal_daftar')";
    
    if (mysqli_query($conn, $query)) {
        $message = "Data customer berhasil ditambahkan!";
        $message_type = "success";
    } else {
        $message = "Error: " . mysqli_error($conn);
        $message_type = "error";
    }
}

// Proses Edit Customer
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $tanggal_daftar = $_POST['tanggal_daftar'];
    
    $query = "UPDATE customer SET 
              nama='$nama', email='$email', telepon='$telepon', 
              alamat='$alamat', tanggal_daftar='$tanggal_daftar' 
              WHERE id=$id";
    
    if (mysqli_query($conn, $query)) {
        $message = "Data customer berhasil diupdate!";
        $message_type = "success";
    } else {
        $message = "Error: " . mysqli_error($conn);
        $message_type = "error";
    }
}

// Proses Hapus Customer
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM customer WHERE id=$id";
    
    if (mysqli_query($conn, $query)) {
        $message = "Data customer berhasil dihapus!";
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
    $query = "SELECT * FROM customer WHERE id=$id";
    $result = mysqli_query($conn, $query);
    $edit_data = mysqli_fetch_assoc($result);
}

// Query untuk menampilkan semua customer
$query = "SELECT * FROM customer ORDER BY id DESC";
$result = mysqli_query($conn, $query);

include 'header.php';
?>

<h1 class="page-title">Data Customer</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<!-- Form Tambah/Edit Customer -->
<div class="table-container" style="margin-bottom: 20px;">
    <h2><?php echo $edit_data ? 'Edit Customer' : 'Tambah Customer Baru'; ?></h2>
    <form method="POST" action="">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>Nama Customer:</label>
            <input type="text" name="nama" required 
                   value="<?php echo $edit_data ? $edit_data['nama'] : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" 
                   value="<?php echo $edit_data ? $edit_data['email'] : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Telepon:</label>
            <input type="text" name="telepon" 
                   value="<?php echo $edit_data ? $edit_data['telepon'] : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Alamat:</label>
            <textarea name="alamat"><?php echo $edit_data ? $edit_data['alamat'] : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Tanggal Daftar:</label>
            <input type="date" name="tanggal_daftar" required 
                   value="<?php echo $edit_data ? $edit_data['tanggal_daftar'] : date('Y-m-d'); ?>">
        </div>
        
        <?php if ($edit_data): ?>
            <button type="submit" name="edit" class="btn btn-warning">Update Customer</button>
            <a href="data_customer.php" class="btn btn-danger">Batal</a>
        <?php else: ?>
            <button type="submit" name="tambah" class="btn btn-primary">Tambah Customer</button>
        <?php endif; ?>
    </form>
</div>

<!-- Tabel Data Customer -->
<div class="table-container">
    <h2>Daftar Customer</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Tanggal Daftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nama'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['telepon'] . "</td>";
                    echo "<td>" . substr($row['alamat'], 0, 50) . "...</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['tanggal_daftar'])) . "</td>";
                    echo "<td>";
                    echo "<a href='data_customer.php?edit=" . $row['id'] . "' class='btn btn-warning' style='padding: 5px 10px; font-size: 12px;'>Edit</a> ";
                    echo "<a href='data_customer.php?hapus=" . $row['id'] . "' class='btn btn-danger' style='padding: 5px 10px; font-size: 12px;' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' style='text-align: center;'>Tidak ada data customer</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>

