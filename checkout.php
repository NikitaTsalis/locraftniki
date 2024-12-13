<?php
session_start();
include 'db_config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Ambil data keranjang pengguna (sesuai dengan kode sebelumnya)
$query = "SELECT p.id, p.name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$totalPrice = 0;
$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
    $totalPrice += $row['price'] * $row['quantity'];
}

// Cek jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pastikan alamat pengiriman dan metode pembayaran ada
    if (empty($_POST['shipping_address']) || empty($_POST['payment_method'])) {
        $errorMessage = "Alamat pengiriman dan metode pembayaran wajib diisi.";
    } else {
        $shippingAddress = $_POST['shipping_address'];
        $paymentMethod = $_POST['payment_method'];
        
        // Proses penyimpanan data checkout, seperti memasukkan ke database (contoh):
        $orderQuery = "INSERT INTO orders (user_id, shipping_address, payment_method, total_price) 
                       VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($orderQuery);
        $stmt->bind_param("issd", $userId, $shippingAddress, $paymentMethod, $totalPrice);
        $stmt->execute();

        // Kosongkan keranjang setelah order
        $deleteQuery = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        // Redirect ke halaman konfirmasi atau riwayat pesanan
        header("Location: orderHistory.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="assets/css/cart.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }

        header.navbar {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        header.navbar .logo img {
            height: 40px;
        }

        header.navbar .main-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        header.navbar .main-nav ul li {
            margin: 0 15px;
        }

        header.navbar .main-nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        header.navbar .main-nav ul li a.active,
        header.navbar .main-nav ul li a:hover {
            text-decoration: underline;
        }

        /* Container checkout */
        h1 {
            text-align: center;
            margin: 20px 0;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form textarea,
        form select {
            width: 98%;
            padding: 5px;
            margin-bottom: 15px;
            border: 1px solid #eda5a6;
            border-radius: 4px;
        }

        form button {
            width: 100%;
            padding: 15px;
            background-color: #eda5a6;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #eda5a6;
        }

        p {
            text-align: center;
            color: black;
        }
    </style>
</head>
<body>
<header class="navbar">
        <div class="logo">
            <img src="assets/images/logo locraft.png" alt="LoCraft Logo">
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="orderHistory.php">Order History</a></li>
                <li><a href="account.php">Account</a></li>
                <li><a href="chat.html">Chat</a></li>
                <li><a href="wishlist.php">Wishlist</a></li>
                <li><a href="cart.php">Cart</a></li>
            </ul>
        </nav>
    </header>
    <h1>Checkout</h1>

    <!-- Tampilkan error jika ada -->
    <?php if (isset($errorMessage)) { echo "<p style='color: red;'>$errorMessage</p>"; } ?>

    <form action="checkout.php" method="POST">
        <label for="shipping_address">Alamat Pengiriman:</label>
        <textarea id="shipping_address" name="shipping_address" required></textarea><br>

        <label for="payment_method">Metode Pembayaran:</label>
        <select id="payment_method" name="payment_method" required>
            <option value="credit_card">Kartu Kredit</option>
            <option value="paypal">PayPal</option>
            <option value="bank_transfer">Transfer Bank</option>
        </select><br>

        <button type="submit">Proses Checkout</button>
    </form>
</body>
</html>