<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';

if (isLoggedIn()) {
    header('Location: /ufc_blog/');
    exit();
}

// Check for remember me cookie
if (!isLoggedIn() && isset($_COOKIE['remember_token'])) {
    $db = new Database();
    $token = $_COOKIE['remember_token'];
    
    $user = $db->query(
        "SELECT * FROM users WHERE remember_token = ?",
        [$token]
    )->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: /ufc_blog/');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $user = $db->query(
        "SELECT * FROM users WHERE username = ?",
        [$_POST['username']]
    )->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($_POST['password'], $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Handle remember me
        if (isset($_POST['remember']) && $_POST['remember'] == 1) {
            $token = bin2hex(random_bytes(32)); // Generate secure token
            
            // Store token in database
            $db->query(
                "UPDATE users SET remember_token = ? WHERE user_id = ?",
                [$token, $user['user_id']]
            );
            
            // Set cookie for 30 days
            setcookie(
                'remember_token',
                $token,
                time() + (86400 * 30), // 30 days
                '/',
                '',
                true,    // Secure
                true     // HttpOnly
            );
        }
        
        header('Location: /ufc_blog/');
        exit();
    }
    $error = "Invalid username or password";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../theme/header.php'; ?>
    <main>
        <div class="auth-container">
            <h1>Login</h1>
            <?php if (isset($error)): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="/ufc_blog/auth/login.php" class="auth-form">
                <div class="form-group">
                    <label>Username:
                        <input type="text" name="username" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Password:
                        <input type="password" name="password" required>
                    </label>
                </div>
                <div class="form-group remember-me">
                    <label>
                        <input type="checkbox" name="remember" value="1">
                        Remember me
                    </label>
                </div>
                <button type="submit" class="button">Login</button>
            </form>
            
            <p class="auth-links">
                Don't have an account? <a href="/ufc_blog/auth/register.php">Register</a>
            </p>
        </div>
    </main>
    <?php include '../theme/footer.php'; ?>
</body>
</html>