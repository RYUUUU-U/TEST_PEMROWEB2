<?php
$host = "sql205.infinityfree.com";
$user = "if0_40684561";
$pass = "testhosting62"; 
$db   = "if0_40684561_inventory";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>