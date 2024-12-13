<?php
session_start();
include 'db_config.php';

// Periksa apakah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil ID produk
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data produk
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

// Proses update produk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = (float)$_POST['price'];
    $categoryId = (int)$_POST['category_id'];
    $description = trim($_POST['description']);
    $tags = trim($_POST['tags']);
    $image = $product['image']; // Default: gunakan gambar lama

    // Periksa jika ada gambar baru
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $targetDir = "assets/images/";
        $targetFile = $targetDir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
    }

    $updateQuery = "UPDATE products SET name = ?, price = ?, category_id = ?, tags = ?, image = ?, description = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sdisssi", $name, $price, $categoryId, $tags, $image, $description, $productId);

    if ($updateStmt->execute()) {
        header("Location: admin_products.php");
        exit;
    } else {
        echo "Error updating product: " . $updateStmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - LoCraft</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <h1>Edit Product</h1>
    <form action="edit_product.php?id=<?php echo $productId; ?>" method="POST" enctype="multipart/form-data">
        <label>Product Name</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

        <label>Price</label>
        <input type="number" name="price" value="<?php echo $product['price']; ?>" required>

        <label>Category</label>
        <select name="category_id" required>
            <?php
            $categories = $conn->query("SELECT id, name FROM categories");
            while ($category = $categories->fetch_assoc()) {
                $selected = $product['category_id'] == $category['id'] ? 'selected' : '';
                echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
            }
            ?>
        </select>

        <label>Tags</label>
        <input type="text" name="tags" value="<?php echo htmlspecialchars($product['tags']); ?>" placeholder="Comma-separated tags" required>

        <label>Description</label>
        <textarea name="description" rows="5" required><?php echo htmlspecialchars($product['description']); ?></textarea>

        <label>Image</label>
        <input type="file" name="image" accept="image/*">
        <img src="assets/images/<?php echo $product['image']; ?>" alt="Current Image" width="100">

        <button type="submit">Update Product</button>
    </form>
</body>
</html>
