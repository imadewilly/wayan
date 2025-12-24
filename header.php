<?php
// File Header untuk Navigasi
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Wayan Jaya Abadi - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>PT Wayan Jaya Abadi</h1>
        <div class="header-nav">
            <a href="index.php">Home</a>
            <a href="#">Contact</a>
        </div>
    </div>

    <!-- Container -->
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul>
                <li class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                    <a href="index.php">Dashboard</a>
                </li>
                <li class="<?php echo ($current_page == 'data_barang.php') ? 'active' : ''; ?>">
                    <a href="data_barang.php">Data Barang</a>
                </li>
                <li class="<?php echo ($current_page == 'data_customer.php') ? 'active' : ''; ?>">
                    <a href="data_customer.php">Data Customer</a>
                </li>
                <li class="<?php echo ($current_page == 'data_transaksi.php') ? 'active' : ''; ?>">
                    <a href="data_transaksi.php">Data Transaksi</a>
                </li>
                <li class="<?php echo ($current_page == 'laporan_penjualan.php') ? 'active' : ''; ?>">
                    <a href="laporan_penjualan.php">Laporan Penjualan</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="breadcrumb">
                <a href="index.php">Home</a> / 
                <?php
                if ($current_page == 'index.php') {
                    echo 'Dashboard';
                } elseif ($current_page == 'data_barang.php') {
                    echo 'Data Barang';
                } elseif ($current_page == 'data_customer.php') {
                    echo 'Data Customer';
                } elseif ($current_page == 'data_transaksi.php') {
                    echo 'Data Transaksi';
                } elseif ($current_page == 'laporan_penjualan.php') {
                    echo 'Laporan Penjualan';
                }
                ?>
            </div>

