<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';

$db = new Database();
$event = $db->query(
    "SELECT * FROM events WHERE event_id = ?",
    [$_GET['id']]
)->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header('Location: /events/');
    exit();
}

$related_posts = $db->query(
    "SELECT p.*, u.username FROM posts p 
     JOIN users u ON p.user_id = u.user_id 
     WHERE p.category = 'event' 
     AND p.content LIKE ?
     ORDER BY p.created_at DESC 
     LIMIT 5",
    ['%' . $event['title'] . '%']
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($event['title']) ?> - UFC Blog</title>
</head>
<body>
    <?php include '../theme/header.php'; ?>
    <main>
        <div class="event-detail">
            <?php if ($event['image_url']): ?>
                <img src="<?= htmlspecialchars($event['image_url']) ?>" 
                     alt="<?= htmlspecialchars($event['title']) ?>">
            <?php endif; ?>
            
            <h1><?= htmlspecialchars($event['title']) ?></h1>
            
            <div class="event-info">
                <p><strong>Date:</strong> <?= date('F j, Y', strtotime($event['event_date'])) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
            </div>
            
            <div class="event-description">
                <?= nl2br(htmlspecialchars($event['description'])) ?>
            </div>
            
            <?php if ($related_posts): ?>
                <div class="related-posts">
                    <h2>Related Posts</h2>
                    <?php foreach ($related_posts as $post): ?>
                        <article class="post-card">
                            <h3>
                                <a href="/posts/detail.php?id=<?= $post['post_id'] ?>">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h3>
                            <p>By <?= htmlspecialchars($post['username']) ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isAdmin()): ?>
                <div class="admin-actions">
                    <a href="/admin/events/edit.php?id=<?= $event['event_id'] ?>" 
                       class="button">Edit Event</a>
                    <form method="POST" action="/admin/events/delete.php" style="display: inline;">
                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                        <button type="submit" class="button delete" 
                                onclick="return confirm('Are you sure?')">Delete Event</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <?php include '../theme/footer.php'; ?>
</body>
</html>