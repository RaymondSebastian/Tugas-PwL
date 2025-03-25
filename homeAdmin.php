<?php session_start()?>
<!DOCTYPE html>
<html>
<head>
	<title>Halaman Home Admin</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="css/styleku.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
	<script src="bootstrap/js/bootstrap.js"></script>
	<script src="bootstrap/jquery/jquery-3.7.1.min.js"></script>

</head>
<body>
<?php
//memanggil file berisi fungsi2 yang sering dipakai
require "fungsi.php";
require "head.html";

//cek logout
if (!isset($_SESSION['username'])){
	header("location:index1.php");
	exit;
}
?>
<div class="utama">
	<br><br>
	<h1 class="text-center">Selamat Datang di Halaman Administrator saudara <?php echo strtoupper($_SESSION['username'])?></h1>
</div>
</body>
</html>	
