<?php
define('HOST', 'localhost');
define('USER', 'rail');
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
	k.jenis='$jenis'
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
$con->close();
?>
