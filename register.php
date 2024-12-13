<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LoCraft</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, #fad0c4, #ff9a9e);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .auth-container {
            background: white;
            padding: 30px 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        .auth-container h2 {
            font-size: 24px;
            color: #ff6f91;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 12px 5px;
            border: 1px solid #ddd;
            border-radius: 50px;
            font-size: 14px;
            transition: border 0.3s, box-shadow 0.3s;
        }

        .form-group input:focus {
            border-color: #ff6f91;
            box-shadow: 0 0 5px rgba(255, 111, 145, 0.6);
            outline: none;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #ff6f91;
        }

        .btn {
            background: linear-gradient(to right, #ff9a9e, #fad0c4);
            color: white;
            font-size: 16px;
            padding: 12px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s, transform 0.3s;
        }

        .btn:hover {
            background: linear-gradient(to right, #fad0c4, #ff9a9e);
            transform: scale(1.05);
        }

        .link {
            color: #ff6f91;
            font-size: 14px;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }

        .alert {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        p {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h2>Create Your Account</h2>

        <!-- Display Notifications -->
        <div id="alert-container">
            <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="alert alert-success">Registration successful! Redirecting to login...</div>
                <script>
                    setTimeout(() => window.location.href = 'login.php', 2000);
                </script>
            <?php elseif (isset($_GET['status']) && $_GET['status'] == 'exists'): ?>
                <div class="alert alert-warning">Email or username already exists. Please try another.</div>
            <?php elseif (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
                <div class="alert alert-error">Registration failed. Please try again.</div>
            <?php endif; ?>
        </div>

        <form action="process_register.php" method="POST">
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <i class="fas fa-phone"></i>
                <input type="tel" name="phone" placeholder="Enter your phone number" pattern="[0-9]{10,15}" required>
            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Register</button>
            <p>Already have an account? <a href="login.php" class="link">Login here</a></p>
        </form>
    </div>
</body>
</html>
