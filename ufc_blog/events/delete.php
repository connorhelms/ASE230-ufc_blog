<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $db = new Database();
    
    $event = $db->query(
        "SELECT image_url FROM events WHERE event_id = ?",
        [$_POST['event_id']]
    )->fetch(PDO::FETCH_ASSOC);
    
    if ($event && $event['image_url']) {
        unlink(".." . $event['image_url']);
    }
    
    $db->query("DELETE FROM events WHERE event_id = ?", [$_POST['event_id']]);
}

header('Location: /events/');
exit();