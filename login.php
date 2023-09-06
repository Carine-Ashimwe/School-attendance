<?php
 session_start();
include "functions.php";

if (isset($_SESSION["user"])) {
    header("Location: attendance.php");
    exit(); 
}

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($sql);

    if (mysqli_num_rows($result) === 1) {
        $user = $result->fetch_assoc();
        if ($password == $user["password"]) {
            $_SESSION["user"] = "yes";
            $_SESSION["id"] = $user["id"];
            header("Location: dateform.php");
            exit();
        } else {
            $error = "Password does not match";
        }
    } else {
        $error = "Username does not exist";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>LOGIN</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles/style.css">
</head>
<body>
     <form action="login.php" method="post">
        <h2>LOGIN</h2>
        <?php if (!empty($error)) { 
            ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <label>Username</label>
        <input type="text" name="username" placeholder="User Name"><br>
        <label>Password</label>
        <input type="password" name="password" placeholder="Password"><br> 
        <button type="submit" name="submit">Login</button>

        <p class="reg">Don't have an account?<br> <a href="register.php">Register here.</a></p>
     </form>
</body>
</html>
