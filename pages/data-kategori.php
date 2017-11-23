<?php
lock_page($con,"master_kategori_menu",$user_name,$keys);
?>

<div class="lokasi">
	<a href="?ref=master">Master</a> &rarr; Data Kategori Menu
</div>

<div class="judul-frame">
	<b>Master Data Kategori Menu</b>
</div>

<div class="data-entry">
	<form method="post" action="">
	<table width="250px" cellspacing="0" cellpadding="2">
		<tr>
			<td>ID</td>
			<td><input type="text" name="txid" id="txid" size="6" readonly style="background-color: #ccc;" /></td>
		</tr>
		<tr>
			<td>Jenis</td>
			<td><!--input type="text" name="txjmlbangku" size="5px" placeholder="Angka" onkeypress="return NumbersOnly(event);" maxlength="3" /-->
				<select name="optjenis" id="optjenis">
					<option value="MAKAN">MAKAN</option>
					<option value="MINUM">MINUM</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Kategori</td>
			<td>
				<input type="text" name="txkategori" id="txkategori" size="20" placeholder="Kategori menu..." />
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
		<tr>
			<th>ID</th>
			<th>Jenis</th>
			<th>Kategori</th>
			<th>Kontrol</th>
		</tr>
		<?php 

if(isset($_POST['submit'])){
	$id=$_POST['txid'];
	$jenis=$_POST['optjenis'];
	$kategori=$_POST['txkategori'];
	if ( strlen(trim($jenis))>0 && strlen(trim($kategori))>0 ) {
		kategori_simpan($con,$id,$jenis,$kategori);
		header('location: ?ref=data-kat-menu');exit;
	}else{
		echo "<script type=\"text/javascript\">alert('Data belum lengkap !');</script>";		
		echo "<script type=\"text/javascript\">document.getElementById('txkategori').focus();</script>";		
	}
}


if (isset($_GET['del'])) {
	$nmr=$_GET['del'];
	kategori_hapus($con,$nmr);
	header('location: ?ref=data-kat-menu');exit;
}

if (isset($_GET['pil'])) {
	$nmr=$_GET['pil'];
	//$tmp=meja_nomor_by_id($con,$nmr);#echo $tmp;
	echo "<script type=\"text/javascript\">document.getElementById('txid').value=\"$nmr\";</script>";
	#header('location: ?ref=data-kat-menu');exit;
}

		echo kategori_tampil($con);

		?>
	</table>
</div>

<?php

?>