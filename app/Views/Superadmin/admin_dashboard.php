<?php
 //session_start();

// Periksa apakah user sudah login
 if (!isset($_SESSION['username']) || $_SESSION['role_user'] !== 'superadmin') {
    header("Location: ../app/Views/login.php");
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
            font-weight: 700; 
        }
        .header .title {
            font-size: 14px;
            font-weight: 700; 
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
        }
        .header .user-info .name div:last-child {
            font-weight: 400; 
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
            transition: margin-left 0.3s ease-in-out;
            padding-top: 70px;
        }
        .content .welcome {
            background-color: #1E1E1E;
            color: #fff;
            padding: 20px;
            border-radius: 0px 0px 20px 20px;
            margin-bottom: 20px;
            height: 189px;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .content .welcome h1 {
            margin-left: 30px;
            font-size: 40px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
        }
        .content .welcome p {
            margin-left: 30px;
            font-size: 16px;
            font-style: medium;
        }
        .content .cards {
            display: flex;
            flex-wrap: wrap; 
            gap: 22px;
            margin: 0 10px;
            
        }
        .content .card {
            background-color: #FFAF01;
            flex: 1 1 calc(33.33% - 22px); 
            min-width: 200px; 
            height: auto;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 4px 20px 0px rgba(0, 0, 0, 0.25);
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card h3, .card h2, .card p {
            margin: 0;
            padding: 5px;
        }

        .bg-warning {
            background-color: #FFC107 !important; /* Warna kuning cerah */
        }

        .text-white {
            color: #fff !important;
        }
        .content .card h2 {
            margin: 0;
            font-size: 36px;
        }
        .content .card p {
            margin: 10px 0 0;
            font-size: 16px;
        }
        .summary {
            margin: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px 0px rgba(0, 0, 0, 0.25);
        }
        .table-responsive {
            overflow-x: auto; 
            -webkit-overflow-scrolling: touch; 
        }
        .summary h3 {
            margin-bottom: 15px;
            font-size: 20px;
            font-weight: 700;
            color: #333;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }
        .summary-table th,
        .summary-table td {
            padding: 10px;
            text-align: left;
            white-space: nowrap; 
        }
        .summary-table th {
            background-color: #f7f7f7;
            font-weight: 700;
            text-transform: uppercase;
            color: #555;
        }
        .summary-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary-table .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            color: #fff;
            display: inline-block;
            text-align: center;
            min-width: 150px; 
            white-space: nowrap; 
        }
        .summary-table .status-approved {
            background-color: #28a745;
        }
        .summary-table .status-rejected {
            background-color: #dc3545;
        }
        .summary-table .status-waited {
            background-color: #FFAF01;
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
            .card {
                margin: 10px 0;
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
   <a class="menu-item active" href="index.php?controller=superAdmin&action=dashboard">
    <i class="bi bi-house"></i>
    Beranda
   </a>
   <a class="menu-item" href="index.php?controller=superAdmin&action=manageUser">
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
            <h1>
            <h1>Selamat Datang, <?= htmlspecialchars($username); ?></h1>
            </h1>
            <p>
            Anda berada di halaman admin
            </p>
        </div>
        <div class="container">
    <div class="row text-center">
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card bg-warning text-white p-3">
                <h3>Data Mahasiswa</h3>
                <h2><?= htmlspecialchars($mahasiswaCount ?? 0); ?></h2>
                <p>Data Mahasiswa Saat Ini</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card bg-warning text-white p-3">
                <h3>Data Verifikator</h3>
                <h2><?= htmlspecialchars($verifikatorCount ?? 0); ?></h2>
                <p>Data Verifikator Saat Ini</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card bg-warning text-white p-3">
                <h3>Data Admin</h3>
                <h2><?= htmlspecialchars($adminCount ?? 0); ?></h2>
                <p>Data Admin Saat Ini</p>
            </div>
        </div>
    </div>
</div>

<div class="summary">
    <h3>Ringkasan Manajemen Dokumen</h3>
    <div class="table-responsive">
        <table class="table summary-table">
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>Tanggal Upload</th>
                    <th>Status Verifikasi</th>
                    <th>Nama Dokumen</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($documents)): ?>
                    <?php foreach ($documents as $doc): ?>
                        <tr>
                            <td><?= htmlspecialchars($doc['nama_mahasiswa']); ?></td>
                            <td><?= htmlspecialchars($doc['tgl_upload']); ?></td>
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
                            <td><?= htmlspecialchars($doc['nama_dokumen']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">Tidak ada data dokumen</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

  <div class="footer">
   ©2024 Jurusan Teknologi Informasi
  </div>
  <script>
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
                // content.style.marginLeft = '300px';
                footer.style.width = 'calc(100% - 300px)';
                footer.style.left = '300px';
            }
        });
  </script>
 </body>
</html>