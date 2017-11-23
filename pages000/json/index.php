<?php
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DB', 'jresto');

error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Jayapura');
$tanggal=date('d-M-Y [H:i:s]',time());
$keys='085244444830';

$con=mysqli_connect(HOST,USER,PASS,DB);

// Check connection
if (mysqli_connect_errno()){
  echo "Failed to connect to MySQL: ".mysqli_connect_error();
}
//======================================================================
//======================================================================
//======================================================================



if(isset($_GET['ref'])&& $_GET['ref']!=''){
		switch ($_GET['ref']){
				case 'produk':
					get_produk($con);
					break;
				case 'meja':
					get_meja($con);
					break;
				case 'produk_dipilih':
					get_produk_dipilih($con);
					break;
				case 'dapur_menus':
					get_dapur_menu($con);
					break;
				default:
				break;
		}
}
//-------------------------------------------------------------------------

function get_produk_dipilih($con){
	if(isset($_GET['order_id']) && $_GET['order_id']!=''){
		$order_id=$_GET['order_id'];

		$sql="SELECT
			d.produk_id,
			d.produk_nama,
			d.produk_harga,
			d.jumlah,
			d.bungkus,
			p.kirim
		FROM
			pesanan_detail AS d
		INNER JOIN pesanan AS p
			ON p.id=d.pesanan_id
		WHERE
			d.pesanan_id=$order_id AND p.kirim='BLM'
		ORDER BY
			d.waktu ASC
		;";

		$arr_hasil=array();
		//$arr_hasil1=array();
		$berhasil=0;

		if($result=$con->query($sql)){
			while ($row=$result->fetch_row()) {
				array_push($arr_hasil, array('produk_id'=>$row[0],'produk_nama'=>$row[1],'produk_harga'=>$row[2],'jumlah'=>$row[3],'bungkus'=>$row[4],'kirim'=>$row[5]));
				$berhasil=1;
			}
			if($berhasil==1)echo json_encode(array("is_sukses"=>"1","hasil"=>$arr_hasil));
			if($berhasil==0)echo json_encode(array("is_sukses"=>"0"));
		}
		$result->close();
	}
}



function get_produk($con){

	if(isset($_GET['jenis']) && $_GET['jenis']!=''){

		$jenis=$_GET['jenis'];


		$sql="SELECT
			p.id,
			p.nama,
			p.harga,
			p.gambar,
			p.kategori_id
		FROM
			produk AS p
		INNER JOIN kategori AS k ON k.id=p.kategori_id
		WHERE
			k.jenis='$jenis' and p.tambahan='TIDAK'
		ORDER BY
			p.nama ASC
		;";

		$arr_hasil=array();
		//$arr_hasil1=array();
		$berhasil=0;

		if($result=$con->query($sql)){
			while ($row=$result->fetch_row()) {
				array_push($arr_hasil, array('id'=>$row[0],'nama'=>$row[1],'harga'=>$row[2],'gambar'=>$row[3],'jenis'=>$row[4]));
				$berhasil=1;
			}
			if($berhasil==1)echo json_encode(array("is_sukses"=>"1","hasil"=>$arr_hasil));
			if($berhasil==0)echo json_encode(array("is_sukses"=>"0"));
			//echo"test";
		}

		$result->close();

	}

}

function get_dapur_menu($con){
	$sort_by=$_GET['sort_by'];
  $jenis_1=$_GET['jenis'];
  $sisip="";
	switch($sort_by){
		case 'Jam':
		$sort="ORDER BY d.waktu ASC";
		break;
		case 'Item':
		$sort="ORDER BY d.produk_nama ASC, d.waktu ASC";
		break;
		case 'Meja':
		$sort="ORDER BY p.meja_nomor ASC, d.waktu ASC";
		break;
		default:
		$sort="ORDER BY d.jumlah ASC, d.waktu ASC";
		break;
	}

  switch ($jenis_1) {
    case 'makan':
      $sisip=" AND d.kategori_id IN (SELECT DISTINCT id FROM kategori WHERE jenis='MAKAN') ";
      break;
    case 'minum':
      $sisip=" AND d.kategori_id IN (SELECT DISTINCT id FROM kategori WHERE jenis='MINUM') ";
      break;
    default:
      //$sisip=" AND d.kategori_id IN (SELECT DISTINCT id FROM kategori WHERE 1) ";
      break;
  }

			$sql="
			SELECT
			  p.id,
			  p.meja_nomor,
			  d.produk_id,
			  d.produk_nama,
			  d.jumlah,
			  TIME(d.waktu),
			  d.bungkus
			FROM
			  pesanan AS p
			INNER JOIN pesanan_detail AS d ON d.pesanan_id=p.id
			WHERE
			  p.status='BUKA' AND p.kirim='SDH' AND d.batal='TIDAK' AND d.status='ON' AND siap='BLM' $sisip
			".$sort."
			;";
			//
			$arr_hasil=array();

			$berhasil=0;

			if($result=$con->query($sql)){
				while ($row=$result->fetch_row()) {
					array_push($arr_hasil, array('pesanan_id'=>$row[0],'nomor_meja'=>$row[1],'produk_id'=>$row[2],'produk_nama'=>$row[3],'jumlah'=>$row[4],'waktu'=>$row[5],'bungkus'=>$row[6]));
					$berhasil=1;
				}
				if($berhasil==1)echo json_encode(array("is_sukses"=>"1","hasil"=>$arr_hasil));
				if($berhasil==0)echo json_encode(array("is_sukses"=>"0"));

			}
			$result->close();
}


function get_meja($con){
		$sql="SELECT
			m.nomor,
			m.status,
			k.nama
		FROM
			meja AS m
		INNER JOIN meja_kat AS k ON k.id=m.meja_kat_id
		WHERE
			m.keadaan='BAIK'
		ORDER BY
			m.nomor ASC
		;";

		$arr_hasil=array();

		$berhasil=0;

		if($result=$con->query($sql)){
			while ($row=$result->fetch_row()) {
				array_push($arr_hasil, array('nomor'=>$row[0],'status'=>$row[1],'klas'=>$row[2]));
				$berhasil=1;
			}
			if($berhasil==1)echo json_encode(array("is_sukses"=>"1","hasil"=>$arr_hasil));
			if($berhasil==0)echo json_encode(array("is_sukses"=>"0"));

		}
		$result->close();
}
//=============================================================================================

if(isset($_POST['json'])){
	$data = json_decode($_POST["json"]);
    //$data->meja_dipilih;// = strrev($data->meja_dipilih);
	$nomor_meja=$data->meja_dipilih;
	$operator=$data->operator;

	$con->query("UPDATE meja SET status='TERISI' WHERE nomor='$nomor_meja' AND status='KOSONG';");
	//echo json_encode($data);

	for($i=0;$i<2;$i++){
		if ($rs=$con->query("SELECT id,meja_nomor,status FROM pesanan WHERE meja_nomor='$nomor_meja' AND status='BUKA' AND kirim='BLM';")) {
			if ($row=$rs->fetch_row()) {
				$pesanan_id=$row[0];	//sdh ada;
				$i=3;
				$data->pesanan_id=$pesanan_id;
			}else{
				$con->query("INSERT IGNORE INTO pesanan(meja_nomor,operator)VALUES('$nomor_meja','$operator');");
			}
		}
	}

	//$data->pesanan_id='123';

	echo json_encode($data);
}


if(isset($_POST['kirim_json'])){
	$data=json_decode($_POST['kirim_json']);
	$aksi=$data->aksi;
	switch($aksi){
		case 'pilih_menu':
			$pesanan_id=$data->pesanan_id;
			$meja_nomor=$data->meja_nomor;
			$produk_id=$data->produk_id;
			$operator=$data->operator;

			$sql="
			INSERT IGNORE INTO pesanan_detail(produk_id,produk_nama,produk_harga,kategori_id,pesanan_id,operator)
			SELECT id, nama, harga, kategori_id,
			(SELECT $pesanan_id) AS pes_id,
      (SELECT '$operator') AS op
			FROM produk
			WHERE id=$produk_id
			LIMIT 1
			;";

			if($con->query($sql)){
				echo json_encode(array("retVal"=>"SUKSES"));
			}else{
				echo json_encode(array("retVal"=>'TIDAK SUKSES'));
			}
			break;
    case 'pilih_menu_with_jml':
  		$pesanan_id=$data->pesanan_id;
  		#$meja_nomor=$data->meja_nomor;
  		$produk_id=$data->produk_id;
  		$operator=$data->operator;
      $jumlah=$data->jumlah;
      $bungkus=$data->bungkus;

  		$sql="
  			INSERT IGNORE INTO pesanan_detail(produk_id,produk_nama,produk_harga,kategori_id,pesanan_id,operator,jumlah,bungkus)
  			SELECT id, nama, harga, kategori_id,
  			(SELECT $pesanan_id) AS pes_id,
        (SELECT '$operator') AS op,
        (SELECT $jumlah) AS jml,
        (SELECT '$bungkus') AS bgks
  			FROM produk
  			WHERE id=$produk_id
  			LIMIT 1
        ON DUPLICATE KEY UPDATE operator='$operator',jumlah=$jumlah,bungkus='$bungkus'
  		;";

  		if($con->query($sql)){
  			echo json_encode(array("retVal"=>"SUKSES"));
  		}else{
  			echo json_encode(array("retVal"=>'TIDAK SUKSES'));
  		}
  		break;
		case 'hapus_menu_dipilih':
			$pesanan_id=$data->pesanan_id;
			$produk_id=$data->produk_id;
			$bungkus=$data->bungkus;
			$sql="DELETE FROM pesanan_detail WHERE pesanan_id=$pesanan_id AND produk_id=$produk_id AND bungkus='$bungkus';";
			if($con->query($sql)){
				echo json_encode(array("retVal"=>"SUKSES"));
			}else{
				echo json_encode(array("retVal"=>'TIDAK SUKSES'));
			}
			break;
		case 'ubah_menu_dipilih':
			$pesanan_id=$data->pesanan_id;
			$produk_id=$data->produk_id;
			$bungkus=$data->bungkus;
			$jumlah=$data->jumlah;
			$sql="UPDATE pesanan_detail SET jumlah=$jumlah, bungkus=IF(bungkus<>'$bungkus','$bungkus',bungkus) WHERE pesanan_id=$pesanan_id AND produk_id=$produk_id";
			if($con->query($sql)){
				echo json_encode(array("retVal"=>$jumlah));
			}else{
				echo json_encode(array("retVal"=>'TIDAK SUKSES'));
			}
			break;
		case 'kirim_pesanan':
		  $pesanan_id=$data->pesanan_id;
		  $sql="UPDATE pesanan SET kirim='SDH' WHERE id=$pesanan_id;";
		  if($con->query($sql)){
					echo json_encode(array("retVal"=>'SUKSES'));
				}else{
					echo json_encode(array("retVal"=>'TIDAK SUKSES'));
				}
		  break;
		case 'login_user':
			$user_name=$data->user_name;
			$user_pass=$data->user_pass;

			$sql="
			SELECT
			  AES_DECRYPT(name,'$keys'),
			  AES_DECRYPT(password,'$keys'),
			  AES_DECRYPT(role,'$keys')
			FROM
			  users
			WHERE
			  name=AES_ENCRYPT('$user_name','$keys') AND password=AES_ENCRYPT('$user_pass','$keys')
			;";
			$arr_hasil=array();

			$berhasil=0;

			if($result=$con->query($sql)){
				while ($row=$result->fetch_row()) {
					array_push($arr_hasil, array('nama'=>$row[0],'pass'=>$row[1],'role'=>$row[2]));
					$berhasil=1;
				}
				if($berhasil==1)echo json_encode(array("retVal"=>"SUKSES","hasil"=>$arr_hasil));
				if($berhasil==0)echo json_encode(array("retVal"=>"GAGAL"));

			}
			$result->close();

			break;

		case 'dapur_menu':
			$sort_by=$data->sort_by;

			$sql="
			SELECT
			  p.id,
			  p.meja_nomor,
			  d.produk_id,
			  d.produk_nama,
			  d.jumlah,
			  TIME(d.waktu)
			FROM
			  pesanan AS p
			INNER JOIN pesanan_detail AS d ON d.pesanan_id=p.id
			WHERE
			  p.status='BUKA' AND p.kirim='SDH' AND d.batal='TIDAK' AND d.status='ON'
			;";
			//
			$arr_hasil=array();

			$berhasil=0;

			if($result=$con->query($sql)){
				while ($row=$result->fetch_row()) {
					array_push($arr_hasil, array('pesanan_id'=>$row[0],'nomor_meja'=>$row[1],'produk_id'=>$row[2],'produk_nama'=>$row[3],'jumlah'=>$row[4],'waktu'=>$row[5]));
					$berhasil=1;
				}
				if($berhasil==1)echo json_encode(array("retVal"=>"SUKSES","hasil"=>$arr_hasil));
				if($berhasil==0)echo json_encode(array("retVal"=>"GAGAL"));

			}
			$result->close();

			break;
		case 'go_update_dapur':
			$pesanan_id=$data->pesanan_id;
			$produk_id=$data->produk_id;
			$bungkus=$data->bungkus;
			$jumlah=$data->jumlah;
			$sql="UPDATE pesanan_detail SET jumlah=$jumlah WHERE pesanan_id=$pesanan_id AND produk_id=$produk_id AND bungkus='$bungkus';";
			if($con->query($sql)){
				echo json_encode(array("retVal"=>'SUKSES'));
			}else{
				echo json_encode(array("retVal"=>'GAGAL'));
			}
			break;
		case 'go_cancel_dapur':
			$pesanan_id=$data->pesanan_id;
			$produk_id=$data->produk_id;
			$bungkus=$data->bungkus;
			$sql="UPDATE pesanan_detail SET batal='YA' WHERE pesanan_id=$pesanan_id AND produk_id=$produk_id AND bungkus='$bungkus';";
			if($con->query($sql)){
				echo json_encode(array("retVal"=>'SUKSES'));
			}else{
				echo json_encode(array("retVal"=>'GAGAL'));
			}
			break;
		case 'go_ready_dapur':
			$pesanan_id=$data->pesanan_id;
			$produk_id=$data->produk_id;
			$bungkus=$data->bungkus;
			$sql="UPDATE pesanan_detail SET siap='SDH', waktu_siap=current_timestamp() WHERE pesanan_id=$pesanan_id AND produk_id=$produk_id AND bungkus='$bungkus';";
			if($con->query($sql)){
				echo json_encode(array("retVal"=>'SUKSES'));
			}else{
				echo json_encode(array("retVal"=>'GAGAL'));
			}
			break;
		case 'get_grand_total':
			$nomor=$data->meja_nomor;
			$sql="
			SELECT m.nomor,
			(SELECT SUM(produk_harga*jumlah) FROM pesanan_detail WHERE pesanan_id IN (SELECT id FROM pesanan WHERE status='BUKA' AND kirim='SDH' AND meja_nomor='$nomor'))as gtot,
			(SELECT DISTINCT k.tarif FROM meja_kat AS k INNER JOIN meja AS m ON m.meja_kat_id=k.id WHERE m.nomor='$nomor') AS biaya_meja,
			(SELECT DISTINCT ppn FROM biaya_lain LIMIT 1) AS ppn_tax
			FROM meja AS m
			WHERE m.nomor='$nomor'
			;";
			$arr_hasil=array();
			$berhasil=0;
			$grandTotal=0;
			$total=0;
			if($result=$con->query($sql)){
				while ($row=$result->fetch_row()) {
					$total=$row[1]+$row[2];
					//$grandTotal=$total+(($row[3]*$total)/100);
          $grandTotal=$total;
					array_push($arr_hasil, array('grand_total'=>$grandTotal));
					$berhasil=1;
				}
				if($berhasil==1)echo json_encode(array("retVal"=>"SUKSES","hasil"=>$arr_hasil));
				if($berhasil==0)echo json_encode(array("retVal"=>"GAGAL"));
			}
			$result->close();
			break;
		default:
			break;
	}
}




//=============================================================================================
$con->close();
?>
