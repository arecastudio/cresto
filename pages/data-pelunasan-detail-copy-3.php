<?php
ob_start();
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

$no_faktur=0;
if($rs=$con->query("SELECT IFNULL(MAX(id),0) FROM faktur;")){
	if($row=$rs->fetch_row()){
		$no_faktur=$row[0]+1;
	}
}

$diskon=0;

if (isset($_POST['btdiskon']) && $_POST['txdiskon']!='' && is_numeric($_POST['txdiskon'])) {
	$jdiskon=$_POST['txdiskon'];
	$con->query("UPDATE biaya_lain SET diskon=$jdiskon;");
}

if($rs=$con->query("SELECT DISTINCT IFNULL(diskon,0) FROM biaya_lain ORDER BY waktu DESC LIMIT 1;")){
	if($row=$rs->fetch_row()){
		$diskon=$row[0];
	}
}

?>


<script type="text/javascript">
function hapus_kembali(){
	document.getElementById('txkembali').value='';
}

function hapus_terima_kembali(){
	document.getElementById('txterima').value='';
	document.getElementById('txkembali').value='';
}


$( document ).ready( function() {

	$('input.number').keyup(function(event) {

	  // skip for arrow keys
	  if(event.which >= 37 && event.which <= 40) return;

	  // format number
	  $(this).val(function(index, value) {
	    return value
	    .replace(/\D/g, "")
	    .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
	    ;
	  });
	});

});
</script>

<div class="lokasi">
	<a href="?ref=payment">Pelunasan</a> &rarr; Pelunasan
</div>

<div class="judul-frame">
	<b>Data Pelunasan Meja <?php echo $nomor." - Nomor Order: ".$pesanan_id;?></b>
</div>

<div>
	<div style="height:300px;overflow:auto;width: 100%;" class="data-view">
		<table width="100%" border="1" cellspacing="3" cellpadding="5">
			<thead>
				<tr>
					<th>#</th>
					<!--th>Item ID</th-->
					<th>Nama</th>
					<th>Harga (Rp.)</th>
					<th>Jumlah</th>
					<th>Total (Rp.)</th>
					<th>Bungkus</th>
					<th>Waktu</th>
					<th>Pelayan</th>
				</tr>
			</thead>
			<tbody>
				<?php
$gTotal=0;$biaya_meja=0;$tamp=0;$ppn=0;
$sql1="
SELECT
	produk_id,
	produk_nama,
	produk_harga,
	jumlah,
	bungkus,
	waktu,
	operator,
	(produk_harga*jumlah) AS tot,
	pesanan_id,
	(SELECT DISTINCT k.tarif FROM meja_kat AS k INNER JOIN meja AS m ON m.meja_kat_id=k.id WHERE m.nomor='$nomor') AS biaya_meja,
	(SELECT DISTINCT ppn FROM biaya_lain LIMIT 1) AS ppn_tax
FROM pesanan_detail
WHERE
	batal='TIDAK' AND status='ON' AND pesanan_id IN (SELECT id FROM pesanan WHERE status='BUKA'
	AND kirim='SDH'
	AND meja_nomor='$nomor')
ORDER BY pesanan_id ASC, waktu ASC
;";
$i=1;
if ($rs=$con->query($sql1)) {
	while ($row=$rs->fetch_row()) {
		echo"
<tr>
	<td align=\"center\">$i</td>
	<!--td>$row[0]</td-->
	<td>$row[1]</td>
	<td align=\"right\">".formatMoney($row[2])."</td>
	<td align=\"center\">$row[3]</td>
	<td align=\"right\">".formatMoney($row[7])."</td>
	<td align=\"center\">$row[4]</td>
	<td align=\"center\">$row[5]</td>
	<td align=\"center\">$row[6]</td>
</tr>
		";
	$tamp+=$row[7];
	$biaya_meja=$row[9];
	$ppn=$row[10];
	$i++;
	}
}
//$gTotal=$tamp+(($ppn*$tamp)/100)+$biaya_meja;
$disc=($tamp*$diskon)/100;
$gTotal=($tamp-$disc)+$biaya_meja;

//========================================
if (isset($_POST['submit']) || isset($_POST['cetak'])) {
	$txterima=$_POST['txterima'];
	$txkembali=$_POST['txkembali'];
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
		(SELECT SUM(produk_harga*jumlah) FROM pesanan_detail WHERE pesanan_id IN (SELECT id FROM pesanan WHERE batal='TIDAK' AND status='BUKA' AND kirim='SDH' AND meja_nomor='$nomor' AND batal='TIDAK'))as gtot,
		(SELECT DISTINCT meja_nomor FROM pesanan WHERE id=$kode)as meja,
		waktu,
		bungkus,
		(SELECT DISTINCT k.tarif FROM meja_kat AS k INNER JOIN meja AS m ON m.meja_kat_id=k.id WHERE m.nomor='$nomeja') AS biaya_meja,
		(SELECT DISTINCT ppn FROM biaya_lain LIMIT 1) AS ppn_tax,
		LENGTH(produk_nama) AS panjang
	FROM pesanan_detail
	WHERE batal='TIDAK' AND status='ON' AND pesanan_id IN (SELECT id FROM pesanan WHERE status='BUKA' AND kirim='SDH' AND meja_nomor='$nomor')
	ORDER BY produk_id ASC;";

	$printer_location="//localhost/JZ-PT250";

	$Data ="...........::CRISTHO RESTO SENTANI::...........\n";
	$Data.="Jl. Sentani - Waena\n";
	$Data.="Telp. 0822 4826 9002\n";
	$Data.="No. Transaksi: Trx-".$no_faktur."/Meja-$nomeja\n";
	$Data.=$tgl."\n";
	$Data.="__________________Detail_Item__________________\n";

	if($meja1=$con->query($sqli)){
		//echo $meja->num_rows;
		//if($meja1->num_rows>0 && printer_open($printer_location)){
		if($meja1->num_rows>0){
			while ($row=$meja1->fetch_row()) {
				//$Data.=formatMoney($row[5])." = ".$row[4]." x ".formatMoney($row[3])." *) ".substr($row[2],0,22)."\n";
				$nama_item=substr($row[2],0,15);
				$spc=chr(9);
				if (strlen($nama_item)<=14) {
					$spc=chr(9).chr(9);
				}
				$Data.=  "*)".$nama_item.$spc.formatMoney($row[3])." x ".$row[4]." = ".formatMoney($row[5])."\n";
				$ppn=$row[11];
				$by_meja=$row[10];
				$grandTot = $row[6];
				//$grandTot_ppn=(($ppn*$grandTot)/100)+$grandTot+$by_meja;
				$disc=($grandTot*$diskon)/100;
				$grandTot_ppn=($grandTot+$disc)+$by_meja;
				$nomeja = $row[7];
			}


			$grandTot=formatMoney($grandTot_ppn);

			//=====================================================================================
			$tmpdir = sys_get_temp_dir();   # ambil direktori temporary untuk simpan file.
			$file =  tempnam($tmpdir, 'ctk');  # nama file temporary yang akan dicetak
			$handle = fopen($file, 'w');
			$corte = Chr(27) . Chr(109);

			$Data .= "";
			$Data .= "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n";
			$Data .= "Discount ".$diskon." %\n";
			$Data .= "Biaya Meja Rp.".formatMoney($by_meja)."\n";
			$Data .= "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n";
			$Data .= "Total Belanja Rp. ".$grandTot."\n";
			$Data .= "==========================\n";
			$Data .= "**Sudah termasuk PPN\n\n";
			$Data .= "Terima Rp. ".$txterima.", Kembali ".$txkembali."\n\n";
			$Data .= "Terima Kasih Atas Kunjungan Anda\n";
			$Data .= "\n\n\n\n\n\n";
			$Data .= $corte;
			fwrite($handle, $Data);
			fclose($handle);
			//copy($file, $printer_location);  # Lakukan cetak
			unlink($file);

		}
		//echo"<script type=\"text/javascript\">alert(\"Transaksi telah lunas!\");</script>";
		if (isset($_POST['submit'])) {
			$trima=$_POST['txterima'];
			$terima=str_replace(',','',$trima);
			if ($terima>=$gTotal) {
				$kembali=$terima-$gTotal;
				//$con->query("UPDATE pesanan SET status='TUTUP',waktu_lunas=current_timestamp() WHERE id IN (SELECT DISTINCT p.id FROM pesanan AS p WHERE p.status='BUKA' AND p.kirim='SDH' AND p.meja_nomor='$nomor');");

				if ($rs=$con->query("SELECT id FROM pesanan WHERE meja_nomor='$nomor';")) {
					while ($row=$rs->fetch_row()) {
						$con->query("INSERT IGNORE INTO faktur(id,pesanan_id)VALUES($no_faktur,$row[0]);");
					}
					$con->query("INSERT IGNORE INTO faktur_stat(faktur_id,diskon,meja_kat_tarif,operator)VALUES($no_faktur,$diskon,$by_meja,'$user_name');");
				}

				$con->query("UPDATE pesanan SET operator='$user_name',status='TUTUP',waktu_lunas=current_timestamp(),uang_diterima=$terima,uang_kembali=$kembali WHERE meja_nomor='$nomor';");
				$con->query("UPDATE meja SET status='KOSONG' WHERE nomor='$nomor';");
				$con->query("UPDATE biaya_lain SET diskon=0;");

				if (isset($_POST['chkedc'])) {
					$edc_jenis=$_POST['optedc'];
					$edc_nomor=$_POST['txedc'];
					$con->query("UPDATE faktur_stat SET edc_jenis='$edc_jenis',edc_nomor='$edc_nomor' WHERE faktur_id=$no_faktur;");
				}
				header('location: ?ref=jual');
			}else{
				echo "<script type='text/javascript'>alert('Uang diterima kurang!');</script>";
			}


			//echo "$nomor";
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
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<form method="post" action="" autocomplete="off">
			<tr style="background-color:#ffc;">
				<td colspan="2"> Jumlah Diskon &nbsp;&nbsp;
					<input type="text" name="txdiskon" id="txdiskon" onKeyUp="return hapus_terima_kembali();" required placeholder="Jumlah Discount" value="<?php echo $diskon;?>" maxlength="3" size="5px" style="text-align:center;color:red;font-weight:bold;"/> %
					&nbsp;&nbsp;
					<input type="submit" name="btdiskon" value="Update Discount"/>
				</td>
			</tr>
		</form>
		<form method="post" action="" autocomplete="off">
			<tr>
				<td width="300px" style="color: #00f;"><h2><?php echo "Grand Total Rp. ".formatMoney($gTotal);?></h2></td>
				<td><h3><i><?php echo konversi($gTotal);?> Rupiah</i></h3></td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" name="counting" value="Hitung" style="width: 100px;height: 40px;font-weight: bold;color: #770; font-size: 20px;" />
					&nbsp;
					&nbsp;Rp.
					<input type="text" name="txterima" id="txterima" onKeyUp="return hapus_kembali();" class="number" required placeholder="Jumlah Uang diterima...." style="width: 200px;height: 30px;font-weight: bold;color: #f00;padding: 3px;font-size: 16px;">
					&nbsp;
					&nbsp;
					<input type="text" name="txkembali" id="txkembali" required placeholder="" readonly  style="width: 200px;height: 30px;font-weight: bold;color: #f00;padding: 3px;font-size: 16px;">
					&nbsp;
					&nbsp;
					<!--input type="submit" disabled name="cetak" value="Cetak Slip" style="width: 130px;height: 40px;font-weight: bold;color: #770; font-size: 20px;" /-->
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
			<tr height="80px">
				<td></td>
				<td>
					<input type="checkbox" name="chkedc" id="chkedc"/> Gunakan EDC &nbsp;&nbsp;&nbsp;
					<select name="optedc" id="optedc">
						<option value="">--Pilih--</option>
						<?php
$sql="SELECT id, nama FROM non_tunai ORDER BY nama ASC;";
if ($rs=$con->query($sql)) {
	while ($row2=$rs->fetch_row()) {
		echo"<option value=\"$row2[1]\">$row2[1]</option>";
	}
}
						?>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="text" name="txedc" id="txedc" placeholder="Nomor kartu" style="width: 200px;height: 30px;font-weight: bold;color: #f00;padding: 3px;font-size: 16px;"/>
				</td>
			</tr>
			</form>
		</table>

	</div>
	<div id="keterangan">
		<b id="ket">Keterangan: </b>
	</div>
</div>

<?php
if (isset($_POST['counting'])) {
	$trima=$_POST['txterima'];
	$terima=str_replace(',','',$trima);
	if (strlen(trim($terima))!=0) {
		//echo"hitung".$gTotal;
		$hasil=$terima-$gTotal;
		$hasil="Kembali Rp. ".formatMoney($hasil);
		echo"<script type=\"text/javascript\">document.getElementById(\"txterima\").value=\"$trima\";</script>";
		echo"<script type=\"text/javascript\">document.getElementById(\"txkembali\").value=\"$hasil\";</script>";
	}
}

ob_end_flush();
?>
