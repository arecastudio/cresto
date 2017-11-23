<?php
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DB', 'jresto');

error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Jayapura');
$tanggal=date('d-M-Y [H:i:s]',time());


$con=mysqli_connect(HOST,USER,PASS,DB);

// Check connection
if (mysqli_connect_errno()){
  echo "Failed to connect to MySQL: ".mysqli_connect_error();
}

function pola($end) {
  $adr=$_SERVER['PHP_SELF'];
  $alamat=explode("/",$adr);
  $akhir=end($alamat);
  $_RESULT=str_replace("$akhir","",$adr);
  echo $_SERVER['HTTP_HOST'],$_RESULT,$end;
}

function getNextAutoID($koneksi,$namaTabel,$namaID){
		$temp="";
		$sql="SELECT (MAX(".$namaID.")+1)as hasil FROM ".$namaTabel.";";
		if($rs=$koneksi->query($sql)){
			if($row=$rs->fetch_row()) $temp=$row[0];
		}
		return $temp;
}

function getAutoInc($koneksi,$namaTabel){
		$temp="";
		//$sql="SELECT (MAX(".$namaID.")+1)as hasil FROM ".$namaTabel.";";
		$sql="
SELECT `AUTO_INCREMENT`
FROM  INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = 'jresto'
AND   TABLE_NAME   = '$namaTabel';
		";
		if($rs=$koneksi->query($sql)){
			if($row=$rs->fetch_row()) $temp=$row[0];
		}
		return $temp;
}

function plusPajak($koneksi){
	$pajak=0;
	if ($rs=$koneksi->query("SELECT ;")) {
		# code...
	}
}

function meja_tampil($koneksi){
	$hasil="";
	$meja=null;
	if($meja=$koneksi->query("SELECT DISTINCT nomor,(SELECT DISTINCT nama FROM meja_kat WHERE id=meja.meja_kat_id LIMIT 1)AS jns,keadaan FROM meja ORDER BY nomor ASC;")){
		while ($row=$meja->fetch_row()) {
			$hasil.="
			<tr>
				<td>$row[0]</td>
				<td>$row[1]</td>
				<td>$row[2]</td>
				<td>
					<a href=\"?ref=data-meja&pil=$row[0]\"><img src=\"images/edit.png\" width=\"16px\" title=\"Edit Meja ini\"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href=\"?ref=data-meja&del=$row[0]\"><img src=\"images/delete.png\" width=\"16px\" title=\"Hapus Meja ini\" onclick=\"return confirm('Yakin untuk hapus?');\"></a>
				</td>
			</tr>
			";
		}
	}
	return $hasil;
}

function kategori_tampil($koneksi){
	$hasil="";
	$meja=null;
	if($meja=$koneksi->query("SELECT DISTINCT id,jenis,kategori FROM kategori ORDER BY id ASC;")){
		while ($row=$meja->fetch_row()) {
			$hasil.="
			<tr>
				<td>$row[0]</td>
				<td>$row[1]</td>
				<td>$row[2]</td>
				<td>
					<a href=\"?ref=data-kat-menu&pil=$row[0]\"><img src=\"images/edit.png\" width=\"16px\" title=\"Edit Meja ini\"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href=\"?ref=data-kat-menu&del=$row[0]\"><img src=\"images/delete.png\" width=\"16px\" title=\"Hapus Meja ini\" onclick=\"return confirm('Yakin untuk hapus?');\"></a>
				</td>
			</tr>
			";
		}
	}
	return $hasil;
}
$keys='085244444830';


function produk_tampil($koneksi){
	$hasil="";
	$meja=null;
	if($meja=$koneksi->query("SELECT DISTINCT id,nama,(SELECT DISTINCT kategori FROM kategori WHERE id=produk.kategori_id)AS kat,harga,gambar,kategori_id FROM produk ORDER BY id ASC;")){
		while ($row=$meja->fetch_row()) {
			$hasil.="
			<tr>
				<td>$row[0]</td>
				<td>$row[1]</td>
				<td>$row[2]</td>
				<td>$row[3]</td>
				<td>$row[4]</td>
				<td>
					<a href=\"?ref=data-menu&pil=$row[0]&produk_nama=$row[1]&produk_kategori=$row[5]&produk_harga=$row[3]&produk_gambar=$row[4]\"><img src=\"images/edit.png\" width=\"16px\" title=\"Edit Meja ini\"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href=\"?ref=data-menu&del=$row[0]\"><img src=\"images/delete.png\" width=\"16px\" title=\"Hapus Meja ini\" onclick=\"return confirm('Yakin untuk hapus?');\"></a>
				</td>
			</tr>
			";
		}
	}
	return $hasil;
}

function meja_simpan($koneksi,$nomor,$jbangku,$keadaan,$meja_kat_id){
	$koneksi->query("INSERT INTO meja(nomor,jumlah_bangku,keadaan,meja_kat_id)VALUES('$nomor',$jbangku,'$keadaan',$meja_kat_id) ON DUPLICATE KEY UPDATE jumlah_bangku=$jbangku,keadaan='$keadaan', meja_kat_id=$meja_kat_id;");
}

function meja_hapus($koneksi,$nomor){
	$koneksi->query("DELETE FROM meja WHERE nomor='$nomor';");
}

function meja_nomor_by_id($koneksi,$id){
	if($meja=$koneksi->query("SELECT DISTINCT nomor FROM meja WHERE id=$id LIMIT 1;")){
		while ($row=$meja->fetch_row()) {
			return trim($row[0]);
		}
	}
}


function kategori_simpan($koneksi,$id,$jenis,$kategori){
	$qry="INSERT INTO kategori(id,jenis,kategori)VALUES($id,'$jenis','$kategori') ON DUPLICATE KEY UPDATE jenis='$jenis',kategori='$kategori';";
	if($id==null) $qry="INSERT INTO kategori(jenis,kategori)VALUES('$jenis','$kategori');";
	$koneksi->query($qry);
}

function kategori_hapus($koneksi,$id){
	$koneksi->query("DELETE FROM kategori WHERE id=$id;");
}


function produk_simpan($koneksi,$id,$nama,$harga,$gambar,$kategori_id){
	$qry="INSERT INTO produk(id,nama,harga,gambar,kategori_id)VALUES($id,'$nama',$harga,'$gambar',$kategori_id) ON DUPLICATE KEY UPDATE nama='$nama',harga=$harga,gambar='$gambar',kategori_id=$kategori_id;";
	if($id==null) $qry="INSERT INTO produk(nama,harga,gambar,kategori_id)VALUES('$nama',$harga,'$gambar',$kategori_id);";
	$koneksi->query($qry);
	uploadFoto($gambar);
	//copy($gambar,"d:/1.jpg");
}

function produk_hapus($koneksi,$id){
	$koneksi->query("DELETE FROM produk WHERE id=$id;");
}

function uploadFoto($fileUp){
	$target_dir = "foto/";
	//$target_file = $target_dir . basename($_FILES[$fileUp]["name"]);
  $target_file = $target_dir . basename($_FILES[$fileUp]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
	    $check = getimagesize($_FILES[$fileUp]["tmp_name"]);
	    if($check !== false) {
	        echo "File is an image - " . $check["mime"] . ".";
	        $uploadOk = 1;
	    } else {
	        echo "File is not an image.";
	        $uploadOk = 0;
	    }
	}
	// Check if file already exists
	if (file_exists($target_file)) {
	    echo "Sorry, file already exists.";
	    $uploadOk = 0;
	}
	// Check file size
	if ($_FILES[$fileUp]["size"] > 500000) {
	    echo "Sorry, your file is too large.";
	    $uploadOk = 0;
	}
	// Allow certain file formats
	//if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"	&& $imageFileType != "gif" ) {
  if($imageFileType != "jpg" ) {
	    echo "Sorry, only JPG files are allowed.";
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES[$fileUp]["tmp_name"], $target_file)) {
	        echo "The file ". basename( $_FILES[$fileUp]["name"]). " has been uploaded.";
	    } else {
	        echo "Sorry, there was an error uploading your file.";
	    }
	}
}
//=============================================================================================================



function formatMoney($number, $fractional=false) {
	if ($fractional) {
		$number = sprintf('%.2f', $number);
	}
	while (true) {
		$replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
		if ($replaced != $number) {
			$number = $replaced;
		} else {
			break;
		}
	}
	return $number;
}

function getHari($numHr){
	$hri="";
	switch ($numHr){
		case 0:$hri="Minggu";break;
		case 1:$hri="Senin";break;
		case 2:$hri="Selasa";break;
		case 3:$hri="Rabu";break;
		case 4:$hri="Kamis";break;
		case 5:$hri="Jumat";break;
		case 6:$hri="Sabtu";
	}
	return $hri;

	//penggunaan: $tgl=getHari(date('w'))."-".date('d_M_Y-h_i_s');
}

function getDay($numHr){
	$hri="";
	switch ($numHr){
		case 1:$hri="Minggu";break;
		case 2:$hri="Senin";break;
		case 3:$hri="Selasa";break;
		case 4:$hri="Rabu";break;
		case 5:$hri="Kamis";break;
		case 6:$hri="Jumat";break;
		case 7:$hri="Sabtu";break;
	}
	return $hri;

	//penggunaan: $tgl=getHari(date('w'))."-".date('d_M_Y-h_i_s');
}

function getBulan($numBul){
	$bln="";
	switch ($numBul){
		case 0:$bln="Des";break;
		case 1:$bln="Jan";break;
		case 2:$bln="Feb";break;
		case 3:$bln="Mar";break;
		case 4:$bln="Apr";break;
		case 5:$bln="Mei";break;
		case 6:$bln="Jun";break;
		case 7:$bln="Jul";break;
		case 8:$bln="Agust";break;
		case 9:$bln="Sept";break;
		case 10:$bln="Okt";break;
		case 11:$bln="Nov";break;
		case 12:$bln="Des";
	}
	return $bln;
}

function konversi($x){
	$x = abs($x);
	$angka = array ("","Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
	$temp = "";
	if($x < 12){
		$temp = " ".$angka[$x];
	}else if($x<20){
		$temp = konversi($x - 10)." Belas";
	}else if ($x<100){
		$temp = konversi($x/10)." Puluh". konversi($x%10);
	}else if($x<200){
		$temp = " Seratus".konversi($x-100);
	}else if($x<1000){
		$temp = konversi($x/100)." Ratus".konversi($x%100);
	}else if($x<2000){
		$temp = " Seribu".konversi($x-1000);
	}else if($x<1000000){
		$temp = konversi($x/1000)." Ribu".konversi($x%1000);
	}else if($x<1000000000){
		$temp = konversi($x/1000000)." Juta".konversi($x%1000000);
	}else if($x<1000000000000){
		$temp = konversi($x/1000000000)." Milyar".konversi($x%1000000000);
	}
	return $temp;
}

//=====================================================================
 function lock_page($koneksi, $field_name, $operator,$keys){
   if($rs=$koneksi->query("SELECT r.$field_name FROM role AS r INNER JOIN users AS u ON AES_DECRYPT(u.role,'$keys')=r.id WHERE AES_DECRYPT(u.name,'$keys')='$operator' LIMIT 1;")){
     if ($row=$rs->fetch_row()) {
       if($row[0]==0){
         #echo"<script>alert('Anda tidak memiliki hak akses pada halaman ini!');</script>";
         header('Location: ?ref=locked');
       }
     }
   }
 }
#update role set master_data_meja=1,master_daftar_menu=1,master_kategori_menu=1,penjualan_pemesanan=1,penjualan_pelunasan=1,tool_pindah_meja=1,tool_batalkan_meja=1,tool_batalkan_pelunasan=1,tool_ubah_pesanan=1,laporan_harian=1,laporan_bulanan=1,laporan_import=1,pengaturan_data_pengguna=1,pengaturan_hak_akses=1,pengaturan_biaya_lain2=1 WHERE id='admin';
?>
