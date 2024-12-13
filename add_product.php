<?php
session_start();
include 'db_config.php';

// Periksa apakah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Proses tambah produk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];
    $tags = trim($_POST['tags']);
    $description = trim($_POST['description']);
    $image = $_FILES['image']['name'];

    // Upload file gambar
    $targetDir = "assets/images/";
    $targetFile = $targetDir . basename($image);
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        // Masukkan data ke database
        $query = "INSERT INTO products (name, price, category_id, tags, image, description) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdisss", $name, $price, $category_id, $tags, $image, $description);

        if ($stmt->execute()) {
            header("Location: admin_dashboard.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - LoCraft</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <h1>Add New Product</h1>
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <label>Product Name</label>
        <input type="text" name="name" required>

        <label>Price</label>
        <input type="number" name="price" step="0.01" required>

        <label>Category</label>
        <select name="category_id" required>
            <?php
            // Ambil kategori dari database
            $categoryQuery = "SELECT id, name FROM categories";
            $categoryResult = $conn->query($categoryQuery);
            while ($row = $categoryResult->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>

        <label>Tags</label>
        <input type="text" name="tags" placeholder="Comma-separated tags" required>

        <label>Description</label>
        <textarea name="description" rows="5" required></textarea>

        <label>Image</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit">Add Product</button>
    </form>
</body>
</html>
