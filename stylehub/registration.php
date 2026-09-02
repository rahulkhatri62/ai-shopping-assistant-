<?php
include("db.php");

$regError = "";
$regSuccess = false;

if(isset($_POST['register']))
{
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (empty($name) || empty($email) || empty($password)) {
        $regError = "Please fill in all required fields.";
    } elseif ($password !== $confirm_password) {
        $regError = "Passwords do not match. Please re-enter.";
    } else {
        $name_safe = mysqli_real_escape_string($conn, $name);
        $email_safe = mysqli_real_escape_string($conn, $email);
        $password_safe = mysqli_real_escape_string($conn, $password);

        // Check if username already exists
        $check = mysqli_query($conn, "SELECT id FROM users WHERE name='$name_safe' OR email='$email_safe'");
        if(mysqli_num_rows($check) > 0) {
            $regError = "A user with this username or email already exists.";
        } else {
            $sql = "INSERT INTO users(name,email,password) VALUES('$name_safe','$email_safe','$password_safe')";

            if(mysqli_query($conn, $sql))
            {
                $regSuccess = true;
            }
            else
            {
                $regError = "Registration error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account | StyleHub</title>
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
        .success-banner {
            background: var(--success-light);
            color: var(--success);
            padding: 14px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 16px;
            text-align: center;
            border: 1px solid #A7F3D0;
        }
    </style>
</head>
<body class="auth-page">

    <a href="index.php" class="back-home-link">← Return to Store</a>

    <div class="auth-card">
        <div class="auth-brand">
            <div class="auth-logo-badge">✨</div>
            <h2>Create Your Account</h2>
            <p>Join StyleHub for personalized AI recommendations and offers</p>
        </div>

        <?php if(!empty($regError)): ?>
            <div class="error-banner"><?php echo $regError; ?></div>
        <?php endif; ?>

        <?php if($regSuccess): ?>
            <div class="success-banner">
                🎉 Account created successfully!<br>
                <a href="login.php" style="color:#065F46; text-decoration:underline; font-weight:700; display:inline-block; margin-top:6px;">Click here to Sign In →</a>
            </div>
        <?php else: ?>
            <form method="POST" action="registration.php" onsubmit="return validatePasswords();">
                <div class="form-group">
                    <label for="name">Choose a Username</label>
                    <input type="text" id="name" name="name" class="form-input" 
                           placeholder="E.g., nandani" required autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" 
                           placeholder="you@example.com" required autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input" 
                           placeholder="Create a strong password" required autocomplete="new-password">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" 
                           placeholder="Re-type your password" required autocomplete="new-password">
                </div>

                <button type="submit" name="register" class="auth-submit-btn">
                    Create StyleHub Account
                </button>

                <div class="auth-footer-text">
                    Already registered?
                    <a href="login.php">Sign In Here</a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script>
    function validatePasswords() {
        let p1 = document.getElementById("password").value;
        let p2 = document.getElementById("confirm_password").value;
        if (p1 !== p2) {
            alert("Passwords do not match. Please re-enter.");
            return false;
        }
        return true;
    }
    </script>
</body>
</html>
