<?php
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunctions.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized: Please log in to comment.");
}

// Lấy thông tin từ form
$postId = $_POST['post_id'] ?? null;
$content = $_POST['content'] ?? '';
$image = null;

// Kiểm tra xem nội dung bình luận có rỗng không
if (empty($content)) {
    die("Comment content cannot be empty.");
}

// Kiểm tra xem có file hình ảnh hay không
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $targetDir = "uploads/";
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    // Di chuyển file upload đến thư mục đích
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
        $image = $targetFilePath; // Lưu đường dẫn hình ảnh
    } else {
        die("Error uploading image.");
    }
}

try {
    // Thêm bình luận vào cơ sở dữ liệu
    $commentId = createComment($_SESSION['user_id'], $postId, $content, $image);
    header("Location: index.php?post_id=" . $postId); // Chuyển hướng về trang bài viết
    exit;
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>