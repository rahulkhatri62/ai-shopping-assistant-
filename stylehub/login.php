<?php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include("db.php");

if(isset($_POST['login']))
{
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $username_safe = mysqli_real_escape_string($conn, $username);
    $password_safe = mysqli_real_escape_string($conn, $password);

    $sql = "SELECT * FROM users WHERE name='$username_safe' AND password='$password_safe'";
    $result = mysqli_query($conn, $sql);

    if($result && mysqli_num_rows($result) > 0)
    {
        $user = mysqli_fetch_assoc($result);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];

        header("Location: index.php");
        exit();
    }
    else
    {
        echo "<script>alert('Invalid Username or Password');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#ddaeae;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.login-box{
    background:#cd7171;
    padding:30px;
    width:350px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(209, 136, 207, 0.2);
}

h2{
    text-align:center;
    margin-bottom:20px;
    color:white;
}

input{
    width:100%;
    padding:10px;
    margin:10px 0;
    border:1px solid #ccc;
    border-radius:5px;
    font-size:14px;
}

button{
    width:100%;
    padding:10px;
    background:#ff6600;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
    font-size:16px;
    font-weight:bold;
    margin-top:10px;
}

button:hover{
    background:#e65c00;
}
</style>
</head>

<body>

<div class="login-box">
    <h2>Clothing Store Login</h2>

<form id="loginForm" method="POST" action="login.php">
    <input type="text" id="usernameInput" name="username" placeholder="Enter username" required autocomplete="username">
    <input type="password" id="passwordInput" name="password" placeholder="Enter password" required autocomplete="current-password">
    <button type="submit" id="loginButton" name="login">Login</button>
    <p style="text-align:center; margin-top:15px; color:white;">
      Don't have an account?
      <a href="registration.php" style="color:#ffe4e1; text-decoration:none; font-weight:bold;">Register</a>
    </p>
</form>

</div>

<script>
// Prevent Enter on username from submitting form or redirecting; focus password instead
const usernameField = document.getElementById('usernameInput');
const passwordField = document.getElementById('passwordInput');

usernameField.addEventListener('keydown', function(event) {
    if (event.key === 'Enter' || event.keyCode === 13) {
        event.preventDefault();
        event.stopPropagation();
        passwordField.focus();
        return false;
    }
});
</script>

</body>
</html>