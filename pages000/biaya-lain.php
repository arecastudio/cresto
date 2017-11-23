<?php
lock_page($con,"pengaturan_biaya_lain2",$user_name,$keys);
?>

<script type="text/javascript">
$(document).ready(function() {
	$('#txppn').bind('keypress', function(e)
	{
	   if(e.keyCode != 13)
	   {
	      return false;
	   }
	});

  $("#txservice").keydown(function(event) {
  	// Allow only backspace and delete
  	if ( event.keyCode == 46 || event.keyCode == 8 ) {
  		// let it happen, don't do anything
  	}
  	else {
  		// Ensure that it is a number and stop the keypress
  		if (event.keyCode < 48 || event.keyCode > 57 ) {
  			event.preventDefault();
  		}
  	}
  });

});
</script>

<div class="lokasi">
	<a href="?ref=atur">Pengaturan</a> &rarr; Biaya Tambahan (Lain-lain)
</div>

<div class="judul-frame">
	<b>Biaya Tambahan (Lain-lain)</b>
</div>

<form id="f1" action="" method="post">
<div style="width:450px;margin:0 auto;">
  <table border="0" cellpadding="2px" cellspacing="2px" width="100%">
    <tr>
      <td>Biaya Layanan (Service)</td>
      <td><input disabled type="text" name="txservice" id="txservice" maxlength="5" style="font-size:20px;width:100px;height:10px;"/> (Rp.)</td>
    </tr>
    <tr>
      <td>Pajak Pertambahan Nilai (PPN)</td>
      <td><input disabled type="number" name="txppn" id="txppn" max="50" min="10" style="width:50px;height:10px;"/> %</td>
    </tr>
    <tr style="height:100px;">
      <td colspan="2" align="center">
        <input name="submit" type="submit" value="Simpan" disabled style="width:300px;height:50px;" />
      </td>
    </tr>
  </table>
</div>
</form>

<div style="color:blue;">
	<?php
	if (isset($_POST['submit']) && strlen(trim($_POST['txservice']!=""))>0 && strlen(trim($_POST['txppn']!=""))>0) {
		$ppn=$_POST['txppn'];
		$svc=$_POST['txservice'];
		$con->query("INSERT IGNORE INTO biaya_lain(service,ppn)VALUES($svc,$ppn) ON DUPLICATE KEY UPDATE service=$svc, ppn=$ppn;");
		if($con)echo"Data berhasil disimpan!";
	}


	if($rs=$con->query("SELECT service, ppn FROM biaya_lain ORDER BY waktu DESC LIMIT 1;")){
		if ($row=$rs->fetch_row()) {
			echo "
			<script type=\"text/javascript\">document.getElementById('txservice').value=\"$row[0]\";</script>
			<script type=\"text/javascript\">document.getElementById('txppn').value=\"$row[1]\";</script>
			";
		}
	}
	?>
</div>
