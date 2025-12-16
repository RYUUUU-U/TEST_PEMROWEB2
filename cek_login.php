<?php 
// Mengaktifkan session php
session_start();
 
// Menghubungkan dengan koneksi
include 'koneksi.php';
 
// Menangkap data yang dikirim dari form
// Tambahkan mysqli_real_escape_string untuk keamanan dasar dari SQL Injection
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = md5($_POST['password']); 
 
// Menyeleksi data user dengan username dan password yang sesuai
$login = mysqli_query($koneksi,"SELECT * FROM users WHERE username='$username' AND password='$password'");

// Menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($login);
 
if($cek > 0){
    $data = mysqli_fetch_assoc($login);
 
    // --- UPDATE PENTING DISINI ---
    // Kita simpan data ke session SEKALI saja di sini agar lebih rapi
    // dan memastikan 'nama_lengkap' tersimpan untuk semua role.
    
    $_SESSION['id_user'] = $data['id_user'];       // Menyimpan ID User (berguna untuk fitur profil nanti)
    $_SESSION['nama_lengkap'] = $data['nama_lengkap']; // SOLUSI ERROR HEADER: Menyimpan Nama Lengkap
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $data['role'];
    $_SESSION['status'] = "login";

    // Cek Role dan Alihkan Halaman
    if($data['role'] == "admin"){
        // Alihkan ke halaman dashboard admin
        header("location:dashboard_admin.php");
 
    }else if($data['role'] == "kasir"){
        // Alihkan ke halaman dashboard kasir
        header("location:dashboard_kasir.php");
 
    }else if($data['role'] == "owner"){
        // Alihkan ke halaman dashboard owner
        header("location:dashboard_owner.php");
 
    }else{
        // Jika role tidak dikenali
        header("location:index.php?pesan=gagal");
    }    
}else{
    // Jika username/password salah
    header("location:index.php?pesan=gagal");
}
?>