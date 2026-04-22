<?php
require_once __DIR__ . '/../Models/VerifikatorModel.php';
require_once __DIR__ . '/../../config/database.php';
session_start();

class adminJurusanController
{
    private $model;

    // Konstruktor untuk menerima parameter atau session
    public function __construct() {
        $dbConnection = Database::connect();
        $this->model = new VerifikatorModel($dbConnection);
    }

    // Dashboard method
    public function dashboard() {
        try {
            $jenisDokumen = 'Jurusan';
    
            // Tangani permintaan POST untuk pembaruan status
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                header('Content-Type: application/json'); // Pastikan respons dalam format JSON
                $input = json_decode(file_get_contents("php://input"), true);
    
                // Debug input untuk memastikan data diterima dengan benar
                error_log("Input JSON: " . print_r($input, true));
    
                if (isset($input['id_dokumen'], $input['nim'], $input['status'])) {
                    // Data pembaruan status diterima
                    $idDokumen = $input['id_dokumen'];
                    $nim = $input['nim'];
                    $status = $input['status'];
                    $catatan = $input['catatan'] ?? null;
    
                    // Pilih operasi berdasarkan status
                    if ($status === 'Disetujui') {
                        $result = $this->model->updateStatusVerifikasiDisetujui($idDokumen, $nim, $status,null);
                    } elseif ($status === 'Tidak Disetujui') {
                        if (!$catatan) {
                            echo json_encode(['success' => false, 'message' => 'Catatan diperlukan untuk status Tidak Disetujui']);
                            return;
                        }
                        $result = $this->model->updateStatusVerifikasiTidakDisetujui($idDokumen, $nim, $catatan);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Status tidak valid']);
                        return;
                    }
    
                    // Kirim respons berdasarkan hasil operasi
                    if ($result) {
                        echo json_encode(['success' => true, 'message' => 'Status berhasil diperbarui']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Gagal memperbarui status']);
                    }
                    return;
                }
    
                // Tangani permintaan untuk data dokumen berdasarkan NIM
                if (isset($input['nim'])) {
                    $documents = $this->model->getDocument($jenisDokumen, $input['nim']);
                    echo json_encode($documents);
                    return;
                }
    
                // Jika tidak ada data yang sesuai, kembalikan error
                echo json_encode(['success' => false, 'message' => 'Permintaan tidak valid']);
                return;
            }
    
            // Jika bukan POST, kirim data default untuk view dashboard
            $terverifikasiCount22 = $this->model->getTerverifikasiCount($jenisDokumen, '2022');
            $terverifikasiCount23 = $this->model->getTerverifikasiCount($jenisDokumen, '2023');
            $terverifikasiCount24 = $this->model->getTerverifikasiCount($jenisDokumen, '2024');
            $belumDiverifikasiCount22 = $this->model->getBelumDiverifikasiCount($jenisDokumen, '2022');
            $belumDiverifikasiCount23 = $this->model->getBelumDiverifikasiCount($jenisDokumen, '2023');
            $belumDiverifikasiCount24 = $this->model->getBelumDiverifikasiCount($jenisDokumen, '2024');
            $mahasiswaCount22 = $this->model->getMahasiswaCount('2022');
            $mahasiswaCount23 = $this->model->getMahasiswaCount('2023');
            $mahasiswaCount24 = $this->model->getMahasiswaCount('2024');
            $mhsDokumenLengkap = $this->model->getMhsWithDocumentCompleteJurusan();
            $riwayatVerifJurusan = $this->model->getMhsWithDocumentApprovedJurusan($jenisDokumen);
    
            $viewPath = __DIR__ . '/../Views/Verifikator/dashboard_admin_jurusan.php';
            if (file_exists($viewPath)) {
                require_once $viewPath;
            } else {
                throw new Exception("View tidak ditemukan: $viewPath");
            }
        } catch (Exception $e) {
            error_log("Exception: " . $e->getMessage());
            die("Error: " . $e->getMessage());
        }
    }
    
    
}
?>