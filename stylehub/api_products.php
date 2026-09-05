<?php
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-cache, no-store, must-revalidate");
include("db.php");

$defaultProducts = [
    [
        'id' => 1,
        'name' => 'Casual Daily Cotton Kurti',
        'category' => 'Daily Wear',
        'price' => 999.00,
        'original_price' => 1499.00,
        'discount_percent' => 33,
        'image' => 'images/kurti1.jpeg',
        'description' => 'Breathable pure cotton straight kurti featuring elegant neck piping. Ideal for daily office, college, and casual outings.',
        'fabric' => '100% Pure Cotton',
        'rating' => 4.8,
        'reviews_count' => 142,
        'badge' => 'Best Value'
    ],
    [
        'id' => 2,
        'name' => 'Royal Party Silk Kurti',
        'category' => 'Party Wear',
        'price' => 1499.00,
        'original_price' => 2299.00,
        'discount_percent' => 35,
        'image' => 'images/kurti2.jpeg',
        'description' => 'Luxurious chanderi silk kurti adorned with delicate zari embroidery. Designed for receptions, evening soirees, and weddings.',
        'fabric' => 'Chanderi Silk',
        'rating' => 4.9,
        'reviews_count' => 230,
        'badge' => 'Bestseller'
    ],
    [
        'id' => 3,
        'name' => 'Festive Embroidered Kurti',
        'category' => 'Festive',
        'price' => 1199.00,
        'original_price' => 1899.00,
        'discount_percent' => 37,
        'image' => 'images/kurti3.jpeg',
        'description' => 'Vibrant festive flared kurti with intricate hand-thread needlework. Perfect for Diwali, Puja ceremonies, and family gatherings.',
        'fabric' => 'Rayon Silk Blend',
        'rating' => 4.7,
        'reviews_count' => 98,
        'badge' => 'Trending'
    ],
    [
        'id' => 4,
        'name' => 'Printed Floral Anarkali Kurti',
        'category' => 'Anarkali',
        'price' => 899.00,
        'original_price' => 1299.00,
        'discount_percent' => 31,
        'image' => 'images/kurti4.jpeg',
        'description' => 'Flowy floral printed Anarkali kurti crafted from soft modal fabric. Lightweight, stylish, and comfortable for all-day wear.',
        'fabric' => 'Modal Cotton',
        'rating' => 4.6,
        'reviews_count' => 64,
        'badge' => 'New Arrival'
    ]
];

$action = $_GET['action'] ?? 'list';

if ($action === 'detail') {
    $id = (int)($_GET['id'] ?? 0);
    if (isset($conn) && $conn) {
        $query = @mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
        if ($query && ($product = mysqli_fetch_assoc($query))) {
            echo json_encode(["status" => true, "data" => $product]);
            exit();
        }
    }
    // Fallback search
    foreach($defaultProducts as $dp) {
        if ($dp['id'] === $id) {
            echo json_encode(["status" => true, "data" => $dp]);
            exit();
        }
    }
    echo json_encode(["status" => false, "msg" => "Product not found"]);
    exit();
}

$category = trim($_GET['category'] ?? 'All');
$search = trim($_GET['search'] ?? '');
$sort = trim($_GET['sort'] ?? 'featured');

$products = [];
if (isset($conn) && $conn) {
    $where = [];
    if ($category !== 'All' && !empty($category)) {
        $catSafe = mysqli_real_escape_string($conn, $category);
        $where[] = "category = '$catSafe'";
    }

    if (!empty($search)) {
        $sSafe = mysqli_real_escape_string($conn, $search);
        $where[] = "(name LIKE '%$sSafe%' OR description LIKE '%$sSafe%' OR fabric LIKE '%$sSafe%' OR category LIKE '%$sSafe%')";
    }

    $whereSql = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";
    $orderSql = "ORDER BY id ASC";
    if ($sort === 'price_asc') $orderSql = "ORDER BY price ASC";
    elseif ($sort === 'price_desc') $orderSql = "ORDER BY price DESC";
    elseif ($sort === 'rating') $orderSql = "ORDER BY rating DESC";
    elseif ($sort === 'newest') $orderSql = "ORDER BY id DESC";

    $result = @mysqli_query($conn, "SELECT * FROM products $whereSql $orderSql");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }
}

if (empty($products)) {
    $products = $defaultProducts;
}

echo json_encode(["status" => true, "count" => count($products), "data" => $products]);
?>
