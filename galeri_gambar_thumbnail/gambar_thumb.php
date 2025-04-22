<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";
    $thumb_dir = "thumbs/";
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $success_count = 0;

    foreach ($_FILES['gambar']['tmp_name'] as $key => $tmp_name) {
        $original_name = basename($_FILES['gambar']['name'][$key]);
        $imageFileType = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

        // Validasi
        if (!in_array($imageFileType, $allowed)) continue;
        if ($_FILES['gambar']['size'][$key] > 2 * 1024 * 1024) continue;

        $mime = mime_content_type($tmp_name);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif'])) continue;

        // Rename unik
        $unique_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $imageFileType;
        $target_file = $target_dir . $unique_name;
        $thumbpath = $thumb_dir . 'thumb_' . $unique_name;

        // Upload
        if (!move_uploaded_file($tmp_name, $target_file)) continue;

        // Buat thumbnail
        list($width, $height) = getimagesize($target_file);
        $new_width = 200;
        $new_height = floor($height * ($new_width / $width));

        switch ($imageFileType) {
            case 'jpg':
            case 'jpeg': $src = imagecreatefromjpeg($target_file); break;
            case 'png': $src = imagecreatefrompng($target_file); break;
            case 'gif': $src = imagecreatefromgif($target_file); break;
        }

        $thumb = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($thumb, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        switch ($imageFileType) {
            case 'jpg':
            case 'jpeg': imagejpeg($thumb, $thumbpath, 80); break;
            case 'png': imagepng($thumb, $thumbpath); break;
            case 'gif': imagegif($thumb, $thumbpath); break;
        }

        imagedestroy($src);
        imagedestroy($thumb);

        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO gambar_thumbnail (filename, filepath, thumbpath, width, height, uploaded_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssii", $unique_name, $target_file, $thumbpath, $width, $height);
        $stmt->execute();
        $success_count++;
    }

    echo "<div style='background: #d4edda; color: #155724; padding: 10px; margin: 10px 0;'>$success_count gambar berhasil diunggah.</div>";
}
?>

<!DOCTYPE html>
<html>
<head><title>Upload Gambar</title></head>
<body>
    <h2>Upload Gambar</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="gambar[]" multiple required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>