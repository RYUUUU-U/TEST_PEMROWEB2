<?php 
include 'koneksi.php';

// Tangkap Filter
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : "";
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : "";

$where_clause = "";
$label = "Semua Data";
if(!empty($tgl_mulai) && !empty($tgl_selesai)){
    $where_clause = "WHERE DATE(barang_keluar.tanggal_keluar) BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
    $label = "Periode: ".date('d-m-Y', strtotime($tgl_mulai))." s/d ".date('d-m-Y', strtotime($tgl_selesai));
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Cetak Laporan Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS Khusus Cetak */
        @media print {
            .no-print { display: none; }
        }
        body { font-family: Arial, sans-serif; }
        .tanda-tangan { margin-top: 50px; text-align: right; margin-right: 50px; }
    </style>
</head>
<body onload="window.print()">

	<div class="container mt-4">
        <div class="text-center mb-4">
            <h3>BENGKEL MOTOR MAJU JAYA</h3>
            <p>Jl. Contoh Alamat Bengkel No. 123, Kota Besar</p>
            <hr>
            <h4>LAPORAN TRANSAKSI PENJUALAN</h4>
            <span class="text-muted"><?php echo $label; ?></span>
        </div>

		<table class="table table-bordered table-striped">
			<thead class="table-dark">
				<tr>
					<th width="5%">No</th>
					<th>Waktu Transaksi</th>
					<th>Nama Barang</th>
					<th class="text-center">Qty</th>
					<th class="text-end">Total Harga</th>
				</tr>
			</thead>
			<tbody>
				<?php 
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
					<td><?php echo $no++; ?></td>
					<td><?php echo date('d/m/Y H:i', strtotime($d['tanggal_keluar'])); ?></td>
					<td><?php echo $d['nama_barang']; ?></td>
					<td class="text-center"><?php echo $d['jumlah_keluar']; ?></td>
					<td class="text-end">Rp <?php echo number_format($d['total_harga']); ?></td>
				</tr>
				<?php } ?>
                <tr>
                    <td colspan="4" class="text-end fw-bold">TOTAL PENDAPATAN</td>
                    <td class="text-end fw-bold bg-light">Rp <?php echo number_format($grand_total); ?></td>
                </tr>
			</tbody>
		</table>

        <div class="tanda-tangan">
            <p>Dicetak pada: <?php echo date('d F Y'); ?></p>
            <br><br><br>
            <p><u>( Petugas Admin / Kasir )</u></p>
        </div>
	</div>

    </body>
</html>