<?php
require_once '../lib/db.php';
require_once '../lib/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    exit(json_encode(['error' => 'Login required']));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['post_id'])) {
    http_response_code(400);
    exit(json_encode(['error' => 'Invalid request']));
}

try {
    $db = new Database();
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    
    $exists = $db->query(
        "SELECT 1 FROM likes WHERE post_id = ? AND user_id = ?",
        [$post_id, $user_id]
    )->fetch();
    
    if ($exists) {
        $db->query("DELETE FROM likes WHERE post_id = ? AND user_id = ?", [$post_id, $user_id]);
    } else {
        $db->query("INSERT INTO likes (post_id, user_id) VALUES (?, ?)", [$post_id, $user_id]);
    }
    
    $count = $db->query(
        "SELECT COUNT(*) as count FROM likes WHERE post_id = ?",
        [$post_id]
    )->fetch()['count'];
    
    echo json_encode(['count' => $count]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}