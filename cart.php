<?php
session_start();
include 'db_config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Ambil data keranjang pengguna
$query = "SELECT p.id, p.name, p.price, p.image, c.quantity FROM cart c 
          JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
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
    <title>Cart - LoCraft</title>
    <link rel="stylesheet" href="assets/css/cart.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .cart-items {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .product-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .product-card img {
            width: 100px;
            border-radius: 10px;
        }
        .product-details {
            flex: 1;
            margin-left: 20px;
        }
        .product-details h3 {
            margin: 0;
        }
        .remove-btn, .update-btn {
            background: #ff6f91;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .remove-btn:hover, .update-btn:hover {
            background: #ff9671;
        }
        .quantity-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .checkout-btn {
            background: linear-gradient(to right, #ff6f91, #ff9671);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .checkout-btn:hover {
            background: linear-gradient(to right, #ff9671, #ff6f91);
        }
    </style>
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
                <li><a href="wishlist.php"><i class="fa-solid fa-heart"></i> Wishlist</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main content -->
    <div class="container">
        <div class="cart-section">
            <h2>Your Shopping Cart</h2>
            <div class="cart-items">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <div class="product-card">
                            <img src="assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                            <div class="product-details">
                                <h3><?php echo $row['name']; ?></h3>
                                <p>Price: Rp<?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                                <div>
                                    <input type="number" class="quantity-input" min="1" value="<?php echo $row['quantity']; ?>" onchange="updateCart(<?php echo $row['id']; ?>, this.value)">
                                </div>
                                <button class="remove-btn" onclick="removeFromCart(<?php echo $row['id']; ?>)">Remove</button>
                            </div>
                            <div class="total-price">
                                <p>Total: Rp<?php echo number_format($row['price'] * $row['quantity'], 0, ',', '.'); ?></p>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="checkout">
                        <form action="checkout.php" method="POST">
                        <button class="btn checkout-btn" type="submit" name="checkout">Proceed to Checkout</button>
                        
                        </form>
                        
                        
                    </div>
                <?php else: ?>
                    <div class="empty-cart">
                        <p>Your cart is empty.</p>
                        <a href="shop.php" class="btn">Go to Shop</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function updateCart(productId, quantity) {
            fetch('update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ product_id: productId, quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update cart.');
                }
            });
        }

        function removeFromCart(productId) {
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                fetch('remove_from_cart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ product_id: productId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to remove item from cart.');
                    }
                });
            }
        }
    </script>
</body>
</html>