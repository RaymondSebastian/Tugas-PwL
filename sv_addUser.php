<?php
require "fungsi.php";

$username = $_POST["username"];
$password = $_POST["password"];
$status = $_POST["status"];

if (empty($username) || empty($password) || empty($status)) {
    die("Field tidak boleh kosong!");
}

$hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

$stmt = $koneksi->prepare("INSERT INTO user (username, password, status) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hashedPassword, $status);
if ($stmt->execute()) {
    echo "<div class='alert alert-success'>
              Data berhasil ditambahkan! 
              <script>
                  setTimeout(function() {
                      window.location.href = 'ajaxUpdateUser.php';
                  }, 2000);
              </script>
            </div>";
} else {
    echo "<div class='alert alert-error'>Terjadi kesalahan dalam menambahkan data</div>";
}

$stmt->close();
$koneksi->close();
?>