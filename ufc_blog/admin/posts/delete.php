<?php
require_once '../../lib/db.php';
require_once '../../lib/auth.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $db = new Database();
    
    // Get post image before deletion
    $post = $db->query(
        "SELECT image_url FROM posts WHERE post_id = ?",
        [$_POST['post_id']]
    )->fetch(PDO::FETCH_ASSOC);
    
    if ($post && $post['image_url']) {
        $image_path = "../../" . $post['image_url'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    // Delete post (comments and likes will be deleted automatically due to CASCADE)
    $db->query("DELETE FROM posts WHERE post_id = ?", [$_POST['post_id']]);
}

header('Location: index.php');
exit();