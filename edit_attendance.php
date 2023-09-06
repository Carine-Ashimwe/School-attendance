<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");

}
  
  include "functions.php";
  $successMessage = '';
  
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_attendance'])) {
    if (isset($_POST['attendance_date'], $_POST['attendance_status'])) {
        $attendanceDate = mysqli_real_escape_string($mysqli, $_POST['attendance_date']);
        $attendance_code = time();

        // Check if attendance for this date already exists
        $existingAttendanceQuery = "SELECT * FROM attendance WHERE attendance_date = '$attendanceDate'";
        $existingAttendanceResult = mysqli_query($mysqli, $existingAttendanceQuery);

        if (mysqli_num_rows($existingAttendanceResult) > 0) {
            echo "<script>
                  fetchAttendanceData('$attendanceDate');
                </script>";
        } else {
            echo "<script>
                  fetchStudentList();
                </script>";
        }

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
            // Clear marked student row from the table
            echo "<script>
                $('#row_$studentId').remove();
            </script>";
}
        }
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Student Attendance</title>
  <link rel="stylesheet" href="styles/attendance.css">
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>
<body>
<header>
    <span class="school-name">STUDENT ATTENDANCE MANAGEMENT SYSTEM</span>
    <a href="logout.php" class="logout-link">Logout</a>
  </header>

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
    <tbody id="students_table_body">

    </tbody>
  </table>
  <div id="attendanceData"></div>
  <div class="success-message"><?php echo $successMessage; ?></div>
  <button class="previous" ><a href="dateform.php" role="button"style="text-decoration: none;
    color:white;">Back</a></button>
  <button class="mark" id="save_button" type="submit" name="save_button">Save Attendance</button>
  </form>
<script>
  const form = document.querySelector('form');
  const table = document.getElementById('students_table');
  const rows = table.getElementsByTagName('tr');
  const attendanceDataDiv = document.getElementById('attendanceData');


form.addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent form submission
    const formData = new FormData(form);
            formData.append('add_attendance', '1'); 

            const selectedDate = formData.get('attendance_date');

            fetchAttendanceData(formData, selectedDate); 
            
   });
   function fetchAttendanceData(formData,selectedDate) {
    $.post('fetch_attendance.php', formData)
        .done(function (data) {
            console.log('Response from fetch_attendance.php:', data);
            const attendanceData = JSON.parse(data);
            console.log('Parsed attendanceData:', attendanceData);
            // Call a function to display attendance data in the table
             displayAttendanceData(attendanceData);
             fetchUnmarkedStudents(selectedDate);

        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.error('Error fetching attendance data:', textStatus, errorThrown);
        });
}
function fetchUnmarkedStudents(selectedDate) {
    $.post('unmarked.php', { attendance_date: selectedDate })
        .done(function(data) {
            const unmarkedStudentList = JSON.parse(data);
            populateStudentTable(unmarkedStudentList);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching unmarked student data:', textStatus, errorThrown);
        });
}

function fetchStudentList() {
    $.get('fetch_students.php', function (data) {
            const studentList = JSON.parse(data);
        populateStudentTable(studentList);
      }).fail(function(jqXHR, textStatus, errorThrown) {
    console.error('Error fetching student data:', textStatus, errorThrown);
});
function populateStudentTable(studentList) {
    const studentsTableBody = document.getElementById('students_table_body');
    studentsTableBody.innerHTML = ''; // Clear existing data

    studentList.forEach(function(student) {
        const row = `
            <tr id="row_${student.id}">
                <td>${student.id}</td>
                <td>${student.name}</td>
                <td>${student.roll_number}</td>
                <td>
                    <input type="hidden" name="student_id[${student.id}]" value="${student.id}" />
                    <select name="attendance_status[${student.id}]">
                        <option value="" style="display: none;">Select status</option>
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Late">Late</option>
                        <option value="Sick">Sick</option>
                    </select>
                </td>
            </tr>
        `;
        studentsTableBody.innerHTML += row;
    });
}

// Call fetchStudentList() when the page loads to populate the table initially
$(document).ready(function() {
    fetchStudentList();
});

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

