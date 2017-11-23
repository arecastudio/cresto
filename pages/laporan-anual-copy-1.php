<?php
lock_page($con,"laporan_harian",$user_name,$keys);
?>


<?php
$jenis="Harian";
$jenis_laporan='detail';

if($isBulan==1)$jenis="Bulanan";

?>
<div class="lokasi">
	<a href="?ref=laporan">Laporan</a> &rarr; <?php echo $jenis;?>
</div>

<div class="judul-frame">
	<b>Laporan Pemasukan <?php echo $jenis;?></b>
</div>

<div style="margin-top:5px;margin-bottom:5px;">
	<form id="f1" action="" method="post">
		<input type="text" id="datepicker1" name="datepicker1" class="datepicker" size="12" placeholder="Tanggal" readonly/>
		<?php
if($isBulan==1) echo" s/d <input type=\"text\" id=\"datepicker2\" name=\"datepicker2\" class=\"datepicker\" size=\"12\" placeholder=\"Tanggal Akhir\" readonly/>";
		?>
		<select id="optjenisrep" name="optjenisrep">
			<option value="">Jenis Laporan</option>
			<option value="detail">DETAIL</option>
			<option value="rekap">REKAP</option>
		</select>&nbsp;&nbsp;&nbsp;
		<input type="submit" name="submit" value="Proses" style="width:150px;font-size:18px;color:blue;" />
	</form>
</div>

<div class="data-view-besar">
	<table width="100%" border="1" name="laporan-anual" id="laporan-anual" cellpadding="5" cellspacing="1">
<?php
if(isset($_POST['submit']) && strlen($_POST['datepicker1'])>0 && $_POST['optjenisrep']!="" ){
	$i=1;
	$tgl1=$_POST['datepicker1'];
	$jenis_laporan=$_POST['optjenisrep'];
	$sisip_sql="AND DATE(fs.tanggal)='$tgl1'";
	echo "<script type=\"text/javascript\">document.getElementById('datepicker1').value=\"$tgl1\";</script>";
	echo "<script type=\"text/javascript\">document.getElementById('optjenisrep').value=\"$jenis_laporan\";</script>";
	if ($isBulan==1){
		$tgl2=$_POST['datepicker2'];
		$sisip_sql="AND (DATE(fs.tanggal) BETWEEN '$tgl1' AND '$tgl2')";
		echo "<script type=\"text/javascript\">document.getElementById('datepicker2').value=\"$tgl2\";</script>";
	}

	$meja_kat_tarif=0;
	$sql_meja_tarif="SELECT SUM(fs.meja_kat_tarif) FROM faktur_stat AS fs WHERE 1 $sisip_sql;";
	if ($rs1=$con->query($sql_meja_tarif)) {
		if ($row1=$rs1->fetch_row()) {
			$meja_kat_tarif=$row1[0];
		}
	}

	if ($jenis_laporan=='detail') {
		/*$sql="SELECT
			(SELECT DISTINCT id FROM faktur WHERE pesanan_id=p.id LIMIT 1),
			p.meja_nomor,
			d.produk_nama,
			d.produk_harga,
			d.jumlah,
			(d.produk_harga*d.jumlah)AS ttl,
			d.bungkus,
			d.waktu,
			d.operator,
			d.produk_id
		FROM pesanan AS p
		INNER JOIN pesanan_detail as d ON d.pesanan_id=p.id
		WHERE d.status='ON' AND p.status='TUTUP' $sisip_sql
		ORDER BY p.id ASC, d.waktu ASC
		;";*/

		$sql="SELECT DISTINCT
			f.id,
			p.meja_nomor,
			d.produk_nama,
			d.produk_harga,
			d.jumlah,
			(d.produk_harga * d.jumlah) AS ttl,
			d.bungkus,
			d.waktu,
			d.operator,
			d.produk_id,
			fs.diskon,
			fs.meja_kat_tarif
		FROM faktur AS f
		INNER JOIN faktur_stat AS fs ON f.id=fs.faktur_id
		INNER JOIN pesanan AS p ON p.id=f.pesanan_id
		INNER JOIN pesanan_detail AS d ON d.pesanan_id=p.id
		WHERE d.status='ON' AND p.status='TUTUP' AND d.batal='TIDAK' AND fs.status='ON' AND f.pesanan_id=d.pesanan_id $sisip_sql
		;";

		/*$sql_total="SELECT
			SUM(d.produk_harga),
			SUM(d.jumlah),
			SUM((d.produk_harga*d.jumlah))AS ttl
		FROM pesanan AS p INNER JOIN pesanan_detail as d ON d.pesanan_id=p.id
		WHERE d.status='ON' AND p.status='TUTUP' $sisip_sql
		ORDER BY p.id ASC, d.waktu ASC
		;";*/

		$sql_total="SELECT
		SUM(d.produk_harga),
		SUM(d.jumlah),
		SUM(d.produk_harga*d.jumlah) AS ttl,
    SUM( fs.meja_kat_tarif ),
		SUM( (d.produk_harga*d.jumlah) - (((d.produk_harga*d.jumlah)*fs.diskon)/100) ) AS ttl
		FROM faktur AS f
		INNER JOIN faktur_stat AS fs ON f.id=fs.faktur_id
		INNER JOIN pesanan AS p ON p.id=f.pesanan_id
		INNER JOIN pesanan_detail AS d ON d.pesanan_id=p.id
		WHERE d.status='ON' AND p.status='TUTUP' AND d.batal='TIDAK' AND fs.status='ON' AND f.pesanan_id=d.pesanan_id $sisip_sql
		;";

		echo "
<thead>
	<tr>
		<th>#</th>
		<th>No. Trx</th>
		<th>Meja</th>
		<th>Item ID</th>
		<th>Item</th>
		<th>Harga</th>
		<th>Jml</th>
		<th>Total</th>
		<th>Bungkus</th>
		<th>Waktu Pesan</th>
		<th>Pelayan</th>
		<th>Diskon</th>
	</tr>
</thead>
		";
		echo"<tbody>";
		if ($rs=$con->query($sql)) {
			while ($row=$rs->fetch_row()) {
				echo"
					<tr>
						<td align=\"center\">$i</td>
						<td align=\"center\">$row[0]</td>
						<td align=\"center\">$row[1]</td>
						<td align=\"center\">$row[9]</td>
						<td align=\"left\">$row[2]</td>

						<td align=\"right\">".formatMoney($row[3])."</td>
						<td align=\"right\">".formatMoney($row[4])."</td>
						<td align=\"right\">".formatMoney($row[5])."</td>

						<td align=\"center\">$row[6]</td>
						<td align=\"center\">$row[7]</td>
						<td align=\"center\">$row[8]</td>
						<td align=\"center\">$row[10] %</td>
					</tr>
				";
				$i++;
			}
		}
		if ($rs=$con->query($sql_total)) {
			while ($row=$rs->fetch_row()) {
				echo"
					<tr style=\"padding:5px;font-weight:bold;\">
						<td align=\"center\" colspan=\"5\">Grand Total</td>

						<td align=\"right\">".formatMoney($row[0])."</td>
						<td align=\"right\">".formatMoney($row[1])."</td>
						<td align=\"right\">".formatMoney($row[2])."</td>

						<td align=\"center\" colspan=\"4\">Akumulasi Biaya Meja (VIP) & Discount : Rp. ".formatMoney($row[4]+$meja_kat_tarif)."</td>
					</tr>
				";
			}
		}
		echo"</tbody>";
	}else{
		/*$sql="SELECT
			p.id,
			p.meja_nomor,
			p.waktu,
			p.waktu_lunas,
			COUNT(d.produk_id),
			SUM(d.produk_harga*d.jumlah),
			p.operator
		FROM pesanan AS p INNER JOIN pesanan_detail as d ON d.pesanan_id=p.id
		WHERE d.status='ON' AND p.status='TUTUP' $sisip_sql
		GROUP BY p.id
		ORDER BY p.id ASC, p.waktu_lunas ASC
		;";*/

		$sql="SELECT DISTINCT
		f.id,
		p.meja_nomor,
		p.waktu,
		p.waktu_lunas,
		SUM(d.jumlah),
		SUM( (d.produk_harga*d.jumlah) - (((d.produk_harga*d.jumlah)*fs.diskon)/100) ) + fs.meja_kat_tarif,
		p.operator,
		fs.edc_jenis,
		fs.edc_nomor,
    SUM( fs.meja_kat_tarif ),
		fs.diskon,
		(fs.uang_diterima-fs.edc_jumlah),
		fs.edc_jumlah
		FROM faktur AS f
		INNER JOIN faktur_stat AS fs ON f.id=fs.faktur_id
		INNER JOIN pesanan AS p ON p.id=f.pesanan_id
		INNER JOIN pesanan_detail AS d ON d.pesanan_id=p.id
		WHERE d.status='ON' AND p.status='TUTUP' AND d.batal='TIDAK' AND fs.status='ON' AND f.pesanan_id=d.pesanan_id  $sisip_sql
		GROUP BY fs.faktur_id
		ORDER BY f.pesanan_id ASC, f.tanggal ASC
		;";

		/*$sql_total="SELECT
			COUNT(d.produk_id),
			SUM(d.produk_harga*d.jumlah)
		FROM pesanan AS p INNER JOIN pesanan_detail as d ON d.pesanan_id=p.id
		WHERE d.status='ON' AND p.status='TUTUP' $sisip_sql
		ORDER BY p.id ASC, p.waktu_lunas ASC
		;";*/

		$sql_total="SELECT DISTINCT
		SUM(d.jumlah),
		SUM( (d.produk_harga*d.jumlah) - (((d.produk_harga*d.jumlah)*fs.diskon)/100) ) AS ttl,
    SUM( fs.meja_kat_tarif )
		FROM faktur AS f
		INNER JOIN faktur_stat AS fs ON f.id=fs.faktur_id
		INNER JOIN pesanan AS p ON p.id=f.pesanan_id
		INNER JOIN pesanan_detail AS d ON d.pesanan_id=p.id
		WHERE d.status='ON' AND p.status='TUTUP' AND d.batal='TIDAK' AND fs.status='ON' AND f.pesanan_id=d.pesanan_id $sisip_sql
		;";

		echo "
<thead>
	<tr>
	<th>#</th>
	<th>No. Trx</th>
	<th>Meja</th>
	<th>Waktu Pesan</th>
	<th>Waktu Pelunasan</th>
	<th>Jml Item</th>
	<th>Total</th>
	<th>Kasir</th>
	<th>EDC</th>
	<th>Discount</th>
	<th>Tunai</th>
	<th>Non-Tunai</th>
	</tr>
</thead>
		";
		echo"<tbody>";
		if ($rs=$con->query($sql)) {
			while ($row=$rs->fetch_row()) {
				echo"
					<tr>
					<td align=\"center\">$i</td>
					<td align=\"center\">$row[0]</td>
					<td align=\"center\">$row[1]</td>
					<td align=\"center\">$row[2]</td>
					<td align=\"center\">$row[3]</td>
					<td align=\"right\">".formatMoney($row[4])."</td>
					<td align=\"right\">".formatMoney($row[5])."</td>
					<td align=\"center\">$row[6]</td>
					<td align=\"center\">$row[7] - $row[8]</td>
					<td align=\"center\">$row[10]%</td>
					<td align=\"right\">".formatMoney($row[11])."</td>
					<td align=\"right\">".formatMoney($row[12])."</td>
					</tr>
				";
				$i++;
			}
		}
		if ($rs=$con->query($sql_total)) {
			while ($row=$rs->fetch_row()) {
				echo"
					<tr style=\"padding:5px;font-weight:bold;\">
						<td align=\"center\" colspan=\"5\">Grand Total</td>

						<td align=\"right\">".formatMoney($row[0])."</td>
						<td align=\"right\">".formatMoney($row[1]+$meja_kat_tarif)."</td>

						<td colspan=\"5\" align=\"center\">&nbsp</td>
					</tr>
				";
			}
		}
		echo"</tbody>";
	}

}
?>
	</table>
</div>
<div>
	<input type="button" name="export" value="Export to Excel" onClick ="$('#laporan-anual').tableExport({type:'excel',escape:'false',htmlContent:'true'});" style="color:#00f;font-size:15px;font-weight:bold;padding:0px;"/>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<!--input type="button" name="exportCSV" value="Export to CSV" onClick ="$('#laporan-anual').tableExport({type:'csv',escape:'false',htmlContent:'true'});" style="color:#070;font-size:15px;font-weight:bold;padding:0px;"/-->
</div>
