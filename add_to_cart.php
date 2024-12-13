<?php
session_start();
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $productId = $input['product_id'];
    $userId = $_SESSION['user_id'];

    // Cek apakah produk sudah ada di cart
    $query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Jika produk belum ada di cart, tambahkan
        $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $userId, $productId);
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Product added to cart successfully!']);
        } else {
            echo json_encode(['message' => 'Failed to add product to cart.']);
        }
    } else {
        // Jika produk sudah ada, update jumlahnya
        $query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $userId, $productId);
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Product quantity updated in cart!']);
        } else {
            echo json_encode(['message' => 'Failed to update product quantity in cart.']);
        }
    }
}
?>
