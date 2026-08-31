<?php
session_start();
include("db.php");

// Demo user id
$user_id = $_SESSION['user_id'] ?? $_SESSION['id'] ?? 1;

// Cart se data lena
$product_name = $_POST['product_name'] ?? '';
$quantity = $_POST['quantity'] ?? 0;
$total_amount = $_POST['total_amount'] ?? 0;

// Order save karna
$sql = "INSERT INTO order (user_id, product_name, quantity, total_amount)
VALUES ('$user_id', '$product_name', '$quantity', '$total_amount')";

if(mysqli_query($conn, $sql)){
    echo "<script>
            alert('Order Placed Successfully!');
            window.location='cart.php';
          </script>";
}else{
    echo "Error: " . mysqli_error($conn);
}
?>