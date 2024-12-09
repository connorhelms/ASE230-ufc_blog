<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';

$db = new Database();
$fighter = $db->query(
    "SELECT * FROM fighters WHERE fighter_id = ?",
    [$_GET['id']]
)->fetch(PDO::FETCH_ASSOC);

if (!$fighter) {
    header('Location: /ufc_blog/fighters/');
    exit();
}

// Get related posts about this fighter
$related_posts = $db->query(
    "SELECT p.*, u.username, 
     (SELECT COUNT(*) FROM likes WHERE post_id = p.post_id) as like_count
     FROM posts p 
     JOIN users u ON p.user_id = u.user_id 
     WHERE p.category = 'fighter' 
     AND p.content LIKE ?
     ORDER BY p.created_at DESC 
     LIMIT 5",
    ['%' . $fighter['name'] . '%']
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($fighter['name']) ?> - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../theme/header.php'; ?>
    
    <main>
        <div class="container">
            <div class="fighter-profile">
                <?php if ($fighter['image_url']): ?>
                    <div class="fighter-image">
                        <img src="<?= htmlspecialchars($fighter['image_url']) ?>" 
                             alt="<?= htmlspecialchars($fighter['name']) ?>">
                    </div>
                <?php endif; ?>
                
                <div class="fighter-info">
                    <h1><?= htmlspecialchars($fighter['name']) ?></h1>
                    
                    <div class="fighter-stats">
                        <div class="stat">
                            <label>Weight Class:</label>
                            <span><?= htmlspecialchars($fighter['weight_class']) ?></span>
                        </div>
                        
                        <div class="stat">
                            <label>Record:</label>
                            <span><?= htmlspecialchars($fighter['record']) ?></span>
                        </div>
                    </div>
                    
                    <div class="fighter-bio">
                        <h2>Biography</h2>
                        <p><?= nl2br(htmlspecialchars($fighter['bio'])) ?></p>
                    </div>
                </div>
            </div>

            <?php if ($related_posts): ?>
                <section class="related-posts">
                    <h2>Related Posts</h2>
                    <div class="posts-grid">
                        <?php foreach ($related_posts as $post): ?>
                            <article class="post-card">
                                <h3>
                                    <a href="/ufc_blog/posts/detail.php?id=<?= $post['post_id'] ?>">
                                        <?= htmlspecialchars($post['title']) ?>
                                    </a>
                                </h3>
                                <div class="post-meta">
                                    <span class="author">By <?= htmlspecialchars($post['username']) ?></span>
                                    <span class="date"><?= date('F j, Y', strtotime($post['created_at'])) ?></span>
                                    <span class="likes"><?= $post['like_count'] ?> likes</span>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if (isAdmin()): ?>
                <div class="admin-actions">
                    <a href="/ufc_blog/admin/fighters/edit.php?id=<?= $fighter['fighter_id'] ?>" 
                       class="button">Edit Fighter</a>
                    <form method="POST" action="/ufc_blog/admin/fighters/delete.php" style="display: inline;">
                        <input type="hidden" name="fighter_id" value="<?= $fighter['fighter_id'] ?>">
                        <button type="submit" class="button delete" 
                                onclick="return confirm('Are you sure you want to delete this fighter?')">
                            Delete Fighter
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include '../theme/footer.php'; ?>
</body>
</html>