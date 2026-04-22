<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Model.php';

class VerifikatorModel extends Model {

    private $conn;

    public function __construct($dbConnection)
    {
        Model::__construct($dbConnection);
        $this->conn = $dbConnection;
    }

    public function getBelumDiverifikasiCount($jenisDokumen, $tahun) {
        $stmt = $this->conn->prepare ("
            SELECT COUNT(*) AS Terverifikasi
            FROM Verifikasi v
            JOIN Mahasiswa m ON v.nim = m.nim
            JOIN Angkatan a ON m.id_angkatan = a.id_angkatan
            JOIN Dokumen d ON v.id_dokumen = d.id_dokumen
            WHERE v.status_verifikasi = 'Menunggu Diverifikasi'
            AND a.role_angkatan = :tahun
            AND d.jenis_dokumen = :jenisDokumen  
        ");
        $stmt->bindParam(':jenisDokumen', $jenisDokumen);
        $stmt->bindParam(':tahun', $tahun);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['Terverifikasi'];
    }

    public function getTerverifikasiCount($jenisDokumen, $tahun) {
        $stmt = $this->conn->prepare ("
            SELECT COUNT(*) AS Terverifikasi
            FROM Verifikasi v
            JOIN Mahasiswa m ON v.nim = m.nim
            JOIN Angkatan a ON m.id_angkatan = a.id_angkatan
            JOIN Dokumen d ON v.id_dokumen = d.id_dokumen
            WHERE v.status_verifikasi = 'Disetujui'
            AND a.role_angkatan = :tahun
            AND d.jenis_dokumen = :jenisDokumen  
        ");
        $stmt->bindParam(':jenisDokumen', $jenisDokumen);
        $stmt->bindParam(':tahun', $tahun);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['Terverifikasi'];
    }

    public function getMahasiswaCount($tahun) {
        $stmt = $this->conn->prepare ("
            SELECT COUNT(*) AS jumlah_mahasiswa
            FROM `User` u
            JOIN Mahasiswa m ON u.id_user = m.id_user
            JOIN Angkatan a ON m.id_angkatan = a.id_angkatan
            WHERE u.role_user = 'mahasiswa'
            AND a.role_angkatan = :tahun
        ");
        
        $stmt->bindParam(':tahun', $tahun);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['jumlah_mahasiswa'];
    }
    
    // --- QUERY DIREVISI: TANPA VIEW, LANGSUNG JOIN TABEL ASLI ---
    public function getMhsWithDocumentCompleteJurusan() {
        $stmt = $this->conn->prepare("
            SELECT
                m.nim,
                u.nama,
                u.no_telp,
                p.role_prodi,
                j.role_jurusan,
                a.role_angkatan,
                m.kelas, 
                MAX(v.tgl_upload) as tgl_upload,
                COUNT(v.id_verifikasi) AS verifikasi_count,
                SUM(CASE WHEN v.status_verifikasi = 'Disetujui' THEN 1 ELSE 0 END) AS disetujui_count
            FROM Mahasiswa m
            JOIN `User` u ON m.id_user = u.id_user
            JOIN Prodi p ON m.id_prodi = p.id_prodi
            JOIN Jurusan j ON m.id_jurusan = j.id_jurusan
            JOIN Angkatan a ON m.id_angkatan = a.id_angkatan
            JOIN Verifikasi v ON m.nim = v.nim
            JOIN Dokumen d ON v.id_dokumen = d.id_dokumen
            WHERE d.jenis_dokumen = 'Jurusan'
            AND v.status_verifikasi IN ('Menunggu Diverifikasi', 'Tidak Disetujui', 'Disetujui')
            GROUP BY
                m.nim, 
                u.nama, 
                u.no_telp, 
                p.role_prodi, 
                j.role_jurusan, 
                a.role_angkatan, 
                m.kelas
            HAVING 
                SUM(CASE WHEN v.status_verifikasi = 'Disetujui' THEN 1 ELSE 0 END) < 7
            ORDER BY
                tgl_upload ASC;
        ");
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- QUERY DIREVISI: TANPA VIEW, LANGSUNG JOIN TABEL ASLI ---
    public function getMhsWithDocumentCompletePusat() {
        $stmt = $this->conn->prepare("
            SELECT
                m.nim,
                u.nama,
                u.no_telp,
                p.role_prodi,
                j.role_jurusan,
                a.role_angkatan,
                m.kelas, 
                MAX(v.tgl_upload) as tgl_upload,
                COUNT(v.id_verifikasi) AS verifikasi_count,
                SUM(CASE WHEN v.status_verifikasi = 'Disetujui' THEN 1 ELSE 0 END) AS disetujui_count
            FROM Mahasiswa m
            JOIN `User` u ON m.id_user = u.id_user
            JOIN Prodi p ON m.id_prodi = p.id_prodi
            JOIN Jurusan j ON m.id_jurusan = j.id_jurusan
            JOIN Angkatan a ON m.id_angkatan = a.id_angkatan
            JOIN Verifikasi v ON m.nim = v.nim
            JOIN Dokumen d ON v.id_dokumen = d.id_dokumen
            WHERE d.jenis_dokumen = 'Pusat'
            AND v.status_verifikasi IN ('Menunggu Diverifikasi', 'Tidak Disetujui', 'Disetujui')
            GROUP BY
                m.nim, 
                u.nama, 
                u.no_telp, 
                p.role_prodi, 
                j.role_jurusan, 
                a.role_angkatan, 
                m.kelas
            HAVING 
                SUM(CASE WHEN v.status_verifikasi = 'Disetujui' THEN 1 ELSE 0 END) < 6
            ORDER BY
                tgl_upload ASC;
        ");
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDocument($jenisDokumen, $nim) {
        $stmt = $this->conn->prepare("
            SELECT v.path, d.nama_dokumen, d.id_dokumen, v.nim, v.status_verifikasi
            FROM Verifikasi v
            JOIN Mahasiswa m ON v.nim = m.nim
            JOIN Dokumen d ON v.id_dokumen = d.id_dokumen
            WHERE d.jenis_dokumen = :jenisDokumen
            AND m.nim = :nim
            ORDER BY d.id_dokumen ASC;
        ");
    
        $stmt->bindParam(':jenisDokumen', $jenisDokumen);
        $stmt->bindParam(':nim', $nim);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateStatusVerifikasiTidakDisetujui($id_dokumen, $nim, $catatan) {
        $stmt = $this->conn->prepare("
            UPDATE Verifikasi
            SET status_verifikasi = 'Tidak Disetujui',
                catatan = :catatan
            WHERE id_dokumen = :idDokumen
            AND nim = :nim
        ");
        
        $stmt->bindParam(':idDokumen', $id_dokumen, PDO::PARAM_INT);
        $stmt->bindParam(':nim', $nim, PDO::PARAM_INT);
        $stmt->bindParam(':catatan', $catatan, PDO::PARAM_STR);
    
        if ($stmt->execute()) {
            return $stmt->rowCount();
        }
        return false;
    }
    
    public function updateStatusVerifikasiDisetujui($id_dokumen, $nim, $status, $catatan) {
        $sql = "
            UPDATE Verifikasi
            SET status_verifikasi = :status, 
                catatan = :catatan
            WHERE id_dokumen = :idDokumen
            AND nim = :nim
        ";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':idDokumen', $id_dokumen, PDO::PARAM_INT);
            $stmt->bindParam(':nim', $nim, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            if ($catatan === null) {
                $stmt->bindValue(':catatan', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':catatan', $catatan);
            }
    
            if ($stmt->execute()) {
                return $stmt->rowCount();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // --- QUERY DIREVISI: TANPA VIEW, LANGSUNG JOIN TABEL ASLI ---
    public function getMhsWithDocumentApprovedPusat($jenisDokumen) {
        $stmt = $this->conn->prepare("
            SELECT
                m.nim,
                u.nama,
                u.no_telp,
                p.role_prodi,
                j.role_jurusan,
                a.role_angkatan,
                m.kelas, 
                MAX(v.tgl_upload) as tgl_upload,
                COUNT(v.id_verifikasi) AS verifikasi_count
            FROM Mahasiswa m
            JOIN `User` u ON m.id_user = u.id_user
            JOIN Prodi p ON m.id_prodi = p.id_prodi
            JOIN Jurusan j ON m.id_jurusan = j.id_jurusan
            JOIN Angkatan a ON m.id_angkatan = a.id_angkatan
            JOIN Verifikasi v ON m.nim = v.nim
            JOIN Dokumen d ON v.id_dokumen = d.id_dokumen
            WHERE d.jenis_dokumen = :jenisDokumen
              AND v.status_verifikasi = 'Disetujui'
            GROUP BY 
                m.nim, 
                u.nama, 
                u.no_telp, 
                p.role_prodi, 
                j.role_jurusan, 
                a.role_angkatan, 
                m.kelas
            HAVING COUNT(v.id_verifikasi) = 6
            ORDER BY
                tgl_upload ASC;
        ");
    
        $stmt->bindParam(':jenisDokumen', $jenisDokumen);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // --- QUERY DIREVISI: TANPA VIEW, LANGSUNG JOIN TABEL ASLI ---
    public function getMhsWithDocumentApprovedJurusan($jenisDokumen) {
        $stmt = $this->conn->prepare("
            SELECT
                m.nim,
                u.nama,
                u.no_telp,
                p.role_prodi,
                j.role_jurusan,
                a.role_angkatan,
                m.kelas, 
                MAX(v.tgl_upload) as tgl_upload,
                COUNT(v.id_verifikasi) AS verifikasi_count
            FROM Mahasiswa m
            JOIN `User` u ON m.id_user = u.id_user
            JOIN Prodi p ON m.id_prodi = p.id_prodi
            JOIN Jurusan j ON m.id_jurusan = j.id_jurusan
            JOIN Angkatan a ON m.id_angkatan = a.id_angkatan
            JOIN Verifikasi v ON m.nim = v.nim
            JOIN Dokumen d ON v.id_dokumen = d.id_dokumen
            WHERE d.jenis_dokumen = :jenisDokumen
              AND v.status_verifikasi = 'Disetujui'
            GROUP BY 
                m.nim, 
                u.nama, 
                u.no_telp, 
                p.role_prodi, 
                j.role_jurusan, 
                a.role_angkatan, 
                m.kelas
            HAVING COUNT(v.id_verifikasi) = 7
            ORDER BY
                tgl_upload ASC;
        ");
    
        $stmt->bindParam(':jenisDokumen', $jenisDokumen);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}