<?php
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunctions.php';

// Get the database connection
$pdo = getConnection();

// Include the view file
include './admin_templates/comment.html.php';
?>