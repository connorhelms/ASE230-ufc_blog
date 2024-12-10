<?php
require_once '../../lib/db.php';
require_once '../../lib/auth.php';
require_once '../../lib/upload.php';

requireAdmin();
$db = new Database();

$event = $db->query(
    "SELECT * FROM events WHERE event_id = ?",
    [$_GET['id']]
)->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $image_url = $event['image_url'];
        
        if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === 0) {
            if ($event['image_url']) {
                unlink("../../" . $event['image_url']);
            }
            $image_url = uploadImage($_FILES['event_image'], '../../data/events');
        }
        
        $db->query(
            "UPDATE events SET title = ?, event_date = ?, location = ?, description = ?, image_url = ? 
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
        
        header('Location: index.php');
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Event - Admin - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../../theme/header.php'; ?>
    <main>
        <div class="container">
            <h1>Edit Event</h1>
            
            <?php if (isset($error)): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Title:
                        <input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" required>
                    </label>
                </div>
                
                <div class="form-group">
                    <label>Event Date and Time:
                        <input type="datetime-local" 
                               name="event_date" 
                               value="<?= date('Y-m-d\TH:i', strtotime($event['event_date'])) ?>" 
                               required>
                    </label>
                </div>
                
                <div class="form-group">
                    <label>Location:
                        <input type="text" name="location" value="<?= htmlspecialchars($event['location']) ?>" required>
                    </label>
                </div>
                
                <div class="form-group">
                    <label>Description:
                        <textarea name="description" required rows="6"><?= htmlspecialchars($event['description']) ?></textarea>
                    </label>
                </div>
                
                <?php if ($event['image_url']): ?>
                    <div class="current-image">
                        <p>Current Image:</p>
                        <img src="<?= htmlspecialchars($event['image_url']) ?>" alt="Event" style="max-width: 200px">
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Update Image:
                        <input type="file" name="event_image" accept="image/*">
                    </label>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="button">Update Event</button>
                    <a href="index.php" class="button">Cancel</a>
                </div>
            </form>
        </div>
    </main>
    <?php include '../../theme/footer.php'; ?>
</body>
</html>