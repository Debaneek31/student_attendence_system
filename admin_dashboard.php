<?php
include('db_connection.php');

// Fetch yearly attendance data
$yearly_attendance = [];
$years = range(date("Y") - 4, date("Y")); // Last 5 years

foreach ($years as $year) {
    $sql = "SELECT COUNT(*) AS total FROM attendance WHERE YEAR(date) = $year";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $yearly_attendance[$year] = $row['total'] ?? 0;
}

// Convert data to JSON format for Chart.js
$years_json = json_encode(array_keys($yearly_attendance));
$attendance_json = json_encode(array_values($yearly_attendance));

// Fetch total students and total attendance records
$sql = "SELECT COUNT(*) AS total_students FROM students";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_students = $row['total_students'];

$sql = "SELECT COUNT(*) AS total_attendance FROM attendance";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_attendance = $row['total_attendance'];

// Fetch latest attendance records
$sql = "SELECT * FROM attendance ORDER BY date DESC LIMIT 10";
$attendance_result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            max-width: 900px;
            margin: 50px auto;
            background: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        }
        .btn {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 20px;
            margin: 10px;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }
        .btn:hover {
            background: #218838;
        }
        .chart-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            color: black;
            margin-top: 20px;
        }
        .chart-container canvas {
            height: 250px !important;
            width: 100% !important;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.9);
            color: black;
        }
        th, td {
            padding: 10px;
            border: 1px solid black;
            text-align: left;
        }
        th {
            background: #28a745;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        
        <a href="admin/register_student.html" class="btn">Register Student</a>
        <a href="admin/manage_students.html" class="btn">Manage Students</a>
        <a href="attendance_report.php" class="btn">View Attendance Reports</a>
        <a href="admin_subject_report.php" class="btn">View Subject Attendance Report</a>

        <a href="index.html" class="btn">Logout</a>

        <div class="stats">
            <h2>Total Students: <?php echo $total_students; ?></h2>
            <h2>Total Attendance Records: <?php echo $total_attendance; ?></h2>
        </div>
        
        <div class="chart-container">
            <h2>Yearly Attendance Overview</h2>
            <canvas id="attendanceChart"></canvas>
        </div>

        <h3>Recent Attendance</h3>
        <table>
            <tr>
                <th>Student ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $attendance_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['student_id']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['time']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <script>
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo $years_json; ?>,
                datasets: [{
                    label: 'Total Attendance',
                    data: <?php echo $attendance_json; ?>,
                    borderColor: '#28a745',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>