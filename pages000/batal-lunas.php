<?php
lock_page($con,"tool_batalkan_pelunasan",$user_name,$keys);
?>

<?php
if (isset($_POST['batal'])) {
	$count=$_POST['hid_count'];
	for ($i=0; $i < $count ; $i++) {
	 if (isset($_POST['btl'.$i])) {
		 $fak_id=$_POST['hid_id'.$i];
		 $no_meja=$_POST['hid_meja'.$i];
	 	$sql="UPDATE meja SET status='TERISI' WHERE status='KOSONG' AND nomor='$no_meja';";
		if($exec=$con->query($sql)){
			$con->query("UPDATE pesanan SET status='BUKA' WHERE status='TUTUP' AND id IN (SELECT pesanan_id FROM faktur WHERE id=$fak_id);");
			$con->query("DELETE FROM faktur WHERE id=$fak_id;");
			$con->query("DELETE FROM faktur_stat WHERE faktur_id=$fak_id;");
		}
	 }
	}
}
?>

<div class="lokasi">
	<a href="?ref=extra">Tool</a> &rarr; Batal Pelunasan
</div>

<div class="judul-frame">
	<b>Batalkan Pelunasan</b>
</div>

<div style="margin-top:5px;margin-bottom:5px;">
	<form id="f1" action="" method="post">
		<input type="text" id="datepicker1" name="datepicker1" class="datepicker" size="12" placeholder="Tanggal" readonly/>
    &nbsp;&nbsp;&nbsp;
		<input type="submit" name="submit" value="Proses" style="width:150px;font-size:18px;color:blue;" />
	</form>
</div>

<div class="data-view-besar">
  <form action="" method="post">
	<table width="100%" border="1" name="laporan-anual" id="laporan-anual" cellpadding="5" cellspacing="1">
    <thead>
    	<tr>
    	<th>#</th>
    	<th>ID Pesanan</th>
    	<th>Meja</th>
    	<th>Waktu Pesan</th>
    	<th>Waktu Pelunasan</th>
    	<th>Jml Item</th>
    	<th>Total</th>
    	<th>Batalkan</th>
    	</tr>
    </thead>
    <tbody>
      <?php
      if(isset($_POST['submit']) && strlen($_POST['datepicker1'])>0){
      	$i=1;
      	$tgl1=$_POST['datepicker1'];
      	echo "<script type=\"text/javascript\">document.getElementById('datepicker1').value=\"$tgl1\";</script>";

      /*  $sql="SELECT
    			p.id,
    			p.meja_nomor,
    			p.waktu,
    			p.waktu_lunas,
    			COUNT(d.produk_id),
    			SUM(d.produk_harga*d.jumlah),
    			p.operator
    		FROM pesanan AS p
				INNER JOIN pesanan_detail as d ON d.pesanan_id=p.id
    		WHERE p.status='TUTUP' AND date(p.waktu_lunas)='$tgl1' AND d.status='ON'
    		GROUP BY p.id
    		ORDER BY p.id ASC, p.waktu_lunas ASC
    		;";*/

				$sql="SELECT DISTINCT
				f.id,
				p.meja_nomor,
				p.waktu,
				p.waktu_lunas,
				SUM(d.jumlah),
				SUM((d.produk_harga * d.jumlah) - (((d.produk_harga*d.jumlah)*fs.diskon)/100))+fs.meja_kat_tarif,
				p.operator
				FROM faktur AS f
				INNER JOIN faktur_stat AS fs ON f.id=fs.faktur_id
				INNER JOIN pesanan AS p ON p.id=f.pesanan_id
				INNER JOIN pesanan_detail AS d ON d.pesanan_id=p.id
				WHERE d.status='ON' AND p.status='TUTUP' AND d.batal='TIDAK' AND fs.status='ON' AND f.pesanan_id=d.pesanan_id AND DATE(f.tanggal)='$tgl1'
				GROUP BY fs.faktur_id
				ORDER BY f.pesanan_id ASC, f.tanggal ASC
				;";



				$i=0;
        if ($rs=$con->query($sql)) {
    			while ($row=$rs->fetch_row()) {
    				echo"
    					<tr>
    					<td align=\"center\">$i</td>
    					<td align=\"center\">$row[0]</td>
    					<td align=\"center\">$row[1]</td>
    					<td align=\"center\">$row[2]</td>
    					<td align=\"center\">$row[3]</td>
    					<td align=\"right\">".formatMoney($row[4])."</td>
    					<td align=\"right\">".formatMoney($row[5])."</td>
    					<td align=\"center\"><input type=\"checkbox\" name=\"btl$i\" id=\"btl$i\" /></td>
							<input type=\"hidden\" name=\"hid_id$i\" value=\"$row[0]\" />
							<input type=\"hidden\" name=\"hid_meja$i\" value=\"$row[1]\" />
    					</tr>
    				";
    				$i++;
    			}
    		}
				echo "<input type=\"hidden\" name=\"hid_count\" value=\"$i\" />";

        echo"
        <tr style=\"background-color:#ffe;height:70px;\">
          <td colspan=\"8\">
            <input type=\"submit\" name=\"batal\" value=\"Batalkan Pelunasan\" style=\"width:250px;font-size:18px;color:red;\"/>
          </td>
        </tr>
        ";

      }
      ?>

    </tbody>
  </table>
  </form>
</div>
