<?php
include("db.php");

$users = mysqli_query($conn, "SELECT name FROM users");

$user = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$userCount = mysqli_fetch_assoc($user);

$order = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `order`");
$orderCount = mysqli_fetch_assoc($order);

$sales = mysqli_query($conn, "SELECT SUM(total_amount) AS total_sales FROM `order`");
$salesCount = mysqli_fetch_assoc($sales);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#f8d7da;
}

header{
    background:#8b3a3a;
    color:white;
    padding:20px;
    text-align:center;
}

.container{
    width:90%;
    margin:30px auto;
}

.cards{
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:20px;
}

.card{
    width:23%;
    background:white;
    padding:20px;
    border-radius:10px;
    text-align:center;
    box-shadow:0 4px 10px rgba(0,0,0,0.2);
}

.card h2{
    color:#8b3a3a;
}

.buttons{
    margin-top:40px;
    display:flex;
    justify-content:center;
    gap:20px;
    flex-wrap:wrap;
}

button{
    padding:12px 25px;
    border:none;
    border-radius:8px;
    background:#8b3a3a;
    color:white;
    cursor:pointer;
}

button:hover{
    background:#b84c4c;
}
</style>
</head>

<body>

<header>
<h1>Admin Dashboard</h1>
</header>

<div class="container">

<div class="cards">

<div class="card">
   <h2>Total Users</h2>
   <h1><?php echo $userCount['total']; ?></h1>
</div>

<div class="card">
  <h2>Total orders</h2>
  <h1><?php echo $orderCount['total']; ?></h1>
</div>

<div class="card">
  <h2>Total sales</h2>
  <h1><?php echo $salesCount['total_sales']; ?></h1>
</div>

</div>

<div style="text-align:right;margin:30px;">

   <button onclick="location.href='admin_login.php'">logout</button>
   <button onclick="location.href='index.php'">home</button>
   <button onclick="location.href='index.php'">product</button>
 </div>


<div class="card" style="width:100%; margin-top:30px;">
<h3>Registered Users</h3>

<table style="width:100%; border-collapse:collapse; margin-top:20px;">
<tr style="background:#8b3a3a; color:white;">
    <th>ID</th>
    <th style="padding:12px;">Name</th>
    <th>Email</th>
    <th>Total Orders</th>
    <th>Total Purchase</th>
</tr>

<?php
$userData = mysqli_query($conn,"
SELECT
users.id,
users.name,
users.email,
COUNT(`order`.id) AS total_order,
IFNULL(SUM(order.total_amount),0) AS total_purchase
FROM users
LEFT JOIN `order` ON users.id = `order`.user_id
GROUP BY users.id
");

while($row=mysqli_fetch_assoc($userData))
{
?>
<tr align="center">
<td><?php echo $row['id']; ?></td>    
<td style="padding:10px;"><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['total_order']; ?></td>
<td>₹<?php echo $row['total_purchase']; ?></td>
</tr>
<?php } ?>

</table>

</div>


</body>
</html>