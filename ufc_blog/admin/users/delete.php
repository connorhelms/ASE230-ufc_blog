<?php
require_once '../../lib/db.php';
require_once '../../lib/auth.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $db = new Database();
    
    // Prevent admin from deleting themselves
    if ($_POST['user_id'] !== $_SESSION['user_id']) {
        $db->query("DELETE FROM users WHERE user_id = ?", [$_POST['user_id']]);
    }
}

header('Location: index.php');
exit();