<div class="lokasi">
	<a href="?ref=dapur">Dapur</a> &rarr; List Pesanan per Meja
</div>

<div class="judul-frame">
	<b>Pesanan Per Meja</b>
</div>


<form method="post" action="">
<div>
	<fieldset>
		Urutkan Order/Pesanan berdasarkan
		&nbsp;
		&nbsp;
		&nbsp;
		&nbsp;
		<select name="optacuan" id="optacuan">
			<option value="jam">Jam</option>
			<option value="menu">Menu</option>
			<option value="meja">Meja</option>
			<option value="jumlah">Jumlah</option>
		</select>
		&nbsp;
		&nbsp;
		&nbsp;
		&nbsp;
		<input type="submit" name="submit" value="Semua">
			<input type="submit" name="submit1" value="Sedang Proses">
				<input type="submit" name="submit2" value="Telah Siap">
	</fieldset>
</div>
</form>

<div style="width: 80%;margin: 0 auto;background-color: #fff;">
	<fieldset>
		<table width="100%" border="1" cellpadding="5" cellspacing="3" style="border-collapse: collapse;">
			<thead style="color: #fff;background:linear-gradient(#888,#000);">
				<tr>
					<th>#</th>
					<th>Nama Item</th>
					<th>Jumlah</th>
					<th>Meja</th>
					<th>Jam Pesan</th>
					<th>Jam Siap</th>
				</tr>
			</thead>
			<tbody>
				<?php
$acuan='d.waktu ASC';
$sisip="";

if (isset($_POST['submit'])) {
	switch ($_POST['optacuan']) {
		case 'menu':
			$acuan='d.produk_id ASC,d.waktu ASC';
			echo "<script type=\"text/javascript\">document.getElementById('optacuan').value=\"menu\";</script>";
			break;
		case 'meja':
			$acuan='p.meja_nomor ASC,d.waktu ASC';
			echo "<script type=\"text/javascript\">document.getElementById('optacuan').value=\"meja\";</script>";
			break;
		case 'jumlah':
			$acuan='d.jumlah ASC,d.waktu ASC';
			echo "<script type=\"text/javascript\">document.getElementById('optacuan').value=\"jumlah\";</script>";
			break;
		default:
			$acuan='d.waktu ASC';
			echo "<script type=\"text/javascript\">document.getElementById('optacuan').value=\"jam\";</script>";
			break;
	}
}

if (isset($_POST['submit1'])) {
	$sisip=" AND d.siap='BLM' ";
	switch ($_POST['optacuan']) {
		case 'menu':
			$acuan='d.produk_id ASC,d.waktu ASC';
			echo "<script type=\"text/javascript\">document.getElementById('optacuan').value=\"menu\";</script>";
			break;
		case 'meja':
			$acuan='p.meja_nomor ASC,d.waktu ASC';
			echo "<script type=\"text/javascript\">document.getElementById('optacuan').value=\"meja\";</script>";
			break;
		case 'jumlah':
			$acuan='d.jumlah ASC,d.waktu ASC';
			echo "<script type=\"text/javascript\">document.getElementById('optacuan').value=\"jumlah\";</script>";
			break;
		default:
			$acuan='d.waktu ASC';
			echo "<script type=\"text/javascript\">document.getElementById('optacuan').value=\"jam\";</script>";
			break;
	}
}

if (isset($_POST['submit2'])) {
	$sisip=" AND d.siap='SDH' ";
	switch ($_POST['optacuan']) {
		case 'menu':
			$acuan='d.produk_id ASC,d.waktu ASC';
			echo "<script type=\"text/javascript\">document.getElementById('optacuan').value=\"menu\";</script>";
			break;
		case 'meja':
			$acuan='p.meja_nomor ASC,d.waktu ASC';
			echo "<script type=\"text/javascript\">document.getElementById('optacuan').value=\"meja\";</script>";
			break;
		case 'jumlah':
			$acuan='d.jumlah ASC,d.waktu ASC';
			echo "<script type=\"text/javascript\">document.getElementById('optacuan').value=\"jumlah\";</script>";
			break;
		default:
			$acuan='d.waktu ASC';
			echo "<script type=\"text/javascript\">document.getElementById('optacuan').value=\"jam\";</script>";
			break;
	}
}


$sql="
SELECT DISTINCT d.produk_id, d.produk_nama, d.jumlah, TIME(d.waktu),p.meja_nomor,p.waktu,TIME(d.waktu_siap)
FROM pesanan_detail AS d
INNER JOIN pesanan AS p ON p.id=d.pesanan_id
WHERE p.status='BUKA' AND p.kirim='SDH' $sisip
ORDER BY $acuan
;";

$i=0;
$rs=$con->query($sql);
if ($rs) {
	while ($row=$rs->fetch_row()) {
		$i++;
		echo"
	<tr style=\"height:20px;\">
	<td align=\"center\">$i</td>
	<td>$row[1]</td>
	<td align=\"right\">$row[2]</td>
	<td align=\"center\">$row[4]</td>
	<td align=\"center\">$row[3]</td>
	<td align=\"center\">$row[6]</td>
	</tr>
		";
	}
}


				?>
			</tbody>
		</table>
	</fieldset>
</div>
