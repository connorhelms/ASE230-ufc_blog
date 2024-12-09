<?php
function uploadImage($file, $directory) {
    if (!isset($file) || $file['error'] !== 0) {
        return '';
    }

    // Create directory if it doesn't exist
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    // Generate unique filename
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $filepath = $directory . '/' . $filename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Return the relative path for database storage
        return '/ufc_blog/data/' . basename($directory) . '/' . $filename;
    }

    return '';
}