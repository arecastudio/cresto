<?php
lock_page($con,"tool_pindah_meja",$user_name,$keys);
?>


<div class="lokasi">
	<a href="?ref=extra">Tool</a> &rarr; Pindah Meja
</div>

<div class="judul-frame">
	<b>Pindah Meja</b>
</div>

<div style="width:800px;margin:0 auto;border:1px solid #ccc;padding:20px;">
  <form id="f1" action="" method="post">
    Pindah dari meja :
    <select name="optmeja1" id="optmeja1">
      <option value="">Pilih Meja</value>
        <?php
if ($meja=$con->query("SELECT nomor FROM meja WHERE status='TERISI' AND nomor IN(SELECT DISTINCT meja_nomor FROM pesanan WHERE status='BUKA');")) {
  while ($rs=$meja->fetch_row()) {
    echo"<option value=\"$rs[0]\">Meja $rs[0]</value>";
  }
}
        ?>
    </select>
    Ke Meja
    <select name="optmeja2" id="optmeja2">
      <option value="">Pilih Meja</value>
        <?php
if ($meja=$con->query("SELECT nomor FROM meja WHERE status='KOSONG' AND nomor NOT IN(SELECT DISTINCT meja_nomor FROM pesanan WHERE status='BUKA');")) {
  while ($rs=$meja->fetch_row()) {
    echo"<option value=\"$rs[0]\">Meja $rs[0]</value>";
  }
}
        ?>
    </select>
    <input type="submit" name="submit" value="Pindah" style="width:100px;font-weight:bold;margin-left:10px;color:#00c;" />
  </form>
</div>

<div style="color:blue;">
  <?php
  if (isset($_POST['submit']) && $_POST['optmeja1']!="" && $_POST['optmeja2']!="") {
    $meja1=$_POST['optmeja1'];
    $meja2=$_POST['optmeja2'];

    $sql0="UPDATE meja SET status='TERISI' WHERE nomor='$meja2' AND status='KOSONG';";
    $con->query($sql0);
    if($con->query($sql0)){
      echo"<h5>Meja $meja1 telah disiapkan !</h5>";
      $sql1="UPDATE pesanan SET meja_nomor='$meja2',operator='$user_name' WHERE meja_nomor='$meja1' AND status='BUKA';";
      if($con->query($sql1)){
        echo"<h5>Meja $meja2 telah terisi dari Meja $meja1 !</h5>";
        $sql2="UPDATE meja SET status='KOSONG' WHERE nomor='$meja1' AND status='TERISI';";
        if($con->query($sql2))echo"<h5>Meja $meja1 telah tersedia untuk tamu lain !</h5>";
      }
    }
  }
  ?>
</div>
