<?php
if(isset($_POST['login']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username == "admin" && $password == "2710")
    {
        header("Location: dashboard.php");
        exit(); 
    }
    else
    {
        echo "<script>alert('Invalid Admin Username or Password');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
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
    box-shadow:0 0 10px rgba(0,0,0,0.2);
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
}

button:hover{
    background:#e65c00;
}
</style>
</head>

<body>

<div class="login-box">
    <h2>Admin Login</h2>

    <form method="POST">

        <input type="text" name="username" placeholder="Admin Username" required>

        <input type="password" name="password" placeholder="Admin Password" required>

        <button type="submit" name="login">Login</button>

    </form>
</div>

</body>
</html>