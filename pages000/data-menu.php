<?php
ob_start();

lock_page($con,"master_daftar_menu",$user_name,$keys);
?>

<div class="lokasi">
	<a href="?ref=master">Master</a> &rarr; Daftar Menu
</div>

<div class="judul-frame">
	<b>Master Daftar Menu</b>
</div>

<div class="data-entry">
	<form method="post" action="" enctype="multipart/form-data" autocomplete="off">
	<table width="250px" cellspacing="0" cellpadding="2">
		<tr>
			<td>ID</td>
			<td><input type="text" name="txid" id="txid" size="6" readonly style="background-color: #ccc;" /></td>
		</tr>
		<tr>
			<td>Nama</td>
			<td>
				<input type="text" name="txnama" id="txnama" size="35" placeholder="Nama menu..." />
			</td>
		</tr>
		<tr>
			<td>Kategori</td>
			<td><!--input type="text" name="txjmlbangku" size="5px" placeholder="Angka" onkeypress="return NumbersOnly(event);" maxlength="3" /-->
				<select name="optkategori" id="optkategori">
					<?php

					if($kate=$con->query("SELECT DISTINCT id,kategori FROM kategori ORDER BY kategori ASC;")){
						while ($row=$kate->fetch_row()) {
							echo"<option value=\"$row[0]\">$row[1]</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Harga</td>
			<td>
				<input type="text" name="txharga" id="txharga" size="20" placeholder="Harga menu..." />
			</td>
		</tr>
		<tr>
			<td>Gambar</td>
			<td>
				<!--input type="hidden" name="size" value="350000"/-->
				<input type="file" name="txfile" id="txfile" accept="image/*" />
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
			<th>Nama</th>
			<th>Kategori</th>
			<th>Harga</th>
			<th>Gambar</th>
			<th>Kontrol</th>
		</tr>
		<?php

		if (isset($_GET['pil'])) {
			$nmr=$_GET['pil'];
			$nama=$_GET['produk_nama'];
			$proka=$_GET['produk_kategori'];
			$proga=$_GET['produk_harga'];
			//$tmp=meja_nomor_by_id($con,$nmr);#echo $tmp;
			echo "<script type=\"text/javascript\">document.getElementById('txid').value=\"$nmr\";</script>";
			echo "<script type=\"text/javascript\">document.getElementById('txnama').value=\"$nama\";</script>";
			echo "<script type=\"text/javascript\">document.getElementById('txharga').value=\"$proga\";</script>";
			echo "<script type=\"text/javascript\">document.getElementById('optkategori').value=\"$proka\";</script>";
			#header('location: ?ref=data-kat-menu');exit;
		}

		if (isset($_GET['del'])) {
			$nmr=$_GET['del'];
			produk_hapus($con,$nmr);
			header('location: ?ref=data-menu');exit;
		}

		if(isset($_POST['submit'])){
			$id=$_POST['txid'];
			$nama=$_POST['txnama'];
			//$gambar=$_POST['txfile'];
			$pic_name=$_FILES['txfile']['name'];
			$harga=$_POST['txharga'];
			$kategori_id=$_POST['optkategori'];

			/*$target_dir = "foto/";
			$target_file = $target_dir . basename($_FILES['txfile']['name']);
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);*/
			//$handle = fopen($_FILES["txfile"]["tmp_name"], 'r');

			$nama_gbr=getAutoInc($con,"produk").".jpg";
			if ($id!=null)$nama_gbr=$id.".jpg";

			if ( strlen(trim($nama))>0 && strlen(trim($harga))>0 ) {
				//produk_simpan($con,$id,$nama,$harga,$gambar,$kategori_id);

				$qry="INSERT INTO produk(id,nama,harga,gambar,kategori_id,waktu_ubah,operator)VALUES($id,'$nama',$harga,'$pic_name',$kategori_id,current_timestamp(),'$user_name') ON DUPLICATE KEY UPDATE nama='$nama',harga=$harga,gambar='$pic_name',kategori_id=$kategori_id,waktu_ubah=current_timestamp(),operator='$user_name';";
				if($id==null) $qry="INSERT INTO produk(nama,harga,gambar,kategori_id,operator)VALUES('$nama',$harga,'$pic_name',$kategori_id,'$user_name');";
				$con->query($qry);

				if(isset($_FILES['txfile']['name'])  ){
					$tmp_name=$_FILES['txfile']['tmp_name'];

					//move_uploaded_file($tmp_name, "foto/".$pic_name);

					move_uploaded_file($tmp_name, $_SERVER["DOCUMENT_ROOT"]."/cresto/foto/".$nama_gbr);
					#move_uploaded_file($tmp_name, "/var/www/html/cresto/foto/".$nama_gbr);

					//echo $tmp_name."<br/>".$pic_name."<br/>".$_FILES['txfile']['error']."<br/>".$_FILES['txfile'];
				}
				//echo $handle;
				//exit(header('location: ?ref=data-menu'));
			}else{
				echo "<script type=\"text/javascript\">alert('Data belum lengkap !');</script>";
				echo "<script type=\"text/javascript\">document.getElementById('txnama').focus();</script>";
			}
		}

		//echo produk_tampil($con);
		if($meja=$con->query("SELECT DISTINCT id,nama,(SELECT DISTINCT kategori FROM kategori WHERE id=produk.kategori_id)AS kat,harga,SUBSTR(gambar,1,10) AS gbr,kategori_id FROM produk WHERE tambahan='TIDAK' ORDER BY id ASC;")){
			while ($row=$meja->fetch_row()) {
				echo"
				<tr>
					<td>$row[0]</td>
					<td>$row[1]</td>
					<td>$row[2]</td>
					<td>$row[3]</td>
					<td>$row[4]</td>
					<td>
						<a href=\"?ref=data-menu&pil=$row[0]&produk_nama=$row[1]&produk_kategori=$row[5]&produk_harga=$row[3]&produk_gambar=$row[4]\"><img src=\"images/edit.png\" width=\"16px\" title=\"Edit Meja ini\"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href=\"?ref=data-menu&del=$row[0]\"><img src=\"images/delete.png\" width=\"16px\" title=\"Hapus Menu ini\" onclick=\"return confirm('Yakin untuk hapus?');\"></a>
					</td>
				</tr>
				";
			}
		}


//echo getNextAutoID($con,"produk","id");
ob_end_flush();
		?>
	</table>
</div>
