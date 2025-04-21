<?php
$host = 'localhost';
$dbname = 'cw1';
$username = 'root';
$password = '';

if (!function_exists('getConnection')) {
    function getConnection() {
        global $host, $dbname, $username, $password;
        try {
            $pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            return $pdo;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}

function isAdmin($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT role FROM user WHERE id = ?");
    $stmt->bindValue(1, $userId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result && $result['role'] === ROLE_ADMIN;
}
?>