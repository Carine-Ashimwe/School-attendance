<!DOCTYPE html>
<html>
<head>
  <title>View Attendance</title>
  <link rel="stylesheet" href="styles/range.css">
</head>
<body>
<header>
    <span class="school-name">STUDENT ATTENDANCE MANAGEMENT SYSTEM</span>
    <a href="logout.php" class="logout-link">Logout</a>
  </header>
  <h1>Select date</h1>
  <div class="view">
  <form action="view.php" method="get">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date" required>
    
    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date" required>
    <button type="submit">Show Attendance</button>
  </form>
</div>
</body>
</html>