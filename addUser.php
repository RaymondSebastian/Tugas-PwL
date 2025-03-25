<!DOCTYPE html>
<html>

<head>
	<title>SELAMAT DATANG</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap lokal -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/styleku.css">
	<script src="bootstrap/js/bootstrap.js"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

	<style>
		.error {
			color: red;
			font-size: 0.9em;
			display: none;
		}
		#nim {
			width: 150px;
		}
		#ajaxResponse {
			margin-top: 15px;
		}
	</style>

</head>

<body>
<?php require "head.html"; ?>
	<div class="utama">
		<br><br><br>
		<h3>TAMBAH DATA USER</h3>
		<form id="userForm" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label for="username">Username :</label>
				<input class="form-control" type="text" name="username" id="username" required>
				<span id="usernameError" class="error"></span>
			</div>
			<div class="form-group">
				<label for="password">Password :</label>
				<input class="form-control" type="password" name="password" id="password" required>
			</div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-select">
                    <option selected>Pilih status user</option>
                    <option value="tu">TU</option>
                    <option value="admin">Admin</option>
                    <option value="dsn">Dosen</option>
                    <option value="mhs">Mahasiswa</option>
                </select>
            </div>
			<div>
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
		</form>
		<div id="ajaxResponse"></div>
	</div>

	<script>
    $(document).ready(() => {
        const checkUsernameExists = (username) => {
            $.ajax({
                url: 'cekDataKembarUser.php',
                type: 'POST',
                data: { username },
                success: (response) => {
                    if (response === "exists") {
                        showError("* Data sudah ada, silahkan isikan yang lain");
                        $("#username").focus();
                    } else {
                        hideError();
                        $("#username").focus();
                    }
                }
            });
        }

        $("#username").on("keyup", (e) => {
            let username = $(e.target).val().trim()
            checkUsernameExists(username)
        })

        function showError(message) {
            $("#usernameError").text(message).show();
        }

        function hideError() {
            $("#usernameError").hide();
        }

        $("#userForm").on("submit", function(event) {
            event.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: 'sv_addUser.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $("#ajaxResponse").html(response);
                },
                error: (err) => {
                    $("#ajaxResponse").html("Terjadi kesalahan saat mengirim data.");
                }
            });
        });
    });
</script>
 
</body>

</html>