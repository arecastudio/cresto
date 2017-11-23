<?php
lock_page($con,"tool_batalkan_meja",$user_name,$keys);
//echo $user_name;
?>


<div class="lokasi">
	<a href="?ref=extra">Tool</a> &rarr; Batalkan Meja Terisi
</div>

<div class="judul-frame">
	<b>Batalkan Meja Terisi</b>
</div>

<div style="color:blue;">
  <?php
  if (isset($_POST['submit']) && $_POST['optmeja1']!="") {
    $meja1=$_POST['optmeja1'];
    //$meja2=$_POST['optmeja2'];

    $sql0="SELECT id FROM pesanan WHERE meja_nomor='$meja1' AND status='BUKA';";
    if($rs=$con->query($sql0)){
			while ($row=$rs->fetch_row()) {
				$temp_pes_id=$row[0];

				$sql3="INSERT IGNORE INTO his_batal_meja(users_name,pesanan_id)VALUES('$user_name',$temp_pes_id);";
				$sql4="INSERT IGNORE INTO his_batal_pesanan SELECT * FROM pesanan WHERE id=$temp_pes_id;";
				$sql5="INSERT IGNORE INTO his_batal_pesanan_detail SELECT * FROM pesanan_detail WHERE pesanan_id=$temp_pes_id;";
				$con->query($sql3);
				$con->query($sql4);
				$con->query($sql5);

				$sql1="DELETE FROM pesanan_detail WHERE pesanan_id=$temp_pes_id;";
				$sql2="DELETE FROM pesanan WHERE id=$temp_pes_id;";
				$con->query($sql1);
				$con->query($sql2);
			}

      $sql3="UPDATE meja SET status='KOSONG' WHERE nomor='$meja1';";
			if ($con->query($sql3)) {
				echo"<h5>Meja $meja1 telah ditutup !</h5>";
			}
    }
  }
  ?>
</div>

<div style="width:800px;margin:0 auto;border:1px solid #ccc;padding:20px;">
  <form id="f1" action="" method="post">
    Nomor Meja :
    <select name="optmeja1" id="optmeja1">
      <option value="">Pilih Meja</value>
        <?php
#$sql_meja_tutup="SELECT nomor FROM meja WHERE status='TERISI' AND nomor IN(SELECT DISTINCT p.meja_nomor FROM pesanan AS p INNER JOIN pesanan_detail AS d ON d.pesanan_id=p.id WHERE p.status='BUKA' AND COUNT(p.produk_id)>0 GROUP BY p.id );";
#$sql_meja_tutup="SELECT nomor FROM meja WHERE status='TERISI' AND nomor IN(SELECT DISTINCT p.meja_nomor FROM pesanan AS p WHERE p.status='BUKA');";
#$sql_meja_tutup="SELECT DISTINCT p.meja_nomor,SUM(d.jumlah) FROM pesanan AS p INNER JOIN pesanan_detail AS d ON d.pesanan_id=p.id WHERE p.status='BUKA' GROUP BY p.id;";
$sql_meja_tutup="SELECT DISTINCT nomor FROM meja WHERE status='TERISI';";
if ($meja=$con->query($sql_meja_tutup)) {
  while ($row4=$meja->fetch_row()) {
    #if($row4<1)echo"<option value=\"$row4[0]\">Meja $row4[0]</value>";
		echo"<option value=\"$row4[0]\">Meja $row4[0]</value>";
  }
}
        ?>
    </select>
    <input type="submit" name="submit" value="Batalkan" style="width:100px;font-weight:bold;margin-left:10px;color:#00c;" />
  </form>
</div>

<div style="color:blue;">
	Tool ini hanya berfungsi untuk meja karena kesalahan input. Untuk membatalkan meja, pastikan anda telah membatalkan pesanan terlebih dahulu.
</div>
