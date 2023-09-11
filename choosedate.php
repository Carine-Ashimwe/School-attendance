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
  <h1>Select Date</h1>
  <form action="view_attendance.php" method="GET">
  <label for="selected_date">Select Date:</label>
    <input type="date" id="selected_date" name="date">
    <input type="submit" name="submit" value="View Attendance">
  </form>
  <br>
</body>
</html>
