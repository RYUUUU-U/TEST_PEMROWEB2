<?php 
include 'koneksi.php';
session_start();

// Cek hanya admin
if($_SESSION['role'] != 'admin'){
    echo "<script>alert('Akses Ditolak'); window.location='index.php';</script>";
    exit;
}

$id = $_GET['id'];

// 1. Ambil data barang masuk yang akan dihapus (untuk tahu jumlahnya)
$data = mysqli_query($koneksi, "SELECT * FROM barang_masuk WHERE id_masuk='$id'");
$row = mysqli_fetch_assoc($data);

$id_barang = $row['id_barang'];
$jumlah_yang_dibatalkan = $row['jumlah_masuk'];

// 2. Kurangi stok di tabel master barang (Karena batal masuk, stok harus ditarik kembali)
// Pastikan stok tidak minus (opsional)
$update_stok = mysqli_query($koneksi, "UPDATE barang SET stok = stok - $jumlah_yang_dibatalkan WHERE id_barang='$id_barang'");

// 3. Hapus data transaksinya
if($update_stok){
    mysqli_query($koneksi, "DELETE FROM barang_masuk WHERE id_masuk='$id'");
    echo "<script>alert('Data berhasil dihapus. Stok barang telah dikembalikan.'); window.location='barang_masuk.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data.'); window.location='barang_masuk.php';</script>";
}
?>