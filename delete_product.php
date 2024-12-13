<?php
session_start();
include 'db_config.php';

// Periksa apakah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil ID produk
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Hapus produk
$query = "DELETE FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $productId);

if ($stmt->execute()) {
    header("Location: admin_products.php");
    exit;
} else {
    echo "Error deleting product: " . $stmt->error;
}
?>
