<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';
require_once '../lib/upload.php';

requireAdmin();
$db = new Database();

$event = $db->query(
    "SELECT * FROM events WHERE event_id = ?",
    [$_GET['id']]
)->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header('Location: /events/');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image_url = $event['image_url'];
    
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === 0) {
        if ($event['image_url']) {
            unlink(".." . $event['image_url']);
        }
        $image_url = uploadImage($_FILES['event_image'], '../data/events');
    }
    
    $db->query(
        "UPDATE events SET 
         title = ?, event_date = ?, location = ?, description = ?, image_url = ? 
         WHERE event_id = ?",
        [
            $_POST['title'],
            $_POST['event_date'],
            $_POST['location'],
            $_POST['description'],
            $image_url,
            $_GET['id']
        ]
    );
    
    header('Location: /events/detail.php?id=' . $_GET['id']);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Event - UFC Blog</title>
</head>
<body>
    <?php include '../theme/header.php'; ?>
    <main>
        <h1>Edit Event</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title:
                    <input type="text" name="title" 
                           value="<?= htmlspecialchars($event['title']) ?>" required>
                </label>
            </div>
            <div class="form-group">
                <label>Date:
                    <input type="datetime-local" name="event_date" 
                           value="<?= date('Y-m-d\TH:i', strtotime($event['event_date'])) ?>" required>
                </label>
            </div>
            <div class="form-group">
                <label>Location:
                    <input type="text" name="location" 
                           value="<?= htmlspecialchars($event['location']) ?>" required>
                </label>
            </div>
            <div class="form-group">
                <label>Description:
                    <textarea name="description" required rows="6"><?= htmlspecialchars($event['description']) ?></textarea>
                </label>
            </div>
            <div class="form-group">
                <?php if ($event['image_url']): ?>
                    <div class="current-image">
                        <p>Current Image:</p>
                        <img src="<?= htmlspecialchars($event['image_url']) ?>" 
                             alt="<?= htmlspecialchars($event['title']) ?>" style="max-width: 200px">
                    </div>
                <?php endif; ?>
                <label>Update Image:
                    <input type="file" name="event_image" accept="image/*">
                </label>
            </div>
            <button type="submit" class="button">Update Event</button>
        </form>
    </main>
    <?php include '../theme/footer.php'; ?>
</body>
</html>