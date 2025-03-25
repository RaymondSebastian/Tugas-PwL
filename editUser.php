<!DOCTYPE html>
<html>
<head>
	<title>Sistem Informasi Akademik::Edit Data Dosen</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/styleku.css">
	<script src="bootstrap/js/bootstrap.js"></script>
</head>
<body>
	<?php
	require "fungsi.php";
	require "head.html";
	$id=$_GET['id'];
    echo $id;

	$stmt = $koneksi->prepare("SELECT * FROM user WHERE iduser = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();
    
    $statusList = [
        ["value" => "tu", "text" => "TU"],
        ["value" => "admin", "text" => "Admin"],
        ["value" => "dsn", "text" => "Dosen"],
        ["value" => "mhs", "text" => "Mahasiswa"]
    ];
	?>
	<div class="utama">
		<h2 class="mb-3 text-center">EDIT DATA User</h2>			
			<form method="post" action="sv_editUser.php">
				<div class="form-group">
					<label for="iduser">ID User :</label>
					<input class="form-control col-md-3" type="text" name="iduser" id="iduser" value="<?php echo $row['iduser']?>" readonly>					
				</div>
				<div class="form-group">
					<label for="username">Username :</label>
					<input class="form-control" type="text" name="username" id="username" value="<?php echo $row['username']?>">
				</div>
				<div class="form-group">
					<label for="status">Status :</label>
					<select id="status" name="status" class="form-select">
                        <option disabled>Pilih status user</option>
                            <?php foreach ($statusList as $status) : ?>
                                <option value="<?= $status['value']; ?>" <?= ($row['status'] == $status['value']) ? 'selected' : ''; ?>>
                                    <?= $status['text']; ?>
                                </option>
                            <?php endforeach; ?>
                    </select>
				</div>
				<div>		
					<button class="btn btn-primary" type="submit">Simpan</button>
				</div>
			</form>
	</div>
</body>
</html>