<?php
session_start();
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
}

input{
    width:100%;
    padding:10px;
    margin:10px 0;
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
}

button:hover{
    background:#e65c00;
}
</style>
</head>

<body>

<div class="login-box">
    <h2>clothing store Login</h2>

<form method="POST">
    <input type="text" name="username" placeholder="enter username" required onkeydown="if(event.key=='Enter'){event.preventDefault(); document.getElementById('password').focus();}">
    <input type="password" name="password" id="password" placeholder="enter password" required>
    <button type="submit" name="login">login</button>
    <p style="text-align:center;
      margin-top:15px">
      Don't have an account?
      <a href="registration.php" style="color:pink; text-decoration:none;
      font-weight:bold;">register</a>
    </p>
</form>

</div>

</body>
</html>