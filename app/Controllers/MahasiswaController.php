<?php
require_once __DIR__ . '/../Models/MahasiswaModel.php';
require_once __DIR__ . '/../../config/database.php';
session_start();

class MahasiswaController{
    private $model;

    public function __construct()
    {
        // Gunakan koneksi dari Database::connect()
        $dbConnection = Database::connect();
        $this->model = new MahasiswaModel($dbConnection);
    }

    public function dashboard() {
        try {
            // Ambil data dari model
            $nim = $_SESSION['nim'];
            $jurusan = $this->model->getCountDokumenByNIMJurusan($nim);
            $pusat = $this->model->getCountDokumenByNIMPusat($nim);
            $data = $this->model->getDataFile($nim);
            $dataIjazah = $this->model->getDataFileIjazah($nim);
            $statusJurusan = $this->model->getStatusJurusan($nim);
            $statusPusat = $this->model->getStatusPusat($nim);

            // Kirim data ke view
            $viewPath = __DIR__ . '/../Views/Mahasiswa/dashboard_Mahasiswa.php';
            if (file_exists($viewPath)) {
                require_once $viewPath;
            } else {
                throw new Exception("View file not found: $viewPath");
            }
        } catch (Exception $e) {
            die("Error loading dashboard: " . $e->getMessage());
        }
    }

    public function getDashboardData($nim) {
        $jumlahDokumen = $this->model->getCountDokumenByNIMJurusan($nim);
        return [
            'jumlahDokumen' => $jumlahDokumen,
        ];
    }
}

// // Routing
// if (isset($_GET['action']) && $_GET['action'] === 'dashboard') {
//     $controller = new MahasiswaController();
//     $controller->dashboard();
// }


