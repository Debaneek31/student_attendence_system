<?php
session_start();
include('db_connection.php'); // ✅ Correct


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    $query = "SELECT * FROM students WHERE student_id = '$student_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['student_id'] = $row['student_id'];
            header("Location: ../student/student_dashboard.html");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No account found!";
    }
}
?>
