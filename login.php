<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/auth.css">

    <title>Login - LoCraft</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #fff8f0;
        }
        .auth-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        .auth-container h2 {
            color: #ff6f91;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background: linear-gradient(to right, #ff6f91, #ff9671);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
        }
        .btn:hover {
            background: linear-gradient(to right, #ff9671, #ff6f91);
            transform: scale(1.05);
        }
        .link {
            color: #ff6f91;
            text-decoration: none;
        }
        .link:hover {
            text-decoration: underline;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            font-size: 14px;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h2>Welcome Back to LoCraft</h2>

        <!-- Display Notifications -->
        <div id="alert-container">
            <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="alert alert-success">Login successful! Redirecting...</div>
                <script>
                    setTimeout(() => window.location.href = 'index.php', 2000);
                </script>
            <?php elseif (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
                <div class="alert alert-error">Invalid username or password. Please try again.</div>
            <?php endif; ?>
        </div>

        <form action="process_login.php" method="POST">
            <div class="form-group">
                <input type="text" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Login</button>
            <p>Don't have an account? <a href="register.php" class="link">Register here</a></p>
        </form>
    </div>
</body>
</html>
