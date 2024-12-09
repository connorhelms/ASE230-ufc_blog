<?php
require_once '../../lib/db.php';
require_once '../../lib/auth.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fighter_id'])) {
    $db = new Database();
    
    // Get fighter image before deletion
    $fighter = $db->query(
        "SELECT image_url FROM fighters WHERE fighter_id = ?",
        [$_POST['fighter_id']]
    )->fetch(PDO::FETCH_ASSOC);
    
    if ($fighter && $fighter['image_url']) {
        $image_path = "../../" . $fighter['image_url'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    $db->query("DELETE FROM fighters WHERE fighter_id = ?", [$_POST['fighter_id']]);
}

header('Location: index.php');
exit();