<?php
require_once __DIR__ . '/../Models/SuperAdminModel.php';
require_once __DIR__ . '/../../config/database.php';
session_start();

class SuperAdminController
{
    private $model;

    public function __construct()
    {
        // Gunakan koneksi dari Database::connect()
        $dbConnection = Database::connect();
        $this->model = new SuperAdminModel($dbConnection);
    }


    // DONE
    public function dashboard()
    {

        try {
            // Ambil data dari model
            $mahasiswaCount = $this->model->getMahasiswaCount();
            $verifikatorCount = $this->model->getVerifikatorCount();
            $adminCount = $this->model->getAdminCount();
            $documents = $this->model->getDocuments();
            // Validasi data sebelum dikirim ke view
            if ($mahasiswaCount === null || $verifikatorCount === null || $adminCount === null) {
                throw new Exception("Failed to fetch data from the database.");
            }

            // Kirim data ke view
            $viewPath = __DIR__ . '/../Views/Superadmin/admin_dashboard.php';
            if (file_exists($viewPath)) {
                require_once($viewPath);
            } else {
                throw new Exception("View file not found: $viewPath");
            }
        } catch (Exception $e) {
            die("Error loading dashboard: " . $e->getMessage());
        }
    }

    public function manageUsers()
    {
        try {
            // Ambil data mahasiswa dari model
            $dataMahasiswa = $this->model->getMahasiswaData();
            $prodiList = $this->model->getAllProdi();
            $jurusanList = $this->model->getAllJurusan();
            $angkatanList = $this->model->getAllAngkatan();
            $dataVerifikator = $this->model->getVerifikatorData();
            $roleVerifikator = $this->model->getAllRole_User();
            $dataAdmin = $this->model->getAdminData();

            // Kirim data ke view
            $viewPath = __DIR__ . '/../Views/Superadmin/manajemen_mahasiswa.php';
            if (file_exists($viewPath)) {
                require_once($viewPath);
            } else {
                throw new Exception("View file not found: $viewPath");
            }
        } catch (Exception $e) {
            die("Error loading user management: " . $e->getMessage());
        }
    }

    public function manageDocuments()
    {
        try {
            $documents = $this->model->getDocuments();
            $viewPath = __DIR__ . '/../Views/Superadmin/manajemen_dokumen.php';
            if (file_exists($viewPath)) {
                require_once($viewPath);
            } else {
                throw new Exception("View file not found: $viewPath");
            }
        } catch (Exception $e) {
            die("Error loading document management: " . $e->getMessage());
        }
    }

    public function addMahasiswa()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nim = $_POST['nim'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $nama = $_POST['nama'];
            $no_telp = $_POST['no_telp'];
            $email = $_POST['email'];
            $kelas = $_POST['kelas'];
            $id_prodi = $_POST['id_prodi'];
            $id_jurusan = $_POST['id_jurusan'];
            $id_angkatan = $_POST['id_angkatan'];

            try {
                $this->model->addMahasiswa($nim, $nama, $username, $password, $no_telp, $email, $kelas, $id_prodi, $id_jurusan, $id_angkatan);
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Mahasiswa berhasil ditambahkan!';
            } catch (Exception $e) {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            }
            header("Location: index.php?controller=superAdmin&action=manageUser");
            exit;
        } else {
            include __DIR__ . '/../../app/Views/superadmin/manageUser.php';
        }
    }

    // Fungsi untuk menghapus mahasiswa
    public function deleteMahasiswa($nim)
    {
        try {
            // Mendapatkan id_user berdasarkan nim
            $id_user = $this->model->getIdUserByNim($nim);

            // Cek apakah id_user ditemukan
            if ($id_user) {
                // Menghapus data mahasiswa
                $this->model->deleteMahasiswa($nim);

                // Menghapus data user
                $this->model->deleteUser($id_user);

                // Set status sukses ke session
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Mahasiswa berhasil dihapus!';
            } else {
                throw new Exception("Mahasiswa dengan NIM $nim tidak ditemukan.");
            }
        } catch (Exception $e) {
            // Menangani jika ada kesalahan
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }

        // Redirect ke halaman manageUser setelah penghapusan
        header("Location: index.php?controller=superAdmin&action=manageUser");
        exit;
    }

    public function editMahasiswa()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nim = $_POST['nim'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $nama = $_POST['nama'];
            $no_telp = $_POST['no_telp'];
            $email = $_POST['email'];
            $kelas = $_POST['kelas'];
            $id_prodi = $_POST['id_prodi'];
            $id_jurusan = $_POST['id_jurusan'];
            $id_angkatan = $_POST['id_angkatan'];

            try {
                // Memanggil model untuk memperbarui data mahasiswa
                $this->model->updateMahasiswa($nim, $nama, $username, $password, $no_telp, $email, $kelas, $id_prodi, $id_jurusan, $id_angkatan);

                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Mahasiswa berhasil diperbarui!';
                header("Location: index.php?controller=superAdmin&action=manageUser");
            } catch (Exception $e) {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Terjadi kesalahan saat memperbarui mahasiswa: ' . $e->getMessage();
                header("Location: index.php?controller=superAdmin&action=manageUser");
            }
        }
    }

    public function addVerifikator()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role_user = $_POST['role_user'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $nama = $_POST['nama'];
            $no_telp = $_POST['no_telp'];
            $email = $_POST['email'];

            try {
                $this->model->addVerifikator($role_user, $username, $password, $nama, $no_telp, $email);
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Verifikator berhasil ditambahkan!';
            } catch (Exception $e) {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            }

            $_SESSION['selected_tab'] = 'verifikator';

            header("Location: index.php?controller=superAdmin&action=manageUser");
            exit;
        } else {
            include __DIR__ . '/../../app/Views/superadmin/manageUser.php';
        }
    }

    // Fungsi untuk menghapus verifikator
    public function deleteVerifikator($id_user)
    {
        try {

            // Cek apakah id_user ditemukan
            if ($id_user) {
                // Menghapus data user
                $this->model->deleteUser($id_user);

                // Set status sukses ke session
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Verifikator berhasil dihapus!';
            } else {
                throw new Exception("Verifikator dengan id_user $id_user tidak ditemukan.");
            }
        } catch (Exception $e) {
            // Menangani jika ada kesalahan
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        $_SESSION['selected_tab'] = 'verifikator';

        // Redirect ke halaman manageUser setelah penghapusan
        header("Location: index.php?controller=superAdmin&action=manageUser");
        exit;
    }

    public function editVerifikator()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_user = $_POST['id_user'];
            $role_user = $_POST['role_user'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $nama = $_POST['nama'];
            $no_telp = $_POST['no_telp'];
            $email = $_POST['email'];

            try {
                // Memanggil model untuk memperbarui data verifikator
                $this->model->updateVerifikator($id_user, $role_user, $username, $password, $nama, $no_telp, $email);

                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Verifikator berhasil diperbarui!';
                $_SESSION['selected_tab'] = 'verifikator';

                header("Location: index.php?controller=superAdmin&action=manageUser");
            } catch (Exception $e) {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Terjadi kesalahan saat memperbarui verifikator: ' . $e->getMessage();
                $_SESSION['selected_tab'] = 'verifikator';

                header("Location: index.php?controller=superAdmin&action=manageUser");
            }
        }
    }

    public function addAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role_user = $_POST['role_user'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $nama = $_POST['nama'];
            $no_telp = $_POST['no_telp'];
            $email = $_POST['email'];

            try {
                $this->model->addAdmin($role_user, $username, $password, $nama, $no_telp, $email);
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Superadmin berhasil ditambahkan!';
            } catch (Exception $e) {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            }
            $_SESSION['selected_tab'] = 'admin';

            header("Location: index.php?controller=superAdmin&action=manageUser");
            exit;
        } else {
            include __DIR__ . '/../../app/Views/superadmin/manageUser.php';
        }
    }

    // Fungsi untuk menghapus data superadmin
    public function deleteAdmin($id_user)
    {
        try {

            // Cek apakah id_user ditemukan
            if ($id_user) {
                // Menghapus data user
                $this->model->deleteUser($id_user);

                // Set status sukses ke session
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Superadmin berhasil dihapus!';
            } else {
                throw new Exception("Superadmin dengan id_user $id_user tidak ditemukan.");
            }
        } catch (Exception $e) {
            // Menangani jika ada kesalahan
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }
        $_SESSION['selected_tab'] = 'admin';

        // Redirect ke halaman manageUser setelah penghapusan
        header("Location: index.php?controller=superAdmin&action=manageUser");
        exit;
    }

    public function editAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_user = $_POST['id_user'];
            $role_user = $_POST['role_user'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $nama = $_POST['nama'];
            $no_telp = $_POST['no_telp'];
            $email = $_POST['email'];

            try {
                // Memanggil model untuk memperbarui data admin
                $this->model->updateAdmin($id_user, $role_user, $username, $password, $nama, $no_telp, $email);

                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Superadmin berhasil diperbarui!';
                $_SESSION['selected_tab'] = 'admin';

                header("Location: index.php?controller=superAdmin&action=manageUser");
            } catch (Exception $e) {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Terjadi kesalahan saat memperbarui superadmin: ' . $e->getMessage();
                $_SESSION['selected_tab'] = 'admin';

                header("Location: index.php?controller=superAdmin&action=manageUser");
            }
        }
    }

    // Fungsi untuk menghapus riwayat verifikasi
    public function deleteVerifikasi($id_verifikasi)
    {
        try {

            // Cek apakah id_user ditemukan
            if ($id_verifikasi) {
                // Menghapus data user
                $this->model->deleteVerifikasi($id_verifikasi);

                // Set status sukses ke session
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Data Verifikasi berhasil dihapus!';
            } else {
                throw new Exception("Data Verifikasi dengan id_verifikasi $id_verifikasi tidak ditemukan.");
            }
        } catch (Exception $e) {
            // Menangani jika ada kesalahan
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        }

        // Redirect ke halaman manageUser setelah penghapusan
        header("Location: index.php?controller=superAdmin&action=manageDocument");
        exit;
    }
}
