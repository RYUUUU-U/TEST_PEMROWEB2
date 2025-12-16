<?php
include 'koneksi.php';

$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : "";
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : "";

$where_clause = "";
$label_waktu = "Semua Waktu";
if(!empty($tgl_mulai) && !empty($tgl_selesai)){
    $where_clause = "WHERE DATE(barang_masuk.tanggal_masuk) BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
    $label_waktu = "Periode: $tgl_mulai s/d $tgl_selesai";
}

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Barang_Masuk.xls");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Export Data Barang Masuk</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        .judul { border: 0; text-align: center; font-weight: bold; font-size: 18px; background: #fff; }
        .header-tabel { background-color: #f2f2f2; text-align: center; }
    </style>
</head>
<body>
	<table>
		<thead>
            <tr><th colspan="5" class="judul">LAPORAN BARANG MASUK (RESTOCK)</th></tr>
            <tr><th colspan="5" style="border:0; text-align:center;"><?php echo $label_waktu; ?></th></tr>
            <tr><th colspan="5" style="border:0;"></th></tr>
			<tr>
				<th class="header-tabel">No</th>
				<th class="header-tabel">Waktu Masuk</th>
				<th class="header-tabel">Nama Barang</th>
				<th class="header-tabel">Supplier</th>
				<th class="header-tabel">Jumlah Masuk</th>
			</tr>
		</thead>
		<tbody>
			<?php 
            $data = mysqli_query($koneksi, "SELECT barang_masuk.*, barang.nama_barang, supplier.nama_supplier 
                                            FROM barang_masuk 
                                            LEFT JOIN barang ON barang_masuk.id_barang = barang.id_barang 
                                            LEFT JOIN supplier ON barang_masuk.id_supplier = supplier.id_supplier
                                            $where_clause 
                                            ORDER BY barang_masuk.tanggal_masuk DESC");
			$no = 1;
			while($d = mysqli_fetch_array($data)){
			?>
			<tr>
				<td style="text-align: center;"><?php echo $no++; ?></td>
				<td style="text-align: center;"><?php echo $d['tanggal_masuk']; ?></td>
				<td><?php echo $d['nama_barang']; ?></td>
				<td><?php echo $d['nama_supplier']; ?></td>
				<td style="text-align: center;"><?php echo $d['jumlah_masuk']; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</body>
</html>