<?php
lock_page($con,"pengaturan_data_pengguna",$user_name,$keys);
?>

<div class="lokasi">
	<a href="?ref=master">Master</a> &rarr; Pembayaran Non Tunai
</div>

<div class="judul-frame">
	<b>Metode Pembayaran Non Tunai</b>
</div>

<form autocomplete="off" action="" method="post">
<div style="margin:5px;">
<input type="text" name="txnama" id="txnama" placeholder="Nama"/>
<input type="hidden" name="hid_id" id="hid_id"/>
&nbsp;&nbsp;&nbsp;
<input type="submit" name="submit" value="Simpan"/>
</div>
</form>

<?php
if (isset($_GET['pil']) && $_GET['pil']!='') {
  $id=$_GET['pil'];
  $nama=$_GET['nama'];
  echo "<script type=\"text/javascript\">document.getElementById('txnama').value=\"$nama\"</script>";
  echo "<script type=\"text/javascript\">document.getElementById('hid_id').value=\"$id\"</script>";
}

if (isset($_GET['del']) && $_GET['del']!='') {
  $id=$_GET['del'];
  $hps=$con->query("DELETE FROM non_tunai WHERE id=$id;");
  if ($hps) {
    header('location: ?ref=non-cash-payment');
  }
}

if (isset($_POST['submit'])) {
  $id=$_POST['hid_id'];
  $nama=$_POST['txnama'];
  if ($id!='') {
    $sql="UPDATE non_tunai SET nama='$nama' WHERE id=$id;";
  }else{
    $sql="INSERT IGNORE INTO non_tunai(nama)VALUES('$nama');";
  }
  $spn=$con->query($sql);
  if ($spn) {
    header('location: ?ref=non-cash-payment');
  }
}
?>

<div class="data-view-besar">
<table width="70%" cellpadding="5" cellspacing="0" border="1" style="border-collapse:collapse;font-size:15px;">
  <thead>
  <tr height="25px">
    <th>#</th>
    <th>Nama</th>
    <th colspan="2">Kontrol</th>
  </tr>
</thead>
<tbody>
  <?php
$sql="SELECT DISTINCT id,nama FROM non_tunai ORDER BY nama ASC;";
if ($rs=$con->query($sql)) {
  $i=0;
  while ($row=$rs->fetch_row()) {
    $i++;
    echo"
    <tr>
      <td align=\"center\">$i</td>
      <td>$row[1]</td>
      <td align=\"center\">
<a href=\"?ref=non-cash-payment&pil=$row[0]&nama=$row[1]\"><img src=\"images/edit.png\" width=\"16px\" title=\"Edit Metode ini\"></a>
      </td>
      <td align=\"center\">
<a href=\"?ref=non-cash-payment&del=$row[0]\"><img src=\"images/delete.png\" width=\"16px\" title=\"Hapus Metode ini\" onclick=\"return confirm('Yakin untuk hapus?');\"></a>
      </td>
    </tr>
    ";
  }
}
  ?>
</tbody>
</table>
</div>
