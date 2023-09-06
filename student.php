<?php
  session_start();
  if (!isset($_SESSION["user"])) {
     header("Location: login.php");
  
  }
include "functions.php";

if (isset($_POST['submit'])) {
$name = $_POST['name'];
$rollNumber = $_POST['roll_number'];

$sql = "INSERT INTO students (name, roll_number) VALUES ('$name', '$rollNumber')";
mysqli_query($mysqli, $sql);

header('Location: attendance.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add student</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="styles/style.css">
</head>
<body>
<header>
    <span class="school-name">STUDENT ATTENDANCE MANAGEMENT SYSTEM</span>
    <a href="logout.php" class="logout-link">Logout</a>
  </header>
  <h1>Add Student</h1>
<form method="post">
    <input type="text" name="name" placeholder="Student Name" required>
    <input type="text" name="roll_number" placeholder="Roll Number" required>
    <input type="submit" name="submit" value="Add Student">
  </form>
  <br>
</body>
</html>
