<?php
require_once __DIR__ . '/../config/database.php';

// Mendapatkan controller dan action dari URL
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Routing
switch ($controller) {
    case 'superAdmin':
        include_once __DIR__ . '/../app/Controllers/SuperAdminController.php'; // HURUF C BESAR
        $superAdminController = new SuperAdminController();

        if ($action === 'manageUser') {
            $superAdminController->manageUsers();
        } elseif ($action === 'addMahasiswa') {
            $superAdminController->addMahasiswa();
        } elseif ($action === 'deleteMahasiswa') {
            $nim = isset($_GET['nim']) ? $_GET['nim'] : null;
            $superAdminController->deleteMahasiswa($nim);
        } elseif ($action === 'editMahasiswa') {
            $superAdminController->editMahasiswa(); // Parameter dihapus agar Intelephense tidak kuning
        } elseif ($action === 'addVerifikator') {
            $superAdminController->addVerifikator();
        } elseif ($action === 'deleteVerifikator') {
            $id_user = isset($_GET['id_user']) ? $_GET['id_user'] : null;
            $superAdminController->deleteVerifikator($id_user);
        } elseif ($action === 'editVerifikator') {
            $superAdminController->editVerifikator(); // Parameter dihapus
        } elseif ($action == 'addAdmin') {
            $superAdminController->addAdmin();
        } elseif ($action === 'deleteAdmin') {
            $id_user = isset($_GET['id_user']) ? $_GET['id_user'] : null;
            $superAdminController->deleteAdmin($id_user);
        } elseif ($action === 'editAdmin') {
            $superAdminController->editAdmin(); // Parameter dihapus
        } elseif ($action === 'manageDocument') {
            $superAdminController->manageDocuments();
        } elseif ($action === 'deleteVerifikasi') {
            $id_verifikasi = isset($_GET['id_verifikasi']) ? $_GET['id_verifikasi'] : null;
            $superAdminController->deleteVerifikasi($id_verifikasi);
        } else {
            $superAdminController->dashboard(); // Default action
        }
        break;
        
    case 'mahasiswa':
        include_once __DIR__ . '/../app/Controllers/MahasiswaController.php'; // HURUF C BESAR
        $mahasiswaController = new MahasiswaController();
    
        if ($action === 'getdatajurusan') {
            $nim = isset($_GET['nim']) ? $_GET['nim'] : null;
            $mahasiswaController->getDashboardData($nim);
        } else {
            $mahasiswaController->dashboard(); // Default action
        }
        break;

    case 'adminPusat':
        include_once __DIR__ . '/../app/Controllers/AdminPusatController.php';
        $adminPusatController = new AdminPusatController();
        
        if ($action === 'verifikasi') {
            $adminPusatController->dashboard();
        } else {
            $adminPusatController->dashboard(); // Default action
        }
        break;
        
    case 'adminJurusan':
        include_once __DIR__ . '/../app/Controllers/AdminJurusanController.php';
        $adminJurusanController = new AdminJurusanController();
        
        if ($action === 'updateStatusVerifikasi') {
            $adminJurusanController->dashboard();
        } else {
            $adminJurusanController->dashboard(); // Default action
        }
        break;

    default:
        // Default controller
        include_once __DIR__ . '/../app/Controllers/SuperAdminController.php'; // HURUF C BESAR
        $superAdminController = new SuperAdminController();
        $superAdminController->dashboard(); // Default action
        break;
}