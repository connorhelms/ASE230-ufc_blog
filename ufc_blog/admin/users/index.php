<?php
require_once '../../lib/db.php';
require_once '../../lib/auth.php';

requireAdmin();
$db = new Database();

$users = $db->query("SELECT * FROM users ORDER BY username ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users - Admin</title>
</head>
<body>
    <?php include '../../theme/header.php'; ?>
    <main>
        <h1>Manage Users</h1>
        <a href="create.php" class="button">Add New User</a>
        
        <table class="admin-table">
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td><?= date('Y-m-d', strtotime($user['created_at'])) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $user['user_id'] ?>">Edit</a>
                        <?php if ($user['user_id'] !== $_SESSION['user_id']): ?>
                            <form method="POST" action="delete.php" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
    <?php include '../../theme/footer.php'; ?>
</body>
</html>
