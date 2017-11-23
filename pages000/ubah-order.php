<?php
lock_page($con,"tool_ubah_pesanan",$user_name,$keys);
?>


<form id="ubah-order-hapus" method="post" action="">
	<input type="hidden" name="hid1" id="hid1" />
	<input type="hidden" name="hid2" id="hid2" />
</form>

<?php
ob_start();

if (isset($_POST['hid1']) && isset($_POST['hid2'])) {
	$hid1=$_POST['hid1'];
	$hid2=$_POST['hid2'];

	//echo "Hapus $hid1 --- $hid2 --- OK";
	$con->query("DELETE FROM pesanan_detail WHERE pesanan_id=$hid2 AND meja_nomor='$hid1';");
	$con->query("DELETE FROM pesanan WHERE id=$hid2;");
}

//kiriman dari file ubah-order-update.php BOF
if (isset($_POST['update_detail'])) {
	//$ctr=$_POST['hid_counter'];
	echo"Jumlah kontrol adalah $ctr";
}
//kiriman dari file ubah-order-update.php EOF
?>


<form id="ubah-order-update" method="post" action="?ref=ubah-order-update">
	<input type="hidden" name="hid3" id="hid3" />
	<input type="hidden" name="hid4" id="hid4" />
</form>

<?php
if (isset($_POST['hid3']) && isset($_POST['hid4'])) {
	$hid3=$_POST['hid3'];
	$hid4=$_POST['hid4'];

	echo "Ubah $hid3 --- $hid4 --- OK";
}
?>

<!--######################################################################-->

<div class="lokasi">
	<a href="?ref=extra">Tool</a> &rarr; Ubah Pesanan
</div>

<div class="judul-frame">
	<b>Ubah Pesanan</b>
</div>
<center><h3>Pesanan dan Meja Aktif</h3></center>
<div class="data-view-besar">
	<table width="100%" border="1" cellpadding="10" cellspacing="2">
		<thead>
			<tr>
				<th>No. Meja</th>
				<th>No. Pesanan</th>
				<th>Item Pesanan</th>
				<th>Kontrol</th>
			</tr>
		<thead>
		<!--tbody style="font-size:110%;"-->
<?php
$sql="
SELECT p.meja_nomor,p.id,(SELECT GROUP_CONCAT(d.produk_nama SEPARATOR ', ') FROM pesanan_detail AS d WHERE d.pesanan_id=p.id AND d.batal='TIDAK' AND d.status='ON') AS prod FROM pesanan AS p WHERE p.status='BUKA' AND p.kirim='SDH' ORDER BY p.meja_nomor ASC, p.waktu ASC
;";
if($rs=$con->query($sql)){
	while($row=$rs->fetch_row()){
		echo"
			<tr>
				<td align=\"center\">Meja $row[0]</td>
				<td align=\"center\"># $row[1]</td>
				<td>".substr($row[2], 0,100)."...</td>
				<td align=\"center\">
				<a href=\"#\" class=\"ubah\" onMouseMove=\"return setPilihUpdate('$row[0]','$row[1]');\"><img src=\"images/edit.png\" width=\"16px\" title=\"Edit Pesanan ini\"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href=\"#\" class=\"hapus\" onMouseMove=\"return setPilih('$row[0]','$row[1]');\"><img src=\"images/delete.png\" width=\"16px\" title=\"Hapus Pesanan ini\"></a>
				</td>
			</tr>
		";

	}
}

?>
		<!--/tbody-->
	</table>
</div>


<!--#####################################################################-->
<script>
$(document).ready(function() {

    $("#dialog").dialog({
        modal: true,
        bgiframe: true,
        resizable: false,
        width: 300,
        height: 200,
        autoOpen: false
    });


    $(".hapus").click(function(e) {
        e.preventDefault();
        var theHREF = $(this).attr("href");
        var theMeja = $("#hid1").val();
        var theItem = $("#hid2").val();

        $("#dialog").dialog('option', 'buttons', {
            "Confirm" : function() {
                //window.location.href = theHREF;
                //window.location.href = "?ref=ubah-order&del="+theMeja+"&item="+theItem;
                $('#ubah-order-hapus').submit();
            },
            "Cancel" : function() {
                $(this).dialog("close");
            }
        });

        $("#dialog").dialog("open");

    });


    $("#dialog-form").dialog({
        modal: true,
        bgiframe: true,
        resizable: false,
        width: 350,
        height: 400,
        autoOpen: false
    });


    $(".ubah").click(function(e) {
        e.preventDefault();
        var theHREF = $(this).attr("href");
        var theMeja = $("#hid3").val();
        var theItem = $("#hid4").val();

				$('#name').val(theMeja);
				$('#email').val(theItem);

				$('#ubah-order-update').submit();

    });

});

function setPilih(vMeja,vItem){
	document.getElementById('hid1').value=String(vMeja);
	document.getElementById('hid2').value=String(vItem);
}

function setPilihUpdate(vMeja,vItem){
	document.getElementById('hid3').value=String(vMeja);
	document.getElementById('hid4').value=String(vItem);
}

</script>

<div id="dialog" title="Konfirmasi dibutuhkan">
  <p>Yakin untuk hapus pesanan ini?</p>
</div>





<div id="dialog-form" title="Create new user">
  <p class="validateTips">All form fields are required.</p>

  <form id="form-simpan-perubahan" method="post" action="">
    <fieldset>
      <label for="name">Name</label>
      <input type="text" name="name" id="name" value="Jane Smith" class="text ui-widget-content ui-corner-all">
      <label for="email">Email</label>
      <input type="text" name="email" id="email" value="jane@smith.com" class="text ui-widget-content ui-corner-all">

      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
			<?php echo $_POST['hid3'];?>
    </fieldset>
  </form>
</div>

<?php
ob_end_flush();
?>
