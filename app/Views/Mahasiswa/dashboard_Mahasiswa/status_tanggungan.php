<?php
// Cek apakah semua dokumen sudah diverifikasi
$allVerified = true;
foreach ($statusJurusan as $row) {
    if ($row['status_verifikasi'] !== 'Disetujui') {
        $allVerified = false;
        break;
    }
}
?>

<!-- Status tanggungan -->
<div id="status" class="tab-content mb-5">
    <div class="welcome">
        <h1>Status Tanggungan</h1>
    </div>
    <div class="cards">
        <div class="card">
            <div class="h3 text-start">Tabel Riwayat Tanggungan</div>
            <hr>
            <!-- Alert Status -->
            <div id="alertStatus" class="alert mt-3 fw-bold" style="display: none;" role="alert">
    <!-- Pesan alert akan diatur menggunakan JavaScript -->
            </div>
            <table class="table mt-2 text-start table-borderless table-striped table-hover">
                <thead>
                    <tr class="table-secondary">
                        <th scope="col">Nama Berkas</th>
                        <th scope="col">Tanggal Unggah</th>
                        <th scope="col">Status</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($statusJurusan as $row): ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($row['nama_dokumen']) ?></th>
                            <td><?= $row['tgl_upload'] ? date('d-m-Y', strtotime($row['tgl_upload'])) : '-' ?></td>
                            <td>
                                <?php if ($row['status_verifikasi'] === 'Disetujui'): ?>
                                    <button class="btn btn-success" disabled>Sudah Diverifikasi</button>
                                <?php elseif ($row['status_verifikasi'] === 'Menunggu Diverifikasi'): ?>
                                    <button class="btn btn-warning" disabled>Menunggu</button>
                                <?php elseif ($row['status_verifikasi'] === 'Tidak Disetujui'): ?>
                                    <button class="btn btn-danger" disabled>Gagal</button>
                                    <!-- <p class="text-danger mt-2">
                                        <?= htmlspecialchars($row['catatan'] ?? 'Tidak ada catatan.') ?>
                                    </p> -->
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled>Belum Diunggah</button>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Tombol Lihat Catatan -->
                                <?php if ($row['status_verifikasi'] === 'Tidak Disetujui'): ?>
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#catatanModal-<?= str_replace(' ', '-', $row['catatan']) ?>"><i class="bi bi-pencil"></i></button>
                                <?php endif; ?>
                                <!-- Tombol Upload Ulang -->
                                <?php if ($row['status_verifikasi'] === 'Tidak Disetujui' || $row['status_verifikasi'] === 'Menunggu Diverifikasi'): ?>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal-<?= $row['id_dokumen'] ?>"><i class="bi bi-upload"></i></button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
    <p class="mt-3">Pastikan semua berkas Anda telah diverifikasi sebelum mengajukan Surat Bebas Tanggungan.</p>
</div>

<script>
    function checkDocuments() {
        var allVerified = <?= json_encode($allVerified) ?>; // Mengambil status dokumen dari PHP

        var alertDiv = document.getElementById('alertStatus'); // Elemen alert
        if (allVerified) {
            // Jika semua dokumen sudah diverifikasi
            alertDiv.className = "alert alert-success"; // Set kelas alert sukses
            alertDiv.innerHTML = "Anda dapat mengambil Surat Bebas Tanggungan Jurusan di admin LT6."; // Set pesan
        } else {
            // Jika dokumen belum lengkap atau belum diverifikasi
            alertDiv.className = "alert alert-warning"; // Set kelas alert peringatan
            alertDiv.innerHTML = "Tunggu Berkas anda disetujui untuk mengambil Surat Bebas Tanggungan Jurusan."; // Set pesan
        }
        alertDiv.style.display = "block"; // Tampilkan alert
    }

    // Panggil fungsi checkDocuments saat halaman dimuat
    document.addEventListener("DOMContentLoaded", function() {
        checkDocuments();
    });
</script>

<!-- Modal Catatan -->
<?php foreach ($statusJurusan as $row): ?>
    <!-- Modal Catatan -->
    <div class="modal fade" id="catatanModal-<?= str_replace(' ', '-', $row['catatan']) ?>" tabindex="-1" aria-labelledby="catatanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="catatanModalLabel">Catatan Verifikator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><?= htmlspecialchars($row['catatan'] ?? 'Tidak ada catatan.') ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php foreach ($statusJurusan as $row): ?>
    <!-- Modal Upload Ulang -->
    <div class="modal fade" id="uploadModal-<?= $row['id_dokumen'] ?>" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="uploadForm-<?= $row['id_dokumen'] ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Upload Ulang Dokumen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Silakan unggah ulang dokumen untuk: <strong><?= htmlspecialchars($row['nama_dokumen']) ?></strong></p>
                        <input type="file" name="dokumen_file" class="form-control" required>
                        <input type="hidden" name="id_dokumen" value="<?= $row['id_dokumen'] ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" onclick="uploadFile(<?= $row['id_dokumen'] ?>)">Upload</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function uploadFile(id_dokumen) {
        var formData = new FormData($('#uploadForm-' + id_dokumen)[0]);

        $.ajax({
            url: '../app/Views/mahasiswa/upload_ulang.php',  
            type: 'POST',
            data: formData,
            processData: false,  // Jangan memproses data form menjadi query string
            contentType: false,  // Jangan set contentType karena FormData sudah menangani itu
            success: function(response) {
                // Jika upload berhasil
                var result = JSON.parse(response);  // Mengambil hasil response JSON
                if (result.success) {
                    alert('Upload berhasil!');
                    $('#uploadModal-' + id_dokumen).modal('hide'); // Menutup modal
                    location.reload(); // Reload halaman untuk update status (optional)
                } else {
                    alert('Gagal mengunggah file: ' + result.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Terjadi kesalahan: ' + error);
            }
        });
    }
</script>

