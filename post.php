<?php
include './includes/DatabaseConnection.php';
include './includes/DatabaseFunctions.php';

// Số bài viết trên mỗi trang
$limit = 3;

// Lấy filter nếu có
$filter = isset($_GET['filter']) ? $_GET['filter'] : null;

// Lấy trang hiện tại từ query string, nếu không có thì mặc định là 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($filter) {
    // Nếu có filter, lấy danh sách bài viết theo filter
    $result = getPostsByFilter($filter, $page, $limit);
    $posts = $result['posts'];
    $totalPosts = $result['total_posts'];
    $totalPages = $result['total_pages'];
} else {
    // Lấy tất cả bài viết với phân trang
    $posts = getAllPosts($page, $limit);
    $totalPosts = countTotalPosts(); 
    $totalPages = ceil($totalPosts / $limit);
}

$data = [
    'posts' => $posts,
    'page_title' => 'Posts',
    'current_filter' => $filter
];

// Include the view file
include './templates/post.html.php';
?>