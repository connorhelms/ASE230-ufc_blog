<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';
require_once '../lib/functions.php';

$db = new Database();

$post = $db->query(
    "SELECT p.*, u.username, 
            (SELECT COUNT(*) FROM likes WHERE post_id = p.post_id) as like_count
     FROM posts p 
     JOIN users u ON p.user_id = u.user_id 
     WHERE p.post_id = ?",
    [$_GET['id']]
)->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header('Location: /ufc_blog/posts/');
    exit();
}

$comments = $db->query(
    "SELECT c.*, u.username 
     FROM comments c 
     JOIN users u ON c.user_id = u.user_id 
     WHERE c.post_id = ? 
     ORDER BY c.created_at DESC",
    [$_GET['id']]
)->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    if (isset($_POST['comment'])) {
        $db->query(
            "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)",
            [$_GET['id'], $_SESSION['user_id'], $_POST['comment']]
        );
        header('Location: /ufc_blog/posts/detail.php?id=' . $_GET['id']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?> - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../theme/header.php'; ?>
    
    <main>
        <div class="container">
            <article class="post-detail">
                <h1><?= htmlspecialchars($post['title']) ?></h1>
                
                <div class="post-meta">
                    <span class="author">By <?= htmlspecialchars($post['username']) ?></span>
                    <span class="date"><?= date('F j, Y', strtotime($post['created_at'])) ?></span>
                    <span class="category">Category: <?= htmlspecialchars(ucfirst($post['category'])) ?></span>
                </div>

                <div class="post-content">
                    <?= nl2br(htmlspecialchars($post['content'])) ?>
                </div>

                <?php if ($post['image_url']): ?>
                    <div class="post-image">
                        <img src="<?= htmlspecialchars($post['image_url']) ?>" 
                             alt="<?= htmlspecialchars($post['title']) ?>">
                    </div>
                <?php endif; ?>

                <button class="like-button <?= hasLiked($post['post_id'], $_SESSION['user_id'], $db) ? 'liked' : '' ?>" data-post-id="<?= $post['post_id'] ?>">
                    <span class="like-count"><?= $post['like_count'] ?></span> 
                    <?= hasLiked($post['post_id'], $_SESSION['user_id'], $db) ? 'Unlike' : 'Like' ?>
                </button>

                <script>
                    document.querySelectorAll('.like-button').forEach(button => {
                    button.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const postId = this.dataset.postId;
        
                    const response = await fetch('/ufc_blog/posts/like.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                    body: `post_id=${postId}`
                });
        
                if (response.ok) {
                    const data = await response.json();
                    this.querySelector('.like-count').textContent = data.count;
                    this.classList.toggle('liked');
                    }
                });
                });
            </script>

                <?php if (isLoggedIn() && ($_SESSION['user_id'] === $post['user_id'] || isAdmin())): ?>
                    <div class="post-actions">
                        <a href="/ufc_blog/posts/edit.php?id=<?= $post['post_id'] ?>" class="button">Edit</a>
                        <form method="POST" action="/ufc_blog/posts/delete.php" style="display: inline;">
                            <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                            <button type="submit" class="button delete" 
                                    onclick="return confirm('Are you sure you want to delete this post?')">
                                Delete
                            </button>
                        </form>
                    </div>
                <?php endif; ?>

                <div class="comments-section">
                    <?php if (isLoggedIn()): ?>
                        <form method="POST" class="comment-form">
                            <h3>Add a Comment</h3>
                            <textarea name="comment" required placeholder="Write a comment..."></textarea>
                            <button type="submit" class="button">Post Comment</button>
                        </form>
                    <?php endif; ?>

                    <h3>Comments (<?= count($comments) ?>)</h3>
                    <?php if (empty($comments)): ?>
                        <p>No comments yet.</p>
                    <?php else: ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment">
                                <div class="comment-header">
                                    <span class="comment-author"><?= htmlspecialchars($comment['username']) ?></span>
                                    <span class="comment-date"><?= formatDate($comment['created_at']) ?></span>
                                </div>
                                <div class="comment-content">
                                    <?= nl2br(htmlspecialchars($comment['content'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </article>
        </div>
    </main>

    <?php include '../theme/footer.php'; ?>
</body>
</html>