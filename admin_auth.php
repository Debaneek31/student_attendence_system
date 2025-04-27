<?php
session_start();
include('db_connection.php');

// Handle Admin Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_username = $_POST['admin_username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $admin_username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin) {
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: admin_dashboard.php"); // Redirect to admin dashboard
        exit();
    } else {
        echo "<script>alert('Invalid Admin Username or Password'); window.location.href='admin_login.html';</script>";
    }
}

// Handle Admin Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.html"); // Redirect to homepage
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            margin: 0;
            padding: 0;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: rgba(0, 0, 0, 0.8);
            box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.3);
            border-radius: 15px;
        }
        h1 {
            margin-bottom: 15px;
            font-size: 24px;
            color: #28a745;
        }
        .input-group {
            margin: 15px 0;
            text-align: left;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .input-group input {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
        }
        .btn {
            display: block;
            width: 100%;
            background: #28a745;
            color: white;
            padding: 14px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: 0.3s;
        }
        .btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <form action="admin_auth.php" method="POST">
            <div class="input-group">
                <label for="admin_username">Username</label>
                <input type="text" id="admin_username" name="admin_username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>
