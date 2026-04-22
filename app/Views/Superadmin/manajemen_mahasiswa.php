<?php
//  session_start();

// Periksa apakah user sudah login
if (!isset($_SESSION['username']) || $_SESSION['role_user'] !== 'superadmin') {
    header("Location: ../Views/login.php");

    exit();
}

// Ambil username dari session
$username = $_SESSION['username'] ?? 'Pengguna';
$nama = $_SESSION['nama'] ?? 'Pengguna';
$role_user = $_SESSION['role_user'] ?? 'Tidak diketahui';

?>
<html>

<head>
    <title>
        Dashboard Admin - Beranda
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .tabs {
            display: flex;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            background-color: lightgray;
            border: 1px solid #ccc;
            margin-right: 5px;
        }

        .tab.active {
            background-color: yellow;
            border-bottom: 2px solid #000;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active-content {
            display: block;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background-color: #f5f5f5;
            color: #1E1E1E;
        }

        .header {
            background-color: #fff;
            color: #1E1E1E;
            box-shadow: 0px -4px 25.1px 0px rgba(0, 0, 0, 0.25);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            position: fixed;
            top: 0;
            width: 100%;
            height: 76px;
            z-index: 1000;
        }

        .header .toggle-sidebar {
            display: flex;
            align-items: center;
        }

        .header .toggle-sidebar i {
            margin-right: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 700;
            /* Inter Bold */
        }

        .header .title {
            font-size: 14px;
            font-weight: 700;
            /* Inter Bold */
            margin-left: 10px;
        }

        .header .user-info {
            display: flex;
            align-items: center;
        }

        .header .user-info img {
            border-radius: 50%;
            margin-right: 10px;
        }

        .header .user-info .name {
            font-size: 14px;
        }

        .header .user-info .role {
            font-size: 12px;
        }

        .header .user-info .name div:first-child {
            font-weight: 700;
            /* Inter Bold */
        }

        .header .user-info .name div:last-child {
            font-weight: 400;
            /* Inter Regular */
        }

        .sidebar {
            height: 100vh;
            width: 300px;
            margin-top: 10px;
            background-color: #fff;
            position: fixed;
            top: 50px;
            bottom: 0;
            border-right: 1px solid #ddd;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease-in-out;
            z-index: 999;
            box-shadow: 0px -4px 25.1px 0px rgba(0, 0, 0, 0.25);
            transform: translateX(0);
        }

        .sidebar.hidden {
            transform: translateX(-100%);
        }

        .sidebar .menu-item {
            padding: 15px 20px;
            color: #333;
            display: flex;
            align-items: center;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            border-radius: 10px;
            margin: 0 14px;
        }

        .sidebar .menu-item:hover,
        .sidebar .menu-item.active {
            background-color: #FFAF01;
            color: #1E1E1E;
        }

        .sidebar .menu-item i {
            margin-right: 10px;
            font-size: 20px;
            display: inline-block;
        }

        .sidebar .menu-item.logout {
            color: #DC3545;
            margin-top: auto;
            margin-bottom: 70px;
        }

        .content {
            margin-left: 300px;
            transition: margin-left 0.3s;
            padding-top: 70px;
        }

        .content .welcome {
            background-color: #1E1E1E;
            ;
            color: #fff;
            padding: 20px;
            border-radius: 0px 0px 20px 20px;
            ;
            margin-bottom: 20px;
            height: 110px;
            width: 100%;
        }

        .content .welcome h1 {
            margin: 10px;
            font-size: 30px;
            font-style: bold;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active-content {
            display: block;
        }

        .tabs-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin: 20px 50px;

        }

        .tab {
            padding: 10px 20px;
            border-radius: 50px;
            background-color: #f7f7f7;
            color: #333;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .tab.active {
            background-color: #FFAF01;
            color: #fff;
        }

        .table-container {
            margin: 20px 50px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            margin-bottom: 20px;
        }

        .table-header h2 {
            margin: 0;
            font-size: 1.5rem;
            color: #333;
        }

        .table-header .btn {
            margin-top: 10px;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            text-align: left;
        }

        .user-table th,
        .user-table td {
            padding: 10px 15px;
            border: 1px solid #ddd;
        }

        .user-table th {
            background-color: #f7f7f7;
            font-weight: 700;
            color: #555;
        }

        .user-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .modal-header {
            font-weight: bold;
        }

        .modal-tambah .modal-header {
            background-color: #28a745;
            color: white;
        }

        .modal-edit .modal-header {
            background-color: #ffc107;
            color: white;
        }

        .modal-hapus .modal-header {
            background-color: #dc3545;
            color: white;
        }

        .modal-footer .btn {
            padding: 10px 15px;
        }

        .btn-primary {
            background-color: #28a745;
            color: white;
            border: none;
        }

        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 10px; /* Memberi jarak antar tombol */
        }

        .btn-edit {
            background-color: #FFAF01;
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;

        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #fff;
            border-top: 1px solid #ddd;
            position: fixed;
            bottom: 0;
            width: calc(100% - 300px);
            left: 300px;
            transition: width 0.3s, left 0.3s;
        }

        .toggle-sidebar {
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                display: none;
            }

            .content {
                margin-left: 0;
            }

            .footer {
                width: 100%;
                left: 0;
            }
        }
    </style>
</head>

<body>
<div class="header">
        <div class="toggle-sidebar ">
            <i class="fas fa-bars pe-auto" style="font-size: 16px;"></i>
            <div class="title fw-bold " style="font-size: 14px; margin-left: 15px;">SISTEM BEBAS TANGGUNGAN TA</div>
        </div>
        <div class="user-info">
                <img alt="User profile picture" height="40" src="https://storage.googleapis.com/a1aa/image/AW0PXsLkpnZ8DNB4clVvuVaJnwXJMkA3KDoEGtUoITZtlc9E.jpg" width="40"/>
                <div class="name">
                    <div>
                    <?= htmlspecialchars($nama); ?> <!-- Nama diambil dari session -->
                    </div>
                    <div class="role">
                    <?= htmlspecialchars($role_user); ?> <!-- Role diambil dari session -->
                    </div>
                </div>
        </div>
  </div>
    <div class="sidebar">
        <a class="menu-item" href="index.php?controller=superAdmin&action=dashboard">
            <i class="bi bi-house"></i>
            Beranda
        </a>
        <a class="menu-item active" href="index.php?controller=superAdmin&action=manageUser">
            <i class="fas fa-users"></i>
            </i>
            Manajemen Pengguna
        </a>
        <a class="menu-item" href="index.php?controller=superAdmin&action=manageDocument">
            <i class="fas fa-folder"></i>
            Manajemen Dokumen
        </a>
        <a class="menu-item logout" href="/app/Views/logout.php">
            <i class="bi bi-power"></i>
            Keluar
        </a>
    </div>
    <div class="content">
        <div class="welcome">
            <h1>Manajemen Pengguna</h1>
        </div>

        <?php
        if (isset($_SESSION['status'])) {
            echo '<div class="alert alert-info m-4" role="alert">';
            if ($_SESSION['status'] == 'success') {
                echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8');
            } elseif ($_SESSION['status'] == 'error') {
                echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8');
            }
            // Hapus pesan setelah ditampilkan untuk mencegah muncul kembali
            unset($_SESSION['status'], $_SESSION['message']);
            echo '</div>';
        }
        ?>

        <div class="tabs-container">
            <?php
            $selected_tab = $_SESSION['selected_tab'] ?? 'mahasiswa';
            unset($_SESSION['selected_tab']);
            ?>
            <div class="tabs">
                <button class="tab active" onclick="changeTab('mahasiswa')">Mahasiswa</button>
                <button class="tab" onclick="changeTab('verifikator')">Verifikator</button>
                <button class="tab" onclick="changeTab('admin')">Admin</button>
            </div>
        </div>

        <!-- Konten Tab -->
        <div class="table-container">
            <div class="tab-content mahasiswa-content active-content">
                <div class="table-header">
                    <div class="row d-flex justify-content-between align-items-center">
                        <!-- Judul dan tombol Tambah -->
                        <h2 class="mb-2 col-md-9">Data User Mahasiswa</h2>
                        <div class="col-md-3 d-flex justify-content-end">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">Tambah Data Mahasiswa</button>
                        </div>
                    </div>

                    <!-- Kolom pencarian -->
                    <div class="d-flex justify-content-start mt-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari data Mahasiswa..." style="width: 250px; max-width: 100%;">
                    </div>
                </div>
                <div style="overflow-x: auto;">
                    <table class="user-table" id="dataTable">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Prodi</th>
                                <th>Jurusan</th>
                                <th>Angkatan</th>
                                <th>Kelas</th>
                                <th>No.Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <?php foreach ($dataMahasiswa as $mahasiswa): ?>
                            <tr>
                                <td><?= $mahasiswa['nim'] ?></td>
                                <td><?= $mahasiswa['nama_mahasiswa'] ?></td>
                                <td><?= $mahasiswa['username'] ?></td>
                                <td><?= $mahasiswa['password'] ?></td>
                                <td><?= $mahasiswa['prodi'] ?></td>
                                <td><?= $mahasiswa['jurusan'] ?></td>
                                <td><?= $mahasiswa['angkatan'] ?></td>
                                <td><?= $mahasiswa['kelas'] ?></td>
                                <td><?= $mahasiswa['no_telp'] ?></td>
                                <td>
                                    
                                    <div class="btn-container">
                                    <button class="btn btn-edit"
                                        data-toggle="modal"
                                        data-target="#modalEdit<?= $mahasiswa['nim'] ?>">
                                        Edit
                                    </button>
                                    <!-- Modal Edit -->
                                    <div class="modal fade modal-edit" id="modalEdit<?= $mahasiswa['nim'] ?>" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalEditLabel">Edit Data Mahasiswa</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="index.php?controller=superAdmin&action=editMahasiswa" method="POST">
                                                        <div class="form-group">
                                                            <label for="editNim">NIM</label>
                                                            <input type="text" class="form-control" id="editNim" name="nim" value="<?= $mahasiswa['nim'] ?>" required readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editNama">Nama Mahasiswa</label>
                                                            <input type="text" class="form-control" id="editNama" name="nama" value="<?= $mahasiswa['nama_mahasiswa'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editUsername">Username</label>
                                                            <input name="username" type="text" class="form-control" id="editUsername" value="<?= $mahasiswa['username'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editPassword">Password</label>
                                                            <input name="password" type="text" class="form-control" id="editPassword" value="<?= $mahasiswa['password'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input name="email" type="email" class="form-control" id="email" value="<?= $mahasiswa['email'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editKelas">Kelas</label>
                                                            <input type="text" class="form-control" id="editKelas" name="kelas" value="<?= $mahasiswa['kelas'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editNo_Telp">No.Telepon</label>
                                                            <input type="text" class="form-control" id="editNo_Telp" name="no_telp" value="<?= $mahasiswa['no_telp'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editProdi">Prodi</label>
                                                            <select class="form-control" id="editProdi" name="id_prodi" required>
                                                                <option value="" disabled selected>Pilih Prodi</option>
                                                                <?php foreach ($prodiList as $prodi): ?>
                                                                    <option value="<?= $prodi['id_prodi'] ?>" <?= $mahasiswa['id_prodi'] == $prodi['id_prodi'] ? 'selected' : '' ?>>
                                                                        <?= $prodi['role_prodi'] ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="editJurusan">Jurusan</label>
                                                            <select class="form-control" id="editJurusan" name="id_jurusan" required>
                                                                <option value="" disabled selected>Pilih Jurusan</option>
                                                                <?php foreach ($jurusanList as $jurusan): ?>
                                                                    <option value="<?= $jurusan['id_jurusan'] ?>" <?= $mahasiswa['id_jurusan'] == $jurusan['id_jurusan'] ? 'selected' : '' ?>>
                                                                        <?= $jurusan['role_jurusan'] ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="editAngkatan">Angkatan</label>
                                                            <select class="form-control" id="editAngkatan" name="id_angkatan" required>
                                                                <option value="" disabled selected>Pilih Angkatan</option>
                                                                <?php foreach ($angkatanList as $angkatan): ?>
                                                                    <option value="<?= $angkatan['id_angkatan'] ?>" <?= $mahasiswa['id_angkatan'] == $angkatan['id_angkatan'] ? 'selected' : '' ?>>
                                                                        <?= $angkatan['role_angkatan'] ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-edit">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-delete"
                                        data-toggle="modal"
                                        data-target="#modalHapus<?= $mahasiswa['nim'] ?>">
                                        Hapus
                                    </button>
                                    </div>
                                    <!-- Modal Hapus -->
                                    <div class="modal fade modal-hapus" id="modalHapus<?= $mahasiswa['nim'] ?>" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalHapusLabel">Hapus Data Mahasiswa</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus data mahasiswa ini?</p>
                                                    <p><strong>NIM:</strong> <span id="hapusNim"><?= $mahasiswa['nim'] ?></span></p>
                                                    <p><strong>Nama:</strong> <span id="hapusNama"><?= $mahasiswa['nama_mahasiswa'] ?></span></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <!-- Pastikan URL ini memuat parameter nim -->
                                                    <a href="index.php?controller=superAdmin&action=deleteMahasiswa&nim=<?= urlencode($mahasiswa['nim']) ?>" id="confirmHapus" class="btn btn-danger">Hapus</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-content verifikator-content">
                <div class="table-header">
                    <div class="row d-flex justify-content-between align-items-center">
                        <!-- Judul dan tombol Tambah -->
                        <h2 class="mb-2 col-md-9">Data User Verifikator</h2>
                        <div class="col-md-3 d-flex justify-content-end">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambahVerifikator">Tambah Data Verifikator</button>

                        </div>
                    </div>

                    <!-- Kolom pencarian -->
                    <div class="d-flex justify-content-start mt-3">
                        <input type="text" id="searchInputVerifikator" class="form-control" placeholder="Cari data Verifikator..." style="width: 250px; max-width: 100%;">
                    </div>
                </div>
                <div style="overflow-x: auto;">
                    <table class="user-table" id="dataTableVerifikator">
                        <thead>
                            <tr>
                                <th>Role User</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Nama Verifikator</th>
                                <th>No.Telepon</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <?php foreach ($dataVerifikator as $verifikator): ?>
                            <tr>
                                <td><?= $verifikator['role_user'] ?></td>
                                <td><?= $verifikator['username'] ?></td>
                                <td><?= $verifikator['password'] ?></td>
                                <td><?= $verifikator['nama'] ?></td>
                                <td><?= $verifikator['no_telp'] ?></td>
                                <td><?= $verifikator['email'] ?></td>
                                <td>
                                    <div class="btn-container">
                                    <button class="btn btn-edit"
                                        data-toggle="modal"
                                        data-target="#modalEdit<?= $verifikator['id_user'] ?>">
                                        Edit
                                    </button>
                                    <!-- Modal Edit -->
                                    <div class="modal fade modal-edit" id="modalEdit<?= $verifikator['id_user'] ?>" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalEditLabel">Edit Data Verifikator</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="index.php?controller=superAdmin&action=editVerifikator" method="POST">
                                                        <div class="form-group">
                                                            <label for="editId_User">Id User</label>
                                                            <input type="text" class="form-control" id="editId_User" name="id_user" value="<?= $verifikator['id_user'] ?>" required readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editRole_User">Role User</label>
                                                            <select class="form-control" id="editRole_User" name="role_user" required>
                                                                <option value="" disabled selected>Pilih Role User</option>
                                                                <?php foreach ($roleVerifikator as $role_user): ?>
                                                                    <option value="<?= $role_user['role_user'] ?>" <?= $verifikator['role_user'] == $role_user['role_user'] ? 'selected' : '' ?>>
                                                                        <?= $role_user['role_user'] ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editUsername">Username</label>
                                                            <input name="username" type="text" class="form-control" id="editUsername" value="<?= $verifikator['username'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editPassword">Password</label>
                                                            <input name="password" type="text" class="form-control" id="editPassword" value="<?= $verifikator['password'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editNama">Nama Verifikator</label>
                                                            <input type="text" class="form-control" id="editNama" name="nama" value="<?= $verifikator['nama'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editNo_Telp">No.Telepon</label>
                                                            <input type="text" class="form-control" id="editNo_Telp" name="no_telp" value="<?= $verifikator['no_telp'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input name="email" type="email" class="form-control" id="email" value="<?= $verifikator['email'] ?>" required>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-edit">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-delete"
                                        data-toggle="modal"
                                        data-target="#modalHapus<?= $verifikator['id_user'] ?>">
                                        Hapus
                                    </button>
                                    </div>
                                    <!-- Modal Hapus -->
                                    <div class="modal fade modal-hapus" id="modalHapus<?= $verifikator['id_user'] ?>" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalHapusLabel">Hapus Data Verifikator</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus data verifikator ini?</p>
                                                    <p><strong>Id User:</strong> <span id="hapusId_User"><?= $verifikator['id_user'] ?></span></p>
                                                    <p><strong>Nama:</strong> <span id="hapusNama"><?= $verifikator['nama'] ?></span></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <!-- Pastikan URL ini memuat parameter nim -->
                                                    <a href="index.php?controller=superAdmin&action=deleteVerifikator&id_user=<?= urlencode($verifikator['id_user']) ?>" id="confirmHapus" class="btn btn-danger">Hapus</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-content admin-content">
                <div class="table-header">
                    <div class="row d-flex justify-content-between align-items-center">
                        <!-- Judul dan tombol Tambah -->
                        <h2 class="mb-2 col-md-9">Data User Admin</h2>
                        <div class="col-md-3 d-flex justify-content-end">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambahAdmin">Tambah Data Admin</button>
                        </div>
                    </div>

                    <!-- Kolom pencarian -->
                    <div class="d-flex justify-content-start mt-3">
                        <input type="text" id="searchInputAdmin" class="form-control" placeholder="Cari data Admin..." style="width: 250px; max-width: 100%;">
                    </div>
                </div>
                <div style="overflow-x: auto;">
                    <table class="user-table" id="dataTableAdmin">
                        <thead>
                            <tr>
                                <th>Role User</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Nama Admin</th>
                                <th>No.Telepon</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <?php foreach ($dataAdmin as $admin): ?>
                            <tr>
                                <td><?= $admin['role_user'] ?></td>
                                <td><?= $admin['username'] ?></td>
                                <td><?= $admin['password'] ?></td>
                                <td><?= $admin['nama'] ?></td>
                                <td><?= $admin['no_telp'] ?></td>
                                <td><?= $admin['email'] ?></td>
                                <td>
                                    <div class="btn-container">
                                    <button class="btn btn-edit"
                                        data-toggle="modal"
                                        data-target="#modalEdit<?= $admin['id_user'] ?>">
                                        Edit
                                    </button>
                                    <!-- Modal Edit -->
                                    <div class="modal fade modal-edit" id="modalEdit<?= $admin['id_user'] ?>" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalEditLabel">Edit Data Admin</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="index.php?controller=superAdmin&action=editAdmin" method="POST">
                                                        <div class="form-group">
                                                            <label for="editId_User">Id User</label>
                                                            <input type="text" class="form-control" id="editId_User" name="id_user" value="<?= $admin['id_user'] ?>" required readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editRole_User">Role User</label>
                                                            <input type="text" class="form-control" id="editRole_User" name="role_user" value="<?= $admin['role_user'] ?>" required readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editUsername">Username</label>
                                                            <input name="username" type="text" class="form-control" id="editUsername" value="<?= $admin['username'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editPassword">Password</label>
                                                            <input name="password" type="text" class="form-control" id="editPassword" value="<?= $admin['password'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editNama">Nama Admin</label>
                                                            <input type="text" class="form-control" id="editNama" name="nama" value="<?= $admin['nama'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="editNo_Telp">No.Telepon</label>
                                                            <input type="text" class="form-control" id="editNo_Telp" name="no_telp" value="<?= $admin['no_telp'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input name="email" type="email" class="form-control" id="email" value="<?= $admin['email'] ?>" required>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-edit">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-delete"
                                        data-toggle="modal"
                                        data-target="#modalHapus<?= $admin['id_user'] ?>">
                                        Hapus
                                    </button>
                                    </div>
                                    <!-- Modal Hapus -->
                                    <div class="modal fade modal-hapus" id="modalHapus<?= $admin['id_user'] ?>" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalHapusLabel">Hapus Data Admin</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus data admin ini?</p>
                                                    <p><strong>Id User:</strong> <span id="hapusId_User"><?= $admin['id_user'] ?></span></p>
                                                    <p><strong>Nama:</strong> <span id="hapusNama"><?= $admin['nama'] ?></span></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <!-- Pastikan URL ini memuat parameter nim -->
                                                    <a href="index.php?controller=superAdmin&action=deleteAdmin&id_user=<?= urlencode($admin['id_user']) ?>" id="confirmHapus" class="btn btn-danger">Hapus</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

            <!-- Modal Tambah Mahasiswa-->
            <div class="modal fade modal-tambah" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahLabel">Tambah Data Mahasiswa</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form action="index.php?controller=superAdmin&action=addMahasiswa" method="POST">
                                <div class="form-group">
                                    <label for="nim">NIM</label>
                                    <input name="nim" type="text" class="form-control" id="nim" placeholder="Masukkan NIM Mahasiswa" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama Mahasiswa</label>
                                    <input name="nama" type="text" class="form-control" id="nama" placeholder="Masukkan Nama Mahasiswa" required>
                                </div>
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input name="username" type="text" class="form-control" id="username" placeholder="Masukkan Username" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input name="password" type="text" class="form-control" id="password" placeholder="Masukkan Password Mahasiswa" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input name="email" type="email" class="form-control" id="email" placeholder="Masukkan Email Mahasiswa" required>
                                </div>
                                <div class="form-group">
                                    <label for="kelas">Kelas</label>
                                    <input name="kelas" type="text" class="form-control" id="kelas" placeholder="Masukkan Kelas Mahasiswa" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_telp">No Telepon</label>
                                    <input name="no_telp" type="text" class="form-control" id="no_telp" placeholder="Masukkan No.Telepon Mahasiswa" required>
                                </div>
                                <div class="form-group">
                                    <label for="editProdi">Prodi</label>
                                    <select class="form-control" id="editProdi" name="id_prodi" required>
                                        <option value="" disabled selected>Pilih Prodi</option>
                                        <?php foreach ($prodiList as $prodi): ?>
                                            <option value="<?= $prodi['id_prodi'] ?>"><?= $prodi['role_prodi'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="editJurusan">Jurusan</label>
                                    <select class="form-control" id="editJurusan" name="id_jurusan" required>
                                        <option value="" disabled selected>Pilih Jurusan</option>
                                        <?php foreach ($jurusanList as $jurusan): ?>
                                            <option value="<?= $jurusan['id_jurusan'] ?>"><?= $jurusan['role_jurusan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="editAngkatan">Angkatan</label>
                                    <select class="form-control" id="editAngkatan" name="id_angkatan" required>
                                        <option value="" disabled selected>Pilih Angkatan</option>
                                        <?php foreach ($angkatanList as $angkatan): ?>
                                            <option value="<?= $angkatan['id_angkatan'] ?>"><?= $angkatan['role_angkatan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah Verifikator -->
            <div class="modal fade modal-tambah" id="modalTambahVerifikator" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahLabel">Tambah Data Verifikator</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form action="index.php?controller=superAdmin&action=addVerifikator" method="POST">
                                <div class="form-group">
                                    <label for="editRole_User">Role User</label>
                                    <select class="form-control" id="editRole_User" name="role_user" required>
                                        <option value="" disabled selected>Pilih Role User</option>
                                        <?php foreach ($roleVerifikator as $role_user): ?>
                                            <option value="<?= $role_user['role_user'] ?>"><?= $role_user['role_user'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input name="username" type="text" class="form-control" id="username" placeholder="Masukkan Username Verifikator" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input name="password" type="text" class="form-control" id="password" placeholder="Masukkan Password Verifikator" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama Verifikator</label>
                                    <input name="nama" type="text" class="form-control" id="nama" placeholder="Masukkan Nama Verifikator" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_telp">No Telepon</label>
                                    <input name="no_telp" type="text" class="form-control" id="no_telp" placeholder="Masukkan No.Telepon Verifikator" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input name="email" type="email" class="form-control" id="email" placeholder="Masukkan Email Verifikator" required>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah Admin -->
            <div class="modal fade modal-tambah" id="modalTambahAdmin" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahLabel">Tambah Data Admin</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form action="index.php?controller=superAdmin&action=addAdmin" method="POST">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input name="username" type="text" class="form-control" id="username" placeholder="Masukkan Username Superadmin" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input name="password" type="text" class="form-control" id="password" placeholder="Masukkan Password Superdmin" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama Verifikator</label>
                                    <input name="nama" type="text" class="form-control" id="nama" placeholder="Masukkan Nama Superadmin" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_telp">No Telepon</label>
                                    <input name="no_telp" type="text" class="form-control" id="no_telp" placeholder="Masukkan No.Telepon Superadmin" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input name="email" type="email" class="form-control" id="email" placeholder="Masukkan Email Superadmin" required>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="footer">
                ©2024 Jurusan Teknologi Informasi
            </div>
    </div>
            <script>
                // Unuk Search Mahasiswa
                document.getElementById('searchInput').addEventListener('keyup', function() {
                    const searchValue = this.value.toLowerCase();
                    const table = document.getElementById('dataTable');
                    const rows = table.getElementsByTagName('tr');

                    for (let i = 1; i < rows.length; i++) { // Mulai dari 1 karena baris pertama adalah header
                        const cells = rows[i].getElementsByTagName('td');
                        let match = false;

                        for (let j = 0; j < cells.length; j++) {
                            if (cells[j].innerText.toLowerCase().includes(searchValue)) {
                                match = true;
                                break;
                            }
                        }

                        rows[i].style.display = match ? '' : 'none';
                    }
                });

                // Unuk Search Verifikator

                document.getElementById('searchInputVerifikator').addEventListener('keyup', function() {
                    const searchValue = this.value.toLowerCase();
                    const table = document.getElementById('dataTableVerifikator');
                    const rows = table.getElementsByTagName('tr');

                    for (let i = 1; i < rows.length; i++) { // Mulai dari 1 karena baris pertama adalah header
                        const cells = rows[i].getElementsByTagName('td');
                        let match = false;

                        for (let j = 0; j < cells.length; j++) {
                            if (cells[j].innerText.toLowerCase().includes(searchValue)) {
                                match = true;
                                break;
                            }
                        }

                        rows[i].style.display = match ? '' : 'none';
                    }
                });

                // Untuk search Admin
                document.getElementById('searchInputAdmin').addEventListener('keyup', function() {
                    const searchValue = this.value.toLowerCase();
                    const table = document.getElementById('dataTableAdmin');
                    const rows = table.getElementsByTagName('tr');

                    for (let i = 1; i < rows.length; i++) { // Mulai dari 1 karena baris pertama adalah header
                        const cells = rows[i].getElementsByTagName('td');
                        let match = false;

                        for (let j = 0; j < cells.length; j++) {
                            if (cells[j].innerText.toLowerCase().includes(searchValue)) {
                                match = true;
                                break;
                            }
                        }

                        rows[i].style.display = match ? '' : 'none';
                    }
                });

                // 



                window.onload = () => {
                    changeTab('<?php echo htmlspecialchars($selected_tab, ENT_QUOTES, 'UTF-8'); ?>');
                };
                document.querySelector('.toggle-sidebar i').addEventListener('click', function() {
            var sidebar = document.querySelector('.sidebar');
            var content = document.querySelector('.content');
            var footer = document.querySelector('.footer');

            // Toggle a class to handle the sidebar visibility
            sidebar.classList.toggle('hidden');

            if (sidebar.classList.contains('hidden')) {
                content.style.marginLeft = '0';
                footer.style.width = '100%';
                footer.style.left = '0';
            } else {
                content.style.marginLeft = sidebar.offsetWidth + 'px';
                // content.style.marginLeft = '250px';
                footer.style.width = 'calc(100% - 250px)';
                footer.style.left = '250px';
            }
        });

                function changeTab(tabName) {
                    // Menyembunyikan semua konten tab
                    const contents = document.querySelectorAll('.tab-content');
                    contents.forEach(content => {
                        content.classList.remove('active-content');
                    });

                    // Menghapus kelas aktif dari semua tombol tab
                    const tabs = document.querySelectorAll('.tab');
                    tabs.forEach(tab => {
                        tab.classList.remove('active');
                    });

                    // Menambahkan kelas aktif ke tab yang dipilih
                    const activeTab = document.querySelector(`.${tabName}-content`);
                    activeTab.classList.add('active-content');

                    const activeButton = document.querySelector(`button[onclick="changeTab('${tabName}')"]`);
                    activeButton.classList.add('active');
                }
            </script>
            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>

</html>