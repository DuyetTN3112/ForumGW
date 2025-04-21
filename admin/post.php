<?php
include './includes/DatabaseConnection.php';
include './includes/DatabaseFunctions.php';
// Số bài viết trên mỗi trang
$limit = 3;

// Lấy trang hiện tại từ query string, nếu không có thì mặc định là 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
// Lấy tất cả bài viết với phân trang
$posts = getAllPosts($page, $limit);
$data = [
    'posts' => $posts,
    'page_title' => 'Posts'
];

// Lấy tổng số bài viết để tính số trang
$totalPosts = countTotalPosts(); // Bạn cần tạo hàm này trong DatabaseFunctions.php
$totalPages = ceil($totalPosts / $limit);

// Include the view file
include './admin_templates/post.html.php';
?>