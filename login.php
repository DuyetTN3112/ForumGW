<?php
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunctions.php';
$pdo = getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $stmt = $pdo->prepare("
            SELECT id, username, role 
            FROM user 
            WHERE email = ? AND password = ?
        ");
        
        $stmt->execute([$email, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['role'] == 1;
            
            // Redirect based on user role
            if ($user['role'] == 1) {
                header("Location: admin_index.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Invalid email or password";
            include 'templates/register.html.php';
            exit;
        }
    } catch (PDOException $e) {
        $error = "An error occurred during login";
        include 'templates/register.html.php';
        exit;
    }
} else {
    if (isset($_SESSION['success_message'])) {
        $success = $_SESSION['success_message'];
        unset($_SESSION['success_message']);
    }
    include 'templates/register.html.php';
}