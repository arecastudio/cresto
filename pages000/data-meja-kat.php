<?php
lock_page($con,"master_data_meja_kat",$user_name,$keys);
?>

<?php
ob_start();
?>
<div class="lokasi">
	<a href="?ref=master">Master</a> &rarr; Kategori Meja
</div>

<div class="judul-frame">
	<b>Kategori Meja</b>
</div>

<form action="" method="post" autocomplete="off">
	<div style="padding:5px;">
		<input type="text" name="txnama" id="txnama" placeholder="Nama/Klas"/>
		<input type="hidden" name="txid" id="txid"/>
		<input type="text" name="txtarif" id="txtarif" placeholder="Tarif" size="5"/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" name="submit" value="Simpan" style="width:100px;" class="ui-button ui-widget ui-corner-all"/>
	</div>
</form>

<div style="padding:5px;">
	<table width="80%" border="1" cellpadding="4" style="border-collapse:collapse;">
		<thead style="background:linear-gradient(#900,#300);color:#fff;text-align:center;height:25px;font-weight:bold;">
			<tr>
				<td>ID</td>
				<td>Nama/Klas</td>
				<td>Tarif (Rp.)</td>
				<td colspan="2">Kontrol</td>
			</tr>
		</thead>
		<tbody>
			<?php


#------------------------------------------------------------------

if ($rs=$con->query("SELECT id, nama, tarif FROM  meja_kat ORDER BY id ASC;")) {
	while ($row=$rs->fetch_row()) {
		echo "
		<tr style=\"height:25px;\">
		<td align=\"center\">$row[0]</td>
		<td>$row[1]</td>
		<td align=\"right\">".formatMoney($row[2])."</td>
		<td align=\"center\">
			<a href=\"?ref=data-meja-kat&pil=$row[0]&nama=$row[1]&tarif=$row[2]\"><img src=\"images/edit.png\" width=\"16px\" title=\"Edit Kategori ini\"></a>
		</td>
		<td align=\"center\">
			<a href=\"?ref=data-meja-kat&del=$row[0]\"><img src=\"images/delete.png\" width=\"16px\" title=\"Hapus Kategori ini\" onclick=\"return confirm('Yakin untuk hapus?');\"></a>
		</td>
		</tr>
		";
	}
}
			?>
		</tbody>
	</table>
</div>

<?php
if (isset($_GET['pil']) && $_GET['pil']!='') {
	$id=$_GET['pil'];
	$nama=$_GET['nama'];
	$tarif=$_GET['tarif'];
	echo "
	<script type=\"text/javascript\">document.getElementById('txid').value=\"$id\";</script>
	<script type=\"text/javascript\">document.getElementById('txnama').value=\"$nama\";</script>
	<script type=\"text/javascript\">document.getElementById('txtarif').value=\"$tarif\";</script>
	";
}

if (isset($_GET['del']) && $_GET['del']!='') {
	$id=$_GET['del'];
	$con->query("DELETE FROM meja_kat WHERE id=$id;");
	header('location: ?ref=data-meja-kat');
}


if (isset($_POST['submit'])) {
	$id=$_POST['txid'];
	$nama=$_POST['txnama'];
	$tarif=$_POST['txtarif'];
	if (strlen(trim($id))>0 && strlen(trim($nama))>0 && strlen(trim($tarif))>0) {
		$con->query("UPDATE meja_kat SET nama='$nama', tarif=$tarif WHERE id=$id;");
	}

	if (strlen(trim($id))<1 && strlen(trim($nama))>0 && strlen(trim($tarif))>0) {
		$con->query("INSERT IGNORE INTO meja_kat(nama,tarif)VALUES('$nama',$tarif);");
	}
	header('location: ?ref=data-meja-kat');
}


ob_end_flush();
?>
