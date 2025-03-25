<?php
    require "fungsi.php";

    if (isset($_POST['nim'])) {
        $nim = $_POST['nim'];
        // Prepared statement untuk mencegah SQL injection
        //prepare= Siapkan pernyataan SQL untuk dieksekusi:
        $stmt = $koneksi->prepare("SELECT nim FROM mhs WHERE nim = ?");
        //bind_param() adalah fungsi bawaan dalam PHP yang digunakan untuk mengikat
        //parameter ke nama variabel yang ditentukan. Fungsi ini mengikat variabel, meneruskan nilainya
        //sebagai input, dan menerima nilai output, jika ada, dari penanda parameter terkait.
        $stmt->bind_param("s", $nim);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "exists";
        } else {
            echo "not_exists";
        }
        
        $stmt->close();
    }
?>