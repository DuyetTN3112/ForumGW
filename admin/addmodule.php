<?php
// Đảm bảo LUÔN LUÔN in ra JSON
header('Content-Type: application/json');

// Loại bỏ bất kỳ output nào trước đó
ob_clean(); 

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../includes/DatabaseFunction_admin.php';
include __DIR__ . '/../includes/DatabaseFunctions.php';

try {
    // Kiểm tra đăng nhập
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        throw new Exception("Bạn cần đăng nhập để tạo module");
    }

    $userId = $_SESSION['user_id'];

    // Xử lý form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // LẤY GIÁ TRỊ TỪ FORM
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // Validate input
        if (empty($name)) {
            http_response_code(400);
            throw new Exception("Tên module không được để trống");
        }

        // Kiểm tra độ dài tên module
        if (strlen($name) < 3) {
            http_response_code(400);
            throw new Exception("Tên module phải có ít nhất 3 ký tự");
        }

        // Tạo module
        $moduleId = createModule($name, $description, $userId);

        // Trả về thông báo thành công
        echo json_encode([
            'status' => 'success', 
            'message' => "Tạo module thành công! Mã module: $moduleId"
        ]);
        exit;
    } else {
        http_response_code(405);
        throw new Exception('Phương thức yêu cầu không hợp lệ');
    }
} catch (Exception $e) {
    // Trả về thông báo lỗi
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage()
    ]);
    exit;
}