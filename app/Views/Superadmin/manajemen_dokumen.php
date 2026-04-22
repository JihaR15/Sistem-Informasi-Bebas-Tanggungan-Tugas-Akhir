<?php
//session_start();

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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet"/>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <style>
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
            font-weight: 700; /* Inter Bold */
        }
        .header .title {
            font-size: 14px;
            font-weight: 700; /* Inter Bold */
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
            font-weight: 700; /* Inter Bold */
        }
        .header .user-info .name div:last-child {
            font-weight: 400; /* Inter Regular */
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
        .sidebar .menu-item:hover, .sidebar .menu-item.active {
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
            background-color: #1E1E1E;;
            color: #fff;
            padding: 20px;
            border-radius: 0px 0px 20px 20px;;
            margin-bottom: 20px;
            height: 110px;
            width: 100%;
        }
        .content .welcome h1 {
            margin: 10px;
            font-size: 30px;
            font-style: bold;
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
        .user-table .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            color: #fff;
            display: inline-block;
            text-align: center;
            min-width: 150px; 
            white-space: nowrap; 
        }
        .user-table .status-approved {
            background-color: #28a745;
        }
        .user-table .status-rejected {
            background-color: #dc3545;    
        }
        .user-table .status-waited {
            background-color: #FFAF01;
        }
        .modal-header {
            font-weight: bold;
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
            width: calc(100% - 250px);
            left: 250px;
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
   <a class="menu-item" href="index.php?controller=superAdmin&action=manageUser">
    <i class="fas fa-users"></i>
    </i>
    Manajemen Pengguna
   </a>
   <a class="menu-item active" href="index.php?controller=superAdmin&action=manageDocument">
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
            <h1>Manajemen Dokumen</h1>
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

                <div class="table-container">
                    <div class="table-header">
                        <div class="row d-flex justify-content-between align-items-center">
                            <!-- Judul dan tombol Tambah -->
                            <h2 class="mb-2 col-md-9">Data Riwayat Verifikasi</h2>
                            <div class="col-md-3 d-flex justify-content-end">
                                
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
                                <th>Nama Mahasiswa</th>
                                <th>Tanggal Upload</th>
                                <th>Status Verifikasi</th>
                                <th>Nama Dokumen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <?php foreach ($documents as $doc): ?>
                            <tr>
                                <td><?= $doc['nama_mahasiswa'] ?></td>
                                <td><?= $doc['tgl_upload'] ?></td>
                                <td>
                                    <span class="status 
                                        <?php 
                                            if ($doc['status_verifikasi'] === 'Belum Diunggah' || $doc['status_verifikasi'] === 'Tidak Disetujui') {
                                            echo 'status-rejected';
                                            } elseif ($doc['status_verifikasi'] === 'Sudah Diunggah' || $doc['status_verifikasi'] === 'Disetujui') {
                                            echo 'status-approved';
                                            } elseif ($doc['status_verifikasi'] === 'Menunggu DIverifikasi' || $doc['status_verifikasi'] === 'Menunggu Diverifikasi') {
                                            echo 'status-waited';
                                            }
                                        ?>">
                                        <?= htmlspecialchars($doc['status_verifikasi']); ?>
                                    </span>
                                    </td>
                                <td><?= $doc['nama_dokumen'] ?></td>
                                <td>
                                    <button class="btn btn-delete"
                                        data-toggle="modal"
                                        data-target="#modalHapus<?= $doc['id_verifikasi'] ?>">
                                        Hapus
                                    </button>
                                    <!-- Modal Hapus -->
                                    <div class="modal fade modal-hapus" id="modalHapus<?= $doc['id_verifikasi'] ?>" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalHapusLabel">Hapus Data Riwayat Verifikasi</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus data riwayat verifikasi ini?</p>
                                                    <p><strong>Id Verifikasi:</strong> <span id="hapusId_Verifikasi"><?= $doc['id_verifikasi'] ?></span></p>
                                                    <p><strong>Nama:</strong> <span id="hapusNama"><?= $doc['nama_mahasiswa'] ?></span></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <!-- Pastikan URL ini memuat parameter nim -->
                                                    <a href="index.php?controller=superAdmin&action=deleteVerifikasi&id_verifikasi=<?= urlencode($doc['id_verifikasi']) ?>" id="confirmHapus" class="btn btn-danger">Hapus</a>
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
                
  <div class="footer">
   ©2024 Jurusan Teknologi Informasi
  </div>
  
  <script>

   // Unuk Search Riwayat Dokumen
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
  </script>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
 </body>
</html>