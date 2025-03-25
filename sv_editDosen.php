<?php
//memanggil file pustaka fungsi
require "fungsi.php";

//memindahkan data kiriman dari form ke var biasa
$npp=$_POST["npp"];
$nama=$_POST["nama"];
$homebase=$_POST["homebase"];

//membuat query
$sql="update dosen set namadosen='$nama',
					   homebase='$homebase'
					 where npp='$npp'";
mysqli_query($koneksi,$sql) or die(mysqli_error($koneksi));
header("location:updateDosen.php");
?>