<?php
header('Content-Type: application/json');
include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../includes/DatabaseFunction_admin.php';

session_start();

// Logging function
function logError($message) {
    error_log($message);
    // Optional: Write to a log file
    file_put_contents(__DIR__ . '/update_post_log.txt', 
        date('[Y-m-d H:i:s] ') . $message . PHP_EOL, 
        FILE_APPEND
    );
}

// Kiểm tra đăng nhập và quyền admin
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit();
}

// Kiểm tra quyền admin
$pdo = getConnection();
if (!isAdmin($pdo, $_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này']);
    exit();
}

// Xử lý form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Detailed logging of all POST and FILES data
    logError('POST Data: ' . print_r($_POST, true));
    logError('FILES Data: ' . print_r($_FILES, true));

    try {
        // Lấy postId từ form POST
        $postId = isset($_POST['post_id']) ? trim($_POST['post_id']) : null;

        // Kiểm tra postId
        if (!$postId || !is_numeric($postId)) {
            logError('Invalid or missing post ID');
            echo json_encode([
                'success' => false, 
                'message' => 'Không tìm thấy bài viết',
                'debug' => [
                    'post_id' => $postId,
                    'post_data' => $_POST
                ]
            ]);
            exit;
        }

        // Rest of the existing code remains the same...
    } catch (Exception $e) {
        logError('Exception: ' . $e->getMessage());
        http_response_code(400);
        echo json_encode([
            'success' => false, 
            'message' => $e->getMessage()
        ]);
        exit();
    }
}