<?php
session_start(); // Pastikan session dimulai
require_once __DIR__ . '/../../../config/database.php';

$db = new Database();
$conn = $db->connect();

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit();
}


$nim = $_SESSION['nim']; // NIM mahasiswa

$uploadDir = "uploads/$nim/";

// Memastikan folder upload ada
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$messages = [];

try {
    // Periksa apakah semua dokumen dengan jenis 'Jurusan' sudah ada
    $checkQuery = "
        SELECT COUNT(*) AS total
        FROM Verifikasi v
        JOIN Dokumen d ON v.id_dokumen = d.id_dokumen
        WHERE v.nim = :nim AND d.jenis_dokumen = 'Pusat'
    ";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bindParam(':nim', $nim, PDO::PARAM_STR);
    $stmtCheck->execute();
    $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    // Hitung total dokumen dengan jenis 'Pusat'
    if ($result['total'] == 6) {
        // Jika semua dokumen dengan jenis 'Pusat' sudah ada, update file dan status
        foreach ($_FILES['dokumen_file']['name'] as $key => $filename) {
            $id_dokumen = $_POST['id_dokumen'][$key];
            $filePath = $uploadDir . basename($filename);

            if (move_uploaded_file($_FILES['dokumen_file']['tmp_name'][$key], $filePath)) {
                $updateQuery = "
                    UPDATE Verifikasi 
                    SET 
                        path = :path,
                        status_verifikasi = 'Menunggu Diverifikasi',
                        tgl_upload = NOW()
                    WHERE nim = :nim 
                      AND id_dokumen = :id_dokumen
                      AND id_dokumen IN (
                          SELECT id_dokumen
                          FROM Dokumen
                          WHERE jenis_dokumen = 'Pusat'
                      )
                ";
                $stmtUpdate = $conn->prepare($updateQuery);
                $stmtUpdate->bindParam(':path', $filePath, PDO::PARAM_STR);
                $stmtUpdate->bindParam(':nim', $nim, PDO::PARAM_STR);
                $stmtUpdate->bindParam(':id_dokumen', $id_dokumen, PDO::PARAM_INT);
                $stmtUpdate->execute();

                $messages[] = "Dokumen berhasil diperbarui: " . htmlspecialchars($filename);
            } else {
                $messages[] = "Gagal memperbarui file: " . htmlspecialchars($filename);
            }
        }
    } else {
        // Jika belum ada 7 dokumen, lakukan insert untuk dokumen baru
        foreach ($_FILES['dokumen_file']['name'] as $key => $filename) {
            $id_dokumen = $_POST['id_dokumen'][$key];
            $filePath = $uploadDir . basename($filename);

            if (move_uploaded_file($_FILES['dokumen_file']['tmp_name'][$key], $filePath)) {
                $insertQuery = "
                    INSERT INTO Verifikasi (nim, id_dokumen, status_verifikasi, path, tgl_upload)
                    VALUES (
                        :nim, 
                        :id_dokumen, 
                        'Menunggu Diverifikasi', 
                        :path, 
                        NOW()
                    )
                ";
                $stmtInsert = $conn->prepare($insertQuery);
                $stmtInsert->bindParam(':nim', $nim, PDO::PARAM_STR);
                $stmtInsert->bindParam(':id_dokumen', $id_dokumen, PDO::PARAM_INT);
                $stmtInsert->bindParam(':path', $filePath, PDO::PARAM_STR);
                $stmtInsert->execute();

                $messages[] = "Dokumen berhasil diunggah: " . htmlspecialchars($filename);
            } else {
                $messages[] = "Gagal mengunggah file: " . htmlspecialchars($filename);
            }
        }
    }

    echo json_encode(['status' => 'success', 'messages' => $messages]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'messages' => ["Database error: " . htmlspecialchars($e->getMessage())]]);
}
?>
