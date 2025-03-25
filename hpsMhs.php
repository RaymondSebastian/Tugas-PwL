<?php
//memanggil file pustaka fungsi
require "fungsi.php";

//memindahkan data kiriman dari form ke var biasa
$id = $_GET["kode"];

// Mengambil informasi foto sebelum menghapus data
$sql = $koneksi->query("SELECT foto FROM mhs WHERE id='$id'");
$data = $sql->fetch_assoc();
$foto = $data['foto'];

// Menghapus foto dari folder jika ada
if (!empty($foto) && file_exists("foto/$foto")) {
    unlink("foto/$foto");
}

// Menghapus data mahasiswa dari database
$sql = "DELETE FROM mhs WHERE id=$id";
if (mysqli_query($koneksi, $sql)) {
    // Jika penghapusan berhasil
    header("location:ajaxUpdateMhs.php");
} else {
    // Jika terjadi kesalahan
    echo "Error: " . mysqli_error($koneksi);
}
