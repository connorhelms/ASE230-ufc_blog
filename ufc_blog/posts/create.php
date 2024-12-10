<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';
require_once '../lib/upload.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $image_url = '';
        if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === 0) {
            $image_url = uploadImage($_FILES['post_image'], '../data/posts');
        }

        $db = new Database();
        $db->query(
            "INSERT INTO posts (user_id, title, content, category, image_url) VALUES (?, ?, ?, ?, ?)",
            [$_SESSION['user_id'], $_POST['title'], $_POST['content'], $_POST['category'], $image_url]
        );

        header('Location: index.php');
        exit();
    } catch (Exception $e) {
        $error = "Error creating post: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../theme/header.php'; ?>
    
    <main>
        <div class="container">
            <h1>Create New Post</h1>
            <?php if (isset($error)): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Title: <input type="text" name="title" required></label>
                </div>
                <div class="form-group">
                    <label>Category:
                        <select name="category" required>
                            <option value="event">Event</option>
                            <option value="fighter">Fighter</option>
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label>Content: <textarea name="content" required rows="10"></textarea></label>
                </div>
                <div class="form-group">
                    <label>Image: <input type="file" name="post_image" accept="image/*"></label>
                </div>
                <div id="image-preview"></div>
                <button type="submit" class="button">Create Post</button>
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