<?php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include("db.php");

$loginError = "";

if(isset($_POST['login']))
{
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $username_safe = mysqli_real_escape_string($conn, $username);
    $password_safe = mysqli_real_escape_string($conn, $password);

    $sql = "SELECT * FROM users WHERE name='$username_safe' AND password='$password_safe'";
    $result = mysqli_query($conn, $sql);

    if($result && mysqli_num_rows($result) > 0)
    {
        $user = mysqli_fetch_assoc($result);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];

        header("Location: index.php");
        exit();
    }
    else
    {
        $loginError = "Invalid username or password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | StyleHub Boutique</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .back-home-link {
            position: absolute;
            top: 24px;
            left: 24px;
            font-size: 14px;
            font-weight: 600;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .error-banner {
            background: var(--danger-light);
            color: var(--danger);
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 16px;
            text-align: center;
            border: 1px solid #FECACA;
        }
        .pw-toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 16px;
            padding: 4px;
        }
    </style>
</head>
<body class="auth-page">

    <a href="index.php" class="back-home-link">← Return to Store</a>

    <div class="auth-card">
        <div class="auth-brand">
            <div class="auth-logo-badge">👗</div>
            <h2>Welcome Back</h2>
            <p>Sign in to access your saved bag and orders</p>
        </div>

        <?php if(!empty($loginError)): ?>
            <div class="error-banner"><?php echo $loginError; ?></div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="login.php">
            <div class="form-group">
                <label for="usernameInput">Username</label>
                <div class="form-input-wrapper">
                    <input type="text" id="usernameInput" name="username" class="form-input" 
                           placeholder="Enter your username" required autocomplete="username">
                </div>
            </div>

            <div class="form-group">
                <label for="passwordInput">Password</label>
                <div class="form-input-wrapper">
                    <input type="password" id="passwordInput" name="password" class="form-input" 
                           placeholder="Enter your password" required autocomplete="current-password">
                    <button type="button" class="pw-toggle-btn" onclick="togglePasswordVisibility('passwordInput', this)" title="Toggle password">👁️</button>
                </div>
            </div>

            <button type="submit" id="loginButton" name="login" class="auth-submit-btn">
                Sign In to StyleHub
            </button>

            <div class="auth-footer-text">
                Don't have an account yet?
                <a href="registration.php">Create Account</a>
            </div>
            
            <div style="text-align:center; margin-top:16px; padding-top:16px; border-top:1px solid var(--border);">
                <a href="admin_login.php" style="font-size:12px; color:var(--text-muted); font-weight:600;">Staff Admin Login →</a>
            </div>
        </form>
    </div>

    <script>
    // Smooth Enter navigation from username to password
    const usernameField = document.getElementById('usernameInput');
    const passwordField = document.getElementById('passwordInput');

    usernameField.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.keyCode === 13) {
            event.preventDefault();
            event.stopPropagation();
            passwordField.focus();
            return false;
        }
    });

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.innerText = '🙈';
        } else {
            input.type = 'password';
            btn.innerText = '👁️';
        }
    }
    </script>
</body>
</html>
