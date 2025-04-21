<?php
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunctions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = $_POST['comment_id'] ?? null;
    $content = $_POST['content'] ?? null;

    // Kiểm tra nếu người dùng đã đăng nhập
    if (!isset($_SESSION['user_id'])) {
        die("Unauthorized: You must be logged in to update a comment.");
    }

    // Kiểm tra xem bình luận có tồn tại hay không
    if (!$commentId || !$content) {
        die("Invalid input: Comment ID and content are required.");
    }

    try {
        // Kiểm tra quyền sở hữu bình luận
        if (!checkOwnership(getConnection(), 'comment', $commentId, $_SESSION['user_id'])) {
            die("Unauthorized: You cannot edit this comment.");
        }

        // Cập nhật bình luận
        $result = updateComment($commentId, $_SESSION['user_id'], $content);

        if ($result) {
            header("Location: admin_index.php");
            exit;
        } else {
            throw new Exception("Failed to update comment.");
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>