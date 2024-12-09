<?php
require_once 'lib/db.php';
require_once 'lib/auth.php';
require_once 'lib/functions.php';

$db = new Database();

// Get UFC history fact for today
$today = date('n-j');
$fact = $db->query(
    "SELECT * FROM ufc_history WHERE month = ? AND day = ?",
    [date('n'), date('j')]
)->fetch(PDO::FETCH_ASSOC);

// Get recent posts
$recentPosts = $db->query("
    SELECT p.*, u.username, 
           (SELECT COUNT(*) FROM likes WHERE post_id = p.post_id) as like_count
    FROM posts p 
    JOIN users u ON p.user_id = u.user_id 
    ORDER BY created_at DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Get upcoming event
$upcomingEvent = $db->query("
    SELECT * FROM events 
    WHERE event_date > NOW() 
    ORDER BY event_date ASC 
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);

// Get featured fighter
$featuredFighter = $db->query("
    SELECT * FROM fighters 
    ORDER BY RAND() 
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UFC Blog - Home</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include 'theme/header.php'; ?>
    
    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Welcome to UFC Blog</h1>
                <p>Your source for UFC news, events, and fighter information</p>
                <?php if (!isLoggedIn()): ?>
                    <div class="hero-buttons">
                        <a href="/ufc_blog/auth/login.php" class="button">Login</a>
                        <a href="/ufc_blog/auth/register.php" class="button secondary">Register</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php if ($fact): ?>
            <section class="ufc-history">
                <div class="card">
                    <h2>This Day in UFC History</h2>
                    <div class="history-content">
                        <div class="history-date">
                            <?= date('F j, Y', strtotime($fact['year'].'-'.$fact['month'].'-'.$fact['day'])) ?>
                        </div>
                        <?php if ($fact['event_name']): ?>
                            <div class="event-name"><?= htmlspecialchars($fact['event_name']) ?></div>
                        <?php endif; ?>
                        <p class="history-fact"><?= htmlspecialchars($fact['fact']) ?></p>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <section class="recent-posts">
            <div class="section-header">
                <h2>Recent Posts</h2>
                <a href="/ufc_blog/posts/" class="view-all">View All Posts</a>
            </div>
            <div class="posts-grid">
                <?php foreach ($recentPosts as $post): ?>
                    <article class="post-card">
                        <?php if ($post['image_url']): ?>
                            <div class="post-image">
                                <img src="<?= htmlspecialchars($post['image_url']) ?>" 
                                     alt="<?= htmlspecialchars($post['title']) ?>">
                            </div>
                        <?php endif; ?>
                        <div class="post-content">
                            <h3>
                                <a href="/ufc_blog/posts/detail.php?id=<?= $post['post_id'] ?>">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h3>
                            <div class="post-meta">
                                <span class="author">By <?= htmlspecialchars($post['username']) ?></span>
                                <span class="date"><?= formatDate($post['created_at']) ?></span>
                                <span class="likes"><?= $post['like_count'] ?> likes</span>
                            </div>
                            <p class="post-excerpt">
                                <?= truncateText(htmlspecialchars($post['content']), 150) ?>
                            </p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <?php if (isLoggedIn()): ?>
                <div class="create-post">
                    <a href="/ufc_blog/posts/create.php" class="button">Create New Post</a>
                </div>
            <?php endif; ?>
        </section>

        <?php if ($upcomingEvent): ?>
            <section class="upcoming-event">
                <h2>Next UFC Event</h2>
                <div class="event-card featured">
                    <?php if ($upcomingEvent['image_url']): ?>
                        <img src="<?= htmlspecialchars($upcomingEvent['image_url']) ?>" 
                             alt="<?= htmlspecialchars($upcomingEvent['title']) ?>">
                    <?php endif; ?>
                    <div class="event-info">
                        <h3><?= htmlspecialchars($upcomingEvent['title']) ?></h3>
                        <p class="event-date">
                            <?= formatDate($upcomingEvent['event_date'], 'F j, Y - g:i A') ?>
                        </p>
                        <p class="event-location"><?= htmlspecialchars($upcomingEvent['location']) ?></p>
                        <a href="/ufc_blog/events/detail.php?id=<?= $upcomingEvent['event_id'] ?>" 
                           class="button">View Details</a>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($featuredFighter): ?>
            <section class="featured-fighter">
                <h2>Featured Fighter</h2>
                <div class="fighter-card featured">
                    <?php if ($featuredFighter['image_url']): ?>
                        <img src="<?= htmlspecialchars($featuredFighter['image_url']) ?>" 
                             alt="<?= htmlspecialchars($featuredFighter['name']) ?>">
                    <?php endif; ?>
                    <div class="fighter-info">
                        <h3><?= htmlspecialchars($featuredFighter['name']) ?></h3>
                        <p class="weight-class">
                            Weight Class: <?= htmlspecialchars($featuredFighter['weight_class']) ?>
                        </p>
                        <p class="record">Record: <?= htmlspecialchars($featuredFighter['record']) ?></p>
                        <a href="/ufc_blog/fighters/detail.php?id=<?= $featuredFighter['fighter_id'] ?>" 
                           class="button">View Profile</a>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <?php include 'theme/footer.php'; ?>
    <script src="/ufc_blog/theme/js/main.js"></script>
</body>
</html>