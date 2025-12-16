<?php
include 'koneksi.php';

// Tangkap Filter Tanggal dari URL
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : "";
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : "";

// Logika Filter Query
$where_clause = "";
$label_waktu = "Semua Waktu";
if(!empty($tgl_mulai) && !empty($tgl_selesai)){
    $where_clause = "WHERE DATE(barang_keluar.tanggal_keluar) BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
    $label_waktu = "Periode: $tgl_mulai s/d $tgl_selesai";
}

// HEADER AGAR DIBACA SEBAGAI EXCEL
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Penjualan.xls");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Export Data Ke Excel</title>
    <style>
        /* CSS Sederhana agar Rapi */
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        
        /* Class khusus untuk judul agar tidak ada border */
        .judul {
            border: 0px solid white; /* Hilangkan garis border pada judul */
            text-align: center;      /* Rata Tengah */
            font-weight: bold;       /* Tebal */
            font-size: 18px;         /* Ukuran Font Besar */
            background-color: #ffffff;
        }
        .sub-judul {
            border: 0px solid white;
            text-align: center;
            font-size: 14px;
            background-color: #ffffff;
            margin-bottom: 20px;
        }
        .header-tabel {
            background-color: #f2f2f2; /* Warna abu-abu untuk header kolom */
            text-align: center;
        }
    </style>
</head>
<body>

	<table>
		<thead>
            <tr>
                <th colspan="5" class="judul">LAPORAN PENJUALAN BARANG</th>
            </tr>
            
            <tr>
                <th colspan="5" class="sub-judul"><?php echo $label_waktu; ?></th>
            </tr>

            <tr>
                <th colspan="5" style="border: 0;"></th>
            </tr>

            <tr>
				<th class="header-tabel">No</th>
				<th class="header-tabel">Waktu Transaksi</th>
				<th class="header-tabel">Nama Barang</th>
				<th class="header-tabel">Jumlah Keluar</th>
				<th class="header-tabel">Total Harga</th>
			</tr>
		</thead>
		<tbody>
			<?php 
            // Query Data
            $data = mysqli_query($koneksi, "SELECT barang_keluar.*, barang.nama_barang 
                                            FROM barang_keluar 
                                            LEFT JOIN barang ON barang_keluar.id_barang = barang.id_barang 
                                            $where_clause 
                                            ORDER BY barang_keluar.tanggal_keluar DESC");
			$no = 1;
            $grand_total = 0;
			while($d = mysqli_fetch_array($data)){
                $grand_total += $d['total_harga'];
			?>
			<tr>
				<td style="text-align: center;"><?php echo $no++; ?></td>
				<td style="text-align: center;"><?php echo $d['tanggal_keluar']; ?></td>
				<td><?php echo $d['nama_barang']; ?></td>
				<td style="text-align: center;"><?php echo $d['jumlah_keluar']; ?></td>
				<td style="text-align: right;">Rp <?php echo number_format($d['total_harga']); ?></td>
			</tr>
			<?php } ?>
            
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold; background-color: #f2f2f2;">TOTAL PENDAPATAN</td>
                <td style="text-align: right; font-weight: bold; background-color: #f2f2f2;">Rp <?php echo number_format($grand_total); ?></td>
            </tr>
		</tbody>
	</table>

</body>
</html>