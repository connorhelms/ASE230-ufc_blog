<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';
require_once '../lib/functions.php';

$db = new Database();
$posts = $db->query("
    SELECT p.*, u.username, 
           (SELECT COUNT(*) FROM likes WHERE post_id = p.post_id) as like_count
    FROM posts p 
    JOIN users u ON p.user_id = u.user_id 
    ORDER BY created_at DESC"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Posts - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../theme/header.php'; ?>
    
    <main>
        <div class="container">
            <div class="posts-header">
                <h1>All Posts</h1>
                <?php if (isLoggedIn()): ?>
                    <a href="create.php" class="button">Create New Post</a>
                <?php endif; ?>
            </div>

            <div class="posts-grid">
                <?php foreach ($posts as $post): ?>
                    <article class="post-card">
                        <div class="post-content">
                            <h2>
                                <a href="detail.php?id=<?= $post['post_id'] ?>">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h2>
                            <div class="post-meta">
                                <span>By <?= htmlspecialchars($post['username']) ?></span>
                                <span><?= formatDate($post['created_at']) ?></span>
                                <span><?= $post['like_count'] ?> likes</span>
                            </div>
                            <p><?= truncateText(htmlspecialchars($post['content']), 150) ?></p>
                        </div>
                        <?php if ($post['image_url']): ?>
                            <div class="post-image">
                                <img src="<?= htmlspecialchars($post['image_url']) ?>" 
                                     alt="<?= htmlspecialchars($post['title']) ?>">
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <?php include '../theme/footer.php'; ?>
</body>
</html>