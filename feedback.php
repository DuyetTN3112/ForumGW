<?php

session_start(); // Đảm bảo session được khởi động
if (session_status() == PHP_SESSION_NONE) {
    die("Session is not started.");
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunctions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

$config = require 'config.php';

// Debug: In ra toàn bộ thông tin session
error_log("SESSION DATA: " . print_r($_SESSION, true));

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    error_log("Không có user_id trong session");
    $_SESSION['message'] = "Vui lòng đăng nhập để gửi phản hồi.";
    $_SESSION['message_type'] = "error";
    header("Location: login.php");
    exit;
}

// Debug: Kiểm tra thông tin user
try {
    $pdo = getConnection();
    $user = getUserById($_SESSION['user_id']);
    
    if ($user === null) {
        error_log("Không tìm thấy thông tin user với ID: " . $_SESSION['user_id']);
        $_SESSION['message'] = "Lỗi: Không tìm thấy thông tin người dùng.";
        $_SESSION['message_type'] = "error";
        header("Location: login.php");
        exit;
    }
    
    error_log("User info: " . print_r($user, true));
} catch (Exception $e) {
    error_log("Lỗi khi lấy thông tin user: " . $e->getMessage());
    $_SESSION['message'] = "Lỗi hệ thống: " . $e->getMessage();
    $_SESSION['message_type'] = "error";
    header("Location: login.php");
    exit;
}

// Chỉ xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_email'])) {
    try {
        // Validate và lọc dữ liệu
        $name = $user['username'];
        $email = $user['email'];
        $userMessage = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
        $studentId = $user['student_id'] ?? 'N/A';

        error_log("Sending email - Name: $name, Email: $email, Message: $userMessage");

        // Kiểm tra dữ liệu
        if (empty($userMessage)) {
            throw new Exception("Vui lòng nhập nội dung phản hồi.");
        }

        // Nội dung email
        $subject = "Phản Hồi từ Người Dùng: " . $name;
        $body = "
            <html>
            <body>
                <h2>Phản Hồi Mới từ Người Dùng</h2>
                <p><strong>Tên:</strong> " . htmlspecialchars($name) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                <p><strong>Mã Sinh Viên:</strong> " . htmlspecialchars($studentId) . "</p>
                <hr>
                <h3>Nội Dung Phản Hồi:</h3>
                <p>" . htmlspecialchars($userMessage) . "</p>
            </body>
            </html>
        ";

        // Gọi hàm gửi email
        $mail = new PHPMailer(true);

        // Cấu hình máy chủ SMTP
        $mail->isSMTP();
        $mail->Host = $config['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['smtp_username'];
        $mail->Password = $config['smtp_password'];
        $mail->SMTPSecure = $config['smtp_secure'];
        $mail->Port = $config['smtp_port'];

        // Debug SMTP
        $mail->SMTPDebug = 2; // Bật debug SMTP
        $mail->Debugoutput = function($str, $level) {
            error_log("SMTP DEBUG ($level): $str");
        };

        // Người gửi
        $mail->setFrom($email, $name);

        // Người nhận (luôn là admin)
        $mail->addAddress($config['admin_email']);

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        // Gửi email
        $result = $mail->send();

        error_log("Email send result: " . ($result ? "Success" : "Fail"));

        // Thông báo thành công
        $_SESSION['message'] = "Phản hồi đã được gửi thành công!";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        // Ghi log lỗi và thông báo
        error_log("Lỗi gửi email: " . $e->getMessage());
        $_SESSION['message'] = "Lỗi: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
    }

    // Chuyển hướng về trang feedback
    header("Location: index.php?page=feedback");
    exit;
}

include 'templates/feedback.html.php';
exit;