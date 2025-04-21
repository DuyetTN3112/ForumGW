<?php
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunctions.php';
$pdo = getConnection();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone_number = $_POST['phone_number'] ?? null;
    $student_id = $_POST['student_id'] ?? null;

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM user WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already exists";
            include 'templates/register.html.php';
            exit;
        }

        // Check if student_id already exists (if provided)
        if ($student_id) {
            $stmt = $pdo->prepare("SELECT id FROM user WHERE student_id = ?");
            $stmt->execute([$student_id]);
            if ($stmt->fetch()) {
                $error = "Student ID already exists";
                include 'templates/register.html.php';
                exit;
            }
        }

        // Insert new user
        $stmt = $pdo->prepare("
            INSERT INTO user (username, email, password, phone_number, student_id)
            VALUES (?, ?, ?, ?, ?)
        ");
        
        if ($stmt->execute([$username, $email, $password, $phone_number, $student_id])) {
            $_SESSION['success_message'] = "Registration successful! Please login.";
            header("Location: login.php");
            exit;
        } else {
            $error = "Registration failed";
            include 'templates/register.html.php';
            exit;
        }
    } catch (PDOException $e) {
        $error = "An error occurred during registration";
        include 'templates/register.html.php';
        exit;
    }
} else {
    include 'templates/register.html.php';
}