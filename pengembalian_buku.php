<?php
// Include file koneksi.php untuk mendapatkan koneksi ke database
include 'koneksi.php';

$conn = getConnection();

// Mendapatkan data yang dikirim melalui metode POST
$idPeminjamanMaster = isset($_POST['id_peminjaman_master']) ? $_POST['id_peminjaman_master'] : '';
$tanggalPengembalian = isset($_POST['tanggal_pengembalian']) ? $_POST['tanggal_pengembalian'] : '';
$durasiKeterlambatan = isset($_POST['durasi_keterlambatan']) ? $_POST['durasi_keterlambatan'] : '';

try {
    // Mulai transaksi
    $conn->beginTransaction();

    // Query SQL untuk mengupdate data peminjaman_master
    $queryMaster = "UPDATE peminjaman_master
                    SET status_peminjaman = 'DIKEMBALIKAN', tanggal_pengembalian = :tanggal_pengembalian, durasi_keterlambatan = :durasi_keterlambatan
                    WHERE id = :id_peminjaman_master";

    // Mempersiapkan statement PDO untuk eksekusi query peminjaman_master
    $statementMaster = $conn->prepare($queryMaster);

    // Mengikat parameter dengan nilai yang sesuai
    $statementMaster->bindParam(':tanggal_pengembalian', $tanggalPengembalian);
    $statementMaster->bindParam(':durasi_keterlambatan', $durasiKeterlambatan);
    $statementMaster->bindParam(':id_peminjaman_master', $idPeminjamanMaster);

    // Eksekusi statement peminjaman_master
    $statementMaster->execute();

    // Commit transaksi
    $conn->commit();

    // Mengembalikan response sukses
    $response = [
        'status' => 'success',
        'message' => 'Pengembalian buku berhasil'
    ];
} catch (PDOException $e) {
    // Rollback transaksi jika terjadi error
    $conn->rollBack();

    // Jika terjadi error, tampilkan pesan error
    $response = [
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat mengembalikan buku: ' . $e->getMessage()
    ];
}

// Mengirimkan response JSON
header('Content-Type: application/json');
echo json_encode($response);

// Menutup koneksi
$conn = null;
?>
