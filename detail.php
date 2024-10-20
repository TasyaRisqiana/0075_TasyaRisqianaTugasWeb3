<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_php";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan ID dari URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Memastikan ID adalah integer

    // Menggunakan prepared statements untuk mencegah SQL Injection
    $stmt = $conn->prepare("SELECT * FROM tb_users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Data tidak ditemukan.";
        exit;
    }

    // Jika diminta gambar, kirim gambar sebagai respons
    if (isset($_GET['show_image'])) {
        header("Content-type: image/jpeg"); // Sesuaikan dengan format gambar
        echo $row['foto']; // Tampilkan gambar dari BLOB
        exit; // Hentikan eksekusi lebih lanjut untuk hanya menampilkan gambar
    }
} else {
    echo "ID tidak ditemukan.";
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet"> <!-- Menggunakan font Montserrat -->
    <style>
        body {
            background-color: #e9ecef; /* Latar belakang lembut */
            font-family: 'Montserrat', sans-serif; /* Font modern */
            color: #343a40; /* Warna teks gelap untuk kontras yang baik */
        }
        .container {
            max-width: 400px; /* Lebar maksimum kontainer */
            margin: 40px auto; /* Margin untuk tengah halaman */
            padding: 20px; /* Ruang dalam kontainer */
            border-radius: 10px; /* Memperhalus sudut */
            background-color: #ffffff; /* Latar belakang putih untuk kontainer */
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1); /* Bayangan halus untuk kedalaman */
        }
        h1 {
            text-align: center; /* Menyelaraskan teks ke tengah */
            margin-bottom: 20px; /* Ruang di bawah judul */
            font-size: 26px; /* Ukuran font judul */
            color: green; /* Warna judul */
            font-weight: 700; /* Berat font judul */
        }
        .card {
            border: none; /* Menghilangkan border default */
            border-radius: 10px; /* Memperhalus sudut */
            margin-bottom: 20px; /* Ruang di bawah card */
        }
        .card-img-top {
            height: 200px; /* Tinggi gambar */
            object-fit: cover; /* Memastikan gambar memenuhi area */
            border-top-left-radius: 10px; /* Memperhalus sudut atas */
            border-top-right-radius: 10px; /* Memperhalus sudut atas */
        }
        .card-body {
            padding: 1rem; /* Ruang di dalam card */
        }
        .btn-primary {
            background-color: #28a745; /* Warna tombol */
            border: none; /* Menghilangkan border */
            border-radius: 5px; /* Sudut tombol lebih halus */
            padding: 10px; /* Ruang dalam tombol */
            transition: background-color 0.3s; /* Efek transisi */
            width: 100%; /* Tombol mengambil lebar penuh */
        }
        .btn-primary:hover {
            background-color: #218838; /* Warna saat hover */
        }
        .card-text strong {
            color: #495057; /* Warna untuk label teks */
            font-weight: 600; /* Berat font label teks */
        }
        .footer {
            text-align: center; /* Menyelaraskan footer ke tengah */
            margin-top: 20px; /* Ruang di atas footer */
            color: #888; /* Warna teks footer */
            font-size: 12px; /* Ukuran font footer */
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Detail User</h1>
    <div class="card">
        <img src="detail.php?id=<?php echo $row['id']; ?>&show_image=1" class="card-img-top" alt="Foto User">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($row['nama']); ?></h5>
            <p class="card-text"><strong>Jenis Kelamin:</strong> <?php echo htmlspecialchars($row['jenis_kelamin']); ?></p>
            <p class="card-text"><strong>No HP:</strong> <?php echo htmlspecialchars($row['nohp']); ?></p>
            <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
            <p class="card-text"><strong>Alamat:</strong> <?php echo htmlspecialchars($row['alamat']); ?></p>
            <a href="index.php" class="btn btn-success">Kembali</a>
        </div>
    </div>
    <div class="footer">
        &copy; <?php echo date("Y"); ?> Detail Informasi Pengguna
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
