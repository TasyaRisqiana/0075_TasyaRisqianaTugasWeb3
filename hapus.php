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
$id = $_GET['id'];

// Menghapus data dari tabel berdasarkan ID
$sql = "DELETE FROM tb_users WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: index.php"); // Redirect ke halaman utama setelah data dihapus
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>