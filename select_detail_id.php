<?php
// Include file koneksi.php untuk mendapatkan koneksi ke database
include 'koneksi.php';

$conn = getConnection();

// Mendapatkan data yang dikirim melalui metode GET
$idPeminjamanMaster = isset($_GET['id']) ? $_GET['id'] : '';

try {
    // Query SQL untuk mengambil data peminjaman_master berdasarkan ID
    $query = "SELECT pm.*, pd.kode_buku
              FROM peminjaman_master AS pm
              INNER JOIN peminjaman_detail AS pd ON pm.id = pd.id_peminjaman_master
              WHERE pm.id = :id_peminjaman_master";

    // Mempersiapkan statement PDO untuk eksekusi query
    $statement = $conn->prepare($query);

    // Mengikat parameter dengan nilai yang sesuai
    $statement->bindParam(':id_peminjaman_master', $idPeminjamanMaster);

    // Eksekusi statement
    $statement->execute();

    // Mengambil semua baris hasil sebagai array
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Mengembalikan response sukses dengan data peminjaman
    $response = [
        'status' => 'success',
        'data' => $result
    ];
} catch (PDOException $e) {
    // Jika terjadi error, tampilkan pesan error
    $response = [
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat mengambil peminjaman: ' . $e->getMessage()
    ];
}

// Mengirimkan response JSON
header('Content-Type: application/json');
echo json_encode($response);

// Menutup koneksi
$conn = null;
?>
