<?php
require "fungsi.php";


$idUser = $_POST['iduser'];
$username = $_POST['username'];
$status = $_POST['status'];

if (empty($idUser) || empty($username) || empty($status)) {
    echo "Field tidak boleh kosong";
    exit;
}

$stmt = $koneksi->prepare("UPDATE user SET username = ?, status = ? WHERE iduser = ?");
$stmt->bind_param("sss", $username, $status, $idUser);

if ($stmt->execute()) {
    echo "Data berhasil diperbarui!";
} else {
    echo "Terjadi kesalahan dalam memperbarui data";
}

$stmt->close();
$koneksi->close();

header("location:ajaxUpdateUser.php");
?>