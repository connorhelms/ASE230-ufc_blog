<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';

$db = new Database();
$upcoming = $db->query(
    "SELECT * FROM events 
     WHERE event_date >= CURDATE() 
     ORDER BY event_date ASC"
)->fetchAll(PDO::FETCH_ASSOC);

$past = $db->query(
    "SELECT * FROM events 
     WHERE event_date < CURDATE() 
     ORDER BY event_date DESC 
     LIMIT 5"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Events - UFC Blog</title>
</head>
<body>
    <?php include '../theme/header.php'; ?>
    <main>
    <div class="container">
   <div class="page-header">
       <h1>UFC Events</h1>
       <?php if (isAdmin()): ?>
           <a href="/ufc_blog/admin/events/create.php" class="button">Add New Event</a>
       <?php endif; ?>
   </div>
        
        <section>
            <h2>Upcoming Events</h2>
            <div class="events-grid">
                <?php foreach ($upcoming as $event): ?>
                    <div class="event-card">
                        <?php if ($event['image_url']): ?>
                            <img src="<?= htmlspecialchars($event['image_url']) ?>" 
                                 alt="<?= htmlspecialchars($event['title']) ?>">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($event['title']) ?></h3>
                        <p>Date: <?= date('F j, Y', strtotime($event['event_date'])) ?></p>
                        <p>Location: <?= htmlspecialchars($event['location']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section>
            <h2>Past Events</h2>
            <div class="events-grid past">
                <?php foreach ($past as $event): ?>
                    <div class="event-card">
                        <h3><?= htmlspecialchars($event['title']) ?></h3>
                        <p>Date: <?= date('F j, Y', strtotime($event['event_date'])) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <?php include '../theme/footer.php'; ?>
</body>
</html>