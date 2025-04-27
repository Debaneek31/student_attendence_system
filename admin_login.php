<?php
session_start();
include('db_connection.php'); // Remove '../' to match root directory

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['admin_username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admins WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_username'] = $row['username'];
            header("Location: admin/dashboard.html");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No admin found!";
    }
}
?>
