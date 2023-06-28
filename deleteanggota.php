<?php
// Include file koneksi.php untuk mendapatkan koneksi ke database
include 'koneksi.php';

$conn = getConnection();

// Mendapatkan data yang dikirim melalui metode POST
$nomor = isset($_POST['nomor']) ? $_POST['nomor'] : '';

try {
    // Query SQL untuk menghapus data buku berdasarkan kode
    $query = "DELETE FROM anggota WHERE nomor = :nomor";

    // Mempersiapkan statement PDO untuk eksekusi query
    $statement = $conn->prepare($query);

    // Mengikat parameter dengan nilai yang sesuai
    $statement->bindParam(':nomor', $nomor);

    // Eksekusi statement
    $statement->execute();

    // Mengembalikan response sukses
    $response = [
        'status' => 'success',
        'message' => 'Data anggota berhasil dihapus'
    ];
} catch(PDOException $e) {
    // Jika terjadi error, tampilkan pesan error
    $response = [
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat menghapus data anggota: ' . $e->getMessage()
    ];
}

// Mengirimkan response JSON
echo json_encode($response);

// Menutup koneksi
$conn = null;
?>