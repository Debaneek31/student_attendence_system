<?php
$servername = "localhost";
$username = "root"; // Default in XAMPP
$password = ""; // No password by default
$database = "attendance_db"; // Change if needed

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
