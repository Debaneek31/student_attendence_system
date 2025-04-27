<?php
include('db_connection.php'); // Ensure this file exists and has a valid database connection

$sql = "SELECT student_id, name, branch, session, rfid_tag, password FROM students ORDER BY student_id ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['student_id']) . "</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['branch']) . "</td>
                <td>" . htmlspecialchars($row['session']) . "</td>
                <td>" . htmlspecialchars($row['rfid_tag']) . "</td>
                <td>
                    <input type='password' class='password-input' value='********' readonly>
                </td>
                <td>
                    <a href='edit_student.php?student_id=" . urlencode($row['student_id']) . "' class='btn'>Edit</a>
                    <a href='delete_student.php?student_id=" . urlencode($row['student_id']) . "' class='btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No students found</td></tr>";
}

$conn->close();
?>
