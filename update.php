<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
   exit;
}

include "functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = $_POST['new_status'];
    $attendanceDate = $_POST['date'];
    $studentName = $_POST['student'];


    $updateSql = "UPDATE attendance
                  SET attendance_status = '$newStatus'
                  WHERE attendance_date = '$attendanceDate'
                  AND student_id = (SELECT id FROM students WHERE name = '$studentName')";

$stmt = $mysqli->prepare($updateSql);
$stmt->bind_param("sss", $newStatus, $attendanceDate, $studentName);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: view_attendance.php?date=$attendanceDate");
    exit();
} else {
    $stmt->close();
    header("Location: view_attendance.php?date=$attendanceDate"); 
    exit();
}
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Attendance</title>
  <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<?php
  if (isset($_GET['date']) && isset($_GET['student'])) {
      $attendanceDate = $_GET['date'];
      $studentName = $_GET['student'];
  ?>
  <form method="post" action="update.php">
    <input type="hidden" name="date" value="<?php echo $attendanceDate; ?>">
    <input type="hidden" name="student" value="<?php echo $studentName; ?>">
    <label for="new_status">New Status:</label>
    <select id="new_status" name="new_status">
    <option value='' style='display: none;'>Select status</option>
        <option value="Present">Present</option>
        <option value="Absent">Absent</option>
        <option value="Late">Late</option>
        <option value="Sick">Sick</option>
    </select>
    <button type="submit">Update Status</button>
  </form>
  <?php } ?>
</body>
</html>
