<?php
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunctions.php';

header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit;
}

try {
    // Kiểm tra xem có file upload không
    if (!isset($_FILES['avatar'])) {
        throw new Exception("Không có ảnh được tải lên");
    }

    // Upload ảnh
    $avatarPath = uploadImage($_FILES['avatar'], 'uploads/avatars/');

    // Cập nhật avatar cho user
    $updateData = ['avatar' => $avatarPath];
    updateUser($_SESSION['user_id'], $_SESSION['user_id'], $updateData);
    // Sau khi update thành công

    // Trả về kết quả
    echo json_encode([
        'success' => true, 
        'avatarPath' => $avatarPath
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
exit;