<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM students WHERE student_id = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $student_id, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if ($student) {
        $_SESSION['student_id'] = $student['student_id'];
        header("Location: student_profile.php"); // Redirect to student profile
        exit();
    } else {
        echo "<script>alert('Invalid Student ID or Password'); window.location.href='student_login.html';</script>";
    }
}
?>
