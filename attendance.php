<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");

}
  
  include "functions.php";
  $successMessage = '';
  $unmarkedResult = null; // Initialize the variable

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceDate = mysqli_real_escape_string($mysqli, $_POST['attendance_date']);
    $currentDate = date('Y-m-d');
    if ($attendanceDate > $currentDate) {
        echo "<script>alert('The selected date is in the future. Please select a valid date.'); window.history.back();</script>";
    } else {
        // echo "Invalid data";
    }

    if (isset($_POST['add_attendance'])) { // Check if Add Attendance button was clicked
      $checkQuery = "SELECT COUNT(*) as count FROM attendance WHERE attendance_date = '$attendanceDate'";
      $checkResult = mysqli_query($mysqli, $checkQuery);
      $checkData = mysqli_fetch_assoc($checkResult);

      if ($checkData['count'] > 0) {
          $allMarkedQuery = "SELECT COUNT(*) as count FROM attendance WHERE attendance_date = '$attendanceDate' AND attendance_status = ''";
          $allMarkedResult = mysqli_query($mysqli, $allMarkedQuery);
          $allMarkedData = mysqli_fetch_assoc($allMarkedResult);

          if ($allMarkedData['count'] == 0) {
              echo "<script>alert('All attendance statuses are marked. Access view_attendance.php for editing.');</script>";
          } else {
              $unmarkedQuery = "SELECT * FROM students WHERE id IN (SELECT student_id FROM attendance WHERE attendance_date = '$attendanceDate' AND attendance_status = '')";
              $unmarkedResult = mysqli_query($mysqli, $unmarkedQuery);
          }
      } else {
          header("Location: attendance.php?date=$attendanceDate");
      }
  }


    if (isset($_POST['attendance_date'], $_POST['attendance_status'])) {
      $attendance_code = time();

foreach ($_POST['attendance_status'] as $studentId => $attendanceStatus) {
  $studentId = mysqli_real_escape_string($mysqli, $studentId);
  $attendanceStatus = mysqli_real_escape_string($mysqli, $attendanceStatus);

  $updateSql = "INSERT INTO attendance (student_id, user_id, attendance_date, attendance_status, attendance_code) 
                VALUES ('$studentId', '{$_SESSION["id"]}', '$attendanceDate', '$attendanceStatus', '$attendance_code')";
  $result = $mysqli->query($updateSql);

  if ($result) {
      $successMessage = "Attendance data saved successfully.";
  } else {
      $successMessage = "Error: Unable to save attendance data.";
  }
}
  }
  $unmarkedQuery = "SELECT * FROM students WHERE id NOT IN (SELECT student_id FROM attendance WHERE attendance_date = '$attendanceDate' AND attendance_status != '')";
 $unmarkedResult = mysqli_query($mysqli, $unmarkedQuery); 
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Student Attendance</title>
  <link rel="stylesheet" href="styles/attendance.css">

</head>
<body>
<header>
    <span class="school-name">STUDENT ATTENDANCE MANAGEMENT SYSTEM</span>
    <a href="logout.php" class="logout-link">Logout</a>
  </header>
  <!-- <h1 class="h1">Student Attendance</h1> -->
  <div class="add-buttons">
  <button class="addnew"><a href="student.php" role="button"style="text-decoration: none;color:white;">Add New student</a></button>
</div>
    <form method="post" action="attendance.php">
    <div class="date">
        <?php
        if (isset($_POST['attendance_date'])) {
            $selectedDate = $_POST['attendance_date'];
            echo "<input type='hidden' name='attendance_date' id='date' class='demoInputBox' value='$selectedDate'>";
        } else {
            echo "<input type='hidden' name='attendance_date' id='date' class='demoInputBox'>";
        }
        ?>
    </div>
  <div class="search">
    <input type="text" id="search_input" placeholder="Search by Name" onkeyup="searchTable()">
  </div>
  <table id="students_table" cellspacing="1">
    <thead>
    <tr>
      <th>#</th> 
      <th>Name</th>
      <th>RollNumber</th>
      <th>Attendance Status</th>
    </tr>
    </thead>
    <tbody>
    <?php 
        if ($unmarkedResult) { 
          while ($row = $unmarkedResult->fetch_assoc()) {
          echo "
        <tr>
          <td>{$row['id']}</td>
          <td>{$row['name']}</td>
          <td>{$row['roll_number']}</td>
          <td>
            <input type='hidden' name='student_id[{$row['id']}]' value='{$row['id']}' />
            <select name='attendance_status[{$row['id']}]'>
              <option value='' style='display: none;'>Select status</option>
              <option value='Present'>Present</option>
              <option value='Absent'>Absent</option>
              <option value='Late'>Late</option>
              <option value='Sick'>Sick</option>
            </select>
          </td>
        </tr>";
      }
    }
      ?>
    </tbody>
  </table>
  <div class="success-message"><?php echo $successMessage; ?></div>
  <button class="previous" ><a href="dateform.php" role="button"style="text-decoration: none;
    color:white;">Back</a></button>
  <button class="mark" id="save_button" type="submit" name="save_button" onclick="return confirmBeforeSubmit();">Save Attendance</button>
  </form>

<script>
  const form = document.querySelector('form');
  const saveButton = document.getElementById('save_button');
  const table = document.getElementById('students_table');
  const rows = table.getElementsByTagName('tr');

  form.addEventListener('change', function (event) {
        if (event.target.tagName === 'SELECT') {
            saveButton.style.display = 'block';
        }
    });
    function confirmBeforeSubmit() {
    if (confirm("Are you sure you want to save attendance?")) {
      return true;
    } else {
      return false;
    }
  }

    function resetSearch() {
      const searchInput = document.getElementById('search_input');
      searchInput.value = '';
      searchTable();
    }

    function searchTable() {
      const searchInput = document.getElementById('search_input');
      const filter = searchInput.value.toUpperCase();

      for (let i = 1; i < rows.length; i++) {
        const nameColumn = rows[i].getElementsByTagName('td')[1];
        const nameValue = nameColumn.textContent || nameColumn.innerText;

        if (nameValue.toUpperCase().indexOf(filter) > -1) {
          rows[i].style.display = 'table-row';
        } else {
          rows[i].style.display = 'none';
        }
      }
      currentRow = 1;
    }

  </script>
</body>
</html>