<?php
function formatDate($date, $format = 'F j, Y') {
    return date($format, strtotime($date));
}

function truncateText($text, $length = 150) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

function hasLiked($post_id, $user_id, $db) {
    $result = $db->query(
        "SELECT 1 FROM likes WHERE post_id = ? AND user_id = ?",
        [$post_id, $user_id]
    )->fetch();
    return $result !== false;
}

function generateSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/\s+/', '-', $text);
    return trim($text, '-');
}

function getLikeCount($postId, $db) {
    $result = $db->query(
        "SELECT COUNT(*) as count FROM likes WHERE post_id = ?",
        [$postId]
    )->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}
