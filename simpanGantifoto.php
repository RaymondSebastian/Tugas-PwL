<?php
	//panggil file fungsi
	require "fungsi.php";

	$id=$_POST['id'];
	$uploadOk=1;

	$sql="select * from mhs where id='$id'";
		$hasil=mysqli_query($koneksi,$sql) or die (mysql_error());
		$row=mysqli_fetch_assoc($hasil);
		$foto_u=$row['foto'];
		
	//Set lokasi dan nama file foto
	$folderupload ="foto/";
	$fileupload = $folderupload . basename($_FILES['foto']['name']);
	$filefoto = basename($_FILES['foto']['name']);                   			


	//ambil jenis file
	$jenisfilefoto = strtolower(pathinfo($fileupload,PATHINFO_EXTENSION));

	

	// Hanya file tertentu yang dapat digunakan
	if($jenisfilefoto != "jpg" && $jenisfilefoto != "png" && $jenisfilefoto != "jpeg"
	&& $jenisfilefoto != "gif" ) {
		echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan<br>";
		$uploadOk = 0;
	}

	// Check jika terjadi kesalahan
	if ($uploadOk == 0) {
		echo "Maaf, file tidak dapat terupload<br>";
	// jika semua berjalan lancar
	} else {

		if (file_exists("foto/$foto_u")){
			unlink("foto/$foto_u");
		}

		if (move_uploaded_file($_FILES["foto"]["tmp_name"], $fileupload)) {
			//membuat query
			//echo $id." - ".$fileupload;exit;
			$sql="update mhs set foto='$filefoto' where id='$id'";
			mysqli_query($koneksi,$sql) or die(mysqli_error($koneksi));
			header("location:ajaxUpdateMhs.php");
		} else {
			echo "Maaf, terjadi kesalahan saat mengupload file foto<br>";
		}
	}

?>