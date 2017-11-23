<?php
lock_page($con,"penjualan_pemesanan",$user_name,$keys);
?>

<div class="lokasi">
	<a href="?ref=jual">Penjualan</a> &rarr; Pemesanan Langsung
</div>

<div class="judul-frame">
	<b>Data Pemesanan Langsung</b>
</div>


<?php
	if($meja=$con->query("SELECT DISTINCT nomor,status FROM meja WHERE keadaan='BAIK' ORDER BY nomor ASC;")){
		while ($row=$meja->fetch_row()) {
			if($row[1]=='KOSONG'){
				echo "
				<a href=\"?ref=data-pesan-meja-langsung&nomor=$row[0]\">
					<div class=\"sub-menu\">
						<center>
							<img src=\"images/data-meja.png\">
							<br/>
							<h3>Meja $row[0] - <small>$row[1]</small></h3>
						</center>
					</div>
				</a>
				";
			}else{
				echo "
				<a href=\"?ref=data-pesan-meja-langsung&nomor=$row[0]\">
					<div class=\"sub-menu\" style=\"border-color:#f00;\">
						<center>
							<img src=\"images/meja-terisi.png\">
							<br/>
							<h3>Meja $row[0] - <small>$row[1]</small></h3>
						</center>
					</div>
				</a>
				";
			}
		}
	}
?>
