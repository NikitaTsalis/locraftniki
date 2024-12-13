<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db_config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Ambil data pengguna dari database
$query = "SELECT username, email, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("No user found with ID: $userId");
}

// Proses update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['profile_submit'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);

        $updateQuery = "UPDATE users SET username = ?, email = ?, phone = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("sssi", $username, $email, $phone, $userId);
        $updateStmt->execute();
    }

    // Proses update password
    if (isset($_POST['password_submit'])) {
        $currentPassword = trim($_POST['current_password']);
        $newPassword = trim($_POST['new_password']);
        $confirmPassword = trim($_POST['confirm_password']);

        // Verifikasi password lama
        $passwordQuery = "SELECT password FROM users WHERE id = ?";
        $passwordStmt = $conn->prepare($passwordQuery);
        $passwordStmt->bind_param("i", $userId);
        $passwordStmt->execute();
        $passwordResult = $passwordStmt->get_result();
        $passwordRow = $passwordResult->fetch_assoc();

        if (password_verify($currentPassword, $passwordRow['password'])) {
            if ($newPassword === $confirmPassword) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updatePasswordQuery = "UPDATE users SET password = ? WHERE id = ?";
                $updatePasswordStmt = $conn->prepare($updatePasswordQuery);
                $updatePasswordStmt->bind_param("si", $hashedPassword, $userId);
                $updatePasswordStmt->execute();
            } else {
                echo "New password and confirmation password do not match.";
            }
        } else {
            echo "Current password is incorrect.";
        }
    }

    header("Location: account.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link rel="stylesheet" href="assets/css/account.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body>
<header class="header">
        <div class="logo">
            <img src="assets/images/logo locraft.png" alt="Logo">
        </div>
         <nav class="main-nav">
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="shop.php"><i class="fas fa-store"></i> Shop</a></li>
                <li><a href="orderHistory.php"><i class="fas fa-history"></i> Order History</a></li>
                <li><a href="account.php" class="active"><i class="fas fa-user"></i> Account</a></li>
                <li><a href="chat.html"><i class="fas fa-comments"></i> Chat</a></li>
                <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                <li><a href="cart.php"><i class="fa-solid fa-heart"></i> Wishlist</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="container">
        <aside class="sidebar">
            <ul>
                <li><a href="orderHistory.php"><i class="fas fa-box"></i> Order History</a></li>
                <li><a href="wishlist.php"><i class="fas fa-heart"></i> Wishlist</a></li>
                <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Shopping Cart</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log-out</a></li>
            </ul>
        </aside>
        
        <main class="main-content">
            <section class="profile-section">
                <h2>Account Settings</h2>
                <form method="POST">
                    <h3><i class="fas fa-user"></i> Profile Information</h3>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

                    <button type="submit" name="profile_submit"><i class="fas fa-save"></i> Save Changes</button>
                </form>

                <form method="POST">
                    <h3><i class="fas fa-lock"></i> Change Password</h3>
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>

                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>

                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>

                    <button type="submit" name="password_submit"><i class="fas fa-key"></i> Change Password</button>
                </form>
            </section>
        </main>
    </div>

    <script>
        // Animasi GSAP
        gsap.from(".header", {duration: 1, y: -100, opacity: 0, ease: "power3.out"});
        gsap.from(".sidebar ul li", {duration: 1, x: -50, opacity: 0, stagger: 0.2});
        gsap.from(".profile-section h2", {duration: 1, scale: 0.5, opacity: 0, ease: "bounce.out"});
        gsap.from("form", {duration: 1, y: 50, opacity: 0, stagger: 0.3});
    </script>
</body>
</html>
