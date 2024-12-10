<?php
require_once '../../lib/db.php';
require_once '../../lib/auth.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $db = new Database();
    
    // Get event image before deletion
    $event = $db->query(
        "SELECT image_url FROM events WHERE event_id = ?",
        [$_POST['event_id']]
    )->fetch(PDO::FETCH_ASSOC);
    
    if ($event && $event['image_url']) {
        $image_path = "../../" . $event['image_url'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    $db->query("DELETE FROM events WHERE event_id = ?", [$_POST['event_id']]);
}

header('Location: index.php');
exit();