<?php
require_once("inc/inc.php");
session_start();
if (isset($_SESSION['user_name']) && $_SESSION['user_name']!='') {
	$user_name=$_SESSION['user_name'];
}else{
	header('location: login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
	<title>Crishto Resto Information System - CResto V.201608</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="inc/jscript.js"></script>

	<!--script src="java/jquery-1.12.4.js"></script>
	<script src="java/jquery-ui.js"></script>
	<link rel="stylesheet" href="css/jquery-ui.css"-->

	<script src="jquery/jquery.js"></script>
	<script src="jquery/jquery-ui.js"></script>
	<link rel="stylesheet" href="jquery/jquery-ui.css">

	<script type="text/javascript" src="java/export/tableExport.js"></script>
	<script type="text/javascript" src="java/export/jquery.base64.js"></script>
	<script type="text/javascript" src="java/export/jspdf/libs/sprintf.js"></script>
	<script type="text/javascript" src="java/export/jspdf/jspdf.js"></script>
	<script type="text/javascript" src="java/export/jspdf/libs/base64.js"></script>

	<script type="text/javascript">
	  	$( function() {
	    	$( ".datepicker" ).datepicker({altFormat: 'yyyy-mm-dd'});
	    	$( ".datepicker" ).change(function() {
	  			$( ".datepicker" ).datepicker( "option", "dateFormat","yy-mm-dd" );
	  		});
	  	});

			$('input').attr('autocomplete','off');
			$('form').attr('autocomplete','off');
	</script>

</head>
<body onFocus="parent_disable();" onclick="parent_disable();" style="background-image:url('images/pattern.png')">
<div id="wrapper">
	<div id="head">
		<h3>&nbsp;</h3>
	</div>
	<div id="body-content">
		<table id="table-menu" border="0" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<td width="200px" class="kiri">
					<center><a href="?"><img src="images/christo-resto-1.png" width="180px"></a></center>
					<ul>
						<li><a href="?ref=master">Master</a></li>
						<li><a href="?ref=jual">Penjualan</a></li>
						<li><a href="?ref=dapur">Dapur</a></li>
						<li><a href="?ref=extra">Tool</a></li>
						<li><a href="?ref=laporan">Laporan</a></li>
						<li><a href="?ref=atur">Pengaturan</a></li>
						<!--li><a href="?ref=kontak">Contacts</a></li-->
						<li><a href="?ref=log-out" style="font-size:11px;color:#830;">Log Out <small>[<?php echo $user_name;?>]</small></a></li>
					</ul>
				</td>
				<td width="90%" class="kanan">
					<?php
						if (isset($_GET['ref'])) {
							switch ($_GET['ref']) {
								case 'master':
									require_once('pages/master.php');
									break;
								case 'jual':
									require_once('pages/jual.php');
									break;
								case 'data-meja':
									require_once('pages/data-meja.php');
									break;
								case 'data-meja-kat':
									require_once('pages/data-meja-kat.php');
									break;
								case 'non-cash-payment':
									require_once('pages/non-cash-payment.php');
									break;
								case 'menu-tambahan':
									require_once('pages/data-menu-tambah.php');
									break;
								case 'data-pesan-meja':
									require_once('pages/data-pesan-meja.php');
									break;
								case 'data-pesan-meja-langsung':
									require_once('pages/data-pesan-meja-langsung.php');
									break;
								case 'data-order':
									require_once('pages/data-order.php');
									break;
								case 'data-order-langsung':
									require_once('pages/data-order-langsung.php');
									break;
								case 'payment':
									require_once('pages/data-pelunasan.php');
									break;
								case 'payment-detail':
									require_once('pages/data-pelunasan-detail.php');
									break;
								case 'reprint':
									require_once('pages/data-pelunasan-reprint.php');
									break;
								case 'reprint-by-date':
									require_once('pages/data-pelunasan-reprint-by-date.php');
									break;
								case 'dapur':
									require_once('pages/pesan-dapur.php');
									break;
								case 'pesan-dapur-list':
									require_once('pages/pesan-dapur-list.php');
									break;
								case 'laporan':
									require_once('pages/laporan.php');
									break;
								case 'pemasukan-hari-ini':
									require_once('pages/laporan-pemasukan.php');
									break;
								case 'pemasukan-hari-ini-rekap':
									require_once('pages/laporan-pemasukan-rekap.php');
									break;
								case 'laporan-per-transaksi':
									require_once('pages/laporan-per-transaksi.php');
									break;
								case 'laporan-harian':
									$isBulan=0;
									require_once('pages/laporan-anual.php');
									break;
								case 'laporan-bulanan':
									$isBulan=1;
									require_once('pages/laporan-anual.php');
									break;
								case 'laporan-import':
									require_once('pages/import-laporan.php');
									break;
								case 'data-kat-menu':
									require_once('pages/data-kategori.php');
									break;
								case 'data-menu':
									require_once('pages/data-menu.php');
									break;
								case 'atur':
									require_once('pages/setting.php');
									break;
								case 'user-data':
									require_once('pages/user-data.php');
									break;
								case 'user-right':
									require_once('pages/user-right.php');
									break;
								case 'extra':
									require_once('pages/extra.php');
									break;
								case 'pindah-meja':
									require_once('pages/pindah-meja.php');
									break;
								case 'tutup-meja-kosong':
									require_once('pages/tutup-meja.php');
									break;
								case 'ubah-order':
									require_once('pages/ubah-order.php');
									break;
								case 'ubah-order-update':
									require_once('pages/ubah-order-update.php');
									break;
								case 'biaya-tambahan':
									require_once('pages/biaya-lain.php');
									break;
								case 'cancel-payment':
									require_once('pages/batal-lunas.php');
									break;
								case 'his-batal-meja':
									require_once('pages/his-batal-meja.php');
									break;
								case 'backup-database':
									#require_once('pages/ubah-order-update.php');
									$bkp=shell_exec("mysqldump -u root jresto>de:\jresto-db-backup-%date:~10,4%%date:~7,2%%date:~4,2%%time:~1,1%%time:~3,2%.sql");
									break;
								case 'log-out':
									$_SESSION['user_name']='';
									session_destroy();
									header('location: login.php');
								case 'locked':
									echo "<div style=\"margin:0 auto;width:400px;border:0px;height:300px;text-align:center;color:#f00;\"><img src=\"images/private-xxl.png\" width=\"150px\"/><h3>Anda tidak memiliki hak akses ke halaman yang dimaksud!</h3></div>";
										//echo $user_name;
									break;
								default:
									echo "<h2>Selamat Datang</h2>";
									break;
							}
						}else{
							echo "
							<div style=\"margin:0 auto;\">
							<center>
								<img src=\"images/background-img.jpg\" style=\"opacity:0.1;filter:alpha(opacity=50); width:60%;\"/>
							</center>
							</div>
							";
						}
					?>
				</td>
			</tr>
		</table>
	</div>
	<div id="foot">
		<a href="http://www.iternesia.com" style="text-decoration:none;color:#ddf;" target="_blank">Developer Site</a> &copy; 2016. All right reserved.
	</div>
</div>
</body>
</html>
<?php
#$con->close();
//echo $_SESSION['user_name'];
?>
