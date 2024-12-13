<?php
session_start();
include 'db_config.php';

// Periksa apakah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil semua pesanan dari database
$query = "SELECT o.id, o.total_price, o.shipping_address, o.payment_method, o.status, o.created_at, u.username 
          FROM orders o 
          JOIN users u ON o.user_id = u.id";
$result = $conn->query($query);

// Proses update status pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = (int)$_POST['order_id'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $status, $orderId);

    if ($stmt->execute()) {
        header("Location: admin_orders.php");
        exit;
    } else {
        echo "Error updating order status: " . $stmt->error;
    }
}

// Proses hapus pesanan
if (isset($_GET['delete_order_id'])) {
    $orderId = (int)$_GET['delete_order_id'];

    $deleteQuery = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $orderId);

    if ($stmt->execute()) {
        header("Location: admin_orders.php");
        exit;
    } else {
        echo "Error deleting order: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - LoCraft</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <h1>Manage Orders</h1>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total Price</th>
                <th>Shipping Address</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td>Rp<?php echo number_format($row['total_price'], 0, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($row['shipping_address']); ?></td>
                        <td><?php echo ucfirst($row['payment_method']); ?></td>
                        <td>
                            <form action="admin_orders.php" method="POST" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="pending" <?php echo $row['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="processing" <?php echo $row['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="completed" <?php echo $row['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="cancelled" <?php echo $row['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </form>
                        </td>
                        <td><?php echo date("d M Y, H:i", strtotime($row['created_at'])); ?></td>
                        <td>
                            <a href="admin_orders.php?delete_order_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
