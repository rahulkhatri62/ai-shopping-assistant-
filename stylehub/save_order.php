<?php
session_start();
include("db.php");

// User id from session or fallback to guest (1)
$user_id = (int)($_SESSION['user_id'] ?? $_SESSION['id'] ?? 1);

// Cart se data lena
$product_name = mysqli_real_escape_string($conn, trim($_POST['product_name'] ?? ''));
$quantity = (int)($_POST['quantity'] ?? 0);
$total_amount = (float)($_POST['total_amount'] ?? 0);

if (empty($product_name) || $quantity <= 0) {
    echo "<script>
            alert('Your cart is empty or invalid!');
            window.location='cart.php';
          </script>";
    exit();
}

// Order save karna (`order` is a reserved SQL keyword, so backticks are required)
$sql = "INSERT INTO `order` (user_id, product_name, quantity, total_amount)
VALUES ('$user_id', '$product_name', '$quantity', '$total_amount')";

if(mysqli_query($conn, $sql)){
    echo "<script>
            localStorage.removeItem('stylehub_cart');
            localStorage.removeItem('cart');
            let userName = localStorage.getItem('name') || 'Guest';
            localStorage.removeItem('cart_' + userName);
            localStorage.removeItem('cart_Guest');
            alert('Order Placed Successfully!');
            window.location='cart.php';
          </script>";
}else{
    echo "Error: " . mysqli_error($conn);
}
?>