<?php
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid_tag = $_POST['rfid_tag'];
    $date = date('Y-m-d');
    $time = date('H:i:s');

    // Determine subject based on current time
    $current_time = strtotime($time);

    if ($current_time >= strtotime('02:00:00') && $current_time <= strtotime('03:00:00')) {
        $subject = 'Mathematics';
    } elseif ($current_time >= strtotime('10:00:01') && $current_time <= strtotime('11:00:00')) {
        $subject = 'Physics';
    } elseif ($current_time >= strtotime('11:00:01') && $current_time <= strtotime('12:00:00')) {
        $subject = 'Chemistry';
    } elseif ($current_time >= strtotime('13:00:00') && $current_time <= strtotime('14:00:00')) {
        $subject = 'Computer Science';
    } else {
        $subject = 'Unknown'; // Outside class times
    }

    if ($subject == 'Unknown') {
        echo json_encode(["status" => "error", "message" => "Attendance not allowed at this time"]);
        exit();
    }

    // Fetch student info
    $query = "SELECT * FROM students WHERE rfid_tag = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $rfid_tag);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        $student_id = $student['student_id'];

        // Check if already marked for same subject
        $checkQuery = "SELECT * FROM attendance WHERE student_id = ? AND date = ? AND subject = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("sss", $student_id, $date, $subject);
        $stmt->execute();
        $checkResult = $stmt->get_result();

        if ($checkResult->num_rows == 0) {
            // Mark Attendance
            $insertQuery = "INSERT INTO attendance (student_id, date, time, subject, status) VALUES (?, ?, ?, ?, 'Present')";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssss", $student_id, $date, $time, $subject);
            $stmt->execute();

            echo json_encode(["status" => "success", "message" => "Attendance marked for subject: $subject"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Already marked for this subject today"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid RFID"]);
    }
}
?>
