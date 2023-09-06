<?php
include "functions.php";

// Add a new user
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
    $sql = "INSERT INTO users(username, email, password) VALUES ('$username','$email','$password')";
    mysqli_query($mysqli, $sql);

    if ($result) {
        $successMessage = "Student added successfully!";
        echo "<script>alert('$successMessage');</script>";
    } else {
        $successMessage = "Error: Unable to add student.";
        echo "<script>alert('$successMessage');</script>";
    }

    header('Location: attendance.php');
  }
?>
<!DOCTYPE html>
<html>
<head>
    <title>REGISTER</title>
    <link rel="stylesheet" type="text/css" href="styles/style.css">

</head>
<body>
     <form action="register.php" method="post">
        <h2>REGISTER</h2>
        <?php

         if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <label>User Name</label>
        <input type="text" name="username" placeholder="User Name"><br>
        <label>Email</label>
        <input type="email" name="email" placeholder="email"><br>        
        <label>Password</label>
        <input type="password" name="password" placeholder="Password"><br> 
        <button type="submit" name='submit'>Register</button>
        <p class="reg">Already have an account?<br> <a href="login.php">Login here.</a></p>
     </form>
</body>
</html>