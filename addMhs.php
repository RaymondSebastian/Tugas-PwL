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
		<h3>TAMBAH DATA MAHASISWA</h3>
		<form id="mahasiswaForm" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label for="nim">NIM:</label>
				<input class="form-control" type="text" name="nim" id="nim"
					placeholder="A99.9999.99999" required>
				<span id="nimError" class="error"></span>
			</div>
			<div class="form-group">
				<label for="nama">Nama:</label>
				<input class="form-control" type="text" name="nama" id="nama" required>
			</div>
			<div class="form-group">
				<label for="email">Email:</label>
				<input class="form-control" type="email" name="email" id="email" required>
			</div>
			<div class="form-group">
				<label for="foto">Foto</label>
				<input class="form-control" type="file" name="foto" id="foto">
			</div><br>
			<div>
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
		</form>
		<div id="ajaxResponse"></div>
	</div>

	<script>
    $(document).ready(function() {
        // Fungsi untuk mengecek NIM pada tabel mhs di database akademik12345
        function checkNIMExists(nim) {
            $.ajax({
                // Memanggil file cek_data_kembar.php
                url: 'cek_data_kembar.php',
                type: 'POST',
                data: { nim: nim },
                success: function(response) {
                    if (response === 'exists') {
                        showError("* Data sudah ada, silahkan isikan yang lain");
                        $("#nim").val("").focus();
                        return false;
                    } else {
                        hideError();
                        $("#nama").focus();
                    }
                }
            });
        }

        function validateNIM() {
            var nim = $("#nim").val();
            var errorMsg = "";

            // Cek apakah NIM kosong
            if (nim.trim() === "") {
                errorMsg = "* NIM tidak boleh kosong! *";
                showError(errorMsg);
                return false;
            }
            // Cek panjang NIM
            else if (nim.length !== 14) {
                errorMsg = "* NIM harus terdiri dari 14 karakter (contoh: A12.2023.12345)";
                showError(errorMsg);
                return false;
            }
            // Cek format NIM
            else if (!/^[A-Z]\d{2}\.\d{4}\.\d{5}$/.test(nim)) {
                errorMsg = "* Format NIM tidak sesuai. Gunakan format: A12.2023.12345";
                showError(errorMsg);
                return false;
            }

            return true;
        }

        function showError(message) {
            $("#nimError").text(message).show();
        }

        function hideError() {
            $("#nimError").hide();
        }

        function formatNIM(input) {
            var value = input.value.replace(/\D/g, '');
            var formattedValue = '';

            if (value.length > 0) {
                if (!/[A-Z]/.test(value[0])) {
                    formattedValue = 'A';
                } else {
                    formattedValue = input.value[0];
                }

                if (value.length > 2) {
                    formattedValue += value.substr(0, 2) + '.';
                } else {
                    formattedValue += value;
                }

                if (value.length > 6) {
                    formattedValue += value.substr(2, 4) + '.';
                } else if (value.length > 2) {
                    formattedValue += value.substr(2);
                }
				
                if (value.length > 6) {
                    formattedValue += value.substr(6, 5);
                }
            }

            input.value = formattedValue.substr(0, 14);
        }

        // Event listeners
        $("#nim").on("blur", function() {
            if (validateNIM()) {
                checkNIMExists($(this).val());
            }
        }).on("keypress", function(event) {
            if (event.which === 13) {
                event.preventDefault();
                if (validateNIM()) {
                    checkNIMExists($(this).val());
                }
            }
        }).on("input", function() {
            formatNIM(this);
            hideError();
        });

        // Form submission with AJAX
        $("#mahasiswaForm").on("submit", function(event) {
            // Menghentikan perilaku submit form standar
            // Memungkinkan proses submit data melalui JavaScript
            event.preventDefault();

            // Memastikan NIM valid sebelum mengirim data ke server
            if (!validateNIM()) {
                return false;
            }

            // Membuat objek FormData untuk menangkap semua data form
            var formData = new FormData(this);

            $.ajax({
                // Memanggil file sv_addMhs.php
                url: 'sv_addMhs.php',
                type: 'POST',
                data: formData,
                // Untuk mendukung upload file
                processData: false,
                contentType: false,
                success: function(response) {
                    $("#ajaxResponse").html(response);
                },
                error: function() {
                    $("#ajaxResponse").html("Terjadi kesalahan saat mengirim data.");
                }
            });
        });
    });
</script>
 
</body>

</html>