<?php 
session_start();
include 'db_config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil filter dari GET
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$maxPrice = isset($_GET['maxPrice']) ? (int) $_GET['maxPrice'] : 0;
$tag = isset($_GET['tag']) ? trim($_GET['tag']) : '';

// Query SQL untuk produk berdasarkan filter
$query = "SELECT * FROM products WHERE 1=1";

if ($search) {
    $query .= " AND name LIKE ?";
}
if ($category) {
    $query .= " AND category = ?";
}
if ($maxPrice > 0) {
    $query .= " AND price <= ?";
}
if ($tag) {
    $query .= " AND FIND_IN_SET(?, tags)";
}

$stmt = $conn->prepare($query);

// Bind parameters
$params = [];
if ($search) {
    $params[] = "%$search%";
}
if ($category) {
    $params[] = $category;
}
if ($maxPrice > 0) {
    $params[] = $maxPrice;
}
if ($tag) {
    $params[] = $tag;
}

if (!empty($params)) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Popular Tags (hardcoded to match database data)
$popularTags = ["Silver", "Gold", "Pearl", "Ribbon", "Coquette", "Star", "Pink", "White"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - LoCraft</title>
    <link rel="stylesheet" href="assets/css/styleLoCraft.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
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
        <div class="header-actions">
            <form method="GET" action="shop.php">
                <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Sidebar for Filters -->
        <aside class="sidebar">
            <form method="GET" action="shop.php">
                <h3>All Categories</h3>
                <select name="category">
                    <option value="">All</option>
                    <option value="Necklace" <?php echo $category === 'Necklace' ? 'selected' : ''; ?>>Necklace</option>
                    <option value="Bracelet" <?php echo $category === 'Bracelet' ? 'selected' : ''; ?>>Bracelet</option>
                    <option value="Ring" <?php echo $category === 'Ring' ? 'selected' : ''; ?>>Ring</option>
                    <option value="Key Chain" <?php echo $category === 'Key Chain' ? 'selected' : ''; ?>>Key Chain</option>
                    <option value="Bag Charm" <?php echo $category === 'Bag Charm' ? 'selected' : ''; ?>>Bag Charm</option>
                    <option value="Phone Strap" <?php echo $category === 'Phone Strap' ? 'selected' : ''; ?>>Phone Strap</option>
                    <option value="Bag" <?php echo $category === 'Bag' ? 'selected' : ''; ?>>Bag</option>
                </select>

                <h3>Price</h3>
                <input type="number" name="maxPrice" placeholder="Max Price (e.g., 100000)" value="<?php echo htmlspecialchars($maxPrice); ?>" min="1">

                <h3>Popular Tags</h3>
                <div class="tags">
                    <?php foreach ($popularTags as $tagOption): ?>
                        <button type="submit" name="tag" value="<?php echo htmlspecialchars($tagOption); ?>" class="tag"><?php echo htmlspecialchars($tagOption); ?></button>
                    <?php endforeach; ?>
                </div>
            </form>
        </aside>

        <!-- Product Grid -->
        <section class="product-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="product-card">
                        <img src="assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                        <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                        <p>Rp<?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                        <div class="product-actions">
                            <button onclick="addToCart(<?php echo $row['id']; ?>)" class="btn-cart">Add to Cart</button>
                            <button onclick="addToWishlist(<?php echo $row['id']; ?>)" class="btn-wishlist">Add to Wishlist</button>
                        </div>
                    </div>
                <?php } ?>
            <?php else: ?>
                <p>No products found matching your criteria.</p>
            <?php endif; ?>
        </section>
        <style>
/* Reset margin dan padding default */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Gaya dasar untuk body */
body {
    font-family: Arial, sans-serif;
}

/* Gaya untuk grid produk */
.product-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 16px; /* Jarak antara kartu produk */
    justify-content: center; /* Pusatkan grid */
    padding: 16px;
}

/* Gaya untuk kartu produk */
.product-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    overflow: hidden;
    text-align: center;
    width: calc(25% - 32px); /* Empat kartu per baris dengan margin */
    min-width: 200px; /* Lebar minimum kartu */
}

.product-card img {
    max-width: 100%;
    height: 270px;
    display: block;
    margin: 0 auto;
}

.product-card h4 {
    font-size: 18px;
    margin: 16px 0;
}

.product-card p {
    color: #888;
    font-size: 16px;
}

.product-actions {
    display: flex;
    justify-content: space-around;
    padding: 16px;
}

.btn-cart, .btn-wishlist {
    background: #eda5a6;
    border: none;
    border-radius: 4px;
    color: #fff;
    cursor: pointer;
    padding: 8px 16px;
    transition: background 0.3s ease;
}

.btn-cart:hover, .btn-wishlist:hover {
    background: #8e7ab5;
}
</style>

    </main>

    <!-- Animations -->
    <script>
        // Navbar animation
        gsap.from(".main-nav ul li", {duration: 1, y: -20, opacity: 0, stagger: 0.2});

        // Sidebar animation
        gsap.from(".sidebar", {duration: 1, x: -100, opacity: 0});

        // Product animation
        gsap.from(".product-card", {duration: 1, opacity: 0, scale: 0.8, stagger: 0.2});

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
