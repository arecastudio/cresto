<?php
lock_page($con,"penjualan_pelunasan",$user_name,$keys);
?>


<?php
$nomor="";
$pesanan_id="";
if (isset($_GET['nomor'])) {
	$nomor=$_GET['nomor'];

	if ($rs=$con->query("SELECT id,meja_nomor,status FROM pesanan WHERE meja_nomor='$nomor' AND status='BUKA';")) {
		if ($row=$rs->fetch_row()) {
			$pesanan_id=$row[0];
			//echo "ada";
		}
	}
}
?>
<div class="lokasi">
	<a href="?ref=payment">Pelunasan</a> &rarr; Pelunasan
</div>

<div class="judul-frame">
	<b>Data Pelunasan Meja <?php echo $nomor." - Kode Order: ".$pesanan_id;?></b>
</div>

<div>
	<div style="height:300px;overflow:auto;width: 100%;" class="data-view">
		<table width="100%" border="1" cellspacing="3" cellpadding="5">
			<thead>
				<tr>
					<th>Order ID</th>
					<th>Item ID</th>
					<th>Nama</th>
					<th>Harga (Rp.)</th>
					<th>Jumlah</th>
					<th>Bungkus</th>
					<th>Waktu</th>
					<th>Pelayan</th>
				</tr>
			</thead>
			<tbody>
				<?php
$gTotal=0;$biaya_meja=0;$tamp=0;
if ($rs=$con->query("SELECT produk_id, produk_nama, produk_harga, jumlah, bungkus, waktu, operator,(produk_harga*jumlah),pesanan_id,(SELECT DISTINCT k.tarif FROM meja_kat AS k INNER JOIN meja AS m ON m.meja_kat_id=k.id WHERE m.nomor='$nomor') AS biaya_meja FROM pesanan_detail WHERE pesanan_id IN (SELECT id FROM pesanan WHERE status='BUKA' AND kirim='SDH' AND meja_nomor='$nomor') ORDER BY pesanan_id ASC, waktu ASC;")) {
	while ($row=$rs->fetch_row()) {
		echo"
<tr>
	<td>$row[8]</td>
	<td>$row[0]</td>
	<td>$row[1]</td>
	<td>".formatMoney($row[2])."</td>
	<td>$row[3]</td>
	<td>$row[4]</td>
	<td>$row[5]</td>
	<td>$row[6]</td>
</tr>
		";
	$tamp+=$row[7];
	$biaya_meja=$row[9];
	}
}
$gTotal=$tamp+$biaya_meja;

//========================================
if (isset($_POST['submit']) || isset($_POST['cetak'])) {
	//cetakFaktur($con,$pesanan_id,$nomor);
	//echo $nomor;
	$kode=$pesanan_id;
	$nomeja=$nomor;
	$grandTot=0;
	$tgl=getHari(date('w'))."-".date('d-M-Y H:i:s');

	$sqli="SELECT DISTINCT
		pesanan_id,
		produk_id,
		produk_nama,
		produk_harga,
		jumlah,
		(produk_harga*jumlah)AS tot,
		(SELECT SUM(produk_harga*jumlah) FROM pesanan_detail WHERE pesanan_id IN (SELECT id FROM pesanan WHERE status='BUKA' AND kirim='SDH' AND meja_nomor='$nomor'))as gtot,
		(SELECT DISTINCT meja_nomor FROM pesanan WHERE id=$kode)as meja,
		waktu,
		bungkus,
		(SELECT DISTINCT k.tarif FROM meja_kat AS k INNER JOIN meja AS m ON m.meja_kat_id=k.id WHERE m.nomor='$nomeja') AS biaya_meja,
		(SELECT DISTINCT ppn FROM biaya_lain LIMIT 1) AS ppn_tax
	FROM pesanan_detail
	WHERE pesanan_id IN (SELECT id FROM pesanan WHERE status='BUKA' AND kirim='SDH' AND meja_nomor='$nomor')
	ORDER BY produk_id ASC;";

	$printer_location="//localhost/JZ-PT250";

	$Data ="............::CRISTO RESTO SENTANI::............\n";
	$Data.="Jl. Sentani - Waena\n";
	$Data.="Telp. \n";
	$Data.="No. Transaksi: Trx-/Meja-$nomeja\n";
	$Data.=$tgl."\n";
	$Data.="__________________Detail_Item__________________\n";

	if($meja1=$con->query($sqli)){
		//echo $meja->num_rows;
		//if($meja1->num_rows>0 && printer_open($printer_location)){
		if($meja1->num_rows>0){
			while ($row=$meja1->fetch_row()) {
				$Data.=formatMoney($row[5])." = ".$row[4]." x ".formatMoney($row[3])." *) ".substr($row[2],0,22)."\n";
				$ppn=$row[11];
				$grandTot = $row[6]+$row[10];
				$grandTot_ppn=($ppn*$grandTot)/100;
				$nomeja = $row[7];
			}


			$grandTot=formatMoney($grandTot);

			//=====================================================================================
			$tmpdir = sys_get_temp_dir();   # ambil direktori temporary untuk simpan file.
			$file =  tempnam($tmpdir, 'ctk');  # nama file temporary yang akan dicetak
			$handle = fopen($file, 'w');
			$corte = Chr(27) . Chr(109);

			$Data .= "";
			$Data .= "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n";
			$Data .= "Total Belanja Rp. ".$grandTot."\n";
			$Data .= "==========================\n\n";

			$Data .= "Terima Kasih Atas Kunjungan Anda\n";
			$Data .= "\n\n\n\n\n\n";
			$Data .= $corte;
			fwrite($handle, $Data);
			fclose($handle);
			copy($file, $printer_location);  # Lakukan cetak
			unlink($file);

		}
		//echo"<script type=\"text/javascript\">alert(\"Transaksi telah lunas!\");</script>";
		if (isset($_POST['submit'])) {
			$con->query("UPDATE pesanan SET status='TUTUP',waktu_lunas=current_timestamp() WHERE id IN (SELECT DISTINCT id FROM pesanan WHERE status='BUKA' AND kirim='SDH' AND meja_nomor='$nomor' LIMIT 1);");
			$con->query("UPDATE meja SET status='KOSONG' WHERE nomor='$nomor';");
			//header('location: ?ref=jual');
		}
	}
	//echo $nomor;
}
//========================================


				?>
			</tbody>
		</table>
	</div>
	<div>
		<form method="post" action="" autocomplete="off">
		<table width="100%" border="0">
			<tr>
				<td width="200px" style="color: #00f;"><h2><?php echo "Total Rp. ".formatMoney($gTotal);?></h2></td>
				<td><h3><i><?php echo konversi($gTotal);?> Rupiah</i></h3></td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" name="counting" value="Hitung" style="width: 100px;height: 40px;font-weight: bold;color: #770; font-size: 20px;" />
					&nbsp;
					&nbsp;
					<input type="text" name="txterima" id="txterima" placeholder="Jumlah Uang diterima...."  style="width: 200px;height: 30px;font-weight: bold;color: #f00;padding: 3px;font-size: 16px;">
					&nbsp;
					&nbsp;
					<input type="text" name="txkembali" id="txkembali" placeholder="" readonly  style="width: 200px;height: 30px;font-weight: bold;color: #f00;padding: 3px;font-size: 16px;">
					&nbsp;
					&nbsp;
					<input type="submit" name="cetak" value="Cetak Slip" style="width: 130px;height: 40px;font-weight: bold;color: #770; font-size: 20px;" />
					&nbsp;
					&nbsp;
					&nbsp;
					&nbsp;
					&nbsp;
					&nbsp;
					&nbsp;
					&nbsp;
					&nbsp;
					&nbsp;
					&nbsp;
					&nbsp;
					&nbsp;
					&nbsp;
					<input type="submit" name="submit" value="Cetak & Lunaskan !" style="width: 200px;height: 40px;font-weight: bold;color: #900; font-size: 20px;" />
				</td>
			</tr>
		</table>
		</form>
	</div>
	<div id="keterangan">
		<b id="ket">Keterangan: </b>
	</div>
</div>

<?php
if (isset($_POST['counting'])) {
	$terima=$_POST['txterima'];
	if (strlen(trim($terima))!=0) {
		//echo"hitung".$gTotal;
		$hasil=$terima-$gTotal;
		$hasil="Kembali Rp. ".formatMoney($hasil);
		echo"<script type=\"text/javascript\">document.getElementById(\"txterima\").value=\"$terima\";</script>";
		echo"<script type=\"text/javascript\">document.getElementById(\"txkembali\").value=\"$hasil\";</script>";
	}
}


?>
