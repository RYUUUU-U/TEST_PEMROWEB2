<?php
include 'koneksi.php';
$id = $_GET['id'];
// PERBAIKAN: Delete from 'users'
$hapus = mysqli_query($koneksi, "DELETE FROM users WHERE id_user='$id'");

if($hapus){
    header("location:user.php");
} else {
    echo "<script>alert('Gagal Menghapus!'); window.location='user.php';</script>";
}
?>