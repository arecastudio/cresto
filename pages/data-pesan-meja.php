<?php
ob_start();

$nomor="";
$pesanan_id="";
if (isset($_GET['nomor'])) {
	$nomor=$_GET['nomor'];

	$con->query("UPDATE meja SET status='TERISI' WHERE nomor='$nomor';");

	if ($rs=$con->query("SELECT id,meja_nomor,status FROM pesanan WHERE meja_nomor='$nomor' AND status='BUKA' AND kirim='BLM';")) {
		if ($row=$rs->fetch_row()) {
			$pesanan_id=$row[0];
			//echo "ada";
		}else{
			$con->query("INSERT INTO pesanan(meja_nomor,operator)VALUES('$nomor','$user_name');");
		}
	}
}

?>
<div class="lokasi">
	<a href="?ref=data-order">Pemesanan</a> &rarr; Per Meja
</div>

<div class="judul-frame">
	<b>Data Pemesanan Meja <?php echo $nomor;?></b>
</div>


<div style="">

	<div style="height:500px;overflow:auto;width: 700px;float: left;">


<?php

$pesanan_id="";
if(isset($_GET['item'])){
	$produk_id=$_GET['item'];
	if($meja=$con->query("SELECT id FROM pesanan WHERE status='BUKA' AND meja_nomor='$nomor' AND kirim='BLM';")){
		if ($row=$meja->fetch_row()) {
			$pesanan_id=$row[0];
			//echo $pesanan_id."\n\n";
			$sql_input="
			INSERT IGNORE INTO pesanan_detail(pesanan_id,produk_id,produk_nama,produk_harga,kategori_id,jumlah,operator)
			VALUES($pesanan_id,$produk_id,(SELECT DISTINCT nama FROM produk WHERE id=$produk_id),(SELECT DISTINCT harga FROM produk WHERE id=$produk_id),(SELECT DISTINCT kategori_id FROM produk WHERE id=$produk_id),1,'$user_name')
			;";
			$con->query($sql_input);
		}
	}
	//echo $produk_id;
}

//**************************************************
$sql_menu="SELECT p.id,p.nama,p.harga,p.gambar,k.id,k.jenis,k.kategori FROM produk AS p LEFT OUTER JOIN kategori AS k ON k.id=p.kategori_id ORDER by k.jenis ASC, p.nama ASC;";

if($meja=$con->query($sql_menu)){
	while ($row=$meja->fetch_row()) {
		$wrn="red";
		if($row[5]=='MINUM')$wrn="blue";
		echo"
		<a href=\"?ref=data-pesan-meja&nomor=$nomor&item=$row[0]\">
			<div class=\"sub-menu\" style=\"border-color:$wrn;\">
				<center>
					<img src=\"foto/$row[0].jpg\">
					<br/>
					<b>$row[1]</b><br/>
					<i>Rp. ".formatMoney($row[2])."</i>
				</center>
			</div>
		</a>
		";
	}
}


if (isset($_POST['submit'])) {
	$produk_id=$_POST['txproduk_id'];
	$Produk_jml=$_POST['txjml'];
	$Produk_bks=$_POST['optbungkus'];
	$con->query("UPDATE pesanan_detail SET jumlah=$Produk_jml,bungkus='$Produk_bks' WHERE produk_id=$produk_id AND pesanan_id=(SELECT id FROM pesanan WHERE status='BUKA' AND meja_nomor='$nomor' AND kirim='BLM');");
	header('location: ?ref=data-pesan-meja&nomor='.$nomor);//exit();
}

if (isset($_POST['kirim']) && isset($_GET['nomor'])) {
	$meja_nomor=$_GET['nomor'];
	$con->query("UPDATE pesanan SET kirim='SDH' WHERE meja_nomor='$meja_nomor' AND status='BUKA' AND kirim='BLM';");
}


if (isset($_GET['del'])) {
	$v1=$_GET['del'];
	$v2=$_GET['pesanan_id'];
	$v3=$_GET['item_bungkus'];
	$con->query("DELETE FROM pesanan_detail WHERE produk_id=$v1 AND pesanan_id=$v2 AND bungkus='$v3';");
}

?>

	</div>
	<div>
		<center><h3>Menu Yang Telah Dipesan</h3></center>
		<div style="width: 400px;float: right;" class="data-view-kecil">
			<table width="100%" cellspacing="3" cellpadding="5" border="1">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nama</th>
						<th>Hrg (Rp.)</th>
						<th>Jml</th>
						<th>Bngks</th>
						<th>Kontrol</th>
					</tr>
				</thead>
				<tbody>
					<?php
if($meja=$con->query("SELECT DISTINCT pesanan_id,produk_id,produk_nama,produk_harga,jumlah,bungkus FROM pesanan_detail WHERE batal='TIDAK' AND pesanan_id IN(SELECT DISTINCT id FROM pesanan WHERE meja_nomor='$nomor' AND status='BUKA' AND kirim='SDH') ORDER BY waktu ASC;")){
	while ($row=$meja->fetch_row()) {
		echo"
<tr style=\"background-color:#ccc;\">
	<td>$row[1]</td>
	<td>$row[2]</td>
	<td>".formatMoney($row[3])."</td>
	<td>$row[4]</td>
	<td>".substr($row[5], 0,1)."</td>
<td>
&nbsp;
</td>
</tr>
		";
	}
}
//******************************************************
if($meja=$con->query("SELECT DISTINCT pesanan_id,produk_id,produk_nama,produk_harga,jumlah,bungkus FROM pesanan_detail WHERE pesanan_id=(SELECT DISTINCT id FROM pesanan WHERE meja_nomor='$nomor' AND status='BUKA' AND kirim='BLM' LIMIT 1) ORDER BY waktu ASC;")){
	while ($row=$meja->fetch_row()) {
		echo"
<tr>
	<td>$row[1]</td>
	<td>$row[2]</td>
	<td>".formatMoney($row[3])."</td>
	<td>$row[4]</td>
	<td>".substr($row[5], 0,1)."</td>
<td>
<a href=\"?ref=data-pesan-meja&nomor=$nomor&item_ubah=$row[1]&item_nama=$row[2]&item_jumlah=$row[4]&item_bungkus=$row[5]\"><img src=\"images/edit.png\" width=\"16px\" title=\"Edit Item ini\"></a>&nbsp;&nbsp;&nbsp;
<a href=\"?ref=data-pesan-meja&nomor=$nomor&del=$row[1]&pesanan_id=$row[0]&item_bungkus=$row[5]\"><img src=\"images/delete.png\" width=\"16px\" title=\"Hapus Item ini\" onclick=\"return confirm('Yakin untuk hapus?');\"></a>
</td>
</tr>
		";
	}
}

					?>
				</tbody>
			</table>
		</div>
	</div>
	<div style="float: right;width: 400px;padding-right:10px;">
		<h5>Ubah Item</h5>
		<form name="f1" method="post" action="" autocomplete="off">
		<table width="100%">
			<tr>
				<td><input type="text" name="txproduk_id" id="txproduk_id" size="3" placeholder="ID" readonly></td>
				<td><input type="text" name="txnama" id="txnama" placeholder="Nama" readonly></td>
				<td><input type="text" name="txjml" id="txjml" size="3" placeholder="Jml"></td>
				<td>
					<select name="optbungkus" id="optbungkus">
						<option value="TIDAK">TDK</option>
						<option value="YA">YA</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="4"><input type="submit" name="submit" value="Update Item"/></td>
			</tr>

			<tr style="height:30px;">
				<td colspan="4" align="right"><input type="submit" name="kirim" value="Kirim Pesanan" style="font-size:18px;padding:5px;width:150px;font-weight:bold;"/></td>
			</tr>

		</table>
		</form>
	</div>

</div>
<?php

if (isset($_GET['item_ubah'])) {
	$n1=$_GET['item_ubah'];
	$n2=$_GET['item_nama'];
	$n3=$_GET['item_jumlah'];
	$n4=$_GET['item_bungkus'];
	echo "
<script type=\"text/javascript\">document.getElementById('txproduk_id').value=\"$n1\";</script>
<script type=\"text/javascript\">document.getElementById('txnama').value=\"$n2\";</script>
<script type=\"text/javascript\">document.getElementById('txjml').value=\"$n3\";</script>
<script type=\"text/javascript\">document.getElementById('optbungkus').value=\"$n4\";</script>
	";
}


ob_end_flush();
?>
