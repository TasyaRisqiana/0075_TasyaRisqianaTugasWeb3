<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id = $_GET['id'];

$sql = "SELECT * FROM tb_users WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fotoLama = $row['foto'];
} else {
    echo "Data tidak ditemukan!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $nohp = !empty($_POST['nohp']) ? $_POST['nohp'] : NULL;
    $email = $_POST['email'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    
    if (!empty($_FILES['foto']['tmp_name'])) {
        $fotoBaru = file_get_contents($_FILES['foto']['tmp_name']);
    } else {
        $fotoBaru = $fotoLama;
    }

    $sql_update = "UPDATE tb_users SET nama=?, alamat=?, nohp=?, foto=?, email=?, jenis_kelamin=? WHERE id=?";

    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param('ssssssi', $nama, $alamat, $nohp, $fotoBaru, $email, $jenis_kelamin, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Data</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $row['nama']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" required><?php echo $row['alamat']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="nohp" class="form-label">No HP</label>
                <input type="text" class="form-control" id="nohp" name="nohp" value="<?php echo $row['nohp']; ?>">
            </div>
			<div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                <small>Biarkan kosong jika tidak ingin mengubah foto.</small>
            </div>
            <div class="mb-3">
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="Laki-laki" <?php echo $row['jenis_kelamin'] == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php echo $row['jenis_kelamin'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
