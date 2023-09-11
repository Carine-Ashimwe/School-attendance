<!DOCTYPE html>
<html>
<head>
  <title>View Attendance</title>
  <link rel="stylesheet" href="styles/home.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Work+Sans:wght@100;300;400;800&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
            integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<header>
    <span class="school-name">STUDENT ATTENDANCE MANAGEMENT SYSTEM</span>
    <a href="logout.php" class="logout-link">Logout</a>
  </header>
  <?php
  $currentHour = date("G");
  if ($currentHour >= 5 && $currentHour < 12) {
      $greeting = "Good morning";
  } elseif ($currentHour >= 12 && $currentHour < 18) {
      $greeting = "Good afternoon";
  } else {
      $greeting = "Good evening";
  }
    echo "<p class='greeting'>$greeting, teacher!</p>";
  ?>

  <div class="add">
  <form method="post" action="attendance.php">
  <label for="selected_date">Select Date:</label>
    <input type="date" name="attendance_date" required>
    <button type="submit">Add Attendance</button>
</form>
</div>
<div class="view">
  <form action="choosedate.php" method="GET">
    <!-- <label for="selected_date">Select Date:</label>
    <input type="date" id="selected_date" name="date"> -->
    <label for="new">Check Attendance</label>
    <div class="icon">
    <i class="fa-solid fa-eye"></i>
  </div>
    <button type="submit">View Attendance</button>
  </form>

  </div>
  <div class="student-add">
  <form method="post" action="student.php">
  <label for="new">New student</label>
  <div class="icon">
  <i class="fa-solid fa-user"></i>
</div>
<button type="submit">Add Student</button>
</form>
</div>
<div class="students">
  <form method="post" action="attendance.php">
  <label for="student">All students</label>
  <div class="icon">
  <i class="fa-solid fa-school"></i>
</div>
<button type="submit">All Students</button>
  </form>
</div>  
</body>
</html>