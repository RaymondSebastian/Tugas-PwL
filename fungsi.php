<?php
$koneksi = mysqli_connect("localhost","root","","akademik06983");
 

if (mysqli_connect_errno()){
	echo "Gagal melakukan koneksi ke MySQL: " . mysqli_connect_error();
}

//mysqli_close($koneksi);
?>