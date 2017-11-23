<?php
lock_page($con,"penjualan_pelunasan",$user_name,$keys);
?>

<div class="lokasi">
	<a href="?ref=jual">Penjualan</a> &rarr; Pelunasan
</div>

<div class="judul-frame">
	<b>Data Pelunasan Per Meja</b>
</div>

<?php
	if($meja=$con->query("SELECT DISTINCT nomor FROM meja WHERE keadaan='BAIK' AND status='TERISI' ORDER BY nomor ASC;")){
		while ($row=$meja->fetch_row()) {

				echo "
				<a href=\"?ref=payment-detail&nomor=$row[0]\">
					<div class=\"sub-menu\" style=\"border-color:#f00;\">
						<center>
							<img src=\"images/meja-terisi.png\">
							<br/>
							<h3>Meja $row[0]
						</center>
					</div>
				</a>
				";

		}
	}
?>
