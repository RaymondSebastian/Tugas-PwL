<?php
require "../fungsi.php";
require "../head.html";

$keyword = $_GET["keyword"];
$jmlDataPerHal = 5;

// Query pencarian data user
$sql = "SELECT iduser, username, status FROM user 
        WHERE idUser LIKE '%$keyword%' 
        OR username LIKE '%$keyword%' 
        OR status LIKE '%$keyword%'";
$qry = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
$jmlData = mysqli_num_rows($qry);
$jmlHal = ceil($jmlData / $jmlDataPerHal);

$halAktif = isset($_GET['hal']) ? $_GET['hal'] : 1;
$awalData = ($jmlDataPerHal * $halAktif) - $jmlDataPerHal;

$kosong = $jmlData == 0;

// Query data dengan limit untuk pagination
$sql = "SELECT iduser, username, status FROM user 
        WHERE iduser LIKE '%$keyword%' 
        OR username LIKE '%$keyword%' 
        OR status LIKE '%$keyword%'
        LIMIT $awalData, $jmlDataPerHal";
$hasil = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
?>

<table class="table table-hover">
    <thead class="thead-light">
        <tr>
            <th>No.</th>
            <th>ID User</th>
            <th>Username</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = $awalData + 1;
        while ($row = mysqli_fetch_assoc($hasil)) {
        ?>    
        <tr>
            <td><?php echo $no ?></td>
            <td><?php echo htmlspecialchars($row["iduser"]) ?></td>
            <td><?php echo htmlspecialchars($row["username"]) ?></td>
            <td><?php echo htmlspecialchars($row["status"]) ?></td>
            <td>
                <a class="btn btn-outline-primary btn-sm" href="editUser.php?kode=<?php echo $row['iduser'] ?>">Edit</a>
                <a class="btn btn-outline-danger btn-sm" href="hpsUser.php?kode=<?php echo $row['iduser'] ?>" id="linkHps" onclick="return confirm('Yakin dihapus nih?')">Hapus</a>
            </td>
        </tr>
        <?php 
            $no++;
        }
        ?>
    </tbody>
</table>
