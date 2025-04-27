<?php
session_start();
include('db_connection.php');

// Ensure student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.html");
    exit();
}

$student_id = $_SESSION['student_id'];
$sql = "SELECT * FROM students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Fetch attendance records
$att_sql = "SELECT * FROM attendance WHERE student_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($att_sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$att_result = $stmt->get_result();
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
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            margin: 0;
            padding: 0;
        }
        .profile-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(0, 0, 0, 0.8);
            box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.3);
            border-radius: 15px;
        }
        img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #28a745;
        }
        h1 {
            margin-bottom: 15px;
            font-size: 28px;
            color: #28a745;
        }
        p {
            font-size: 18px;
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 25px;
            margin-top: 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
            font-size: 16px;
        }
        .btn:hover {
            background: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.9);
            color: black;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border: 1px solid black;
            text-align: left;
        }
        th {
            background: #28a745;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
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
        <a href="student_subject_report.php" class="btn">My Subject Attendance Report</a>

        <h2>Attendance Records</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $att_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['time']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
            <?php } ?>
        </table>
        <br>
        <a href="index.html" class="btn">Logout</a>
    </div>
</body>
</html>
