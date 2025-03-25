<?php
//memanggil file pustaka fungsi
require "fungsi.php";

//memindahkan data kiriman dari form ke var biasa
$nim = $_POST["nim"];
$nama = $_POST["nama"];
$email = $_POST["email"];
$uploadOk = 1;

//Set lokasi dan nama file foto
$folderupload = "foto/";
$fileupload = $folderupload . basename($_FILES['foto']['name']); // foto/A12.2018.05555.jpg
$filefoto = basename($_FILES['foto']['name']);                  // A12.2018.0555.jpg

//ambil jenis file
$jenisfilefoto = strtolower(pathinfo($fileupload, PATHINFO_EXTENSION)); //jpg,png,gif

// Hanya file tertentu yang dapat digunakan
if (
    $jenisfilefoto != "jpg" && $jenisfilefoto != "png" && $jenisfilefoto != "jpeg"
    && $jenisfilefoto != "gif"
) {
    echo "<div class='alert alert-danger'>
            Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan
          </div>";
    $uploadOk = 0;
}

// Check jika terjadi kesalahan
if ($uploadOk == 0) {
    echo "<div class='alert alert-danger'>
            Maaf, file tidak dapat terupload
          </div>";
    // jika semua berjalan lancar
} else {
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $fileupload)) {
        // Cek apakah NIM sudah ada di database
        $cekNIM = "SELECT * FROM mhs WHERE nim='$nim'";
        $result = mysqli_query($koneksi, $cekNIM);

        if (mysqli_num_rows($result) > 0) {
            // Jika NIM sudah ada, tampilkan pesan alert
            echo "<div class='alert alert-danger'>
                    NIM sudah ada di database. Silakan masukkan NIM yang berbeda.
                  </div>";
        } else {
            // Jika NIM belum ada, simpan data
            $sql = "INSERT INTO mhs (nim, nama, email, foto) VALUES ('$nim', '$nama', '$email', '$filefoto')";
            if (mysqli_query($koneksi, $sql)) {
                echo "<div class='alert alert-success'>
                        Data berhasil ditambahkan! 
                        <script>
                            setTimeout(function() {
                                window.location.href = 'ajaxUpdateMhs.php';
                            }, 2000);
                        </script>
                      </div>";
            } else {
                echo "<div class='alert alert-danger'>
                        Data gagal tersimpan
                      </div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>
                Maaf, terjadi kesalahan saat mengupload file.
              </div>";
    }
}
