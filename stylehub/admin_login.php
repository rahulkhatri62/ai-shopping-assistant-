<?php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$error = "";

if(isset($_POST['login']))
{
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if($username === "admin" && $password === "2710")
    {
        $_SESSION['admin_auth'] = true;
        header("Location: dashboard.php");
        exit(); 
    }
    else
    {
        $error = "Invalid administrator username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal Login | StyleHub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top right, #3A1020 0%, #15151D 60%, #0E0E14 100%);
            padding: 24px;
        }
        .admin-card {
            background: rgba(26, 26, 36, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-lg);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            color: white;
        }
        .admin-card .auth-logo-badge {
            background: rgba(212,175,55,0.15);
            color: var(--gold);
            font-size: 28px;
        }
        .admin-card h2 {
            color: white;
        }
        .admin-card p {
            color: #94A3B8;
        }
        .admin-card .form-input {
            background: #101016;
            border-color: #2D2D3E;
            color: white;
        }
        .admin-card .form-input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(212,175,55,0.15);
        }
        .admin-card label {
            color: #CBD5E1;
        }
        .btn-admin-submit {
            width: 100%;
            background: linear-gradient(135deg, #E5C358 0%, #D4AF37 100%);
            color: #1A1A22;
            font-weight: 800;
            padding: 13px;
            border-radius: var(--radius-sm);
            border: none;
            cursor: pointer;
            font-size: 15px;
            margin-top: 10px;
            transition: var(--transition);
        }
        .btn-admin-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212,175,55,0.3);
        }
    </style>
</head>
<body class="admin-auth-page">

    <div class="admin-card">
        <div class="auth-brand">
            <div class="auth-logo-badge">🛡️</div>
            <h2>StyleHub Admin</h2>
            <p>Authorized personnel only</p>
        </div>

        <?php if(!empty($error)): ?>
            <div style="background:rgba(220,38,38,0.2); border:1px solid #DC2626; color:#FCA5A5; padding:10px 14px; border-radius:6px; font-size:13px; margin-bottom:16px; text-align:center;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="admin_login.php">
            <div class="form-group">
                <label for="adminUsername">Administrator Username</label>
                <input type="text" id="adminUsername" name="username" class="form-input" 
                       placeholder="Enter admin username" required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="adminPassword">Master Password</label>
                <input type="password" id="adminPassword" name="password" class="form-input" 
                       placeholder="Enter password" required autocomplete="current-password">
            </div>

            <button type="submit" name="login" class="btn-admin-submit">
                Access Admin Dashboard →
            </button>

            <div style="text-align:center; margin-top:24px;">
                <a href="index.php" style="color:#94A3B8; font-size:13px;">← Return to Main Storefront</a>
            </div>
        </form>
    </div>

</body>
</html>
