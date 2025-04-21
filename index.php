<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If user arrived from registration with success message, redirect to login
    if (isset($_SESSION['success_message'])) {
        header("Location: login.php");
        exit;
    }
    // Otherwise, redirect to register
    header("Location: register.php");
    exit;
}

// Check if user is admin, redirect to admin dashboard if true
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    header("Location: admin_index.php");
}
else {
    // For regular users, include the main layout
$page_title = "Home"; // Default page title
$content = "post.php"; // Default content file

// Handle different pages based on GET parameter
if (isset($_GET['page'])) {
    switch ($_GET['page']) {
        case 'modules':
            $page_title = "Modules";
            $content = "module.php";
            break;
        case 'feedback':
            $page_title = "Feedback";
            $content = 'feedback.php';
            break;
        case 'user':
            $page_title = "My Account";
            $content = "user.php";
            break;
        case 'users':
            $page_title = "List Users";
            $content = "users.php";
            break;
        default:
            $page_title = "Post";
            $content = "post.php";
    }
}
    // Ensure the content file exists before including
    if (!file_exists($content)) {
        // Set a default error content or redirect
        $content = "post.php";
        $_SESSION['message'] = "Requested page not found.";
        $_SESSION['message_type'] = "error";
    }
}

include 'templates/layout.html.php';