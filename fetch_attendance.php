<?php
include('db_connection.php');

// Fetch year-wise attendance percentage
$sql = "SELECT YEAR(date) as year, ROUND((SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) AS attendance_percentage
        FROM attendance
        GROUP BY YEAR(date)";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
