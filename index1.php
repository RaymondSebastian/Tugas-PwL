<?php 
//session_start(); // memulai session

//if(isset($_SESSION['username'])){ // cek jika sudah ada session username
//  header("location:homeadmin.php"); // arahkan ke halaman homeadmin.php
//  exit; // hentikan eksekusi kode selanjutnya
//}

//?>

<!DOCTYPE html>
<html>
<head>
	<title>Login Sistem</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
	<script src="bootstrap/js/bootstrap.js"></script>
	<script src="bootstrap/jquery/jquery-3.7.1.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="w-25 mx-auto text-center mt-5">
			<div class="card bg-dark text-light">
				<div class="card-body">
				<h2 class="card-title">LOGIN</h2>	
				<form method="post" action="">
					<div class="form-group">
						<label for="username">Nama user</label>
						<input class="form-control" type="text" name="username" id="username" autofocus>
					</div>
					<div class="form-group">
						<label for="passw">Password</label>
						<input class="form-control" type="password" name="passw" id="passw">
					</div>			
					<div>		
						<button class="btn btn-info" type="submit">Login</button>
					</div>
				</form>
				</div>
			</div>
		</div>	
	</div>
	<?php

	session_start(); // Memulai session
	require "fungsi.php"; // Pastikan file fungsi.php berisi koneksi yang aman ke database


	if (isset($_SESSION['username'])) { 
    	header("location: homeAdmin.php"); 
	exit; 
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
    	$username = trim($_POST['username']);
    	$passw = trim($_POST['passw']); // Ambil password tanpa di-hash!
    
    if (!empty($username) && !empty($passw)) {
        $sql1 = "SELECT * FROM user WHERE username = ?";
        $stmt = $koneksi->prepare($sql1);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
            
	if ($result->num_rows ==1) {
            $user1 = $result->fetch_assoc();
            if (password_verify($passw, $user1['password'])) {
                $_SESSION['iduser'] = $user1['iduser']; // Simpan session user ID
                header("location: homeAdmin.php");
                exit();
            } else {
                echo "Password salah.";
            }
        } else {
            echo "User tidak ditemukan.";
        }

        $stmt->close();
	    }
	 $koneksi->close();
	} else {
    echo "This script only accepts POST requests.";
 }
?>

</body>
</html>