<?php
require_once '../../lib/db.php';
require_once '../../lib/auth.php';

requireAdmin();
$db = new Database();

$fighters = $db->query("SELECT * FROM fighters ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Fighters - Admin - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../../theme/header.php'; ?>
    <main>
        <div class="container">
            <div class="admin-header">
                <h1>Manage Fighters</h1>
                <a href="create.php" class="button">Add New Fighter</a>
            </div>

            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Weight Class</th>
                            <th>Record</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fighters as $fighter): ?>
                            <tr>
                                <td><?= htmlspecialchars($fighter['name']) ?></td>
                                <td><?= htmlspecialchars($fighter['weight_class']) ?></td>
                                <td><?= htmlspecialchars($fighter['record']) ?></td>
                                <td class="actions">
                                    <a href="edit.php?id=<?= $fighter['fighter_id'] ?>" class="button small">Edit</a>
                                    <form method="POST" action="delete.php" style="display: inline;">
                                        <input type="hidden" name="fighter_id" value="<?= $fighter['fighter_id'] ?>">
                                        <button type="submit" class="button small delete" 
                                                onclick="return confirm('Are you sure you want to delete this fighter?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <?php include '../../theme/footer.php'; ?>
</body>
</html>