<?php
        // MySQL database configuration
        $servername = "localhost";
        $username = "root"; 
        $password = "";
        $database = "newtrial"; 
        
        $mysqli = new mysqli($servername, $username, $password, $database);
        
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        if (isset($_GET['delete'], $_GET['date'])) {

          $deleteId = mysqli_real_escape_string($mysqli, $_GET['delete']);
          $deleteDate = mysqli_real_escape_string($mysqli, $_GET['date']);
      
          $DeleteQuery = "UPDATE students SET deleted = 1 WHERE id = '$deleteId'";
          $DeleteResult = mysqli_query($mysqli, $DeleteQuery);
      
          if ($DeleteResult) {
            // Handle successful deletion
            echo "Student deleted successfully.";
        } else {
            // Handle error
            echo "Error: " . mysqli_error($mysqli);
        }
    }
  ?>
