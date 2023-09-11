<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
   exit;
}

include "functions.php";

if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];


    $sql_dates = "SELECT DISTINCT attendance_date FROM attendance WHERE attendance_date BETWEEN '$start_date' AND '$end_date'";
    $datesResult = mysqli_query($mysqli, $sql_dates);

    while ($dateRow = mysqli_fetch_assoc($datesResult)) {
        $dates[] = $dateRow['attendance_date']; // Add the dates to the array
    }

    $sql = "SELECT a.attendance_date, s.name AS student_name, a.attendance_status
            FROM students s
            LEFT JOIN attendance a ON s.id = a.student_id AND a.attendance_date BETWEEN '$start_date' AND '$end_date'
            ORDER BY s.name, a.attendance_date";

    $attendanceResult = mysqli_query($mysqli, $sql);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>View Attendance</title>
  <link rel="stylesheet" href="styles/rotate_headers.css">

<style>

    .rotate {
      height:0px;
      white-space: nowrap;
      transform: rotate(-45deg);
      vertical-align: middle; 
      padding-top: 49px;
      padding-right: 11px;
      top: 2px;
      position: relative; 
      padding-left: 50px;
    }
    
    .rotate > div {
      width: 100%; 
    }
    th.rotate > div > span {
        border-bottom: 1px solid black;
        padding-bottom: 5px;
        padding-right: -13px;
}
    table {
      border-collapse: collapse;
    }
    
 td {
      border: 1px solid black;
      padding: 8px;
    }
    
    .status {
      text-align: center;
    }
  </style>
</head>
<body>
  <header>
    <span class="school-name">STUDENT ATTENDANCE MANAGEMENT SYSTEM</span>
    <a href="logout.php" class="logout-link">Logout</a>
  </header>
  <p class="titlee">View Attendance </p>
  <?php
  if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    echo '<table>';
    echo '<tr>';
    echo '<th>Id</th>';
    echo '<th>Student name</th>';
    
    foreach ($dates as $formattedDate) {
      echo "<th class='rotate'><div><span>$formattedDate</span></div></th>";
    }
    // echo '<th>Actions</th>';
    echo '</tr>';
    
    $currentStudent = '';
    $attendanceRows = array();
    $uniqueId = 1;

    while ($attendanceRow = mysqli_fetch_assoc($attendanceResult)) {
      if ($currentStudent !== $attendanceRow['student_name']) {
        if ($currentStudent !== '') {
          echo '<tr>';
          echo '<td>' . $uniqueId++ . '</td>'; 
          echo '<td>' . $currentStudent . '</td>';

          foreach ($dates as $formattedDate) {
            $attendanceStatus = $attendanceRows[$formattedDate] ?? '';
            $colorClass = ''; 

            if ($attendanceStatus === 'Present') {
                $colorClass = 'present';
            } elseif ($attendanceStatus === 'Absent') {
                $colorClass = 'absent';
            } elseif ($attendanceStatus === 'Late') {
                $colorClass = 'late';
            } elseif ($attendanceStatus === 'Sick') {
                $colorClass = 'sick';
            } 

            echo '<td class="status ' . $colorClass . '">' . $attendanceStatus . '</td>';
        }
        // echo '<th>Actions</th>';
        echo '</tr>';
    }
        $currentStudent = $attendanceRow['student_name'];
        $attendanceRows = array();
      }

      $attendanceRows[$attendanceRow['attendance_date']] = $attendanceRow['attendance_status'];
    }

    if ($currentStudent !== '') {
      echo '<tr>';
      echo '<td>' . $currentStudent . '</td>';

      foreach ($dates as $formattedDate) {
        echo '<td class="status">' . ($attendanceRows[$formattedDate] ?? '') . '</td>';
      }

      echo '</tr>';
    }
    echo '</table>';
  }

  ?>
  
  <button class="previous"><a href="dateform.php" role="button" style="text-decoration: none; color:white;">Back</a></button>
</body>
</html>