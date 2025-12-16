<?php
include 'koneksi.php';

// Ambil ID yang mau dihapus
$id = $_GET['id'];

// Hapus query
$hapus = mysqli_query($koneksi, "DELETE FROM barang WHERE id_barang='$id'");

if($hapus){
    header("location:barang.php");
} else {
    echo "<script>alert('Gagal Menghapus Data!'); window.location='barang.php';</script>";
}
?>