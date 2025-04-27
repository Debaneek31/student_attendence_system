<?php
include('db_connection.php'); // ✅ Correct path now

// Fetch all students' subject-wise attendance
$sql = "SELECT s.student_id, s.name, a.subject, COUNT(a.subject) AS total_attendance
        FROM students s
        LEFT JOIN attendance a ON s.student_id = a.student_id
        GROUP BY s.student_id, a.subject
        ORDER BY s.student_id ASC, a.subject ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Subject-wise Attendance Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('college.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 40px;
            color: white;
            text-align: center;
        }
        .container {
            background: rgba(0,0,0,0.8);
            padding: 30px;
            border-radius: 15px;
            margin: auto;
            max-width: 1100px;
            box-shadow: 0px 5px 20px rgba(255, 255, 255, 0.3);
        }
        h1 {
            margin-bottom: 20px;
            color: #f8f9fa;
        }
        table {
            width: 100%;
            background: white;
            color: black;
            margin-top: 20px;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: white;
        }
        .btn {
            margin-top: 20px;
            padding: 10px 20px;
            background: #28a745;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            text-decoration: none;
            transition: 0.3s;
        }
        .btn:hover {
            background: #218838;
        }
        #searchInput {
            width: 60%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 10px;
            border: none;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Admin Panel - Subject-wise Attendance Report</h1>

    <!-- 🔍 Search bar -->
    <input type="text" id="searchInput" placeholder="Search by Student ID, Name, or Subject..." onkeyup="searchTable()">

    <table id="attendanceTable">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Subject</th>
                <th>Attendance Count</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php if (!empty($row['subject'])): ?> 
                        <tr>
                            <td><?= htmlspecialchars($row['student_id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['subject']) ?></td>
                            <td><?= htmlspecialchars($row['total_attendance']) ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">No attendance records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <button onclick="exportTableToExcel('attendanceTable', 'Attendance_Report')" class="btn" style="background:#17a2b8;">Export to Excel</button>

    <a href="admin_dashboard.php" class="btn">Back to Dashboard</a>
</div>

<script>
function searchTable() {
    var input, filter, table, tr, td, i, j, textValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("attendanceTable");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {
        tr[i].style.display = "none"; // Hide all initially
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j]) {
                textValue = td[j].textContent || td[j].innerText;
                if (textValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    break; // Show the row if match found
                }
            }
        }
    }
}
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    filename = filename?filename+'.xls':'excel_data.xls';
    
    downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob(blob, filename);
    }else{
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        downloadLink.download = filename;
        
        downloadLink.click();
    }
}


</script>

</body>
</html>

<?php
$conn->close();
?>
