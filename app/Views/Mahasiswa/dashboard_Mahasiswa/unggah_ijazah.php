<?php
// Array untuk menyimpan data file yang sudah diunggah
$filePaths = [];
foreach ($dataIjazah as $file) {
    $filePaths[$file['id_dokumen']] = $file['path'];
}
?> 
 
 <!-- Unggah Berkas Ijazah -->
    <div class="tab-content" id="unggah-ijazah">
        <div class="welcome">
            <h1>Unggah Berkas Ijazah</h1>
        </div>

        <div class="cards mx-1 d-flex flex-column mb-5">
            <div class="card text-start ">
                <form id="uploadForm2" action="upload_ijazah.php" method="POST" enctype="multipart/form-data">
                <div id="messageArea2"></div>
                    <p class="fw-bold">Berkas Pengajuan Ijazah</p>
                    <hr>
                    <div class="form-group mb-3">
                        <label for="berkas2" class="fw-bold">Surat Bebas Tanggungan Jurusan:</label>
                        <div class="d-flex align-items-center">
                        <?php if (!empty($filePaths[8])): ?>
                                    <!-- Tampilkan nama file dan tombol untuk melihat -->
                                    <span class="me-2"><?= basename($filePaths[8]) ?></span>
                                    <a href="javascript:void(0);" class="btn btn-info me-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#filePreviewModal" 
                                        onclick="showPreview('../app/Views/mahasiswa/serve_file.php?file=<?= urlencode($filePaths[8]) ?>')">
                                    <i class="bi bi-eye"></i></a>

                                    <input type="file" name="dokumen_file[]" id="file8" class="form-control">
                                <?php else: ?>
                                    <!-- Input file jika belum diunggah -->
                                    <input type="file" name="dokumen_file[]" id="file8" class="form-control" required>
                                <?php endif; ?>
                        </div>
                        <label class="form-text">Format PDF, Maksimal 10 MB</label>
                        <input type="hidden" name="id_dokumen[]" value="8">
                    </div>
                    <div class="form-group mb-3">
                        <label for="berkas2" class="fw-bold">Surat Bebas Tanggungan Akademik Pusat:</label>
                        <div class="d-flex align-items-center">
                        <?php if (!empty($filePaths[9])): ?>
                                    <!-- Tampilkan nama file dan tombol untuk melihat -->
                                    <span class="me-2"><?= basename($filePaths[9]) ?></span>
                                    <a href="javascript:void(0);" class="btn btn-info me-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#filePreviewModal" 
                                        onclick="showPreview('../app/Views/mahasiswa/serve_file.php?file=<?= urlencode($filePaths[9]) ?>')">
                                    <i class="bi bi-eye"></i></a>

                                    <input type="file" name="dokumen_file[]" id="file9" class="form-control">
                                <?php else: ?>
                                    <!-- Input file jika belum diunggah -->
                                    <input type="file" name="dokumen_file[]" id="file9" class="form-control" required>
                                <?php endif; ?>
                            <a href="https://drive.google.com/file/d/1G6yal9YBha4vDtP61sndFhKiErkzNBTo/view?usp=sharing" class="btn btn-primary" target="_blank">Panduan</a>
                        </div>
                        <label class="form-text">Format PDF, Maksimal 10 MB</label>
                        <input type="hidden" name="id_dokumen[]" value="9">

                    </div>
                    <div class="form-group mb-3">
                        <label for="berkas2" class="fw-bold">Surat Bebas Pustaka Perpustakaan Polinema:</label>
                        <div class="d-flex align-items-center">
                        <?php if (!empty($filePaths[10])): ?>
                                    <!-- Tampilkan nama file dan tombol untuk melihat -->
                                    <span class="me-2"><?= basename($filePaths[10]) ?></span>
                                    <a href="javascript:void(0);" class="btn btn-info me-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#filePreviewModal" 
                                        onclick="showPreview('../app/Views/mahasiswa/serve_file.php?file=<?= urlencode($filePaths[10]) ?>')">
                                    <i class="bi bi-eye"></i></a>

                                    <input type="file" name="dokumen_file[]" id="file10" class="form-control">
                                <?php else: ?>
                                    <!-- Input file jika belum diunggah -->
                                    <input type="file" name="dokumen_file[]" id="file10" class="form-control" required>
                                <?php endif; ?>
                            <a href=" https://drive.google.com/file/d/1IjdvqKnPWmolQPGs5ltfMXM7jj02uhuV/view?usp=sharing" class="btn btn-primary" target="_blank">Panduan</a>
                        </div>
                        <label class="form-text">Format PDF, Maksimal 10 MB</label>
                        <input type="hidden" name="id_dokumen[]" value="10">
                    </div>
                    <div class="form-group mb-3">
                        <label for="berkas2" class="fw-bold">Surat Kebenaran Data Diri:</label>
                        <div class="d-flex align-items-center">
                        <?php if (!empty($filePaths[11])): ?>
                                    <!-- Tampilkan nama file dan tombol untuk melihat -->
                                    <span class="me-2"><?= basename($filePaths[11]) ?></span>
                                    <a href="javascript:void(0);" class="btn btn-info me-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#filePreviewModal" 
                                        onclick="showPreview('../app/Views/mahasiswa/serve_file.php?file=<?= urlencode($filePaths[11]) ?>')">
                                    <i class="bi bi-eye"></i></a>

                                    <input type="file" name="dokumen_file[]" id="file11" class="form-control">

                                <?php else: ?>
                                    <!-- Input file jika belum diunggah -->
                                    <input type="file" name="dokumen_file[]" id="file11" class="form-control" required>

                                <?php endif; ?>
                            <a href="https://siakad.polinema.ac.id/" class="btn btn-primary" target="_blank">SIAKAD</a>
                        </div>
                        <label class="form-text">Format PDF, Maksimal 10 MB</label>
                        <input type="hidden" name="id_dokumen[]" value="11">

                    </div>
                    <div class="form-group mb-3">
                        <label for="berkas2" class="fw-bold">Bukti Pengisian Kuisioner Kantor Jaminan Mutu:</label>
                        <div class="d-flex align-items-center">
                        <?php if (!empty($filePaths[12])): ?>
                                    <!-- Tampilkan nama file dan tombol untuk melihat -->
                                    <span class="me-2"><?= basename($filePaths[12]) ?></span>
                                    <a href="javascript:void(0);" class="btn btn-info me-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#filePreviewModal" 
                                        onclick="showPreview('../app/Views/mahasiswa/serve_file.php?file=<?= urlencode($filePaths[12]) ?>')">
                                    <i class="bi bi-eye"></i></a>

                                    <input type="file" name="dokumen_file[]" id="file12" class="form-control">
                                <?php else: ?>
                                    <!-- Input file jika belum diunggah -->
                                    <input type="file" name="dokumen_file[]" id="file12" class="form-control" required>
                                <?php endif; ?>
                            <a href="https://wa.link/ujcmlw" class="btn btn-primary" target="_blank">Kontak</a>
                        </div>
                        <label class="form-text">Format PDF, Maksimal 10 MB</label>
                        <input type="hidden" name="id_dokumen[]" value="12">

                    </div>
                    <div class="form-group mb-3">
                        <label for="berkas2" class="fw-bold">Bukti Pengisian SKPI:</label>
                        <div class="d-flex align-items-center">
                        <?php if (!empty($filePaths[13])): ?>
                                    <!-- Tampilkan nama file dan tombol untuk melihat -->
                                    <span class="me-2"><?= basename($filePaths[13]) ?></span>
                                    <a href="javascript:void(0);" class="btn btn-info me-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#filePreviewModal" 
                                        onclick="showPreview('../app/Views/mahasiswa/serve_file.php?file=<?= urlencode($filePaths[13]) ?>')">
                                    <i class="bi bi-eye"></i></a>

                                    <input type="file" name="dokumen_file[]" id="file13" class="form-control">
                                <?php else: ?>
                                    <!-- Input file jika belum diunggah -->
                                    <input type="file" name="dokumen_file[]" id="file13" class="form-control" required>
                                <?php endif; ?>
                        </div>
                        <label class="form-text">Format PDF, Maksimal 10 MB</label>
                        <input type="hidden" name="id_dokumen[]" value="13">
                    </div>
                    <br>


                    <button type="submit" class="btn btn-success mt-3"><i class="bi bi-floppy2 me-2"></i>Simpan</button>
                </form>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#uploadForm2'). submit(function(event) {
        event.preventDefault();

        var formData = new FormData(this); 

        $.ajax({
            url: '../app/Views/Mahasiswa/upload_ijazah.php', 
            type: 'POST',
            data: formData,
            contentType: false, 
            processData: false, 
            success: function(response) {
                console.log("Raw response from server:", response);
                var result = JSON.parse(response);
                var messageArea = $('#messageArea2');
                messageArea.empty();

                if (result.status === 'success') {
                    result.messages.forEach(function(message) {
                        messageArea.append('<div class="alert alert-success">' + message + '</div>');
                    });
                } else {
                    result.messages.forEach(function(message) {
                        messageArea.append('<div class="alert alert-danger">' + message + '</div>');
                    });
                }
            },
            error: function() {
                $('#messageArea2').html('<div class="alert alert-danger">An error occurred while uploading the files.</div>');
                console.log("Error details:", textStatus, errorThrown);
            }
        });
    });
});
</script>

<!-- Modal untuk Preview File -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filePreviewModalLabel">Preview Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Iframe untuk menampilkan file -->
                <iframe id="filePreviewFrame" src="" frameborder="0" style="width: 100%; height: 500px;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    function showPreview(fileUrl) {
        var filePreviewFrame = document.getElementById('filePreviewFrame');
        filePreviewFrame.src = fileUrl; // Set URL file ke iframe
    }
</script>

