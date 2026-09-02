<?php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include("db.php");

// Handle Order Status Update
if (isset($_POST['update_order_status'])) {
    $orderId = (int)$_POST['order_id'];
    $newStatus = mysqli_real_escape_string($conn, $_POST['new_status']);
    mysqli_query($conn, "UPDATE `order` SET status = '$newStatus' WHERE id = $orderId");
    header("Location: dashboard.php?tab=orders&msg=status_updated");
    exit();
}

// Handle Add New Product
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $category = mysqli_real_escape_string($conn, trim($_POST['category']));
    $price = (float)$_POST['price'];
    $original_price = (float)$_POST['original_price'];
    $discount = $original_price > 0 ? round((($original_price - $price) / $original_price) * 100) : 0;
    $fabric = mysqli_real_escape_string($conn, trim($_POST['fabric']));
    $image = mysqli_real_escape_string($conn, trim($_POST['image']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $stock = (int)$_POST['stock'];

    mysqli_query($conn, "INSERT INTO products (name, category, price, original_price, discount_percent, image, description, fabric, stock, rating, reviews_count) 
                         VALUES ('$name', '$category', '$price', '$original_price', '$discount', '$image', '$description', '$fabric', '$stock', 4.8, 1)");
    header("Location: dashboard.php?tab=products&msg=product_added");
    exit();
}

// Handle Delete Product
if (isset($_GET['delete_product'])) {
    $delId = (int)$_GET['delete_product'];
    mysqli_query($conn, "DELETE FROM products WHERE id = $delId");
    header("Location: dashboard.php?tab=products&msg=product_deleted");
    exit();
}

// Metrics
$userCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'] ?? 0;
$orderCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM `order`"))['total'] ?? 0;
$totalSales = (float)(mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) AS total_sales FROM `order`"))['total_sales'] ?? 0);
$prodCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products"))['total'] ?? 0;

// Data sets
$ordersList = mysqli_query($conn, "SELECT * FROM `order` ORDER BY id DESC");
$productsList = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
$customersList = mysqli_query($conn, "
    SELECT users.id, users.name, users.email, COUNT(`order`.id) AS total_order, IFNULL(SUM(`order`.total_amount), 0) AS total_purchase
    FROM users
    LEFT JOIN `order` ON users.id = `order`.user_id
    GROUP BY users.id
    ORDER BY users.id DESC
");

$activeTab = $_GET['tab'] ?? 'orders';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Control Center | StyleHub Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-body {
            background: #F3F3F0;
            min-height: 100vh;
        }
        .admin-nav-bar {
            background: #181824;
            color: white;
            padding: 16px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-tab-nav {
            display: flex;
            gap: 12px;
            margin: 24px 0;
            border-bottom: 2px solid var(--border);
            padding-bottom: 12px;
        }
        .tab-btn {
            background: white;
            border: 1px solid var(--border);
            padding: 10px 22px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
        }
        .tab-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .status-select {
            padding: 5px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid var(--border);
        }
    </style>
</head>
<body class="dashboard-body">

    <!-- Top Navigation -->
    <header class="admin-nav-bar">
        <h1 style="font-size:20px; font-weight:800; display:flex; align-items:center; gap:10px;">
            🛡️ StyleHub <span>ADMIN CENTER</span>
        </h1>
        <div style="display:flex; gap:10px; align-items:center;">
            <a href="index.php" class="btn-admin-nav">🌐 View Store</a>
            <a href="admin_login.php" class="btn-admin-nav" style="background:#EF4444; border-color:#DC2626;">🚪 Logout</a>
        </div>
    </header>

    <main class="section-container" style="max-width:1240px; padding:30px 24px;">
        
        <!-- 4 KPI Summary Cards -->
        <div class="kpi-cards-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom:28px;">
            <div class="kpi-card">
                <div class="kpi-icon orders">🛍️</div>
                <div class="kpi-data">
                    <h4>Total Orders</h4>
                    <h2><?php echo number_format($orderCount); ?></h2>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon sales">💰</div>
                <div class="kpi-data">
                    <h4>Gross Sales</h4>
                    <h2>₹<?php echo number_format($totalSales, 2); ?></h2>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon" style="background:#FDF2F8; color:#DB2777;">👗</div>
                <div class="kpi-data">
                    <h4>Catalog Items</h4>
                    <h2><?php echo number_format($prodCount); ?></h2>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon users">👥</div>
                <div class="kpi-data">
                    <h4>Shoppers</h4>
                    <h2><?php echo number_format($userCount); ?></h2>
                </div>
            </div>
        </div>

        <!-- Tab Controls -->
        <div class="admin-tab-nav">
            <button class="tab-btn <?php echo $activeTab === 'orders' ? 'active' : ''; ?>" onclick="location.href='dashboard.php?tab=orders'">
                📦 Orders Management (<?php echo $orderCount; ?>)
            </button>
            <button class="tab-btn <?php echo $activeTab === 'products' ? 'active' : ''; ?>" onclick="location.href='dashboard.php?tab=products'">
                👗 Product Catalog (<?php echo $prodCount; ?>)
            </button>
            <button class="tab-btn <?php echo $activeTab === 'customers' ? 'active' : ''; ?>" onclick="location.href='dashboard.php?tab=customers'">
                👥 Customer Accounts (<?php echo $userCount; ?>)
            </button>
        </div>

        <!-- ==================== TAB 1: ORDERS ==================== -->
        <?php if ($activeTab === 'orders'): ?>
            <div class="datatable-wrapper">
                <div class="datatable-header">
                    <div>
                        <h3>Customer Orders & Delivery Status</h3>
                        <p style="font-size:13px; color:var(--text-muted);">Manage real-time customer purchases and change status.</p>
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#FAF8F5; border-bottom:1px solid var(--border); text-align:left; font-size:12px; color:var(--text-muted); text-transform:uppercase;">
                                <th style="padding:14px 16px;">Order ID</th>
                                <th style="padding:14px 16px;">Recipient & Address</th>
                                <th style="padding:14px 16px;">Items Ordered</th>
                                <th style="padding:14px 16px; text-align:right;">Total (₹)</th>
                                <th style="padding:14px 16px; text-align:center;">Payment</th>
                                <th style="padding:14px 16px; text-align:center;">Delivery Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($ordersList) > 0): ?>
                                <?php while($o = mysqli_fetch_assoc($ordersList)): ?>
                                    <tr style="border-bottom:1px solid var(--border); font-size:13px;">
                                        <td style="padding:14px 16px; font-weight:700; color:var(--primary);">
                                            #ORD-<?php echo str_pad($o['id'], 4, '0', STR_PAD_LEFT); ?><br>
                                            <small style="color:var(--text-muted); font-weight:400;"><?php echo date('d M, h:i A', strtotime($o['created_at'] ?? 'now')); ?></small>
                                        </td>
                                        <td style="padding:14px 16px;">
                                            <strong><?php echo htmlspecialchars($o['customer_name'] ?? 'Shopper'); ?></strong><br>
                                            <span style="color:var(--text-muted); font-size:12px;">
                                                <?php echo htmlspecialchars($o['shipping_address'] ?? 'N/A'); ?>, <?php echo htmlspecialchars($o['city'] ?? ''); ?> <?php echo htmlspecialchars($o['pincode'] ?? ''); ?>
                                                <?php if(!empty($o['phone'])): ?><br>📞 <?php echo htmlspecialchars($o['phone']); ?><?php endif; ?>
                                            </span>
                                        </td>
                                        <td style="padding:14px 16px; max-width:240px; color:var(--text-main);">
                                            <?php echo htmlspecialchars($o['product_name']); ?>
                                            <?php if(!empty($o['coupon_code'])): ?>
                                                <br><small style="color:var(--success); font-weight:600;">Coupon: <?php echo htmlspecialchars($o['coupon_code']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding:14px 16px; text-align:right; font-weight:800; font-size:15px; color:var(--primary);">
                                            ₹<?php echo number_format((float)$o['total_amount'], 2); ?>
                                        </td>
                                        <td style="padding:14px 16px; text-align:center;">
                                            <span style="background:var(--surface-alt); border:1px solid var(--border); padding:3px 8px; border-radius:4px; font-size:11px; font-weight:600;">
                                                <?php echo htmlspecialchars($o['payment_method'] ?? 'COD'); ?>
                                            </span>
                                        </td>
                                        <td style="padding:14px 16px; text-align:center;">
                                            <form method="POST" action="dashboard.php" style="display:inline-flex; gap:6px;">
                                                <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                                                <select name="new_status" class="status-select" onchange="this.form.submit()">
                                                    <option value="Pending" <?php echo ($o['status'] ?? '') === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="Processing" <?php echo ($o['status'] ?? '') === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                                    <option value="Shipped" <?php echo ($o['status'] ?? '') === 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                                    <option value="Delivered" <?php echo ($o['status'] ?? '') === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                                </select>
                                                <input type="hidden" name="update_order_status" value="1">
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="padding:30px; text-align:center; color:var(--text-muted);">No orders recorded yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <!-- ==================== TAB 2: PRODUCTS ==================== -->
        <?php if ($activeTab === 'products'): ?>
            <div style="display:grid; grid-template-columns: 1fr 340px; gap:24px; align-items:start;">
                <!-- Product List -->
                <div class="datatable-wrapper">
                    <div class="datatable-header">
                        <h3>Active Kurti Catalog</h3>
                    </div>
                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr style="background:#FAF8F5; border-bottom:1px solid var(--border); font-size:12px; color:var(--text-muted); text-transform:uppercase;">
                                    <th style="padding:12px 16px;">Product</th>
                                    <th style="padding:12px 16px;">Category</th>
                                    <th style="padding:12px 16px;">Price</th>
                                    <th style="padding:12px 16px;">Stock</th>
                                    <th style="padding:12px 16px; text-align:center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($p = mysqli_fetch_assoc($productsList)): ?>
                                    <tr style="border-bottom:1px solid var(--border); font-size:13px;">
                                        <td style="padding:12px 16px;">
                                            <div style="display:flex; align-items:center; gap:10px;">
                                                <img src="<?php echo htmlspecialchars($p['image']); ?>" width="44" height="44" style="object-fit:cover; border-radius:6px;">
                                                <strong><?php echo htmlspecialchars($p['name']); ?></strong>
                                            </div>
                                        </td>
                                        <td style="padding:12px 16px; color:var(--text-muted);"><?php echo htmlspecialchars($p['category']); ?></td>
                                        <td style="padding:12px 16px; font-weight:700; color:var(--primary);">₹<?php echo number_format($p['price']); ?></td>
                                        <td style="padding:12px 16px;"><?php echo $p['stock']; ?> units</td>
                                        <td style="padding:12px 16px; text-align:center;">
                                            <a href="dashboard.php?tab=products&delete_product=<?php echo $p['id']; ?>" 
                                               onclick="return confirm('Delete this kurti from catalog?');" 
                                               style="color:#EF4444; font-weight:700; font-size:12px;">Delete 🗑️</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Add New Product Form Card -->
                <div style="background:white; border-radius:var(--radius-md); border:1px solid var(--border); padding:24px; box-shadow:var(--shadow-sm);">
                    <h3 style="font-size:18px; margin-bottom:16px;">➕ Add New Kurti</h3>
                    <form method="POST" action="dashboard.php">
                        <input type="hidden" name="add_product" value="1">
                        
                        <div class="form-group">
                            <label style="font-size:12px; font-weight:600;">Product Name</label>
                            <input type="text" name="name" class="form-input" placeholder="E.g., Pastel Chanderi Kurti" required>
                        </div>

                        <div class="form-group">
                            <label style="font-size:12px; font-weight:600;">Category</label>
                            <select name="category" class="form-input" required>
                                <option value="Daily Wear">Daily Wear</option>
                                <option value="Party Wear">Party Wear</option>
                                <option value="Festive">Festive</option>
                                <option value="Anarkali">Anarkali</option>
                            </select>
                        </div>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                            <div class="form-group">
                                <label style="font-size:12px; font-weight:600;">Selling Price (₹)</label>
                                <input type="number" name="price" class="form-input" placeholder="999" required>
                            </div>
                            <div class="form-group">
                                <label style="font-size:12px; font-weight:600;">Original Price (₹)</label>
                                <input type="number" name="original_price" class="form-input" placeholder="1499" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label style="font-size:12px; font-weight:600;">Fabric Info</label>
                            <input type="text" name="fabric" class="form-input" placeholder="Pure Cotton / Silk" required>
                        </div>

                        <div class="form-group">
                            <label style="font-size:12px; font-weight:600;">Image Path / URL</label>
                            <input type="text" name="image" class="form-input" value="images/kurti1.jpeg" required>
                        </div>

                        <div class="form-group">
                            <label style="font-size:12px; font-weight:600;">Stock Units</label>
                            <input type="number" name="stock" class="form-input" value="50" required>
                        </div>

                        <div class="form-group">
                            <label style="font-size:12px; font-weight:600;">Description</label>
                            <textarea name="description" class="form-input" rows="2" placeholder="Brief details about the kurti..." required></textarea>
                        </div>

                        <button type="submit" class="auth-submit-btn" style="margin-top:6px;">Add to Catalog</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- ==================== TAB 3: CUSTOMERS ==================== -->
        <?php if ($activeTab === 'customers'): ?>
            <div class="datatable-wrapper">
                <div class="datatable-header">
                    <h3>Registered Shoppers</h3>
                </div>
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#FAF8F5; border-bottom:1px solid var(--border); font-size:12px; color:var(--text-muted); text-transform:uppercase;">
                                <th style="padding:14px 20px;">User ID</th>
                                <th style="padding:14px 20px;">Customer Name</th>
                                <th style="padding:14px 20px;">Email</th>
                                <th style="padding:14px 20px; text-align:center;">Orders</th>
                                <th style="padding:14px 20px; text-align:right;">Lifetime Spend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($c = mysqli_fetch_assoc($customersList)): ?>
                                <tr style="border-bottom:1px solid var(--border); font-size:14px;">
                                    <td style="padding:14px 20px; font-weight:600; color:var(--text-muted);">#<?php echo $c['id']; ?></td>
                                    <td style="padding:14px 20px;"><strong><?php echo htmlspecialchars($c['name']); ?></strong></td>
                                    <td style="padding:14px 20px; color:var(--text-muted);"><?php echo htmlspecialchars($c['email']); ?></td>
                                    <td style="padding:14px 20px; text-align:center;">
                                        <span class="badge-orders"><?php echo $c['total_order']; ?> order(s)</span>
                                    </td>
                                    <td style="padding:14px 20px; text-align:right; font-weight:800; color:var(--primary);">
                                        ₹<?php echo number_format((float)$c['total_purchase'], 2); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    </main>

</body>
</html>
