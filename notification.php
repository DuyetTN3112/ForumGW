<?php
session_start(); // Start the session to access session variables
include 'includes/DatabaseConnection.php'; // Include the database connection
include 'includes/DatabaseFunctions.php'; // Include the database functions

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Lấy ID người dùng từ session
$userId = $_SESSION['user_id'];

// Lấy thông tin người dùng hiện tại
$user = getUserById($_SESSION['user_id']);

// Xác định trang hiện tại
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10; // Số thông báo trên mỗi trang

try {
    // Lấy danh sách thông báo
    $notificationData = getUserNotifications($userId, $page, $limit);
    
    // Xử lý các hành động với thông báo
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'mark_read':
                    // Đánh dấu một thông báo cụ thể là đã đọc
                    if (isset($_POST['notification_id'])) {
                        $notificationId = intval($_POST['notification_id']);
                        markNotificationAsRead($notificationId, $userId);
                        header("Location: notification.php?page=$page&success=1");
                        exit();
                    }
                    break;
                
                case 'mark_all_read':
                    // Đánh dấu tất cả thông báo là đã đọc
                    markAllNotificationsAsRead($userId);
                    header("Location: notification.php?page=$page&success=1");
                    exit();
                    break;
            }
        }
    }
} catch (Exception $e) {
    // Xử lý lỗi
    $error = $e->getMessage();
}
?>