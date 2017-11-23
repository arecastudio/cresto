<?php
lock_page($con,"laporan_bulanan",$user_name,$keys);
//pakai ini saja daripada tambah field baru
?>

<div class="lokasi">
	<a href="?ref=laporan">Laporan</a> &rarr; Pemasukan Hari Ini
</div>

<div class="judul-frame">
	<b>Laporan Pemasukan Hari Ini --> <?php echo date('D, d-M-Y');?></b>
</div>


<div class="data-view-besar">
	<table width="100%" border="1" name="laporan-hari-ini" id="laporan-hari-ini" cellpadding="5" cellspacing="1">
<?php
//if(isset($_POST['submit']) && strlen($_POST['datepicker1'])>0 && $_POST['optjenisrep']!="" ){
	$i=1;

	//if ($jenis_laporan=='detail') {

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
      k.kategori,
			fs.diskon
		FROM faktur AS f
		INNER JOIN faktur_stat AS fs ON f.id=fs.faktur_id
		INNER JOIN pesanan AS p ON p.id=f.pesanan_id
		INNER JOIN pesanan_detail AS d ON d.pesanan_id=p.id
    LEFT OUTER JOIN kategori AS k ON k.id=d.kategori_id
		WHERE d.status='ON' AND p.status='TUTUP' AND d.batal='TIDAK' AND fs.status='ON' AND f.pesanan_id=d.pesanan_id AND DATE(fs.tanggal)=DATE(CURRENT_DATE())
		;";


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
		WHERE d.status='ON' AND p.status='TUTUP' AND d.batal='TIDAK' AND fs.status='ON' AND f.pesanan_id=d.pesanan_id AND DATE(fs.tanggal)=DATE(CURRENT_DATE())
		;";

		$meja_kat_tarif=0;
		$sql_meja_tarif="SELECT SUM(meja_kat_tarif) FROM faktur_stat WHERE DATE(tanggal)=CURRENT_DATE;";
		if ($rs1=$con->query($sql_meja_tarif)) {
			if ($row1=$rs1->fetch_row()) {
				$meja_kat_tarif=$row1[0];
			}
		}

		echo "
<thead>
	<tr>
		<th>#</th>
		<th>No. Trx</th>
		<th>Meja</th>
		<!--th>Item ID</th-->
		<th>Item</th>
		<th>Harga</th>
		<th>Jml</th>
		<th>Total</th>
		<th>Bungkus</th>
		<th>Waktu Pesan</th>
		<th>Pelayan</th>
    <th>Kategori</th>
		<th>Discount</th>
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
						<!--td align=\"center\">$row[9]</td-->
						<td align=\"left\">$row[2]</td>

						<td align=\"right\">".formatMoney($row[3])."</td>
						<td align=\"right\">".formatMoney($row[4])."</td>
						<td align=\"right\">".formatMoney($row[5])."</td>

						<td align=\"center\">$row[6]</td>
						<td align=\"center\">$row[7]</td>
						<td align=\"center\">$row[8]</td>
            <td align=\"center\">$row[10]</td>
						<td align=\"center\">$row[11] %</td>
					</tr>
				";
				$i++;
			}
		}
		if ($rs=$con->query($sql_total)) {
			while ($row=$rs->fetch_row()) {
				echo"
					<tr style=\"padding:5px;font-weight:bold;\">
						<td align=\"center\" colspan=\"4\">Grand Total</td>

						<td align=\"right\">".formatMoney($row[0])."</td>
						<td align=\"right\">".formatMoney($row[1])."</td>
						<td align=\"right\">".formatMoney($row[2])."</td>

						<td align=\"center\" colspan=\"5\">Akumulasi Biaya Meja (VIP) & Discount : Rp. ".formatMoney($row[4]+$meja_kat_tarif)."</td>
					</tr>
				";
			}
		}
		echo"</tbody>";
	//}
//}
?>
	</table>
</div>
<div>
	<input type="button" name="export" value="Export to Excel" onClick ="$('#laporan-hari-ini').tableExport({type:'excel',escape:'false',htmlContent:'true'});" style="color:#00f;font-size:15px;font-weight:bold;padding:0px;"/>
</div>
