<?php
session_start();
include 'db_config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Ambil data wishlist pengguna
$query = "SELECT p.id, p.name, p.price, p.image FROM wishlist w 
          JOIN products p ON w.product_id = p.id WHERE w.user_id = ?";
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
    <title>Wishlist - LoCraft</title>
    <link rel="stylesheet" href="assets/css/wishlist.css">
    <link rel="stylesheet" href="assets/css/header.css">
    
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

    <!-- Main content -->
    <div class="container">
        <div class="wishlist-section">
            <h2>Your Wishlist</h2>
            <div class="wishlist-items">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <div class="product-card">
                            <img src="assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                            <h3><?php echo $row['name']; ?></h3>
                            <p>Price: Rp<?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                            <a href="cart.php">
                            <button class="btn">Add to Cart</button>
                        </div>
                    <?php } ?>
                <?php else: ?>
                    <div class="empty-wishlist">
                        <p>Your wishlist is empty.</p>
                        <a href="shop.php" class="btn">Go to Shop</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
    // Navbar animation
    gsap.from(".navbar", {duration: 1, y: -50, opacity: 0});

    // Wishlist heading animation
    gsap.from(".wishlist-section h2", {duration: 1, x: -50, opacity: 0});

    // Product card animation
    gsap.from(".product-card", {duration: 1, scale: 0.8, opacity: 0, stagger: 0.2});
</script>
</body>
</html>
