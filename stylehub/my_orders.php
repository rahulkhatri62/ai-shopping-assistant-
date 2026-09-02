<?php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include("db.php");

$userId = (int)($_SESSION['user_id'] ?? 0);
$userName = $_SESSION['name'] ?? 'Guest';

if ($userId > 0) {
    $ordersQuery = mysqli_query($conn, "SELECT * FROM `order` WHERE user_id = $userId ORDER BY id DESC");
} else {
    // Show recent orders or guest demo
    $ordersQuery = mysqli_query($conn, "SELECT * FROM `order` ORDER BY id DESC LIMIT 5");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | StyleHub Boutique</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .order-card {
            background: white;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            padding-bottom: 14px;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .status-pill {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .status-Pending { background: #FEF3C7; color: #B45309; }
        .status-Processing { background: #EFF6FF; color: #1D4ED8; }
        .status-Shipped { background: #F3E8FF; color: #7E22CE; }
        .status-Delivered { background: #DCFCE7; color: #15803D; }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="site-header">
        <div class="header-container">
            <div class="brand-logo" onclick="window.location='index.php'">
                <h1>StyleHub <span>Boutique</span></h1>
            </div>

            <nav>
                <ul class="nav-menu">
                    <li><a href="index.php" class="nav-link">← Back to Shop</a></li>
                    <li><a href="index.php#collection" class="nav-link">Kurtis</a></li>
                    <li><a href="cart.php" class="nav-link">🛒 Cart</a></li>
                    <?php if(isset($_SESSION['name']) && !empty($_SESSION['name'])): ?>
                        <li>
                            <div class="user-pill">
                                <span>👤 <?php echo htmlspecialchars($_SESSION['name']); ?></span>
                                <a href="login.php" class="logout-btn">Logout</a>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="section-container" style="max-width:900px; padding:40px 20px;">
        <div style="margin-bottom:30px;">
            <h1 style="font-size:32px; color:var(--text-main);">My Purchase History</h1>
            <p style="color:var(--text-muted); font-size:15px;">Track your ongoing and past kurti deliveries.</p>
        </div>

        <?php if(mysqli_num_rows($ordersQuery) > 0): ?>
            <?php while($order = mysqli_fetch_assoc($ordersQuery)): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <span style="font-size:12px; color:var(--text-muted); text-transform:uppercase; font-weight:700;">Order ID</span>
                            <h3 style="font-size:18px; color:var(--text-main); margin-top:2px;">#ORD-<?php echo str_pad($order['id'], 4, '0', STR_PAD_LEFT); ?></h3>
                            <small style="color:var(--text-muted);"><?php echo date('d M Y, h:i A', strtotime($order['created_at'] ?? 'now')); ?></small>
                        </div>
                        <div>
                            <span class="status-pill status-<?php echo $order['status'] ?? 'Pending'; ?>">
                                ● <?php echo htmlspecialchars($order['status'] ?? 'Pending'); ?>
                            </span>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:20px; font-size:14px;">
                        <div>
                            <strong style="color:var(--text-main); display:block; margin-bottom:6px;">Items Ordered:</strong>
                            <p style="color:var(--text-muted); line-height:1.5;"><?php echo htmlspecialchars($order['product_name']); ?></p>
                            
                            <?php if(!empty($order['shipping_address'])): ?>
                                <div style="margin-top:12px; font-size:13px; color:var(--text-muted);">
                                    📍 <strong>Delivery Address:</strong> <?php echo htmlspecialchars($order['customer_name'] ?? ''); ?>, <?php echo htmlspecialchars($order['shipping_address'] ?? ''); ?>, <?php echo htmlspecialchars($order['city'] ?? ''); ?> - <?php echo htmlspecialchars($order['pincode'] ?? ''); ?>
                                    <?php if(!empty($order['phone'])): ?> (Phone: <?php echo htmlspecialchars($order['phone']); ?>)<?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div style="text-align:right;">
                            <span style="font-size:12px; color:var(--text-muted); display:block;">Total Paid</span>
                            <span style="font-size:22px; font-weight:800; color:var(--primary);">₹<?php echo number_format((float)$order['total_amount'], 2); ?></span>
                            <small style="display:block; color:var(--success); font-weight:600; margin-top:4px;">
                                Paid via <?php echo htmlspecialchars($order['payment_method'] ?? 'COD'); ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align:center; padding:60px 20px; background:white; border-radius:var(--radius-lg); border:1px solid var(--border);">
                <div style="font-size:54px; margin-bottom:14px;">📦</div>
                <h3 style="font-size:22px; margin-bottom:8px;">No orders found yet</h3>
                <p style="color:var(--text-muted); margin-bottom:20px;">You have not placed any orders yet. Check out our signature kurtis!</p>
                <a href="index.php#collection" class="btn-gold">Explore Kurtis</a>
            </div>
        <?php endif; ?>
    </main>

</body>
</html>
