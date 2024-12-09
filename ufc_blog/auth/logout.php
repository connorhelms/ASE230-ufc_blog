<?php
require_once '../lib/auth.php';
require_once '../lib/db.php';

if (isset($_SESSION['user_id'])) {
    $db = new Database();
    // Clear remember token in database
    $db->query(
        "UPDATE users SET remember_token = NULL WHERE user_id = ?",
        [$_SESSION['user_id']]
    );
}

// Clear remember me cookie
setcookie('remember_token', '', time() - 3600, '/');

// Destroy session
session_destroy();

// Redirect to home page
header('Location: /ufc_blog/');
exit();