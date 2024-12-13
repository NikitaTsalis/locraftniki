<?php
session_start();
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $productId = $input['product_id'];
    $userId = $_SESSION['user_id'];

    // Periksa apakah produk sudah ada di wishlist
    $query = "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Jika produk belum ada di wishlist, tambahkan
        $query = "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $userId, $productId);
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Product added to wishlist successfully!']);
        } else {
            echo json_encode(['message' => 'Failed to add product to wishlist.']);
        }
    } else {
        echo json_encode(['message' => 'Product already in wishlist.']);
    }
}
?>
