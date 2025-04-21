<?php
// Ghi log để debug
error_log('Delete post request received. Post ID: ' . ($_GET['postId'] ?? 'No post ID'));
error_log('Current script path: ' . __FILE__);
error_log('Current working directory: ' . getcwd());

include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../includes/DatabaseFunction_admin.php';
include __DIR__ . '/../includes/DatabaseFunctions.php';

session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    error_log('User not logged in');
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit;
}

// Lấy kết nối PDO
$pdo = getConnection();

// Kiểm tra quyền admin
if (!isAdmin($pdo, $_SESSION['user_id'])) {
    error_log('Unauthorized: Not an admin');
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này']);
    exit;
}

// Lấy ID post từ request
$postId = $_GET['postId'] ?? null;

if (!$postId) {
    error_log('No post ID provided');
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy bài viết']);
    exit;
}

try {
    // Gọi hàm xóa post với quyền admin
    $result = deletePost($postId, $_SESSION['user_id'], true);
    
    if ($result) {
        error_log('Post deleted successfully');
        echo json_encode(['success' => true]);
    } else {
        error_log('Failed to delete post');
        echo json_encode(['success' => false, 'message' => 'Không thể xóa bài viết']);
    }
} catch (Exception $e) {
    error_log('Delete post error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
exit;