<?php 
include 'koneksi.php';

$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : "";
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : "";

$where_clause = "";
$label = "Semua Data";
if(!empty($tgl_mulai) && !empty($tgl_selesai)){
    $where_clause = "WHERE DATE(barang_masuk.tanggal_masuk) BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
    $label = "Periode: ".date('d-m-Y', strtotime($tgl_mulai))." s/d ".date('d-m-Y', strtotime($tgl_selesai));
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Cetak Laporan Barang Masuk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>@media print { .no-print { display: none; } } body { font-family: Arial; }</style>
</head>
<body onload="window.print()">
	<div class="container mt-4">
        <div class="text-center mb-4">
            <h3>BENGKEL MOTOR MAJU JAYA</h3>
            <hr>
            <h4>LAPORAN BARANG MASUK (RESTOCK)</h4>
            <span class="text-muted"><?php echo $label; ?></span>
        </div>
		<table class="table table-bordered table-striped">
			<thead class="table-dark">
				<tr>
					<th width="5%">No</th>
					<th>Waktu Masuk</th>
					<th>Nama Barang</th>
					<th>Supplier</th>
					<th class="text-center">Jumlah</th>
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
					<td><?php echo $no++; ?></td>
					<td><?php echo date('d/m/Y H:i', strtotime($d['tanggal_masuk'])); ?></td>
					<td><?php echo $d['nama_barang']; ?></td>
					<td><?php echo $d['nama_supplier']; ?></td>
					<td class="text-center"><?php echo $d['jumlah_masuk']; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</body>
</html>