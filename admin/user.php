<?php
include './includes/DatabaseConnection.php';
include './includes/DatabaseFunctions.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Lấy ID người dùng từ session
$userId = $_SESSION['user_id'];

// Lấy thông tin người dùng hiện tại
$user = getUserById($_SESSION['user_id']);

// Lấy các bài post của người dùng
$posts = getUserPosts($userId);

// Lấy các comment của người dùng
$userComments = getUserComments($userId);

// Chuẩn bị dữ liệu để truyền vào view
$data = [
    'user' => $user,
    'posts' => $posts,
    'comments' => $userComments
];

// Bao gồm file view
include './admin_templates/user.html.php';
?>