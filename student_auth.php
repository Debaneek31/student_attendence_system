<?php
session_start();
include('db_connection.php');

// Handle Student Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if ($student && password_verify($password, $student['password'])) { // Verify hashed password
        $_SESSION['student_id'] = $student['student_id'];
        header("Location: student_profile.php"); // Redirect to profile
        exit();
    } else {
        echo "<script>alert('Invalid Student ID or Password'); window.location.href='student_login.html';</script>";
    }
}

// Handle Student Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php"); // Redirect to homepage
    exit();
}

// Fetch Student Profile Data
if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];
    $sql = "SELECT * FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Profile</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
        }
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .btn {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 10px 20px;
            margin-top: 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }
        .btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h1>Student Profile</h1>
        <img src="uploads/<?php echo htmlspecialchars($student['photo']); ?>" alt="Profile Picture">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
        <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>
        <p><strong>Branch:</strong> <?php echo htmlspecialchars($student['branch']); ?></p>
        <p><strong>Session:</strong> <?php echo htmlspecialchars($student['session']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone']); ?></p>
        <br>
        <a href="?logout=true" class="btn">Logout</a>
    </div>
</body>
</html>
