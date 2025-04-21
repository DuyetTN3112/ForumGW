<?php
session_start();
require 'includes/DatabaseFunctions.php'; // Kết nối tới các hàm truy vấn

if (!isset($_SESSION['user_id']) || !isAdmin($pdo, $_SESSION['user_id'])) {
    // Kiểm tra xem người dùng có phải là admin không
    header('Location: index.php'); // Chuyển hướng nếu không phải admin
    exit;
}

if (isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    $currentUserId = $_SESSION['user_id'];

    try {
        deleteUser ($userId, $currentUserId); // Gọi hàm xóa người dùng
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User  ID is required']);
}
?>