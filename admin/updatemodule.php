<?php
require_once __DIR__ . '/../includes/DatabaseConnection.php';
require_once __DIR__ . '/../includes/DatabaseFunction_admin.php';
require_once __DIR__ . '/../includes/DatabaseFunctions.php';

header('Content-Type: application/json');

// Kiểm tra đăng nhập và quyền admin
session_start();
if (!isset($_SESSION['user_id']) || 
    !isAdmin(getConnection(), $_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Bạn không có quyền truy cập'
    ]);
    exit;
}

// Lấy dữ liệu từ form
$moduleId = $_POST['module_id'] ?? null;
$moduleName = $_POST['name'] ?? null;
$moduleDescription = $_POST['description'] ?? null;

// Kiểm tra dữ liệu
if ($moduleId && $moduleName) {
    try {
        // Sử dụng hàm adminUpdateModule từ DatabaseFunction_admin.php
        $updateStatus = adminUpdateModule($moduleId, $moduleName, $moduleDescription);

        if ($updateStatus) {
            echo json_encode(['status' => 'success', 'message' => 'Module updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update module']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}