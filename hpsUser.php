<?php
require "fungsi.php";

$idUser=$_GET["id"];

$stmt = $koneksi->prepare("DELETE FROM user WHERE idUser = ?");
$stmt->bind_param("i", $idUser);
if ($stmt->execute()) {
    echo "Data berhasil dihapus";
    header("location:ajaxUpdateUser.php");
} else {
    echo "Terjadi kesalahan ketika menghapus data";
}

$stmt->close();
$koneksi->close();
?>