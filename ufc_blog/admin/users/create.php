// admin/users/create.php
<?php
require_once '../../lib/db.php';
require_once '../../lib/auth.php';
require_once '../../lib/validation.php';

// Ensure admin access only
requireAdmin();

$db = new Database();
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (!validateUsername($_POST['username'])) {
        $errors[] = "Username must be 3-20 characters and contain only letters, numbers, and underscores";
    }
    
    if (!validateEmail($_POST['email'])) {
        $errors[] = "Invalid email format";
    }
    
    if (!validatePassword($_POST['password'])) {
        $errors[] = "Password must be at least 8 characters long";
    }
    
    if (empty($errors)) {
        try {
            // Check if username/email already exists
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
                    "INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)",
                    [
                        $_POST['username'],
                        $_POST['email'],
                        $hash,
                        $_POST['role']
                    ]
                );
                $success = true;
            }
        } catch (Exception $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - Admin - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../../theme/header.php'; ?>
    
    <main>
        <div class="container">
            <div class="admin-header">
                <h1>Create New User</h1>
                <a href="/ufc_blog/admin/users/" class="button secondary">Back to Users</a>
            </div>

            <?php if ($success): ?>
                <div class="success-message">
                    User created successfully!
                    <a href="/ufc_blog/admin/users/" class="button">Return to Users List</a>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!$success): ?>
                <form method="POST" class="form-container">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               required 
                               minlength="3" 
                               maxlength="20"
                               pattern="^[a-zA-Z0-9_]+$"
                               value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                               title="Username must be 3-20 characters and contain only letters, numbers, and underscores">
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required
                               value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               minlength="8"
                               title="Password must be at least 8 characters long">
                        <div class="password-requirements">
                            Minimum 8 characters required
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="role">User Role:</label>
                        <select id="role" name="role" required>
                            <option value="member" <?= isset($_POST['role']) && $_POST['role'] === 'member' ? 'selected' : '' ?>>
                                Member
                            </option>
                            <option value="admin" <?= isset($_POST['role']) && $_POST['role'] === 'admin' ? 'selected' : '' ?>>
                                Admin
                            </option>
                        </select>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="button primary">Create User</button>
                        <a href="/ufc_blog/admin/users/" class="button secondary">Cancel</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </main>

    <?php include '../../theme/footer.php'; ?>

    <script>
        // Client-side validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const username = document.getElementById('username').value;
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long');
                return;
            }
            
            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                e.preventDefault();
                alert('Username can only contain letters, numbers, and underscores');
                return;
            }
        });
    </script>
</body>
</html>