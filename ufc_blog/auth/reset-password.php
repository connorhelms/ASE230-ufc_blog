<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';
require_once '../lib/validation.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $error = null;
    
    if (isset($_POST['email'])) {
        if (!validateEmail($_POST['email'])) {
            $error = "Invalid email format";
        } else {
            $user = $db->query(
                "SELECT user_id FROM users WHERE email = ?",
                [$_POST['email']]
            )->fetch();
            
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                $db->query(
                    "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)",
                    [$user['user_id'], $token, $expiry]
                );
                
                // In real application, send email with reset link
                $message = "Password reset successful. In a real application, an email would be sent.";
            } else {
                $error = "No account found with that email";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - UFC Blog</title>
</head>
<body>
    <?php include '../theme/header.php'; ?>
    <main>
        <div class="auth-container">
            <h1>Reset Password</h1>
            <?php if (isset($error)): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if (isset($message)): ?>
                <div class="success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label>Email:
                        <input type="email" name="email" required>
                    </label>
                </div>
                <button type="submit" class="button">Reset Password</button>
            </form>
            
            <p class="auth-links">
                Remember your password? <a href="/auth/login.php">Login</a>
            </p>
        </div>
    </main>
    <?php include '../theme/footer.php'; ?>
</body>
</html>