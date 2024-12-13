<?php
session_start();
include 'db_config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Ambil data pesanan pengguna
$query = "SELECT id, total_price, shipping_address, payment_method, status, created_at 
          FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - LoCraft</title>
    <link rel="stylesheet" href="assets/css/orderHistory.css">
    <link rel="stylesheet" href="assets/css/header.css.">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <header class="header">
        <div class="logo">
            <img src="assets/images/logo locraft.png" alt="Logo">
        </div>
         <nav class="main-nav">
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="shop.php"><i class="fas fa-store"></i> Shop</a></li>
                <li><a href="orderHistory.php"><i class="fas fa-history"></i> Order History</a></li>
                <li><a href="account.php"><i class="fas fa-user"></i> Account</a></li>
                <li><a href="chat.html"><i class="fas fa-comments"></i> Chat</a></li>
                <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                <li><a href="cart.php"><i class="fa-solid fa-heart"></i> Wishlist</a></li>
            </ul>
        </nav>
    </header>

    <!-- Order History Section -->
    <main class="main-content">
        <h1 class="page-title">Your Order History</h1>
        <div class="order-container">
            <?php if ($result->num_rows > 0): ?>
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Total Price</th>
                            <th>Shipping Address</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td>Rp<?php echo number_format($row['total_price'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($row['shipping_address']); ?></td>
                                <td><?php echo ucfirst($row['payment_method']); ?></td>
                                <td class="status <?php echo $row['status'] === 'pending' ? 'status-pending' : 'status-completed'; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </td>
                                <td><?php echo date("d M Y, H:i", strtotime($row['created_at'])); ?></td>
                                <td>
                                    <button class="btn-view">View Details</button>
                                    <a href="feedback1.html" class="btn-view">Feedback</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-order-history">
                    <p>You have no orders yet.</p>
                    <a href="shop.php" class="btn">Start Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // Navbar animation
        gsap.from(".main-nav ul li", {duration: 1, y: -20, opacity: 0, stagger: 0.2});

        // Order card animation
        gsap.from(".order-table tbody tr", {duration: 1, opacity: 0, scale: 0.9, stagger: 0.3});
    </script>
</body>
</html>
