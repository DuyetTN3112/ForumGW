<?php
session_start();
require_once __DIR__ . '/../includes/DatabaseConnection.php';
require_once __DIR__ . '/../includes/DatabaseFunction_admin.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || !isAdmin(getConnection(), $_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Bạn không có quyền thực hiện thao tác này'
    ]);
    exit;
}

// Kiểm tra phương thức POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Phương thức không hợp lệ'
    ]);
    exit;
}

// Kiểm tra module ID từ request
$moduleId = $_POST['module_id'] ?? null;

if (!$moduleId) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Thiếu thông tin module'
    ]);
    exit;
}

try {
    // Gọi hàm xóa module
    $result = adminDeleteModule($moduleId);

    if ($result) {
        echo json_encode([
            'status' => 'success', 
            'message' => 'Xóa module thành công'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'status' => 'error', 
            'message' => 'Không thể xóa module'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage()
    ]);
}
exit;