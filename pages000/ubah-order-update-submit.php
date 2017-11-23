<?php
require_once('../inc/inc.php');

$meja_nomor=$_POST['hid_meja_nomor'];
$pesanan_id=$_POST['hid_pesanan_id'];
$ctr=$_POST['hid_counter'];

for ($i=0; $i <$ctr; $i++) {
  $item_id[$i]=$_POST['txproduk_id'.$i];
  $jml[$i]=$_POST['jml'.$i];
  $hps[$i]=0;
  if(isset($_POST['hps'.$i])) $hps[$i]=1;

  #echo "<br/> $item_id[$i] -- $jml[$i] -- $hps[$i] -- $i";

  if ($hps[$i]==1) {
    # do hapus
    //$con->query("DELETE FROM pesanan_detail WHERE pesanan_id=$pesanan_id AND produk_id=$item_id[$i];");
    $con->query("UPDATE pesanan_detail SET batal='YA' WHERE pesanan_id=$pesanan_id AND produk_id=$item_id[$i];");
  }else{
    #do simpan
    $con->query("UPDATE pesanan_detail SET jumlah=$jml[$i] WHERE pesanan_id=$pesanan_id AND produk_id=$item_id[$i];");
  }

}


#echo "<br/>Proses selesai";

header('location: ../?ref=ubah-order');
?>
