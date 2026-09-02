<?php
include("db.php");

if(isset($_POST['register']))
{
    $name = trim($_POST['name'] ?? $_POST['Username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (empty($name) || empty($email) || empty($password)) {
        echo "<script>alert('Please fill in all required fields');</script>";
    } elseif ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match');</script>";
    } else {
        $name_safe = mysqli_real_escape_string($conn, $name);
        $email_safe = mysqli_real_escape_string($conn, $email);
        $password_safe = mysqli_real_escape_string($conn, $password);

        $sql = "INSERT INTO users(name,email,password)
        VALUES('$name_safe','$email_safe','$password_safe')";

        if(mysqli_query($conn, $sql))
        {
            echo "<script>
                    alert('Registration Successful! Please login.');
                    window.location='login.php';
                  </script>";
            exit();
        }
        else
        {
            echo "<script>alert('Registration Failed: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registration</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#ffe4e1;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.container{
    background:white;
    padding:30px;
    width:350px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.2);
}

h2{
    text-align:center;
    color:#8b3a3a;
    margin-bottom:20px;
}

input{
    width:100%;
    padding:10px;
    margin:10px 0;
    border:1px solid #ccc;
    border-radius:5px;
}

button{
    width:100%;
    padding:12px;
    background:#8b3a3a;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

button:hover{
    background:#b84c4c;
}

p{
    text-align:center;
    margin-top:15px;
}

a{
    color:#8b3a3a;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="container">
<h2>User Registration</h2>

<form method="POST">

   <input type="text" name="name" placeholder="Username" required>

   <input type="email" name="email" placeholder="Email" required>

   <input type="password" name="password" placeholder="Password" required>

   <input type="password" name="confirm_password" placeholder="Confirm Password" required>

<button type="submit" name="register">Register</button>
<p style="text-align:center;
      margin-top:15px">
      Already have an account?
      <a href="login.php" style="color:#8b3a3a; text-decoration:none;
      font-weight:bold;">Login</a>
    </p>
</form>

</body>
</html>
