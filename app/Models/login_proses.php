<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

// Instantiate the Database class and establish a connection
$db = new Database();
$conn = $db->connect();

if (!$conn) {
    $_SESSION['error'] = "Database connection failed.";
    header("Location: ../views/login.php");
    exit();
}

try {
    // Get data from the form
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Query to check if the username exists (KURUNG SIKU DIGANTI BACKTICK)
    $sql = "SELECT * FROM `User` WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $input_username, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Compare passwords directly (plaintext comparison)
        if ($input_password === $user['password']) { 
            // Store user info in session
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_user'] = $user['role_user']; // Assuming 'role_user' stores user role
            $_SESSION['nama'] = $user['nama'];
            
            if ($user['role_user'] === 'mahasiswa') {
                $sql_nim = "SELECT nim FROM Mahasiswa WHERE id_user = :id_user";
                $stmt_nim = $conn->prepare($sql_nim);
                $stmt_nim->bindParam(':id_user', $user['id_user'], PDO::PARAM_INT);
                $stmt_nim->execute();
                $mahasiswa = $stmt_nim->fetch(PDO::FETCH_ASSOC);
    
                if ($mahasiswa) {
                    $_SESSION['nim'] = $mahasiswa['nim']; // Store NIM in session
                } else {
                    $_SESSION['error'] = "No associated NIM found!";
                    header("Location: ../views/login.php");
                    exit();
                }
            }
            
            // Redirect based on role
            switch ($user['role_user']) {
                case 'superadmin':
                    header("Location: ../../public/index.php");
                    exit();
                case 'admin pusat':
                    header("Location: ../../public/index.php?controller=adminPusat&action=dashboard");
                    exit();
                case 'admin jurusan':
                    header("Location: ../../public/index.php?controller=adminJurusan&action=dashboard");
                    exit();
                case 'mahasiswa':
                    header("Location: ../../public/index.php?controller=mahasiswa&action=dashboard");
                    exit();
                default:
                    $_SESSION['error'] = "Invalid role assigned to user!";
                    header("Location: ../views/login.php");
                    exit();
            }
        } else {
            $_SESSION['error'] = "Invalid username or password!";
            header("Location: ../views/login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "User not found!";
        header("Location: ../views/login.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Exception occurred: " . htmlspecialchars($e->getMessage());
    header("Location: ../views/login.php");
    exit();
}
?>