<?php
include './includes/DatabaseConnection.php';
include './includes/DatabaseFunctions.php';

// Get the database connection
// Đảm bảo rằng request này luôn được xử lý như users
$_GET['type'] = 'users';

// Get the database connection
$pdo = getConnection();

// Lấy toàn bộ danh sách người dùng (không phân trang)
$users = getAllUsers(); // Loại bỏ tham số phân trang

// Dữ liệu để truyền vào view
$data = [
    'user' => $users
];

// Include the view file
include './admin_templates/users.html.php';
?>