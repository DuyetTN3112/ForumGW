<?php
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunction.php';


session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Xử lý form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $postId = $_POST['post_id'];
        $userId = $_SESSION['user_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $image = null; // Xử lý upload ảnh nếu cần
        $moduleIds = isset($_POST['modules']) ? $_POST['modules'] : [];

        // Validate module IDs
        $pdo = getConnection();
        if (validateModuleIds($pdo, $moduleIds)) {
            $result = updatePost($userId, $postId, $title, $content, $image, $moduleIds);
            
            if ($result) {
                $_SESSION['success_message'] = "Bài viết đã được cập nhật thành công.";
                header("Location: admin_index.php?page=user");
                exit();
            } else {
                $_SESSION['error_message'] = "Có lỗi xảy ra khi cập nhật bài viết.";
                header("Location: admin_index.php?page=user");
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Các module không hợp lệ.";
            header("Location: admin_index.php?page=user");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: admin_index.php?page=user");
        exit();
    }
}