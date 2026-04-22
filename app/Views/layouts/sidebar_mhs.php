<link rel="stylesheet" href="../public/css/sidebar.css">

<div class="sidebar" id="sidebar">
        <div>
            <a href="#beranda" class="menu-item active" onclick="showTab('beranda')"><i class="bi bi-house"></i> Beranda</a>
            <a class="btn btn-toggle align-items-center menu-item" data-bs-toggle="collapse" data-bs-target="#menu1" aria-expanded="false" onclick="toggleIcon(this)">
                <i class="bi bi-cloud-upload"></i> 
                Unggah Berkas 
                <i class="bi bi-chevron-right rotate-icon me-0 ms-auto"></i>
              </a>

              <div class="collapse" id="menu1">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                  <li><a href="#unggah-jurusan" class="menu-item dropdown" onclick="showTab('unggah-jurusan')">Jurusan</a></li>
                  <li><a href="#unggah-ijazah" class="menu-item dropdown" onclick="showTab('unggah-ijazah')">Ijazah</a></li>
                </ul>
              </div>
    
            <a href="#status" class="menu-item" onclick="showTab('status')"><i class="bi bi-patch-check"></i> Status Tanggungan</a>
            <a href="#ijazah" class="menu-item" onclick="showTab('ijazah')"><i class="bi bi-book"></i> Pengajuan Ijazah</a>
            <a href="#panduan" class="menu-item" onclick="showTab('panduan')"><i class="bi bi-info-circle"></i> Panduan &amp; Kontak</a>
        </div>
        <div>
            <!-- <a href="#" class="menu-item logout"><i class="bi bi-power"></i> Keluar</a> -->
            <a class="menu-item logout" href="../app/Views/logout.php">
              <i class="bi bi-power"></i> Keluar
            </a>
        </div>
    </div>
