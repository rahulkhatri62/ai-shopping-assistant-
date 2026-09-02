<?php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include("db.php");

$userQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$userCount = mysqli_fetch_assoc($userQuery)['total'] ?? 0;

$orderQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `order`");
$orderCount = mysqli_fetch_assoc($orderQuery)['total'] ?? 0;

$salesQuery = mysqli_query($conn, "SELECT SUM(total_amount) AS total_sales FROM `order`");
$salesData = mysqli_fetch_assoc($salesQuery);
$totalSales = (float)($salesData['total_sales'] ?? 0);

$userData = mysqli_query($conn, "
    SELECT
        users.id,
        users.name,
        users.email,
        COUNT(`order`.id) AS total_order,
        IFNULL(SUM(`order`.total_amount), 0) AS total_purchase
    FROM users
    LEFT JOIN `order` ON users.id = `order`.user_id
    GROUP BY users.id
    ORDER BY users.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Dashboard | StyleHub Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-body {
            background: #F4F3EF;
            min-height: 100vh;
        }
        .admin-nav-bar {
            background: #181824;
            color: white;
            padding: 16px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        .admin-nav-bar h1 {
            font-size: 20px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .admin-nav-bar h1 span {
            font-size: 11px;
            background: var(--gold);
            color: #1A1A22;
            padding: 2px 8px;
            border-radius: 20px;
            letter-spacing: 0.05em;
        }
        .badge-orders {
            background: #EFF6FF;
            color: #2563EB;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 12px;
            display: inline-block;
        }
        .user-avatar-initial {
            width: 34px;
            height: 34px;
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
    </style>
</head>
<body class="dashboard-body">

    <!-- Admin Topbar -->
    <header class="admin-nav-bar">
        <h1>
            🛡️ StyleHub <span>MANAGEMENT PORTAL</span>
        </h1>
        <div style="display:flex; gap:10px; align-items:center;">
            <a href="index.php" class="btn-admin-nav">🌐 View Store</a>
            <a href="index.php#collection" class="btn-admin-nav">👗 Products</a>
            <a href="admin_login.php" class="btn-admin-nav" style="background:#EF4444; border-color:#DC2626;">🚪 Logout</a>
        </div>
    </header>

    <main class="section-container" style="max-width:1200px; padding:35px 24px;">
        <div style="margin-bottom:28px;">
            <h2 style="font-size:28px; color:var(--text-main);">Store Performance Overview</h2>
            <p style="color:var(--text-muted); font-size:14px;">Live sales analytics and registered shopper metrics.</p>
        </div>

        <!-- 3 KPI Metric Cards -->
        <div class="kpi-cards-grid">
            <div class="kpi-card">
                <div class="kpi-icon users">👥</div>
                <div class="kpi-data">
                    <h4>Total Customers</h4>
                    <h2><?php echo number_format($userCount); ?></h2>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon orders">🛍️</div>
                <div class="kpi-data">
                    <h4>Total Orders Placed</h4>
                    <h2><?php echo number_format($orderCount); ?></h2>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon sales">💰</div>
                <div class="kpi-data">
                    <h4>Gross Store Revenue</h4>
                    <h2>₹<?php echo number_format($totalSales, 2); ?></h2>
                </div>
            </div>
        </div>

        <!-- Registered Users & Purchase Summary -->
        <div class="datatable-wrapper">
            <div class="datatable-header">
                <div>
                    <h3>Registered Shoppers & Order Records</h3>
                    <p style="font-size:13px; color:var(--text-muted); margin-top:2px;">Track registered accounts and their lifetime purchase volume.</p>
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#FAF8F5; border-bottom:1px solid var(--border); text-align:left;">
                            <th style="padding:14px 20px; font-size:12px; color:var(--text-muted); text-transform:uppercase;">User ID</th>
                            <th style="padding:14px 20px; font-size:12px; color:var(--text-muted); text-transform:uppercase;">Shopper Name</th>
                            <th style="padding:14px 20px; font-size:12px; color:var(--text-muted); text-transform:uppercase;">Email Address</th>
                            <th style="padding:14px 20px; font-size:12px; color:var(--text-muted); text-transform:uppercase; text-align:center;">Orders</th>
                            <th style="padding:14px 20px; font-size:12px; color:var(--text-muted); text-transform:uppercase; text-align:right;">Lifetime Spend</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($userData) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($userData)): ?>
                                <tr style="border-bottom:1px solid var(--border); transition:background 0.2s ease;">
                                    <td style="padding:14px 20px; color:var(--text-muted); font-weight:600;">#<?php echo $row['id']; ?></td>
                                    <td style="padding:14px 20px;">
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <div class="user-avatar-initial">
                                                <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                            </div>
                                            <strong style="color:var(--text-main); font-size:14px;"><?php echo htmlspecialchars($row['name']); ?></strong>
                                        </div>
                                    </td>
                                    <td style="padding:14px 20px; color:var(--text-muted); font-size:14px;"><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td style="padding:14px 20px; text-align:center;">
                                        <span class="badge-orders"><?php echo $row['total_order']; ?> order(s)</span>
                                    </td>
                                    <td style="padding:14px 20px; text-align:right; font-weight:800; color:var(--primary); font-size:15px;">
                                        ₹<?php echo number_format((float)$row['total_purchase'], 2); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="padding:30px; text-align:center; color:var(--text-muted);">
                                    No registered shoppers found in database.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
