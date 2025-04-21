<?php
header('Content-Type: application/json');
session_start();
include './includes/DatabaseConnection.php';
include './includes/DatabaseFunctions.php';

$query = $_GET['query'] ?? '';
$type = '';

// Xác định loại tìm kiếm
if (strpos($query, '@') === 0) {
    $type = 'user';
    $keyword = substr($query, 1);
    $results = searchUsers($keyword);
} elseif (strpos($query, '#') === 0) {
    $type = 'module';
    $keyword = substr($query, 1);
    $results = searchModules($keyword);
} else {
    $type = 'post';
    $results = searchPosts($query);
}

echo json_encode([
    'type' => $type,
    'results' => $results
]);