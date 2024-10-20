<?php 
// Konfigurasi database
$host = 'localhost'; // Sesuaikan dengan host database Anda
$dbname = 'db_php'; // Nama database Anda
$username = 'root'; // Ganti jika username database Anda berbeda
$password = ''; // Ganti jika password database Anda berbeda

try {
    // Membuat koneksi PDO ke database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Mengatur mode error PDO ke exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cek apakah form sudah disubmit dengan metode POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Pastikan semua data tersedia
        if (!empty($_POST['nama']) && !empty($_POST['alamat']) && !empty($_POST['nohp']) && !empty($_POST['email']) && !empty($_POST['jenis_kelamin']) && !empty($_FILES['foto']['tmp_name'])) {
            
            // Ambil data dari form
            $nama = htmlspecialchars($_POST['nama']); // Hindari XSS dengan htmlspecialchars
            $alamat = htmlspecialchars($_POST['alamat']);
            $nohp = htmlspecialchars($_POST['nohp']);
            $email = htmlspecialchars($_POST['email']);
            
            // Validasi jenis_kelamin sebagai ENUM (misalnya 'Laki-laki' atau 'Perempuan')
            $jenis_kelamin = $_POST['jenis_kelamin'];
            if ($jenis_kelamin !== 'Laki-laki' && $jenis_kelamin !== 'Perempuan') {
                die('Jenis kelamin tidak valid.');
            }
            
            // Ambil data foto sebagai BLOB
            $foto = file_get_contents($_FILES['foto']['tmp_name']);
            
            // SQL untuk memasukkan data ke dalam tabel tb_users
            $sql = "INSERT INTO tb_users (nama, alamat, nohp, email, jenis_kelamin, foto) 
                    VALUES (:nama, :alamat, :nohp, :email, :jenis_kelamin, :foto)";
            
            // Siapkan pernyataan SQL
            $stmt = $pdo->prepare($sql);

            // Bind parameter ke nilai
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':alamat', $alamat);
            $stmt->bindParam(':nohp', $nohp);
			$stmt->bindParam(':foto', $foto, PDO::PARAM_LOB); // Bind foto sebagai BLOB
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':jenis_kelamin', $jenis_kelamin);
            

            // Eksekusi pernyataan SQL
            if ($stmt->execute()) {
                // Redirect ke halaman utama setelah data berhasil disimpan
                header("Location: index.php?status=success");
                exit();
            } else {
                echo "Gagal menyimpan data.";
            }
        } else {
            echo "Semua kolom wajib diisi!";
        }
    }
} catch (PDOException $e) {
    // Menangani error jika koneksi atau eksekusi gagal
    echo "Error: " . $e->getMessage();
}
?>
