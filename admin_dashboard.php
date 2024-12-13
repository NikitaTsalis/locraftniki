<?php
session_start();
include 'db_config.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LoCraft</title>
    <link rel="stylesheet" href="assets/css/admin_dashboard.css">
</head>
<body>
    <header class="admin-header">
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="admin_products.php">Manage Products</a></li>
                <li><a href="admin_orders.php">Manage Orders</a></li>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="index.php">Home</a></li>

            </ul>
        </nav>
    </header>

    <main class="admin-content">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <div class="admin-options">
            <a href="admin_products.php" class="admin-card">
                <h3>Manage Products</h3>
                <p>Add, edit, or delete products in your shop.</p>
            </a>
            <a href="admin_orders.php" class="admin-card">
                <h3>Manage Orders</h3>
                <p>View and process customer orders.</p>
            </a>
        </div>
    </main>
</body>
</html>
