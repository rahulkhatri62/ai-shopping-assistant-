<?php
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-cache, no-store, must-revalidate");
include("db.php");

$action = $_GET['action'] ?? 'list';

if ($action === 'detail') {
    $id = (int)($_GET['id'] ?? 0);
    $query = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
    if ($product = mysqli_fetch_assoc($query)) {
        echo json_encode(["status" => true, "data" => $product]);
    } else {
        echo json_encode(["status" => false, "msg" => "Product not found"]);
    }
    exit();
}

// List with filters
$category = trim($_GET['category'] ?? 'All');
$search = trim($_GET['search'] ?? '');
$sort = trim($_GET['sort'] ?? 'featured');

$where = [];
if ($category !== 'All' && !empty($category)) {
    $catSafe = mysqli_real_escape_string($conn, $category);
    $where[] = "category = '$catSafe'";
}

if (!empty($search)) {
    $sSafe = mysqli_real_escape_string($conn, $search);
    $where[] = "(name LIKE '%$sSafe%' OR description LIKE '%$sSafe%' OR fabric LIKE '%$sSafe%' OR category LIKE '%$sSafe%')";
}

$whereSql = "";
if (count($where) > 0) {
    $whereSql = "WHERE " . implode(" AND ", $where);
}

$orderSql = "ORDER BY id ASC";
if ($sort === 'price_asc') {
    $orderSql = "ORDER BY price ASC";
} elseif ($sort === 'price_desc') {
    $orderSql = "ORDER BY price DESC";
} elseif ($sort === 'rating') {
    $orderSql = "ORDER BY rating DESC";
} elseif ($sort === 'newest') {
    $orderSql = "ORDER BY id DESC";
}

$sql = "SELECT * FROM products $whereSql $orderSql";
$result = mysqli_query($conn, $sql);
$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

echo json_encode(["status" => true, "count" => count($products), "data" => $products]);
?>
