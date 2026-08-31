<?php
include("db.php");

if(isset($_POST['register']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users(name,email,password)
    VALUES('$name','$email','$password')";

    if(mysqli_query($conn,$sql))
    {
        header("Location: login.php");
        exit();
    }
    else
    {
        echo "Registration Failed";
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

   <input type="text" name="fullname" placeholder="Full Name" required>

   <input type="email" name="email" placeholder="email" required>

   <input type="text" name="Username" placeholder="Username" required>

   <input type="password" name="password" placeholder="password" required>

   <input type="password" name="confirm_password" placeholder="confirm password" required>

<button type="submit" name="register">register</button>
<p style="text-align:center;
      margin-top:15px">
      already have an account?
      <a href="login.php" style="color:pink; text-decoration:none;
      font-weight:bold;">login</a>
    </p>
</form>

</body>
</html>
