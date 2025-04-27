<?php
include('db_connection.php');

// Handle date filter
$filter_query = "";
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $filter_query = " WHERE attendance.date BETWEEN '$start_date' AND '$end_date'";
}

// Fetch filtered or all attendance records
$sql = "SELECT students.name, students.student_id, attendance.date, attendance.time, attendance.status 
        FROM attendance 
        JOIN students ON attendance.student_id = students.student_id" . $filter_query . " ORDER BY attendance.date DESC";
$result = $conn->query($sql);

// Export to CSV functionality
if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendance_report.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Student ID', 'Name', 'Date', 'Time', 'Status']);
    
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <link rel="stylesheet" href="styles.css">
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
        h1 {
            color: #28a745;
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
        .btn {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 20px;
            margin-top: 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }
        .btn:hover {
            background: #218838;
        }
        .filter-form {
            margin-bottom: 20px;
        }
        .filter-form input {
            padding: 8px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Attendance Report</h1>
        <form method="GET" class="filter-form">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" required>
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" required>
            <button type="submit" class="btn">Filter</button>
        </form>
        <a href="?export=true" class="btn">Export as CSV</a>
        <table>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['student_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['time']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
            <?php } ?>
        </table>
        <br>
        <a href="admin_dashboard.php" class="btn">Back to Dashboard</a>
    </div>
</body>
</html>
