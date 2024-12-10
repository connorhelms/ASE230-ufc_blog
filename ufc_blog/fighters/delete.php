<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fighter_id'])) {
    $db = new Database();
    
    $fighter = $db->query(
        "SELECT image_url FROM fighters WHERE fighter_id = ?",
        [$_POST['fighter_id']]
    )->fetch(PDO::FETCH_ASSOC);
    
    if ($fighter && $fighter['image_url']) {
        unlink(".." . $fighter['image_url']);
    }
    
    $db->query(
        "DELETE FROM fighters WHERE fighter_id = ?",
        [$_POST['fighter_id']]
    );
}

header('Location: /fighters/');
exit();