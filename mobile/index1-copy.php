<?php
require_once('../inc/inc.php');
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="assets/jquery.mobile-1.4.5.min.css">
<script src="assets/jquery-1.11.3.min.js"></script>
<script src="assets/jquery.mobile-1.4.5.min.js"></script>
<meta http-equiv="refresh" content="5" >
<style>
/* BOF menu dan sub menu*/
.sub-menu{
	min-width: 300px;
	min-height: 250px;
	border: dashed 2px #00d;
	border-radius: 10px;
	float: left;
	background-color: #fff;
	margin: 9px;color: #000;
	padding: 5px;
}
.sub-menu img{
	width: 100px;
	height: 100px;
}
.sub-menu:hover{
	background-color: #ffb;
}
/* EOF menu dan sub menu*/
hr{border: solid 1px #00d;}
.kiri{
float:left;
margin-right: 10px;
padding-right: 10px;
}
</style>
</head>
<body>

<div data-role="page">
	<div data-role="header" data-theme="b">
		<a href="./" data-icon="home" data-iconpos="notext" data-transition="fade">Home</a>
		<h1>Menu Pesanan Per Meja ~ Cristho Resto Sentani</h1>
	</div>

	<div data-role="main" class="ui-content" style="vertical-align: text-top;padding:5px;">

    <?php
$sql="SELECT DISTINCT
m.nomor,
(SELECT GROUP_CONCAT(d.produk_nama SEPARATOR '<br/> ') FROM pesanan_detail AS d INNER JOIN pesanan AS p ON p.id=d.pesanan_id WHERE p.meja_nomor=m.nomor AND d.batal='TIDAK' AND d.status='ON' AND p.kirim='SDH' AND siap='BLM') AS prod,
(SELECT GROUP_CONCAT(d.jumlah SEPARATOR '<br/> ') FROM pesanan_detail AS d INNER JOIN pesanan AS p ON p.id=d.pesanan_id WHERE p.meja_nomor=m.nomor AND d.batal='TIDAK' AND d.status='ON' AND p.kirim='SDH' AND siap='BLM') AS jml
FROM meja AS m
INNER JOIN pesanan AS ps ON ps.meja_nomor=m.nomor
WHERE m.status='TERISI' AND ps.status='BUKA' AND (SELECT COUNT(produk_id) FROM pesanan_detail WHERE pesanan_id=ps.id)>0
ORDER BY TIME(ps.waktu) ASC
;";
$no_meja="";$pesanan_array="";$jml=0;
if ($rs=$con->query($sql)) {
  while ($row=$rs->fetch_row()) {
    $no_meja=$row[0];
    $pesanan_array=$row[1];
    $jml=$row[2];
    echo"
    <div class=\"sub-menu\">
      <center>
        <h3>Pesanan Meja $no_meja</h3><hr/>
      </center>

      <div class=\"kiri\">$pesanan_array</div>
      <div class=\"kiri\">$jml</div>
    </div>
    ";
  }
}
    ?>



	</div>

	<div data-role="footer" data-theme="b">
		<h5 style="font-size:60%;">&copy; 2016</h5>
	</div>

</div>

</body>
</html>
