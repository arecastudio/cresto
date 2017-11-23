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
	min-width: 200px;
	min-height: 150px;
	border: solid 2px #000;
	border-radius: 10px;
	float: left;
	background-color: #fff;
	margin: 9px;color: #000;
	padding: 1px;
}
.sub-menu img{
	/*width: 100px;
	height: 100px;*/
}
/*.sub-menu:hover{
	background: gradient-linear(#ffb,#00b);
}*/
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
	SUM(d.jumlah),
	(SELECT DISTINCT psd.waktu FROM pesanan AS ps INNER JOIN pesanan_detail AS psd ON psd.pesanan_id=ps.id WHERE ps.meja_nomor=m.nomor AND ps.status='BUKA' AND ps.kirim='SDH' ORDER BY ps.waktu ASC LIMIT 1) AS tsort
FROM pesanan AS p
INNER JOIN pesanan_detail AS d ON d.pesanan_id=p.id
INNER JOIN meja AS m ON m.nomor=p.meja_nomor
WHERE m.status='TERISI' AND p.status='BUKA' AND d.siap='BLM' AND p.kirim='SDH' AND d.batal='TIDAK'
GROUP BY m.nomor
ORDER BY tsort ASC
;";
$no_meja="";$pesanan_array="";$jml=0;
if ($rs=$con->query($sql)) {
  while ($row=$rs->fetch_row()) {
    $no_meja=$row[0];
		$jml=$row[1];
		if ($jml>0) {
			echo"
	    <div class=\"sub-menu\" style=\"background:linear-gradient(#505,#fff);color:#fff;\">
	      <center>
	        <b>Pesanan Meja $no_meja</b>
					<table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" style=\"border-collapse:collapse;background-color:#fff;color:#000;\">
					<tr>
					<!--th>Jenis</th-->
					<th>Nama</th>
					<th>Jml</th>
					<th>Bgks</th>
					</tr>
					";
					$sql1="
					SELECT DISTINCT k.jenis, d.produk_nama, d.jumlah, d.bungkus
					FROM pesanan AS p
					INNER JOIN pesanan_detail AS d ON d.pesanan_id=p.id
					INNER JOIN meja AS m ON m.nomor=p.meja_nomor
					LEFT OUTER JOIN kategori AS k ON k.id=d.kategori_id
					WHERE  m.status='TERISI' AND p.status='BUKA' AND d.siap='BLM' AND d.batal='TIDAK' AND p.kirim='SDH' AND m.nomor='$no_meja'
					ORDER BY k.jenis DESC, d.waktu ASC
					;";
			if ($rs1=$con->query($sql1)) {
				while ($row1=$rs1->fetch_row()) {
					$ic_bungkus="&nbsp";
					if ($row1[3]=='YA') {
						$ic_bungkus="<img src=\"../images/ic_yes.png\" width=\"30px\" />";
					}
					echo "
						<tr>
						<!--td align=\"center\">$row1[0]</td-->
						<td>".strtoupper($row1[1])."</td>
						<td align=\"center\">$row1[2]</td>
						<td align=\"center\">$ic_bungkus</td>
						</tr>
					";
				}
			}
			echo"</table>
	      </center>
	    </div>
	    ";
		}
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
