<?php
class Database {
    private static $host = null;
    private static $dbName = null;
    private static $username = null;
    private static $password = null;
    private static $port = null; // <-- TAMBAHAN: Variabel untuk Port
    private static $pdo;

    public static function connect() {
        // Deteksi apakah sedang di Render (cloud) atau di Lokal (XAMPP)
        if (self::$host === null) {
            self::$host = getenv('DB_HOST') ?: '127.0.0.1'; 
            self::$dbName = getenv('DB_NAME') ?: 'bebas_tanggungan'; // Nama DB di XAMPP
            self::$username = getenv('DB_USER') ?: 'root';          // Default XAMPP
            self::$password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : ''; // Default XAMPP
            self::$port = getenv('DB_PORT') ?: '3306'; // <-- TAMBAHAN: Ambil port Aiven, default XAMPP 3306
        }

        if (!self::$pdo) {
            try {
                // <-- TAMBAHAN: Masukkan ";port=" . self::$port ke dalam DSN
                $dsn = "mysql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$dbName . ";charset=utf8mb4";
                self::$pdo = new PDO($dsn, self::$username, self::$password);
                
                // Konfigurasi Error Mode
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Konfigurasi agar hasil fetch otomatis berupa array asosiatif
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
?>