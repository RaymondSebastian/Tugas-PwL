<?php include 'koneksi.php'; ?> 

<?php
$tanggal = $_GET['tanggal'] ?? '';

// Pagination Setup
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$tanggal_filter = !empty($tanggal) ? $conn->real_escape_string($tanggal) : '';

// Hitung total data
$count_sql = "SELECT COUNT(*) FROM gambar_thumbnail" . 
             ($tanggal_filter ? " WHERE DATE(uploaded_at) = '$tanggal_filter'" : "");
$total_result = $conn->query($count_sql)->fetch_row()[0];
$total_pages = ceil($total_result / $limit);

// Query ambil gambar
$sql = "SELECT * FROM gambar_thumbnail" . 
       ($tanggal_filter ? " WHERE DATE(uploaded_at) = '$tanggal_filter'" : "") . 
       " ORDER BY uploaded_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Galeri Gambar Responsive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container py-5">
    <h2 class="text-center mb-4">Galeri Gambar</h2>

    <!-- Filter tanggal -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($tanggal) ?>">
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn btn-success">Filter Tanggal</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <?php if (!empty($tanggal)): ?>
        <p class="text-muted">Menampilkan gambar yang diunggah pada tanggal: <strong><?= htmlspecialchars($tanggal) ?></strong></p>
    <?php endif; ?>

    <div class="row g-4">
        <?php 
        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card shadow-sm h-100">
                <img src="<?= htmlspecialchars($row['thumbpath']) ?>" class="card-img-top img-thumbnail" alt="Thumbnail"
                     data-bs-toggle="modal" data-bs-target="#modal<?= $row['id'] ?>">
                <div class="card-body">
                    <p class="card-text"><strong>Ukuran:</strong> <?= $row['width'] ?>x<?= $row['height'] ?></p>
                    <a href="<?= htmlspecialchars($row['filepath']) ?>" class="btn btn-sm btn-primary" target="_blank">Lihat Asli</a>
                    <form action="hapus.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus gambar ini?')">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="filepath" value="<?= htmlspecialchars($row['filepath']) ?>">
                        <input type="hidden" name="thumbpath" value="<?= htmlspecialchars($row['thumbpath']) ?>">
                        <button type="submit" class="btn btn-sm btn-danger mt-2">Hapus</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal preview -->
        <div class="modal fade" id="modal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <img src="<?= htmlspecialchars($row['filepath']) ?>" class="img-fluid w-100" alt="Full Image">
                    </div>
                </div>
            </div>
        </div>
        <?php 
            endwhile;
        else:
            echo "<p class='text-center'>Belum ada gambar diunggah.</p>";
        endif;
        $conn->close();
        ?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center mt-4">
            <?php
            $get_params = $_GET;
            unset($get_params['page']);
            for ($i = 1; $i <= $total_pages; $i++):
                $get_params['page'] = $i;
                $link = htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($get_params));
            ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $link ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
</body>
</html>