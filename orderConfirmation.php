<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: login.php");
    exit;
}

$orderId = $_GET['order_id'];
$query = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Invalid order ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="assets/css/orderConfirmation.css">
</head>
<body>
    <div class="confirmation-container">
        <h1>Thank You for Your Order!</h1>
        <p>Your order ID is <strong><?php echo $orderId; ?></strong>.</p>
        <p>Total: <strong>Rp<?php echo number_format($order['total_price'], 0, ',', '.'); ?></strong></p>
        <p>We will deliver your order to:</p>
        <p><strong><?php echo htmlspecialchars($order['shipping_address']); ?></strong></p>
        <a href="orderHistory.php" class="btn">View Your Orders</a>
    </div>
</body>
</html>
