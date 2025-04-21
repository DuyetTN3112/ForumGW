<?php
session_start();
include './includes/DatabaseConnection.php';
include './includes/DatabaseFunctions.php';

header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'Bạn phải đăng nhập để thực hiện thao tác này'
    ]);
    exit;
}

$userId = $_SESSION['user_id'];
$postId = $_POST['post_id'] ?? null;
$type = $_POST['type'] ?? null;

if (!$postId || !$type || !in_array($type, ['like', 'dislike'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'Dữ liệu không hợp lệ'
    ]);
    exit;
}

// Gọi hàm xử lý tương tác từ DatabaseFunctions.php
$result = handlePostInteraction($userId, $postId, $type);
echo json_encode($result);