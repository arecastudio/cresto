<?php
lock_page($con,"pengaturan_hak_akses",$user_name,$keys);
?>

<div class="lokasi">
	<a href="?ref=atur">Pengaturan</a> &rarr; Hak Pengguna
</div>

<div class="judul-frame">
	<b>Pengaturan Hak Akses Aplikasi</b>
</div>

<div style="margin-top:5px;margin-bottom:5px;">
	<form id="f1" action="" method="post">
		<select id="optid" name="optid">
			<option value="">Jenis Pengguna</option>
			<?php
$sql="SELECT DISTINCT id FROM role WHERE LCASE(id) NOT LIKE '%admin%' ORDER BY id ASC;";
$res=$con->query($sql);
while($row=$res->fetch_row()){
	echo "<option value=\"$row[0]\">".ucwords($row[0])."</option>";
}
			?>
		</select>&nbsp;&nbsp;&nbsp;
		<input type="submit" name="tampilkan" value="Tampilkan" style="width:150px;font-size:18px;color:blue;" />
	</form>
</div>

<!--
BATAS FORM
-->

<form action="" method="post">
<table width="90%">
	<tr>
		<td>
<fieldset>
			<div style="width:100%;margin-top:20px;">
				<input type="hidden" name="hid_id" id="hid_id"/>
				<div class="sub-menu-role">
					<h3>Master</h3>
					<input type="checkbox" name="chk_master_data_meja" id="chk_master_data_meja"/> Data Meja<br/>
					<input type="checkbox" name="chk_master_kategori_meja" id="chk_master_kategori_meja"/> Kategori Meja<br/>
					<input type="checkbox" name="chk_master_daftar_menu" id="chk_master_daftar_menu"/> Daftar menu<br/>
					<input type="checkbox" name="chk_master_kategori_menu" id="chk_master_kategori_menu"/> Kategori Menu<br/>
					<input type="checkbox" name="chk_master_daftar_menu_tambahan" id="chk_master_daftar_menu_tambahan"/> Daftar menu Tambahan<br/>
				</div>

				<div class="sub-menu-role">
					<h3>Penjualan</h3>
					<input type="checkbox" name="chk_penjualan_pemesanan" id="chk_penjualan_pemesanan"/> Pemesanan<br/>
					<input type="checkbox" name="chk_penjualan_pelunasan" id="chk_penjualan_pelunasan"/> Pelunasan<br/>
				</div>

				<div class="sub-menu-role">
					<h3>Tool</h3>
					<input type="checkbox" name="chk_tool_pindah_meja" id="chk_tool_pindah_meja"/> Pindah Meja<br/>
					<input type="checkbox" name="chk_tool_batalkan_meja" id="chk_tool_batalkan_meja"/> Batalkan Meja<br/>
					<input type="checkbox" name="chk_tool_batalkan_pelunasan" id="chk_tool_batalkan_pelunasan"/> Batalkan Pelunasan<br/>
					<input type="checkbox" name="chk_tool_ubah_pesanan" id="chk_tool_ubah_pesanan"/> Ubah Pesanan<br/>
				</div>

				<div class="sub-menu-role">
					<h3>Laporan</h3>
					<input type="checkbox" name="chk_laporan_harian" id="chk_laporan_harian"/> Harian & Bulanan<br/>
					<input type="checkbox" name="chk_laporan_bulanan" id="chk_laporan_bulanan"/> Pemasuakan Hari Ini<br/>
					<input type="checkbox" name="chk_laporan_import" id="chk_laporan_import"/> Import Laporan & Pajak<br/>
				</div>

				<div class="sub-menu-role">
					<h3>Pengaturan</h3>
					<input type="checkbox" name="chk_pengaturan_biaya_lain2" id="chk_pengaturan_biaya_lain2"/> Biaya Lain-lain<br/>
				</div>

			</div>
</fieldset>

		</td>
	</tr>
	<tr style="height:100px;text-align:center;">
		<td>
			<input type="reset" name="batal" value="Reset" style="width:150px;font-size:18px;color:green;"/>
			<input type="submit" name="simpan" value="Simpan" style="width:150px;font-size:18px;color:blue;"/>
		</td>
	</tr>
</table>
</form>

<!--
SCRIPT PHP
-->
<?php

if(isset($_POST['simpan']) && $_POST['hid_id']!=''){
	$id=$_POST['hid_id'];
	$master_data_meja=0;
	$master_data_meja_kat=0;
	$master_daftar_menu=0;
	$master_kategori_menu=0;

	$penjualan_pemesanan=0;
	$penjualan_pelunasan=0;

	$tool_pindah_meja=0;
	$tool_batalkan_meja=0;
	$tool_batalkan_pelunasan=0;
	$tool_ubah_pesanan=0;

	$laporan_harian=0;
	$laporan_bulanan=0;
	$laporan_import=0;

	$pengaturan_data_pengguna=0;
	$pengaturan_hak_akses=0;
	$pengaturan_biaya_lain2=0;

	$master_daftar_menu_tambahan=0;

	if(isset($_POST['chk_master_data_meja']))	$master_data_meja=1;
	if(isset($_POST['chk_master_kategori_meja']))$master_data_meja_kat=1;
	if(isset($_POST['chk_master_daftar_menu']))$master_daftar_menu=1;
	if(isset($_POST['chk_master_kategori_menu']))$master_kategori_menu=1;
	if(isset($_POST['chk_master_daftar_menu_tambahan']))$master_daftar_menu_tambahan=1;

	if(isset($_POST['chk_penjualan_pemesanan']))$penjualan_pemesanan=1;
	if(isset($_POST['chk_penjualan_pelunasan']))$penjualan_pelunasan=1;

	if(isset($_POST['chk_tool_pindah_meja']))$tool_pindah_meja=1;
	if(isset($_POST['chk_tool_batalkan_meja']))$tool_batalkan_meja=1;
	if(isset($_POST['chk_tool_batalkan_pelunasan']))$tool_batalkan_pelunasan=1;
	if(isset($_POST['chk_tool_ubah_pesanan']))$tool_ubah_pesanan=1;

	if(isset($_POST['chk_laporan_harian']))$laporan_harian=1;
	if(isset($_POST['chk_laporan_bulanan']))$laporan_bulanan=1;
	if(isset($_POST['chk_laporan_import']))$laporan_import=1;

	if(isset($_POST['chk_pengaturan_biaya_lain2']))$pengaturan_biaya_lain2=1;

	$sql="
		UPDATE role SET
			master_data_meja=$master_data_meja,
			master_data_meja_kat=$master_data_meja_kat,
			master_daftar_menu=$master_daftar_menu,
			master_daftar_menu_tambahan=$master_daftar_menu_tambahan,
			master_kategori_menu=$master_kategori_menu,
			penjualan_pemesanan=$penjualan_pemesanan,
			penjualan_pelunasan=$penjualan_pelunasan,
			tool_pindah_meja=$tool_pindah_meja,
			tool_batalkan_meja=$tool_batalkan_meja,
			tool_batalkan_pelunasan=$tool_batalkan_pelunasan,
			tool_ubah_pesanan=$tool_ubah_pesanan,
			laporan_harian=$laporan_harian,
			laporan_bulanan=$laporan_bulanan,
			laporan_import=$laporan_import,
			pengaturan_data_pengguna=$pengaturan_data_pengguna,
			pengaturan_hak_akses=$pengaturan_hak_akses,
			pengaturan_biaya_lain2=$pengaturan_biaya_lain2
		WHERE
			id='$id' AND (LCASE(id) NOT LIKE '%admin%')
		;";

		//echo"<fieldset>$sql</fieldset>";
		if($con->query($sql)){
			echo "<script type=\"text/javascript\">alert('Data berhasil disimpan!');</script>";
		}

}

//*******************************************************

if(isset($_POST['tampilkan']) && $_POST['optid']!=''){
	$id=$_POST['optid'];
	$sql="
		SELECT
			id,
			master_data_meja,
			master_data_meja_kat,
			master_daftar_menu,
			master_kategori_menu,
			penjualan_pemesanan,
			penjualan_pelunasan,
			tool_pindah_meja,
			tool_batalkan_meja,
			tool_batalkan_pelunasan,
			tool_ubah_pesanan,
			laporan_harian,
			laporan_bulanan,
			laporan_import,
			pengaturan_data_pengguna,
			pengaturan_hak_akses,
			pengaturan_biaya_lain2,
			master_daftar_menu_tambahan
		FROM
			role
		WHERE
			id='$id'
		LIMIT 1
		;";

echo "<script type=\"text/javascript\">document.getElementById('hid_id').value=\"$id\";</script>";

$res=$con->query($sql);
if($row=$res->fetch_row()){
	echo "<script type=\"text/javascript\">document.getElementById('optid').value=\"$id\";</script>";
	if($row[1]==1) echo "<script type=\"text/javascript\">document.getElementById('chk_master_data_meja').checked=\"checked\";</script>";
	if($row[2]==1) echo "<script type=\"text/javascript\">document.getElementById('chk_master_kategori_meja').checked=\"checked\";</script>";
	if($row[3]==1) echo "<script type=\"text/javascript\">document.getElementById('chk_master_daftar_menu').checked=\"checked\";</script>";
	if($row[4]==1) echo "<script type=\"text/javascript\">document.getElementById('chk_master_kategori_menu').checked=\"checked\";</script>";
	if($row[17]==1) echo "<script type=\"text/javascript\">document.getElementById('chk_master_daftar_menu_tambahan').checked=\"checked\";</script>";

	if($row[5]==1) echo "<script type=\"text/javascript\">document.getElementById('chk_penjualan_pemesanan').checked=\"checked\";</script>";
	if($row[6]==1) echo "<script type=\"text/javascript\">document.getElementById('chk_penjualan_pelunasan').checked=\"checked\";</script>";

	if($row[7]==1) echo "<script type=\"text/javascript\">document.getElementById('chk_tool_pindah_meja').checked=\"checked\";</script>";
	if($row[8]==1) echo "<script type=\"text/javascript\">document.getElementById('chk_tool_batalkan_meja').checked=\"checked\";</script>";
	//batalkan meja tidak dimasukkan
	if($row[9]==1) echo "<script type=\"text/javascript\">document.getElementById('chk_tool_batalkan_pelunasan').checked=\"checked\";</script>";
	if($row[10]==1) echo "<script type=\"text/javascript\">document.getElementById('chk_tool_ubah_pesanan').checked=\"checked\";</script>";

	if($row[11]==1) echo "<script type=\"text/javascript\">document.getElementById('chk_laporan_harian').checked=\"checked\";</script>";
	if($row[12]==1) echo "<script type=\"text/javascript\">document.getElementById('chk_laporan_bulanan').checked=\"checked\";</script>";
	if($row[13]==1) echo "<script type=\"text/javascript\">document.getElementById('chk_laporan_import').checked=\"checked\";</script>";

	if($row[16]==1) echo "<script type=\"text/javascript\">document.getElementById('chk_pengaturan_biaya_lain2').checked=\"checked\";</script>";
}


}

?>
