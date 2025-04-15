<?php
// Koneksi database
$conn = new mysqli("localhost", "root", "", "akademik06983");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk membuat thumbnail
function createThumbnail($source_path, $target_path, $max_width = 200) {
    if (!function_exists('imagecreatefromjpeg')) {
        return false; // GD tidak tersedia
    }

    list($width, $height, $type) = getimagesize($source_path);

    switch ($type) {
        case IMAGETYPE_JPEG:
            $src = imagecreatefromjpeg($source_path);
            break;
        case IMAGETYPE_PNG:
            $src = imagecreatefrompng($source_path);
            break;
        case IMAGETYPE_GIF:
            $src = imagecreatefromgif($source_path);
            break;
        default:
            return false;
    }

    $new_height = floor($height * ($max_width / $width));
    $tmp = imagecreatetruecolor($max_width, $new_height);

    // Preserve transparency untuk PNG/GIF
    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
        imagecolortransparent($tmp, imagecolorallocatealpha($tmp, 0, 0, 0, 127));
        imagealphablending($tmp, false);
        imagesavealpha($tmp, true);
    }

    imagecopyresampled($tmp, $src, 0, 0, 0, 0, $max_width, $new_height, $width, $height);

    $result = imagejpeg($tmp, $target_path, 80);
    imagedestroy($src);
    imagedestroy($tmp);

    return $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["gambar"])) {
    $target_dir = "uploads/";
    
    // Buat folder upload jika belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $file_name = uniqid() . '_' . basename($_FILES["gambar"]["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // 1. Cek apakah benar gambar
    $check = getimagesize($_FILES["gambar"]["tmp_name"]);
    if ($check === false) {
        die("File bukan gambar.");
    }

    // 2. Cek ukuran file (maksimal 2MB)
    if ($_FILES["gambar"]["size"] > 2 * 1024 * 1024) {
        die("Ukuran file terlalu besar. Maksimal 2MB.");
    }

    // 3. Validasi ekstensi
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed)) {
        die("Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.");
    }

    // 4. Pindahkan file ke folder upload
    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
        echo "File " . htmlspecialchars($file_name) . " berhasil diupload.<br>";

        // 5. Buat thumbnail jika GD tersedia
        $thumbnail_path = $target_dir . "thumb_" . $file_name;
        $thumbnail_created = createThumbnail($target_file, $thumbnail_path);

        // 6. Simpan ke database
        $stmt = $conn->prepare("INSERT INTO gambar (nama_file, lokasi_file) VALUES (?, ?)");
        $file_to_store = $thumbnail_created ? $thumbnail_path : $target_file;
        $stmt->bind_param("ss", $file_name, $file_to_store);
        
        if ($stmt->execute()) {
            echo "Gambar disimpan ke database.<br>";
        } else {
            echo "Gagal menyimpan ke database: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Gagal mengupload file. Error: " . $_FILES["gambar"]["error"];
    }
}
?>

<!-- Tampilkan Gambar -->
<?php
$result = $conn->query("SELECT * FROM gambar ORDER BY id DESC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<img src='" . htmlspecialchars($row['lokasi_file']) . "' width='150' style='margin:10px;'><br>";
        echo "Nama: " . htmlspecialchars($row['nama_file']) . "<br><br>";
    }
} else {
    echo "Belum ada gambar yang diupload.";
}
$conn->close();
?>