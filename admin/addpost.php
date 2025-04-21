<?php
include './includes/DatabaseConnection.php';
include './includes/DatabaseFunctions.php';

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

    // Validate module IDs before creating post
    $pdo = getConnection();
    if (validateModuleIds($pdo, $moduleIds)) {
        $postId = createPost($userId, $title, $content, $image, $moduleIds);

        if ($postId) {
            header("Location: admin_index.php");
            exit;
        } else {
            $error = "Failed to create post.";
        }
    } else {
        $error = "Invalid module selection.";
    }
}

// Include the view file
include './admin_templates/post.html.php';