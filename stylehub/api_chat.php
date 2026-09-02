<?php
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-cache, no-store, must-revalidate");
include("db.php");

$input = json_decode(file_get_contents("php://input"), true);
$message = trim($input['message'] ?? $_GET['message'] ?? '');

if (empty($message)) {
    echo json_encode([
        "status" => true,
        "reply" => "Hello! 👋 I am StyleBot, your AI Personal Shopper. Tell me what kind of kurti you are looking for, or your preferred occasion and budget!",
        "products" => []
    ]);
    exit();
}

$lower = strtolower($message);

// 1. FAQ Intent Matching
if (preg_match('/(deliver|ship|when|fast|reach|courier)/i', $lower)) {
    echo json_encode([
        "status" => true,
        "reply" => "🚚 <strong>Express Shipping:</strong> We deliver all across India within <strong>2 to 4 business days</strong>. Shipping is <strong>100% FREE</strong> on every order!",
        "products" => []
    ]);
    exit();
}

if (preg_match('/(return|exchange|refund|replace)/i', $lower)) {
    echo json_encode([
        "status" => true,
        "reply" => "🔄 <strong>Easy Returns:</strong> We have a <strong>7-day hassle-free return and exchange policy</strong>. If the fit isn't perfect, we arrange a pickup and swap it free of charge!",
        "products" => []
    ]);
    exit();
}

if (preg_match('/(coupon|discount|offer|promo|code)/i', $lower)) {
    echo json_encode([
        "status" => true,
        "reply" => "🎉 <strong>Active Coupons:</strong><br>• Use <code>STYLE20</code> for <strong>20% OFF</strong> on orders above ₹500.<br>• Use <code>FESTIVE100</code> for <strong>₹100 Flat OFF</strong> on orders above ₹999!<br>Enter either code at checkout.",
        "products" => []
    ]);
    exit();
}

if (preg_match('/(payment|cod|cash on delivery|upi|card|pay)/i', $lower)) {
    echo json_encode([
        "status" => true,
        "reply" => "💳 <strong>Safe Payment Modes:</strong> We support <strong>Cash on Delivery (COD)</strong>, UPI (Google Pay, PhonePe, Paytm), and Credit/Debit Cards with 256-bit encryption.",
        "products" => []
    ]);
    exit();
}

if (preg_match('/(size|fit|measurement|chart)/i', $lower)) {
    echo json_encode([
        "status" => true,
        "reply" => "📏 <strong>Standard Indian Sizing:</strong><br>• <strong>S</strong> (Bust 36\") • <strong>M</strong> (Bust 38\")<br>• <strong>L</strong> (Bust 40\") • <strong>XL</strong> (Bust 42\") • <strong>XXL</strong> (Bust 44\")<br>You can choose your exact size when adding an item to the bag!",
        "products" => []
    ]);
    exit();
}

// 2. Product Search Intent
$conditions = [];
$matchedIntents = [];

// Normalize currency and query
$cleanText = preg_replace('/[₹]|rs\.?|inr/ui', '', $lower);

// Price detection
if (preg_match('/(?:under|below|less than|within|budget of)\s*(\d+)/i', $cleanText, $matches)) {
    $maxPrice = (float)$matches[1];
    $conditions[] = "price <= $maxPrice";
    $matchedIntents[] = "budget under ₹$maxPrice";
} elseif (preg_match('/(\d+)\s*(?:and|to|-)\s*(\d+)/i', $cleanText, $matches)) {
    $min = min((float)$matches[1], (float)$matches[2]);
    $max = max((float)$matches[1], (float)$matches[2]);
    $conditions[] = "price BETWEEN $min AND $max";
    $matchedIntents[] = "price between ₹$min and ₹$max";
} elseif (preg_match('/(?:under|below)\s*(\d+)/i', $lower, $matches)) {
    $maxPrice = (float)$matches[1];
    $conditions[] = "price <= $maxPrice";
    $matchedIntents[] = "budget under ₹$maxPrice";
} elseif (strpos($lower, 'cheap') !== false || strpos($lower, 'budget') !== false || strpos($lower, 'affordable') !== false) {
    $conditions[] = "price <= 1000";
    $matchedIntents[] = "budget-friendly options";
} elseif (strpos($lower, 'premium') !== false || strpos($lower, 'expensive') !== false || strpos($lower, 'luxury') !== false) {
    $conditions[] = "price >= 1200";
    $matchedIntents[] = "premium picks";
}

// Occasion / Category
if (strpos($lower, 'party') !== false || strpos($lower, 'wedding') !== false || strpos($lower, 'reception') !== false) {
    $conditions[] = "(category = 'Party Wear' OR fabric LIKE '%Silk%')";
    $matchedIntents[] = "party and celebration wear";
} elseif (strpos($lower, 'college') !== false || strpos($lower, 'daily') !== false || strpos($lower, 'office') !== false || strpos($lower, 'casual') !== false) {
    $conditions[] = "(category = 'Daily Wear' OR fabric LIKE '%Cotton%')";
    $matchedIntents[] = "daily comfort wear";
} elseif (strpos($lower, 'festive') !== false || strpos($lower, 'diwali') !== false || strpos($lower, 'puja') !== false || strpos($lower, 'eid') !== false) {
    $conditions[] = "(category = 'Festive' OR name LIKE '%Embroidered%')";
    $matchedIntents[] = "festive occasions";
} elseif (strpos($lower, 'anarkali') !== false || strpos($lower, 'flair') !== false || strpos($lower, 'flare') !== false) {
    $conditions[] = "category = 'Anarkali'";
    $matchedIntents[] = "Anarkali flowy silhouette";
}

// Fabric
if (strpos($lower, 'silk') !== false) {
    $conditions[] = "fabric LIKE '%Silk%'";
    $matchedIntents[] = "silk fabric";
} elseif (strpos($lower, 'cotton') !== false) {
    $conditions[] = "fabric LIKE '%Cotton%'";
    $matchedIntents[] = "pure cotton fabric";
}

$whereSql = "";
if (count($conditions) > 0) {
    $whereSql = "WHERE " . implode(" AND ", $conditions);
} else {
    // Fallback search keywords
    $safeWord = mysqli_real_escape_string($conn, $lower);
    $whereSql = "WHERE name LIKE '%$safeWord%' OR category LIKE '%$safeWord%' OR description LIKE '%$safeWord%' OR fabric LIKE '%$safeWord%'";
}

$sql = "SELECT id, name, category, price, original_price, discount_percent, image, fabric, rating FROM products $whereSql LIMIT 3";
$res = mysqli_query($conn, $sql);
$products = [];

while ($row = mysqli_fetch_assoc($res)) {
    $products[] = $row;
}

// If no direct matches, fallback to top 2 bestsellers
if (count($products) === 0) {
    $fallbackRes = mysqli_query($conn, "SELECT id, name, category, price, original_price, discount_percent, image, fabric, rating FROM products ORDER BY rating DESC LIMIT 2");
    while ($row = mysqli_fetch_assoc($fallbackRes)) {
        $products[] = $row;
    }
    $reply = "I couldn't find exact matches for your query, but here are our <strong>top-rated bestselling kurtis</strong> that you'll love:";
} else {
    $intentDesc = count($matchedIntents) > 0 ? " for <em>" . implode(", ", $matchedIntents) . "</em>" : "";
    $reply = "✨ Here are my top recommended kurtis$intentDesc tailored just for you:";
}

echo json_encode([
    "status" => true,
    "reply" => $reply,
    "products" => $products
]);
?>
