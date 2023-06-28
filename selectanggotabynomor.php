<?php
// Include file koneksi.php untuk mendapatkan koneksi ke database
include 'koneksi.php';

$conn = getConnection();

// Mendapatkan data yang dikirim melalui metode GET
$nomor = isset($_GET['nomor']) ? $_GET['nomor'] : '';

try {
    // Query SQL untuk memilih data buku berdasarkan kode
    $query = "SELECT * FROM anggota WHERE nomor = :nomor";
    
    // Mempersiapkan statement PDO untuk eksekusi query
    $statement = $conn->prepare($query);
    
    // Mengikat parameter dengan nilai yang sesuai
    $statement->bindParam(':nomor', $nomor);
    
    // Eksekusi statement
    $statement->execute();
    
    // Mendapatkan hasil seleksi
    $anggota = $statement->fetch(PDO::FETCH_ASSOC);
    
    // Mengirimkan response dengan data buku
    if ($anggota) {
        $response = [
            'status' => 'success',
            'data' => $anggota
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Data anggota tidak ditemukan'
        ];
    }
} catch(PDOException $e) {
    // Jika terjadi error, tampilkan pesan error
    $response = [
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat memilih data anggota: ' . $e->getMessage()
    ];
}

// Mengirimkan response JSON
echo json_encode($response);

// Menutup koneksi
$conn = null;
?>