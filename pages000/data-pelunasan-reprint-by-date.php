<?php
ob_start();
lock_page($con,"penjualan_pelunasan",$user_name,$keys);
?>

<div class="lokasi">
	<a href="?ref=payment">Pelunasan</a> &rarr; Ceatak Ulang Pelunasan Per Tanggal
</div>

<div class="judul-frame">
	<b>Cetak Ulang Pelunasan (Transaksi) Per Tanggal</b>
</div>


<div style="margin-top:5px;margin-bottom:5px;">
	<form id="f1" action="" method="post" autocomplete="off">
		<input type="text" id="datepicker1" name="datepicker1" class="datepicker" size="12" placeholder="Tgl Awal" readonly/>
		 s/d
		<input type="text" id="datepicker2" name="datepicker2" class="datepicker" size="12" placeholder="Tgl Akhir" readonly/>
		<!--input type="text" id="txidtrx" name="txidtrx"size="12" placeholder="ID/No. Transaksi" style="color:red;font-weight:bold;text-align:center;"/-->
		&nbsp;&nbsp;&nbsp;
		<input type="submit" name="submit2" value="Proses" style="width:150px;font-size:18px;color:blue;" />
	</form>
</div>

<?php
if ( isset($_POST['submit2']) && $_POST['datepicker1']!='' && $_POST['datepicker1']!='' ) {
	$tawal=$_POST['datepicker1'];
	$takhir=$_POST['datepicker2'];
	echo "<script type=\"text/javascript\">document.getElementById('datepicker1').value=\"$tawal\";</script>";
	echo "<script type=\"text/javascript\">document.getElementById('datepicker2').value=\"$takhir\";</script>";

	$sql1="SELECT DISTINCT
	fs.faktur_id
	FROM faktur_stat AS fs
	WHERE (DATE(fs.tanggal) BETWEEN '$tawal' AND '$takhir')
	;";

	if ($rs=$con->query($sql1)) {
		while ($row1=$rs->fetch_row()) {
			//echo "$row1[0]<br/>";
			cetak_ulang($row1[0],$con);
		}
	}

	echo "$tawal";
}

//if (isset($_POST['submit']) && $_POST['txidtrx']!='') {
function cetak_ulang($no_faktur,$con){
  //$no_faktur=trim($_POST['txidtrx']);
  $printer_location="//localhost/JZ-PT250";
  $nomeja="";
  $by_meja=0;
  $cetak=0;

  $sqli="SELECT DISTINCT
    d.pesanan_id,
    d.produk_id,
    d.produk_nama,
    (d.produk_harga) - ((d.produk_harga*fs.diskon)/100),
    d.jumlah,
    ( (d.produk_harga * d.jumlah) - (((d.produk_harga*d.jumlah)*fs.diskon)/100) *d.jumlah)AS tot,
    d.waktu,
    d.waktu,
    d.bungkus,
    p.meja_nomor,
    fs.diskon,
    fs.meja_kat_tarif,
    fs.tanggal,
    LENGTH(d.produk_nama) AS panjang,
		DAYOFWEEK(fs.tanggal)
  FROM pesanan_detail AS d
  INNER JOIN pesanan AS p ON p.id=d.pesanan_id
  INNER JOIN faktur AS f ON f.pesanan_id=p.id
  INNER JOIN faktur_stat AS fs ON fs.faktur_id=f.id
  WHERE d.batal='TIDAK' AND d.status='ON' AND fs.faktur_id=$no_faktur
  ORDER BY d.produk_id ASC
  ;";

  $sisipan="";$tot=0;
	$numhari="";$tgl0="";$diskon="0";

  if ($rs=$con->query($sqli)) {
    $cetak=1;
    while ($row=$rs->fetch_row()) {
      $nama_item=substr($row[2],0,15);
      $tgl0=$row[12];
			$numhari=$row[14];
      $spc=chr(9);
      if (strlen($nama_item)<=14) {
        $spc=chr(9).chr(9);
      }
      $sisipan.=  "*)".$nama_item.$spc.formatMoney($row[3])." x ".$row[4]." = ".formatMoney($row[5])."\n";
      $by_meja=$row[11];
      $grandTot = $row[6];
      $diskon=$row[10];
      $tot+=$row[5];
      $nomeja = $row[9];
    }
    //$grandTot_ppn=(($ppn*$grandTot)/100)+$grandTot+$by_meja;
    #$disc=($tot*$diskon)/100;
    $grandTot_ppn=$tot+$by_meja;
  }

$tgl=getDay($numhari).", ".date($tgl0);

if ($cetak==1) {
  $Data ="...........::CRISTHO RESTO SENTANI::...........\n";
  $Data.="Jl. Sentani - Waena\n";
  $Data.="Telp. 0822 4826 9002\n";
  $Data.="No. Transaksi: Trx-".$no_faktur."/Meja-$nomeja\n";
  $Data.=$tgl."\n";
  $Data.="__________________Detail_Item__________________\n";

  $Data=$Data."".$sisipan;


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
  $Data .= "Terima Kasih Atas Kunjungan Anda\n";
  $Data .= "\n\n\n\n\n\n";
  $Data .= $corte;
  echo $Data;
  fwrite($handle, $Data);
  fclose($handle);
  copy($file, $printer_location);  # Lakukan cetak
  unlink($file);
}


}
?>

<?php ob_end_flush()?>
