<?php
include 'koneksi.php';
$id = $_GET['id'];

$hapus = mysqli_query($koneksi, "DELETE FROM supplier WHERE id_supplier='$id'");

if($hapus){
    header("location:supplier.php");
} else {
    echo "<script>alert('Gagal Menghapus!'); window.location='supplier.php';</script>";
}
?>