<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Tự động load thư viện (khuyến nghị)
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Đọc cấu hình
$config = require 'config.php';

function sendEmail($to, $subject, $body, $from = null) {
    global $config;
    
    $mail = new PHPMailer(true);

    try {
        // Cấu hình máy chủ SMTP
        $mail->isSMTP();
        $mail->Host = $config['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['smtp_username'];
        $mail->Password = $config['smtp_password'];
        $mail->SMTPSecure = $config['smtp_secure'];
        $mail->Port = $config['smtp_port'];

        // Tắt debug khi triển khai
        $mail->SMTPDebug = 0;

        // Người gửi
        $mail->setFrom($from ?? $config['smtp_username'], 'Your Name');

        // Người nhận
        $mail->addAddress($to);

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        // Gửi email
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Ghi log lỗi (nên sử dụng hệ thống log chuyên nghiệp)
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}

// Ví dụ sử dụng
if (isset($_POST['send_email'])) {
    $to = $config['admin_email'];
    $subject = 'Test Email';
    $body = '<b>This is a test email</b>';

    if (sendEmail($to, $subject, $body)) {
        echo "Email sent successfully!";
    } else {
        echo "Failed to send email.";
    }
}