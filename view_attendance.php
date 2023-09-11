<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
   exit;
}

include "functions.php";

if (isset($_GET['date'])) {
    $selectedDate = $_GET['date'];

$sql = "SELECT a.id, a.attendance_date, s.name AS student_name, a.attendance_status
     FROM attendance a
     INNER JOIN students s ON a.student_id = s.id
     WHERE a.attendance_date = '$selectedDate'";
  
$attendanceResult = mysqli_query($mysqli, $sql);
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>View Attendance</title>
  <link rel="stylesheet" href="styles/view_attendance.css">
</head>
<body>
  <header>
    <span class="school-name">STUDENT ATTENDANCE MANAGEMENT SYSTEM</span>
    <a href="logout.php" class="logout-link">Logout</a>

</header>
  
  <?php
  
   if (isset($selectedDate)) {
    echo "<p class='selected-date'>View Attendance On: $selectedDate</p>";

}
  if (isset($attendanceResult) && mysqli_num_rows($attendanceResult) > 0) {
      echo "<table>
                <tr>
                    <th>Student name</th>
                    <th>Attendance Date</th>
                    <th>Attendance Status</th>
                    <th>Action</th>
                </tr>";
      
      while ($attendanceRow = mysqli_fetch_assoc($attendanceResult)) {
        $status = $attendanceRow['attendance_status'];
        $statusClass = '';
    
        if ($status === 'Present') {
            $statusClass = 'present';
        } elseif ($status === 'Absent') {
            $statusClass = 'absent';
        } elseif ($status === 'Late') {
            $statusClass = 'late';
        } elseif ($status === 'Sick') {
            $statusClass = 'sick';
        }
        $deleteLink = "delete.php?delete={$attendanceRow['id']}&date={$attendanceRow['attendance_date']}";
        $confirmation = "return confirm('Are you sure you want to delete this student? This action is irreversible.');";

        echo "<tr>
                    <td>{$attendanceRow['student_name']}</td>
                    <td>{$attendanceRow['attendance_date']}</td>
                    <td class='$statusClass'>$status</td> 
                    <td>
                    <a href='update.php?date={$attendanceRow['attendance_date']}&student={$attendanceRow['student_name']}'>Edit</a>
                    <a href='$deleteLink' onclick='$confirmation'>Delete</a>                   
                    </td>
                </tr>";
      }
      
      echo "</table>";
  } else {
      echo "<p>No attendance records found for the selected date.</p>";
  }
  ?>
    <button class="previous" ><a href="dateform.php" role="button"style="text-decoration: none;
    color:white;">Back</a></button>
</body>
</html>
