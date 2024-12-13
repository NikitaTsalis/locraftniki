<?php
session_start();
include 'db_config.php';

$userId = $_SESSION['user_id'];

// Query untuk mengambil produk yang ada di wishlist
$query = "SELECT p.id, p.name, p.price, p.image FROM wishlist w JOIN products p ON w.product_id = p.id WHERE w.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$wishlist = [];
while ($row = $result->fetch_assoc()) {
    $wishlist[] = $row;
}

echo json_encode($wishlist);
?>
