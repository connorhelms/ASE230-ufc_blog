<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';
require_once '../lib/validation.php';

if (isLoggedIn()) {
    header('Location: /ufc_blog/');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Validate input
    if (!validateUsername($_POST['username'])) {
        $errors[] = "Username must be 3-20 characters and contain only letters, numbers, and underscores";
    }
    
    if (!validateEmail($_POST['email'])) {
        $errors[] = "Invalid email format";
    }
    
    if (!validatePassword($_POST['password'])) {
        $errors[] = "Password must be at least 8 characters";
    }
    
    if (empty($errors)) {
        $db = new Database();
        
        // Check if username/email exists
        $exists = $db->query(
            "SELECT 1 FROM users WHERE username = ? OR email = ?",
            [$_POST['username'], $_POST['email']]
        )->fetch();
        
        if ($exists) {
            $errors[] = "Username or email already exists";
        } else {
            // Create user
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $db->query(
                "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)",
                [$_POST['username'], $_POST['email'], $hash]
            );
            
            header('Location: /ufc_blog/auth/login.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../theme/header.php'; ?>
    <main>
        <div class="auth-container">
            <h1>Register</h1>
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/ufc_blog/auth/register.php" class="auth-form">
                <div class="form-group">
                    <label>Username:
                        <input type="text" 
                               name="username" 
                               value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                               required 
                               minlength="3" 
                               maxlength="20" 
                               pattern="^[a-zA-Z0-9_]+$">
                    </label>
                    <small>3-20 characters, letters, numbers, and underscores only</small>
                </div>
                
                <div class="form-group">
                    <label>Email:
                        <input type="email" 
                               name="email" 
                               value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                               required>
                    </label>
                </div>
                
                <div class="form-group">
                    <label>Password:
                        <input type="password" 
                               name="password" 
                               required 
                               minlength="8">
                    </label>
                    <small>Minimum 8 characters</small>
                </div>
                
                <button type="submit" class="button">Register</button>
            </form>
            
            <p class="auth-links">
                Already have an account? <a href="/ufc_blog/auth/login.php">Login</a>
            </p>
        </div>
    </main>
    <?php include '../theme/footer.php'; ?>
    
    <script src="/ufc_blog/theme/js/main.js"></script>
</body>
</html>