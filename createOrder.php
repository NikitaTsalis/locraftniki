<?php
session_start();
include 'db_config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$shippingAddress = $_POST['shipping_address'];
$paymentMethod = $_POST['payment_method'];
$cartItems = $_SESSION['cart']; // Asumsikan keranjang belanja disimpan di session

// Hitung total harga
$totalPrice = 0;
foreach ($cartItems as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

// Masukkan data ke tabel orders
$query = "INSERT INTO orders (user_id, total_price, shipping_address, payment_method, status) 
          VALUES (?, ?, ?, ?, 'pending')";
$stmt = $conn->prepare($query);
$stmt->bind_param("idss", $userId, $totalPrice, $shippingAddress, $paymentMethod);
$stmt->execute();
$orderId = $conn->insert_id; // Ambil ID pesanan yang baru saja dibuat

// Masukkan data ke tabel order_items
foreach ($cartItems as $item) {
    $query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
              VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiid", $orderId, $item['product_id'], $item['quantity'], $item['price']);
    $stmt->execute();
}

// Kosongkan cart setelah pesanan dibuat
unset($_SESSION['cart']);

// Redirect ke halaman konfirmasi
header("Location: orderConfirmation.php?order_id=$orderId");
exit;
?>
