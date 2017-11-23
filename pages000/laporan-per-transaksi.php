<?php
lock_page($con,"laporan_bulanan",$user_name,$keys);
//pakai ini saja daripada tambah field baru
?>

<div class="lokasi">
	<a href="?ref=laporan">Laporan</a> &rarr; Pajak
</div>

<div class="judul-frame">
	<b>Laporan Pajak Cristho Resto</b>
</div>

<div style="margin-top:5px;margin-bottom:5px;">
	<form id="f1" action="" method="post">
		<input type="text" id="datepicker1" name="datepicker1" class="datepicker" size="12" placeholder="Tanggal" readonly/>
		s/d <input type="text" id="datepicker2" name="datepicker2" class="datepicker" size="12" placeholder="Tanggal Akhir" readonly/>
		<!--select id="optjenisrep" name="optjenisrep">
			<option value="">Jenis Laporan</option>
			<option value="detail">DETAIL</option>
			<option value="rekap">REKAP</option>
		</select-->&nbsp;&nbsp;&nbsp;
		<input type="submit" name="submit" value="Proses" style="width:150px;font-size:18px;color:blue;" />
	</form>
</div>

<div class="data-view-besar">
	<table width="100%" border="1" name="laporan-hari-ini" id="laporan-hari-ini" cellpadding="5" cellspacing="1">
<?php
if(isset($_POST['submit']) && strlen($_POST['datepicker1'])>0 && strlen($_POST['datepicker2'])>0 ){
	$i=1;
	$tgl_awal=$_POST['datepicker1'];
	$tgl_akhir=$_POST['datepicker2'];

	echo"<script type=\"text/javascript\">document.getElementById(\"datepicker1\").value=\"$tgl_awal\";</script>";
	echo"<script type=\"text/javascript\">document.getElementById(\"datepicker2\").value=\"$tgl_akhir\";</script>";

	//if ($jenis_laporan=='detail') {

		$sql="SELECT DISTINCT
			DATE(fs.tanggal),
			fs.faktur_id,
			SUM((d.produk_harga * d.jumlah) - (((d.produk_harga*d.jumlah)*fs.diskon)/100))+fs.meja_kat_tarif,
			ROUND(((SUM((d.produk_harga * d.jumlah) - (((d.produk_harga*d.jumlah)*fs.diskon)/100))+fs.meja_kat_tarif)/110)*100,2),
			ROUND((SUM((d.produk_harga * d.jumlah) - (((d.produk_harga*d.jumlah)*fs.diskon)/100))+fs.meja_kat_tarif) - (((SUM((d.produk_harga * d.jumlah) - (((d.produk_harga*d.jumlah)*fs.diskon)/100))+fs.meja_kat_tarif)/110)*100),2),
			SUM(d.produk_harga * d.jumlah) AS ttl,
			DAYOFWEEK(fs.tanggal)
		FROM faktur AS f
		INNER JOIN faktur_stat AS fs ON f.id=fs.faktur_id
		INNER JOIN pesanan AS p ON p.id=f.pesanan_id
		INNER JOIN pesanan_detail AS d ON d.pesanan_id=p.id
    	LEFT OUTER JOIN kategori AS k ON k.id=d.kategori_id
		WHERE d.status='ON' AND p.status='TUTUP' AND d.batal='TIDAK' AND fs.status='ON' AND f.pesanan_id=d.pesanan_id AND (DATE(fs.tanggal) BETWEEN '$tgl_awal' AND '$tgl_akhir')
		GROUP BY f.id
		;";


		$sql_total="SELECT
		SUM(d.produk_harga),
		SUM(d.jumlah),
		SUM(d.produk_harga*d.jumlah),
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
		<th>Tanggal</th>
		<th>No. Transaksi</th>
		<th>Total Harga Termasuk PPN</th>		
		<th>Total Harga Tanpa PPN</th>		
		<th>PPN 10%</th>
		<th>Jumlah PPN</th>
	</tr>
</thead>
		";
		echo"<tbody>";
		if ($rs=$con->query($sql)) {
			$temp=0;
			while ($row=$rs->fetch_row()) {
				$temp+=$row[4];
				echo"
					<tr>
						<td align=\"center\">$i</td>
						<td align=\"center\">".getDay($row[6]).", $row[0]</td>
						<td align=\"center\">$row[1]</td>
						<td align=\"right\">".formatMoney($row[2])."</td>
						<td align=\"right\">".formatMoney($row[3])."</td>
						<td align=\"right\">".formatMoney($row[4])."</td>						
						<td align=\"right\">".formatMoney($temp)."</td>
					</tr>
				";
				$i++;
			}
		}
		/*if ($rs=$con->query($sql_total)) {
			while ($row=$rs->fetch_row()) {
				echo"
					<tr style=\"padding:5px;font-weight:bold;\">
						<td align=\"center\" colspan=\"3\">Grand Total</td>

						<td align=\"right\">".formatMoney($row[0])."</td>
						<td align=\"right\">".formatMoney($row[1])."</td>
						<td align=\"right\">".formatMoney($row[2])."</td>

						<td align=\"center\" >Akumulasi Biaya Meja (VIP) & Discount : Rp. ".formatMoney($row[4]+$meja_kat_tarif)."</td>
					</tr>
				";
			}
		}*/
		echo"</tbody>";
	//}
}
?>
	</table>
</div>
<div>
	<input type="button" name="export" value="Export to Excel" onClick ="$('#laporan-hari-ini').tableExport({type:'excel',escape:'false',htmlContent:'true'});" style="color:#00f;font-size:15px;font-weight:bold;padding:0px;"/>
</div>
