
<script type="text/javascript">
$(document).ready(function() {
	$('html').bind('keypress', function(e)
	{
	   if(e.keyCode != 13)
	   {
	      return false;
	   }
	});
});
</script>

<?php
$i=0;

if (isset($_POST['hid3']) && isset($_POST['hid4'])) {
	$meja_nomor=$_POST['hid3'];
	$pesanan_id=$_POST['hid4'];
}else{
	header('location: ?ref=ubah-order');
}
?>

<div class="lokasi">
	<a href="?ref=ubah-order">Ubah Pesanan</a> &rarr; Detail Perubahan Item
</div>

<div class="judul-frame">
	<b>Ubah Item, Nomor Pesanan #<?php echo "$pesanan_id pada Meja $meja_nomor";?></b>
</div>
<center><h3>Pesanan Yang Aktif</h3></center>

<form id="form-ubah-menu-update-detail" method="post" action="pages/ubah-order-update-submit.php">
<div class="data-view-besar">
  <table width="100%" border="1" cellpadding="10" cellspacing="2">
		<thead>
			<tr>
				<th>ID Item</th>
				<th>Nama Item</th>
				<th>Kurangi Jumlah</th>
				<th>Batalkan Item ?</th>
			</tr>
		</thead>
    <tbody>
      <?php

if ($rs=$con->query("SELECT produk_id, produk_nama, jumlah FROM pesanan_detail WHERE pesanan_id=$pesanan_id AND batal='TIDAK' AND status='ON';")) {
  while ($row=$rs->fetch_row()) {
    echo"
    <tr>
      <td align=\"center\">
				$row[0]
				<input type=\"hidden\" name=\"txproduk_id$i\" id=\"txproduk_id$i\" value=\"$row[0]\"/>
			</td>
      <td>$row[1]</td>
      <td align=\"center\">
        <input type=\"number\" name=\"jml$i\" id=\"jml$i\" min=\"1\" max=\"$row[2]\" value=\"$row[2]\" style=\"width:50px;\" />
      </td>
      <td align=\"center\">
        <input type=\"checkbox\"  name=\"hps$i\" id=\"hps$i\" /> Ya
      </td>
    </tr>
    ";
    $i++;
  }
}
      ?>
    </tbody>
  </table>
</div>
<div style="padding:5px;">
	<input type="hidden" name="hid_counter" value="<?php echo $i;?>"/>
	<input type="hidden" name="hid_meja_nomor" value="<?php echo $meja_nomor;?>"/>
	<input type="hidden" name="hid_pesanan_id" value="<?php echo $pesanan_id;?>"/>

	<a href="?ref=ubah-order" style="border:1px solid #ccc;border-radius:3px;padding:8px;font-weight:bold;color:darkblue;"><~~ Kembali</a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="submit" value="Update" name="update-detail"/>
</div>
</form>
