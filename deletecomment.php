<?php
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunctions.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Bạn cần đăng nhập để thực hiện thao tác này.';
    $_SESSION['message_type'] = 'error';
    header("Location: login.php");
    exit;
}

// Lấy ID comment cần xóa
$commentId = $_GET['commentId'] ?? null;

if (is_null($commentId) || !is_numeric($commentId)) {
    $_SESSION['message'] = 'ID bình luận không hợp lệ.';
    $_SESSION['message_type'] = 'error';
    header("Location: index.php?page=user");
    exit;
}

try {
    // Gọi hàm xóa comment từ DatabaseFunctions
    $result = deleteComment($commentId, $_SESSION['user_id']);

    if ($result) {
        $_SESSION['message'] = 'Xóa bình luận thành công';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Không thể xóa bình luận. Vui lòng thử lại.';
        $_SESSION['message_type'] = 'error';
    }
} catch (Exception $e) {
    $_SESSION['message'] = $e->getMessage();
    $_SESSION['message_type'] = 'error';
}

// Redirect back to the previous page
header("Location: index.php?");
exit;
?>