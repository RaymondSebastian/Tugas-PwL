<!DOCTYPE html>
<html>
	<head>
		<title>Sistem Informasi Akademik::Daftar Mahasiswa</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/styleku.css">
		<script src="bootstrap/js/bootstrap.js"></script>
		<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
		<!-- Use fontawesome 5-->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
	</head>
	<body>
		<?php
		
			require "fungsi.php";
			require "head.html";
			$jmlDataPerHal = 5;

			if (isset($_POST['cari'])){
				$cari=$_POST['cari'];
				$sql="select * from user where username like'%$cari%' or status like '%$cari%'";
			}else{
				$sql="select * from user";		
			}

			$qry = mysqli_query($koneksi,$sql) or die(mysqli_error($koneksi));
			$jmlData = mysqli_num_rows($qry);

			$jmlHal = ceil($jmlData / $jmlDataPerHal);

			if (isset($_GET['hal'])){
				$halAktif=$_GET['hal'];
			}else{
				$halAktif=1;
			}

			$awalData=($jmlDataPerHal * $halAktif)-$jmlDataPerHal;

			$kosong=false;
			if (!$jmlData){
				$kosong=true;
			}

			if (isset($_POST['cari'])){
				$cari=$_POST['cari'];
				$sql="select * from user where username like '%$cari%' or status like '%$cari%' limit $awalData,$jmlDataPerHal";
			}else{
				$sql="select * from user limit $awalData,$jmlDataPerHal";		
			}

			$hasil=mysqli_query($koneksi,$sql) or die(mysqli_error($koneksi));

		?>
		<div class="utama">
			<h2 class="text-center">Daftar Mahasiswa</h2>
			<div class="text-center"><a href="prnMhsPdf.php"><span class="fas fa-print">&nbsp;Print</span></a></div>
			<span class="float-left">
				<a class="btn btn-success" href="addUser.php">Tambah Data</a>
			</span>
			<span class="float-right">
				<form action="" method="post" class="form-inline">
					<button class="btn btn-success" type="submit" name="cari" id="tombol-cari"> Cari</button>
					<input class="form-control mr-2 ml-2" type="text" name="cari" placeholder="cari data mahasiswa..." autofocus autocomplete="off" id="keyword">
				</form>
			</span>
			<br><br>

			<ul class="pagination">
				<?php
					if ($halAktif>1){
						$back=$halAktif-1;
						echo "<li class='page-item'><a class='page-link' href=?hal=$back>&laquo;</a></li>";
					}
					for($i=1;$i<=$jmlHal;$i++){
						if ($i==$halAktif){
							echo "<li class='page-item'><a class='page-link' href=?hal=$i style='font-weight:bold;color:red;'>$i</a></li>";
						}else{
							
							echo "<li class='page-item'><a class='page-link' href=?hal=$i>$i</a></li>";
						}	
					}
					if ($halAktif<$jmlHal){
						$forward=$halAktif+1;
						echo "<li class='page-item'><a class='page-link' href=?hal=$forward>&raquo;</a></li>";
					}
				?>
			</ul>	

		<div id="container">		
			<!-- Cetak data dengan tampilan tabel -->
			<table class="table table-hover">
				<thead class="thead-light">
					<tr>
						<th>No.</th>
						<th>Username</th>
						<th>Status</th>
						<th>Aksi</th>
					</tr>
				</thead>
				
				<tbody>
					<?php
						if ($kosong){
					?>
						<tr><th colspan="6">
							<div class="alert alert-info alert-dismissible fade show text-center">
							<!--<button type="button" class="close" data-dismiss="alert">&times;</button>-->
							Data user tidak ada
							</div>
						</th></tr>
					<?php
					}else{	
						if($awalData==0){
							$no=$awalData+1;
						}else{
							$no=$awalData+1;
						}
						while($row=mysqli_fetch_assoc($hasil)){
					?>	
						<tr>
							<td><?php echo $no?></td>
							<td><?php echo $row["username"]?></td>
							<td><?php echo $row["status"]?></td>
							<td>
								<a class="btn btn-outline-primary btn-sm" href="editUser.php?id=<?php echo $row['iduser']?>">Edit</a>
								<a class="btn btn-outline-danger btn-sm" href="hpsUser.php?id=<?php echo $row["iduser"]?>" iduser="linkHps" onclick="return confirm('Yakin dihapus nih?')">Hapus</a>
							</td>
						</tr>
								<?php 
									$no++;
						}
					}
							?>
				</tbody>
			</table>
		</div>	
		
	</div>
	<script src="js/userScript.js"> </script>
</body>
</html>	
