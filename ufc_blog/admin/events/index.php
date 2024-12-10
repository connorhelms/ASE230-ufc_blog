<?php
require_once '../../lib/db.php';
require_once '../../lib/auth.php';

requireAdmin();
$db = new Database();

$events = $db->query(
    "SELECT * FROM events ORDER BY event_date DESC"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Events - Admin - UFC Blog</title>
    <link rel="stylesheet" href="/ufc_blog/theme/style.css">
</head>
<body>
    <?php include '../../theme/header.php'; ?>
    <main>
        <div class="container">
            <div class="admin-header">
                <h1>Manage Events</h1>
                <a href="create.php" class="button">Add New Event</a>
            </div>

            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event): ?>
                            <tr>
                                <td><?= htmlspecialchars($event['title']) ?></td>
                                <td><?= date('F j, Y', strtotime($event['event_date'])) ?></td>
                                <td><?= htmlspecialchars($event['location']) ?></td>
                                <td class="actions">
                                    <a href="edit.php?id=<?= $event['event_id'] ?>" class="button small">Edit</a>
                                    <form method="POST" action="delete.php" style="display: inline;">
                                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                                        <button type="submit" class="button small delete" 
                                                onclick="return confirm('Are you sure you want to delete this event?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <?php include '../../theme/footer.php'; ?>
</body>
</html>