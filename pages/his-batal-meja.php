<?php
lock_page($con,"pengaturan_hak_akses",$user_name,$keys);
?>

<div class="lokasi">
	<a href="?ref=atur">Pengaturan</a> &rarr; History Pembatalan Meja
</div>
<div class="judul-frame">
	<b>Laporan History Pembatalan Meja</b>
</div>

<div style="margin-top:5px;margin-bottom:5px;">
	<form id="f1" action="" method="post">
		<input type="text" id="datepicker1" name="datepicker1" class="datepicker" size="12" placeholder="Tanggal" readonly/>
		s/d <input type="text" id="datepicker2" name="datepicker2" class="datepicker" size="12" placeholder="Tanggal" readonly/>
		<input type="submit" name="submit" value="Proses" style="width:150px;font-size:18px;color:blue;" />
	</form>
</div>

<div class="data-view-besar">
	<table width="100%" border="1" name="laporan-anual" id="laporan-anual" cellpadding="5" cellspacing="1">
    <thead>
    	<tr>
      	<th>#</th>
      	<th>Operator</th>
      	<th>No. Meja</th>
      	<th>Waktu Pembatalan</th>
      	<th>Menu Pesanan</th>
      	<th>Est. Harga</th>
    	</tr>
    </thead>
    <tbody>
      <?php

if(isset($_POST['submit']) && strlen($_POST['datepicker1'])>0 && strlen($_POST['datepicker2'])>0  ){
  $i=1;
  $tgl1=$_POST['datepicker1'];
  $tgl2=$_POST['datepicker2'];
//
  $sql="
SELECT b.users_name,p.meja_nomor,b.tgl,GROUP_CONCAT(CONCAT(d.produk_nama,'<br/>')),SUM(d.produk_harga*d.jumlah)
FROM his_batal_meja AS b
LEFT OUTER JOIN his_batal_pesanan AS p ON p.id=b.pesanan_id
LEFT OUTER JOIN his_batal_pesanan_detail AS d ON d.pesanan_id=p.id
WHERE (DATE(b.tgl) BETWEEN '$tgl1' AND '$tgl2')
GROUP BY p.id
ORDER BY p.id ASC,d.produk_id ASC
;";

  if ($rs=$con->query($sql)) {
    while ($row=$rs->fetch_row()) {
      echo"
        <tr>
        <td align=\"center\">$i</td>
        <td align=\"center\">$row[0]</td>
        <td align=\"center\">$row[1]</td>
        <td align=\"center\">$row[2]</td>
        <td align=\"center\">$row[3]</td>
        <td align=\"center\">$row[4]</td>
        </tr>
      ";
      $i++;
    }
  }
}
      ?>
    </tbody>
  </table>
</div>
