<?php
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunctions.php';

// Get the database connection
$pdo = getConnection();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start(); 
    $userId = $_SESSION['user_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = null; 
    $moduleIds = isset($_POST['modules']) ? $_POST['modules'] : [];

    // Debug: In ra toàn bộ thông tin file
    error_log('FILES content: ' . print_r($_FILES, true));

    // Xử lý upload ảnh
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        try {
            $image = uploadImage($_FILES['image']);
            
            // Debug: In ra đường dẫn ảnh
            error_log('Uploaded Image Path: ' . $image);
        } catch (Exception $e) {
            $error = $e->getMessage();
            error_log('Image Upload Error: ' . $error);
        }
    }

    if (validateModuleIds($pdo, $moduleIds)) {
        try {
            $postId = createPost($userId, $title, $content, $image, $moduleIds);

            if ($postId) {
                header("Location: index.php");
                exit;
            } else {
                $error = "Failed to create post.";
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            error_log('Post Creation Error: ' . $error);
        }
    } else {
        $error = "Invalid module selection.";
    }
}

// Include the view file
include 'templates/post.html.php';