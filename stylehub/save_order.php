<?php
session_start();
include("db.php");

// User id from session or fallback to guest (1)
$user_id = (int)($_SESSION['user_id'] ?? $_SESSION['id'] ?? 1);

// Cart & Customer details
$product_name = mysqli_real_escape_string($conn, trim($_POST['product_name'] ?? ''));
$quantity = (int)($_POST['quantity'] ?? 0);
$total_amount = (float)($_POST['total_amount'] ?? 0);
$discount_amount = (float)($_POST['discount_amount'] ?? 0);
$coupon_code = mysqli_real_escape_string($conn, trim($_POST['coupon_code'] ?? ''));
$customer_name = mysqli_real_escape_string($conn, trim($_POST['customer_name'] ?? ($_SESSION['name'] ?? 'Guest Shopper')));
$phone = mysqli_real_escape_string($conn, trim($_POST['phone'] ?? ''));
$shipping_address = mysqli_real_escape_string($conn, trim($_POST['shipping_address'] ?? ''));
$city = mysqli_real_escape_string($conn, trim($_POST['city'] ?? ''));
$pincode = mysqli_real_escape_string($conn, trim($_POST['pincode'] ?? ''));
$payment_method = mysqli_real_escape_string($conn, trim($_POST['payment_method'] ?? 'Cash On Delivery'));

if (empty($product_name) || $quantity <= 0) {
    echo "<script>
            alert('Your cart is empty or invalid!');
            window.location='cart.php';
          </script>";
    exit();
}

$sql = "INSERT INTO `order` 
        (user_id, customer_name, phone, shipping_address, city, pincode, product_name, quantity, total_amount, discount_amount, coupon_code, payment_method, status)
        VALUES 
        ('$user_id', '$customer_name', '$phone', '$shipping_address', '$city', '$pincode', '$product_name', '$quantity', '$total_amount', '$discount_amount', '$coupon_code', '$payment_method', 'Pending')";

if(mysqli_query($conn, $sql)){
    echo "<script>
            localStorage.removeItem('stylehub_cart');
            localStorage.removeItem('cart');
            let userName = localStorage.getItem('name') || 'Guest';
            localStorage.removeItem('cart_' + userName);
            localStorage.removeItem('cart_Guest');
            alert('🎉 Order Placed Successfully! Thank you for shopping with StyleHub.');
            window.location='my_orders.php';
          </script>";
}else{
    echo "Error saving order: " . mysqli_error($conn);
}
?>
