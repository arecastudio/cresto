<?php
lock_page($con,"laporan_import",$user_name,$keys);
?>

<div class="lokasi">
	<a href="?ref=laporan">Laporan</a> &rarr; Import Laporan
</div>

<div class="judul-frame">
	<b>Import Laporan</b>
</div>

<form method="post" action="" enctype='multipart/form-data'>
	<div>
	<fieldset>
		Pilih laporan CSV yang hendak di-import
		&nbsp;
		&nbsp;
		&nbsp;
		&nbsp;
		<input type="file" name="filename" placeholder="File Laporan CSV" />
		&nbsp;
		&nbsp;
		&nbsp;
		&nbsp;
		<input type="submit" name="submit" value="Import" style="width:150px;font-size:18px;color:blue;">
	</fieldset>
	</div>
</form>

<?php
if (isset($_POST['submit'])) {
	if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
		echo "<h1>" . "File <i style=\"color:#700;\">". $_FILES['filename']['name'] ."</i> berhasil di-import." . "</h1>";
		echo "<h2>Isi file adalah:</h2>";
		readfile($_FILES['filename']['tmp_name']);
	}

	//Import uploaded file to Database
	$handle = fopen($_FILES['filename']['tmp_name'], "r");

	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		#$sql="UPDATE pesanan_detail SET status='$data[4]' WHERE pesanan_id=$data[0] AND produk_id=$data[1] AND bungkus='$data[3]';";
		$sql="UPDATE faktur_stat SET status=UCASE('$data[12]') WHERE faktur_id=$data[1];";
		$con->query($sql);
	}
	fclose($handle);

	$sql="SELECT faktur_id FROM faktur_stat WHERE UCASE(status)='OFF';";
	if ($rs=$con->query($sql)) {
		while ($row=$rs->fetch_row()) {
			$faktur_id=$row[0];
			$con->query("DELETE FROM pesanan_detail WHERE pesanan_id IN (SELECT pesanan_id FROM faktur WHERE id=$faktur_id);");
			$con->query("DELETE FROM pesanan WHERE id IN (SELECT pesanan_id FROM faktur WHERE id=$faktur_id);");
			$con->query("DELETE FROM faktur_stat WHERE faktur_id=$faktur_id;");
			$con->query("DELETE FROM faktur WHERE id=$faktur_id;");
		}
	}

	$handle2 = fopen($_FILES['filename']['tmp_name'], "r");

	while (($data = fgetcsv($handle2, 1000, ",")) !== FALSE) {
		#$sql="UPDATE pesanan_detail SET status='$data[4]' WHERE pesanan_id=$data[0] AND produk_id=$data[1] AND bungkus='$data[3]';";
		$sql1="UPDATE faktur_stat SET faktur_id=$data[13] WHERE faktur_id=$data[1];";
		$con->query($sql1);

		$sql2="UPDATE faktur SET id=$data[13] WHERE id=$data[1];";
		$con->query($sql2);
	}

	fclose($handle2);

	print "<h3>Proses import selesai</h3>";
}
?>
