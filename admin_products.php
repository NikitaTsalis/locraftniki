<?php
session_start();
include 'db_config.php';

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil data produk dari database
$query = "SELECT p.id, p.name, p.price, p.image, c.name AS category
          FROM products p
          LEFT JOIN categories c ON p.category_id = c.id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Products</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <header class="admin-navbar">
        <div class="logo">
            <img src="assets/images/logo-locraft.png" alt="LoCraft Logo">
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="add_product.php">Add Product</a></li>
                <li><a href="admin_products.php" class="active">Manage Products</a></li>
                <li><a href="index.php">Homepage</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="admin-container">
        <h1>Manage Products</h1>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td>Rp<?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                            <td><?php echo $row['category'] ?? 'No Category'; ?></td> <!-- Default jika category null -->
                            <td><img src="assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" width="50"></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                                <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
