<?php
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-cache, no-store, must-revalidate");
include("db.php");

$code = strtoupper(trim($_POST['code'] ?? $_GET['code'] ?? ''));
$subtotal = (float)($_POST['subtotal'] ?? $_GET['subtotal'] ?? 0);

if (empty($code)) {
    echo json_encode(["status" => false, "msg" => "Please enter a coupon code."]);
    exit();
}

$codeSafe = mysqli_real_escape_string($conn, $code);
$q = mysqli_query($conn, "SELECT * FROM coupons WHERE code = '$codeSafe' AND is_active = 1 LIMIT 1");

if ($coupon = mysqli_fetch_assoc($q)) {
    $minAmount = (float)$coupon['min_order_amount'];
    if ($subtotal < $minAmount) {
        echo json_encode([
            "status" => false, 
            "msg" => "Coupon requires a minimum cart value of ₹" . number_format($minAmount, 2)
        ]);
        exit();
    }

    $discount = 0;
    if ($coupon['discount_type'] === 'percent') {
        $discount = ($subtotal * (float)$coupon['discount_value']) / 100;
    } else {
        $discount = (float)$coupon['discount_value'];
    }

    if ($discount > $subtotal) {
        $discount = $subtotal;
    }

    $newTotal = $subtotal - $discount;

    echo json_encode([
        "status" => true,
        "code" => $coupon['code'],
        "discount_type" => $coupon['discount_type'],
        "discount_value" => (float)$coupon['discount_value'],
        "discount_amount" => round($discount, 2),
        "new_total" => round($newTotal, 2),
        "msg" => "🎉 Coupon " . $coupon['code'] . " applied! You saved ₹" . number_format($discount, 2)
    ]);
} else {
    echo json_encode([
        "status" => false,
        "msg" => "Invalid or expired coupon code. Try STYLE20 or FESTIVE100"
    ]);
}
?>
