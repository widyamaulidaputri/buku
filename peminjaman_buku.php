<?php
// Include file koneksi.php untuk mendapatkan koneksi ke database
include 'koneksi.php';

$conn = getConnection();

// Mendapatkan data yang dikirim melalui metode POST
$tanggalPeminjaman = isset($_POST['tanggal_peminjaman']) ? $_POST['tanggal_peminjaman'] : '';
$nomorAnggota = isset($_POST['nomor_anggota']) ? $_POST['nomor_anggota'] : '';
$statusPeminjaman = 'DIPINJAM';

try {
    // Mulai transaksi
    $conn->beginTransaction();

    // Query SQL untuk memasukkan data ke table peminjaman_master
    $queryMaster = "INSERT INTO peminjaman_master (tanggal_peminjaman, nomor_anggota, status_peminjaman)
                    VALUES (:tanggal_peminjaman, :nomor_anggota, :status_peminjaman)";

    // Mempersiapkan statement PDO untuk eksekusi query peminjaman_master
    $statementMaster = $conn->prepare($queryMaster);

    // Mengikat parameter dengan nilai yang sesuai
    $statementMaster->bindParam(':tanggal_peminjaman', $tanggalPeminjaman);
    $statementMaster->bindParam(':nomor_anggota', $nomorAnggota);
    $statementMaster->bindParam(':status_peminjaman', $statusPeminjaman);

    // Eksekusi statement peminjaman_master
    $statementMaster->execute();

    // Mendapatkan ID peminjaman_master yang baru saja dimasukkan
    $idPeminjamanMaster = $conn->lastInsertId();

    // Query SQL untuk memasukkan data ke table peminjaman_detail
    $queryDetail = "INSERT INTO peminjaman_detail (id_peminjaman_master, kode_buku)
                    VALUES (:id_peminjaman_master, :kode_buku)";

    // Looping untuk memasukkan data peminjaman_detail
    foreach ($_POST['kode_buku'] as $kodeBuku) {
        // Mempersiapkan statement PDO untuk eksekusi query peminjaman_detail
        $statementDetail = $conn->prepare($queryDetail);

        // Mengikat parameter dengan nilai yang sesuai
        $statementDetail->bindParam(':id_peminjaman_master', $idPeminjamanMaster);
        $statementDetail->bindParam(':kode_buku', $kodeBuku);

        // Eksekusi statement peminjaman_detail
        $statementDetail->execute();
    }

    // Commit transaksi
    $conn->commit();

    // Mengembalikan response sukses
    $response = [
        'status' => 'success',
        'message' => 'Peminjaman buku berhasil',
        'id_peminjaman_master' => $idPeminjamanMaster
    ];
} catch (PDOException $e) {
    // Rollback transaksi jika terjadi error
    $conn->rollBack();

    // Jika terjadi error, tampilkan pesan error
    $response = [
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat meminjam buku: ' . $e->getMessage()
    ];
}

// Mengirimkan response JSON
header('Content-Type: application/json');
echo json_encode($response);

// Menutup koneksi
$conn = null;
?>
