<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';
require_once '../lib/upload.php';

requireLogin();

$db = new Database();
$post = $db->query(
    "SELECT * FROM posts WHERE post_id = ?",
    [$_GET['id']]
)->fetch(PDO::FETCH_ASSOC);

if (!$post || (!isAdmin() && $post['user_id'] !== $_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image_url = $post['image_url'];
    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === 0) {
        if ($post['image_url']) {
            unlink(".." . $post['image_url']);
        }
        $image_url = uploadImage($_FILES['post_image'], '../data/posts');
    }

    $db->query(
        "UPDATE posts SET title = ?, content = ?, category = ?, image_url = ? WHERE post_id = ?",
        [$_POST['title'], $_POST['content'], $_POST['category'], $image_url, $_GET['id']]
    );

    header('Location: detail.php?id=' . $_GET['id']);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../theme/header.php'; ?>
    
    <main>
        <div class="container">
            <h1>Edit Post</h1>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Title:
                        <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
                    </label>
                </div>
                <div class="form-group">
                    <label>Category:
                        <select name="category" required>
                            <option value="event" <?= $post['category'] === 'event' ? 'selected' : '' ?>>Event</option>
                            <option value="fighter" <?= $post['category'] === 'fighter' ? 'selected' : '' ?>>Fighter</option>
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label>Content:
                        <textarea name="content" required rows="10"><?= htmlspecialchars($post['content']) ?></textarea>
                    </label>
                </div>
                <?php if ($post['image_url']): ?>
                    <div class="current-image">
                        <p>Current Image:</p>
                        <img src="<?= htmlspecialchars($post['image_url']) ?>" style="max-width: 200px;">
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label>Update Image:
                        <input type="file" name="post_image" accept="image/*">
                    </label>
                </div>
                <div id="image-preview"></div>
                <button type="submit" class="button">Update Post</button>
            </form>
        </div>
    </main>

    <?php include '../theme/footer.php'; ?>
    <script>
        document.querySelector('input[type="file"]').addEventListener('change', function(e) {
            const preview = document.getElementById('image-preview');
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" style="max-width: 300px;">`;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>