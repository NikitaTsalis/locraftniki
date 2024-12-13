<?php
session_start();
include 'db_config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data produk dari database untuk ditampilkan
$query = "SELECT * FROM products";
$result = $conn->query($query);

// Periksa role pengguna
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'customer';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LoCraft - Home</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body>
    <!-- Navigation Bar -->
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
    <!-- Promo Section -->
    <section class="promo-section">
        <div class="promo-banner">
            <h2>SUMMER SALE</h2>
            <h1>37% OFF</h1>
            <p>Enjoy free shipping and 30 days money-back guarantee!</p>
            <a href="shop.php" class="btn">Shop Now</a>
        </div>
    </section>

    <!-- Product Section -->
    <section class="product-list">
        <h2>Our Products</h2>
        <div class="products">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="product-card">
                    <img src="assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <div class="product-info">
                        <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p>Price: Rp<?php echo number_format($row['price'], 2); ?></p>
                    </div>
                    <div class="product-actions">
                        <button onclick="addToCart(<?php echo $row['id']; ?>)" class="btn-cart">Add to Cart</button>
                        <button onclick="addToWishlist(<?php echo $row['id']; ?>)" class="btn-wishlist">Add to Wishlist</button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- GSAP Animations -->
    <script>
        // Navbar animation
        gsap.from(".main-nav ul li", {duration: 1, y: -20, opacity: 0, stagger: 0.2});

        // Promo banner animation
        gsap.from(".promo-banner", {duration: 1, opacity: 0, y: 50});

        // Product card animation
        gsap.from(".product-card", {duration: 1, opacity: 0, scale: 0.8, stagger: 0.2});

        // Add to Cart
        function addToCart(productId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({product_id: productId})
            })
            .then(response => response.json())
            .then(data => alert(data.message));
        }

        // Add to Wishlist
        function addToWishlist(productId) {
            fetch('add_to_wishlist.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({product_id: productId})
            })
            .then(response => response.json())
            .then(data => alert(data.message));
        }
    </script>
</body>
</html>
