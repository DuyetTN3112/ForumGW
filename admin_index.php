<?php
// Kiểm tra xem phiên đã được khởi động chưa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Nếu là admin, bạn có thể điều hướng đến các phần khác nhau của admin
$page_title = "Home"; // Tiêu đề trang admin
$content = "admin/post.php"; // Nội dung mặc định cho trang admin

// Handle different admin pages based on GET parameter
if (isset($_GET['page'])) {
    switch ($_GET['page']) {
        case 'modules':
            $page_title = "Modules";
            $content = "admin/module.php";
            break;
        case 'user':
            $page_title = "My Account";
            $content = "admin/user.php";
            break;
        case 'users':
            $page_title = "List Users";
            $content = "admin/users.php";
            break;
        default:
            $page_title = "Post";
            $content = "admin/post.php";
    }
}
include './admin_templates/admin_layout.html.php';