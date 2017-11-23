<?php
lock_page($con,"pengaturan_data_pengguna",$user_name,$keys);

if (isset($_GET['del'])&& $_GET['del']!='admin') {
	$nama=$_GET['del'];
	$sql="DELETE FROM users WHERE name=AES_ENCRYPT('$nama','$keys');";
	if ($con->query($sql)) {
		//echo "<script type=\"text/javascript\">alert('User berhasil dihapus!');</script>";
		header('location: ?ref=user-data');
	}
}

if (isset($_POST['submit'])) {
	$simpan=false;
	$nama=strtolower($_POST['user_name']);
	$pass=$_POST['user_password'];
	$role=$_POST['opthak'];

echo "<h1>$nama</h1>";

	if ($nama=='admin') {
		$role='admin';
	}

	$sql="
		INSERT INTO users (name,password,role)
		VALUES(
		AES_ENCRYPT('$nama','$keys'),
		AES_ENCRYPT('$pass','$keys'),
		AES_ENCRYPT('$role','$keys')
		)
		ON DUPLICATE KEY UPDATE
		password=AES_ENCRYPT('$pass','$keys'),
		role=AES_ENCRYPT('$role','$keys'),
		date_change=current_timestamp()
	;";

	if (strlen(trim($nama))>0 && strlen(trim($pass))>0 && strlen(trim($role))>0) {
		$simpan=true;
	}

	if ($simpan==true) {
		if ($con->query($sql)) {
			//echo "<script type=\"text/javascript\">alert('Data User berhasil disimpan!');</script>";
			header('location: ?ref=user-data');
		}
	}
}

?>

<div class="lokasi">
	<a href="?ref=atur">Pengaturan</a> &rarr; Pengguna
</div>

<div class="judul-frame">
	<b>Data Pengguna Aplikasi</b>
</div>


<form name="f1" method="post" action="" autocomplete="off">
	<div style="margin:10px;padding:15px;border:dotted 1px #ccc;text-align: center;">
		<input type="text" name="user_name" id="user_name" size="20px" placeholder="Nama User [tanpa spasi]"/>
		<input type="text" name="user_password" id="user_password" size="30px" placeholder="Password User/Pengguna" />
		<select name="opthak" id="opthak">
			<option value="">--Hak Akses--</option>
			<?php
				$res=$con->query("SELECT id FROM role WHERE id NOT LIKE '%admin%' ORDER BY id ASC;");
				while ($row=$res->fetch_row()) {
					echo "<option value=\"$row[0]\">".ucwords($row[0])."</option>";
				}
			?>
		</select>

		<input type="submit" name="submit" value="Simpan" style="width:150px;font-size:18px;color:blue;" />

	</div>
</form>

<div style="margin:0 auto; width: 60%;border:solid 1px #ccc;overflow:auto;height:350px;">
	<table width="100%" border="1" cellpadding="5" style="border-collapse: collapse;background-color: #fff;">
		<thead style="color: #fff;background:linear-gradient(#888,#000);">
			<tr>
				<th>#</th>
				<th>Nama</th>
				<th>Password</th>
				<th>Hak Akses</th>
				<th colspan="2">Kontrol</th>
			</tr>
		</thead>
		<tbody>
			<?php
$i=0;
$sql="
SELECT
	AES_DECRYPT(name,'$keys'),
	AES_DECRYPT(password,'$keys'),
	AES_DECRYPT(role,'$keys')
FROM
	users
ORDER BY
	date_add ASC
;";

$res=$con->query($sql);
while ($row=$res->fetch_row()) {
	$i++;
	echo "
		<tr>
		<td align=\"center\">$i</td>
		<td>$row[0]</td>
		<td align=\"center\">".substr($row[1],0,1)."***</td>
		<td align=\"center\">$row[2]</td>

				<td align=\"center\">
					<a href=\"?ref=user-data&pil=$row[0]&password=$row[1]&hak_akses=$row[2]\"><img src=\"images/edit.png\" width=\"16px\" title=\"Edit User ini\"></a>
				</td>
				<td align=\"center\">";
				if ($row[0]=='admin') {
					echo "&nbsp;";
				}else{
				echo "
					<a href=\"?ref=user-data&del=$row[0]\"><img src=\"images/delete.png\" width=\"16px\" title=\"Hapus User ini\" onclick=\"return confirm('Yakin untuk hapus?');\"></a>
					";
				}
				echo "
				</td>


		</tr>
	";
}
			?>
		</tbody>
	</table>
</div>

<div style="margin:10px;padding:15px;border:dotted 1px #ccc;text-align: center;">
Keterangan: User Admin hanya berjumlah 1 (satu) dan hanya diperkenankan untuk kontrol ubah password.
</div>

<?php
if (isset($_GET['pil']) && isset($_GET['password']) && isset($_GET['hak_akses'])) {
	$nama=$_GET['pil'];
	$pass=$_GET['password'];
	$role=$_GET['hak_akses'];

	echo "<script type=\"text/javascript\">document.getElementById('user_name').value=\"$nama\";</script>";
	echo "<script type=\"text/javascript\">document.getElementById('user_password').value=\"$pass\";</script>";
	echo "<script type=\"text/javascript\">document.getElementById('opthak').value=\"$role\";</script>";
}

?>
