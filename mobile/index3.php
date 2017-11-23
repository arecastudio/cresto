<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="assets/jquery.mobile-1.4.5.min.css">
<script src="assets/jquery-1.11.3.min.js"></script>
<script src="assets/jquery.mobile-1.4.5.min.js"></script>
</head>
<body>

<div data-role="page">

	<div data-role="header" data-theme="b">
		<a href="./" data-icon="home" data-iconpos="notext" data-transition="fade">Home</a>
		<h1>Cristho Resto Sentani</h1>
	</div>

	<div data-role="main" class="ui-content">

	   <ul data-role="listview" data-inset="true">
		  <li data-role="divider">Pilih Menu</li>
		  <li>
			<a href="?p=new-order" data-transition="slidedown">
			<img src="chrome.png" alt="New"/>
			<h2>Buka Order Baru</h2>
			<p>Gunakan menu ini untuk anda yang baru saja tiba dan hendak memesan menu dan meja.</p>
			</a>
		  </li>
		  <li>
			<a href="?p=add-order" data-transition="slidedown">
			<img src="firefox.png" alt="add">
			<h2>Tambah Order Sebelumnya</h2>
			<p>Gunakan menu ini jika anda telah memesan menu sebelumnya dan menempati meja.</p>
			</a>
		  </li>
		</ul>

	</div>

	<div data-role="footer" data-theme="b">
		<h5 style="font-size:60%;">kasuariweb.com &copy; 2016</h5>
	</div>

</div>

</body>
</html>
