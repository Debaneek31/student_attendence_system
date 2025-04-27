<?php
session_start();
include('db_connection.php');

// Check login
if (!isset($_SESSION['student_id'])) {
    echo "<script>alert('Please login first!'); window.location.href='student_login.html';</script>";
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch attendance records
$sql = "SELECT subject, COUNT(*) as total FROM attendance WHERE student_id = ? GROUP BY subject";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Database Error: " . $conn->error);
}

$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subject-wise Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('college.jpg') no-repeat center center fixed;
            background-size: cover;
            text-align: center;
            margin: 0;
            padding: 0;
            color: white;
        }
        .container {
            margin: 50px auto;
            padding: 40px;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 15px;
            width: 80%;
            max-width: 800px;
            box-shadow: 0px 5px 20px rgba(255, 255, 255, 0.2);
        }
        table {
            width: 100%;
            background: white;
            color: black;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: white;
        }
        h2 {
            margin-bottom: 20px;
            color: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Subject-wise Attendance Report</h2>

    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Attendance Count</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['subject']) ?></td>
                        <td><?= htmlspecialchars($row['total']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No attendance records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
