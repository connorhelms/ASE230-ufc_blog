<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $db = new Database();
    
    $post = $db->query(
        "SELECT user_id, image_url FROM posts WHERE post_id = ?",
        [$_POST['post_id']]
    )->fetch(PDO::FETCH_ASSOC);
    
    if ($post && (isAdmin() || $post['user_id'] === $_SESSION['user_id'])) {
        if ($post['image_url']) {
            $image_path = "../" . $post['image_url'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        $db->query("DELETE FROM posts WHERE post_id = ?", [$_POST['post_id']]);
    }
}

header('Location: index.php');
exit();
