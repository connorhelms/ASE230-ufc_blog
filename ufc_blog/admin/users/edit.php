<?php
require_once '../../lib/db.php';
require_once '../../lib/auth.php';

requireAdmin();
$db = new Database();

$user = $db->query(
    "SELECT * FROM users WHERE user_id = ?",
    [$_GET['id']]
)->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: /ufc_blog/admin/users/');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $params = [
        $_POST['email'],
        $_POST['role'],
        $user['user_id']
    ];
    
    $sql = "UPDATE users SET email = ?, role = ?";
    
    if (!empty($_POST['password'])) {
        $sql .= ", password_hash = ?";
        $params = array_merge(
            [
                $_POST['email'],
                $_POST['role'],
                password_hash($_POST['password'], PASSWORD_DEFAULT)
            ],
            [$user['user_id']]
        );
    }
    
    $sql .= " WHERE user_id = ?";
    
    $db->query($sql, $params);
    header('Location: /ufc_blog/admin/users/');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User - Admin - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../../theme/header.php'; ?>
    <main>
        <div class="container">
            <h1>Edit User</h1>
            
            <form method="POST">
                <div class="form-group">
                    <label>Username:
                        <input type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                    </label>
                </div>
                
                <div class="form-group">
                    <label>Email:
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </label>
                </div>
                
                <div class="form-group">
                    <label>Role:
                        <select name="role">
                            <option value="member" <?= $user['role'] === 'member' ? 'selected' : '' ?>>Member</option>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </label>
                </div>
                
                <div class="form-group">
                    <label>New Password (leave blank to keep current):
                        <input type="password" name="password" minlength="8">
                    </label>
                </div>
                
                <button type="submit" class="button">Update User</button>
                <a href="/ufc_blog/admin/users/" class="button">Cancel</a>
            </form>
        </div>
    </main>
    <?php include '../../theme/footer.php'; ?>
</body>
</html>