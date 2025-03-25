<?php
//memanggil file pustaka fungsi
require "fungsi.php";

//memindahkan data kiriman dari form ke var biasa
//$npp=$_POST["npp1"].".".$_POST["npp2"].".".$_POST["npp3"];

$npp=$_POST["npp"];
$nama=$_POST["nama"];
$homebase=$_POST["homebase"];

// simpan data
$sql="insert dosen values('$npp','$nama','$homebase')";
    mysqli_query($koneksi,$sql);
    echo "Data telah tersimpan";
//header("location:addDosen.php");
?>