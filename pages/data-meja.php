<?php
lock_page($con,"master_data_meja",$user_name,$keys);
?>

<div class="lokasi">
	<a href="?ref=master">Master</a> &rarr; Data Meja
</div>

<div class="judul-frame">
	<b>Master Data Meja</b>
</div>

<div class="data-entry">
	<form method="post" action="" autocomplete="off">
	<table width="250px" cellspacing="0" cellpadding="2">
		<tr>
			<td>Nomor/Kode</td>
			<td><input type="text" name="txnomeja" id="txnomeja" placeholder="Contoh: 01 atau VIP-07"  /></td>
		</tr>
		<tr>
			<td>Jenis</td>
			<td><input type="hidden" name="txjmlbangku" size="5px" placeholder="Angka" value="4" onkeypress="return NumbersOnly(event);" maxlength="3" />
				<select name="optjenis" id="optjenis">
					<?php
					if($rs=$con->query("SELECT id,nama, tarif FROM meja_kat ORDER BY nama ASC;")){
						while ($row=$rs->fetch_row()) {
							echo"<option value=\"$row[0]\">$row[1]</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Keadaan</td>
			<td>
				<input type="radio" name="rstat" value="BAIK" checked /> Baik
				&nbsp;
				&nbsp;
				&nbsp;
				<input type="radio" name="rstat" value="RUSAK" /> Rusak
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" name="submit" value="Simpan"/>
				<input type="reset" name="reset"/>
			</td>
		</tr>
	</table>
	</form>
</div>

<div class="data-view">
	<table width="700px" cellspacing="3" cellpadding="5" border="1">
		<thead>
			<tr>
				<th>Nomor/Kode</th>
				<th>Jenis</th>
				<th>Keadaan</th>
				<th>Kontrol</th>
		</tr>
		</thead>
		<tbody>
		<?php

if(isset($_POST['submit'])){
	$nmr=$_POST['txnomeja'];
	$jbangku=$_POST['txjmlbangku'];
	$keadaan=$_POST['rstat'];
	$meja_kat_id=$_POST['optjenis'];
	if ( strlen(trim($nmr))>0 && strlen(trim($jbangku))>0 && strlen(trim($keadaan))>0 ) {
		meja_simpan($con,$nmr,$jbangku,$keadaan,$meja_kat_id);
		header('location: ?ref=data-meja');exit;
	}else{
		echo "<script type=\"text/javascript\">alert('Data belum lengkap !');</script>";
		echo "<script type=\"text/javascript\">document.getElementById('txnomeja').focus();</script>";
	}
}

if (isset($_GET['del'])) {
	$nmr=$_GET['del'];
	meja_hapus($con,$nmr);
	header('location: ?ref=data-meja');exit;
}

if (isset($_GET['pil'])) {
	$nmr=$_GET['pil'];
	//$tmp=meja_nomor_by_id($con,$nmr);#echo $tmp;
	echo "<script type=\"text/javascript\">document.getElementById('txnomeja').value=\"$nmr\";</script>";
	#header('location: ?ref=data-meja');exit;
}


echo meja_tampil($con);
		?>
		</tbody>
	</table>
</div>

<?php

?>
