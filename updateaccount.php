<?php
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunctions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id']; // ID của người dùng hiện tại
    $data = [
        'username' => $_POST['username'] ?? null,
        'email' => $_POST['email'] ?? null,
        'phone_number' => $_POST['phone_number'] ?? null,
        'password' => $_POST['password'] ?? null,
        'avatar' => $_POST['avatar'] ?? null
    ];

    try {
        updateUser ($userId, $userId, $data);
        header('Location: index.php?page=user'); // Chuyển hướng về trang người dùng
        exit();
    } catch (Exception $e) {
        // Xử lý lỗi
        header('Location: index.php?page=user&error=' . urlencode($e->getMessage()));
        exit();
    }
}

?>