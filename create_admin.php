<?php
include 'db_config.php'; // Sambungkan ke database

// Data akun admin
$username = "admin1";
$email = "admin1@locraft.com";
$password = "admin1";
$role = "admin";

// Hash password menggunakan password_hash
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Masukkan data ke database
$query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);
    if ($stmt->execute()) {
        echo "Akun admin berhasil dibuat!";
    } else {
        echo "Gagal membuat akun admin: " . $stmt->error;
    }
} else {
    echo "Gagal mempersiapkan query: " . $conn->error;
}

// Tutup koneksi
$conn->close();
?>
