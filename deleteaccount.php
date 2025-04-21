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

try {
    // Gọi hàm xóa user từ DatabaseFunctions
    $result = deleteUser($_SESSION['user_id'], $_SESSION['user_id']);

    if ($result) {
        // Xóa tài khoản thành công
        // Hủy session
        session_unset();
        session_destroy();

        // Chuyển hướng về trang chính với thông báo thành công
        $_SESSION['message'] = 'Tài khoản của bạn đã được xóa.';
        $_SESSION['message_type'] = 'success';
        header("Location: index.php");
        exit;
    } else {
        // Xóa tài khoản thất bại
        $_SESSION['message'] = 'Không thể xóa tài khoản. Vui lòng thử lại.';
        $_SESSION['message_type'] = 'error';
        header("Location: index.php?page=user");
        exit;
    }
} catch (Exception $e) {
    // Xử lý nếu có lỗi
    $_SESSION['message'] = $e->getMessage();
    $_SESSION['message_type'] = 'error';
    header("Location: index.php?page=user");
    exit;
}