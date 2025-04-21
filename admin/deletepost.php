<?php
session_start(); // Start the session to access session variables
include 'includes/DatabaseConnection.php'; // Include the database connection
include 'includes/DatabaseFunctions.php'; // Include the database functions

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'You need to log in to delete a post.';
    $_SESSION['message_type'] = 'error';
    header("Location: admin_index.php?page=user");
    exit;
}

// Lấy ID bài post cần xóa
$postId = $_GET['postId'] ?? null;

if (is_null($postId) || !is_numeric($postId)) {
    $_SESSION['message'] = 'Invalid post ID.';
    $_SESSION['message_type'] = 'error';
    header("Location: admin_index.php?page=user");
    exit;
}

try {
    // Gọi hàm xóa bài post từ DatabaseFunctions
    $result = deletePost($postId, $_SESSION['user_id']);

    if ($result) {
        $_SESSION['message'] = 'Xóa bài viết thành công';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Failed to delete the post.';
        $_SESSION['message_type'] = 'error';
    }
} catch (Exception $e) {
    $_SESSION['message'] = $e->getMessage();
    $_SESSION['message_type'] = 'error';
}

// Redirect back to the user profile page
header("Location: admin_index.php?page=user");
exit;