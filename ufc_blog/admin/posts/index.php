<?php
require_once '../../lib/db.php';
require_once '../../lib/auth.php';

requireAdmin();
$db = new Database();

$posts = $db->query(
    "SELECT p.*, u.username 
     FROM posts p 
     JOIN users u ON p.user_id = u.user_id 
     ORDER BY p.created_at DESC"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Posts - Admin - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../../theme/header.php'; ?>
    <main>
        <div class="container">
            <div class="admin-header">
                <h1>Manage Posts</h1>
                <a href="/ufc_blog/posts/create.php" class="button">Add New Post</a>
            </div>

            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?= htmlspecialchars($post['title']) ?></td>
                                <td><?= htmlspecialchars($post['username']) ?></td>
                                <td><?= htmlspecialchars(ucfirst($post['category'])) ?></td>
                                <td><?= date('F j, Y', strtotime($post['created_at'])) ?></td>
                                <td class="actions">
                                    <a href="edit.php?id=<?= $post['post_id'] ?>" class="button small">Edit</a>
                                    <form method="POST" action="delete.php" style="display: inline;">
                                        <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                                        <button type="submit" class="button small delete" 
                                                onclick="return confirm('Are you sure you want to delete this post?')">
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