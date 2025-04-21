<?php
// Require connection file
// Trong DatabaseFunctions.php
if (!defined('ROLE_USER')) {
    define('ROLE_USER', 0);
}

if (!defined('ROLE_ADMIN')) {
    define('ROLE_ADMIN', 1);
}
// Trong DatabaseFunctions.php và DatabaseFunction_admin.php
include_once __DIR__ . '/DatabaseConnection.php';
// Admin Module Functions
function createModule($name, $description, $userId) {
    $pdo = getConnection();
    
    // Validate input
    if (empty($name)) {
        error_log("Module name cannot be empty in createModule");
        throw new Exception("Module name cannot be empty");
    }
    
    try {
        error_log("Preparing to insert module: $name");
        
        // Kiểm tra kết nối
        if (!$pdo) {
            throw new Exception("Database connection failed");
        }
        
        // Kiểm tra độ dài tên module
        if (strlen($name) < 3) {
            throw new Exception("Module name must be at least 3 characters long");
        }
        
        $stmt = $pdo->prepare("INSERT INTO module (name, description, created_at) VALUES (:name, :description, NOW())");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        
        $result = $stmt->execute();
        
        if (!$result) {
            $errorInfo = $stmt->errorInfo();
            error_log("MySQL Error in createModule: " . print_r($errorInfo, true));
            throw new Exception("Failed to create module: " . ($errorInfo[2] ?? 'Unknown error'));
        }
        
        $moduleId = $pdo->lastInsertId();
        error_log("Module created successfully. ID: $moduleId");
        
        return $moduleId;
    } catch (PDOException $e) {
        error_log("PDO Error in createModule: " . $e->getMessage());
        error_log("Error Trace: " . $e->getTraceAsString());
        throw new Exception("Database error: " . $e->getMessage());
    } catch (Exception $e) {
        error_log("General Error in createModule: " . $e->getMessage());
        throw $e;
    }
}


function adminUpdateModule($moduleId, $name, $description) {
    if (empty($name)) {
        throw new Exception("Module name is required");
    }

    $pdo = getConnection();
    $stmt = $pdo->prepare("
        UPDATE module 
        SET name = ?, description = ? 
        WHERE id = ?
    ");
    $stmt->bindValue(1, $name, PDO::PARAM_STR);
    $stmt->bindValue(2, $description, PDO::PARAM_STR);
    $stmt->bindValue(3, $moduleId, PDO::PARAM_INT);
    return $stmt->execute();
}

function adminDeleteModule($moduleId) {
    $pdo = getConnection();
    try {
        $pdo->beginTransaction();

        // Xóa các liên kết module-post trước
        $stmt = $pdo->prepare("DELETE FROM module_post WHERE module_id = ?");
        $stmt->bindValue(1, $moduleId, PDO::PARAM_INT);
        $stmt->execute();

        // Sau đó mới xóa module
        $stmt = $pdo->prepare("DELETE FROM module WHERE id = ?");
        $stmt->bindValue(1, $moduleId, PDO::PARAM_INT);
        $result = $stmt->execute();

        $pdo->commit();
        return $result;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

// Admin Post Functions
function updateAllPost($userId, $postId, $title, $content, $image = null, $moduleIds = []) {
    if (empty($title) || empty($content)) {
        throw new Exception("Title and content are required");
    }

    $pdo = getConnection();
    try {
        $pdo->beginTransaction();

        // Cập nhật bài post
        $sql = "UPDATE post SET title = ?, content = ?";
        $params = [$title, $content];
        
        // Thêm image nếu có
        if ($image !== null) {
            $sql .= ", image = ?";
            $params[] = $image;
        }
        
        $sql .= " WHERE id = ? AND user_id = ?";
        $params[] = $postId;
        $params[] = $userId;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Xóa các liên kết module cũ
        $stmt = $pdo->prepare("DELETE FROM module_post WHERE post_id = ?");
        $stmt->execute([$postId]);

        // Thêm các module mới
        if (!empty($moduleIds)) {
            $insertStmt = $pdo->prepare("INSERT INTO module_post (module_id, post_id) VALUES (?, ?)");
            foreach ($moduleIds as $moduleId) {
                $insertStmt->execute([$moduleId, $postId]);
            }
        }

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}




// Admin Comment Functions
function adminDeleteComment($commentId) {
    $pdo = getConnection();
    try {
        $pdo->beginTransaction();
        
        // Delete related notifications first
        $stmt = $pdo->prepare("DELETE FROM notification WHERE comment_id = ?");
        $stmt->bindValue(1, $commentId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Delete the comment
        $stmt = $pdo->prepare("DELETE FROM comment WHERE id = ?");
        $stmt->bindValue(1, $commentId, PDO::PARAM_INT);
        $result = $stmt->execute();
        
        $pdo->commit();
        return $result;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function adminUpdateComment($commentId, $content, $image = null) {
    if (empty($content)) {
        throw new Exception("Comment content cannot be empty");
    }
    
    $pdo = getConnection();
    try {
        $stmt = $pdo->prepare("
            UPDATE comment 
            SET content = ?, 
                image = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        
        $stmt->bindValue(1, $content, PDO::PARAM_STR);
        $stmt->bindValue(2, $image, PDO::PARAM_STR);
        $stmt->bindValue(3, $commentId, PDO::PARAM_INT);
        
        return $stmt->execute();
    } catch (Exception $e) {
        throw $e;
    }
}

// Admin User Functions
function adminUpdateUser($userId, $data) {
    $pdo = getConnection();
    $allowedFields = ['username', 'email', 'phone_number', 'password', 'role', 'student_id', 'avatar'];
    $updates = [];
    $values = [];

    foreach ($data as $key => $value) {
        if (in_array($key, $allowedFields)) {
            if ($key === 'password') {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }
            if ($key === 'role' && !is_numeric($value)) {
                throw new Exception("Invalid role value");
            }
            $updates[] = "$key = ?";
            $values[] = $value;
        }
    }

    if (empty($updates)) {
        throw new Exception("No valid fields to update");
    }

    $values[] = $userId;
    $sql = "UPDATE user SET " . implode(', ', $updates) . " WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    for ($i = 0; $i < count($values); $i++) {
        $paramType = is_numeric($values[$i]) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($i + 1, $values[$i], $paramType);
    }
    return $stmt->execute();
}

function deleteAllUser($userId, $currentUserId) {
    $pdo = getConnection();

    // Kiểm tra xem người thực hiện có phải admin không
    $checkAdminStmt = $pdo->prepare("SELECT role FROM user WHERE id = ?");
    $checkAdminStmt->bindValue(1, $currentUserId, PDO::PARAM_INT);
    $checkAdminStmt->execute();
    $adminUser = $checkAdminStmt->fetch(PDO::FETCH_ASSOC);

    // Nếu không phải admin
    if ($adminUser['role'] !== ROLE_ADMIN) {
        throw new Exception("Bạn không có quyền xóa tài khoản");
    }

    // Ngăn không cho xóa tài khoản admin cuối cùng
    $adminCountStmt = $pdo->prepare("SELECT COUNT(*) as admin_count FROM user WHERE role = ?");
    $adminCountStmt->bindValue(1, ROLE_ADMIN, PDO::PARAM_INT);
    $adminCountStmt->execute();
    $adminCount = $adminCountStmt->fetch(PDO::FETCH_ASSOC)['admin_count'];

    // Kiểm tra xem user cần xóa có phải admin không
    $userToDeleteStmt = $pdo->prepare("SELECT role FROM user WHERE id = ?");
    $userToDeleteStmt->bindValue(1, $userId, PDO::PARAM_INT);
    $userToDeleteStmt->execute();
    $userToDelete = $userToDeleteStmt->fetch(PDO::FETCH_ASSOC);

    if ($userToDelete['role'] === ROLE_ADMIN && $adminCount <= 1) {
        throw new Exception("Không thể xóa admin cuối cùng");
    }

    try {
        $pdo->beginTransaction();

        // Xóa thông báo liên quan đến user
        $stmt = $pdo->prepare("DELETE FROM notification WHERE user_id = ? OR comment_id IN (SELECT id FROM comment WHERE user_id = ?) OR post_id IN (SELECT id FROM post WHERE user_id = ?)");
        $stmt->execute([$userId, $userId, $userId]);

        // Xóa comment của user trên các bài post
        $stmt = $pdo->prepare("DELETE FROM comment WHERE post_id IN (SELECT id FROM post WHERE user_id = ?)");
        $stmt->execute([$userId]);

        // Xóa các comment do user tạo
        $stmt = $pdo->prepare("DELETE FROM comment WHERE user_id = ?");
        $stmt->execute([$userId]);

        // Xóa liên kết module_post của các bài post user
        $stmt = $pdo->prepare("DELETE FROM module_post WHERE post_id IN (SELECT id FROM post WHERE user_id = ?)");
        $stmt->execute([$userId]);

        // Xóa các bài post của user
        $stmt = $pdo->prepare("DELETE FROM post WHERE user_id = ?");
        $stmt->execute([$userId]);

        // Cuối cùng xóa user
        $stmt = $pdo->prepare("DELETE FROM user WHERE id = ?");
        $result = $stmt->execute([$userId]);

        $pdo->commit();
        return $result;

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function adminPromoteToAdmin($userId) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("UPDATE user SET role = ? WHERE id = ?");
    $stmt->bindValue(1, ROLE_ADMIN, PDO::PARAM_INT);
    $stmt->bindValue(2, $userId, PDO::PARAM_INT);
    return $stmt->execute();
}

function adminDemoteFromAdmin($userId) {
    $pdo = getConnection();
    // Prevent demoting the last admin
    $stmt = $pdo->prepare("SELECT COUNT(*) as admin_count FROM user WHERE role = ?");
    $stmt->bindValue(1, ROLE_ADMIN, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['admin_count'] <= 1) {
        throw new Exception("Cannot demote the last administrator");
    }
    
    $stmt = $pdo->prepare("UPDATE user SET role = ? WHERE id = ?");
    $stmt->bindValue(1, ROLE_USER, PDO::PARAM_INT);
    $stmt->bindValue(2, $userId, PDO::PARAM_INT);
    return $stmt->execute();
}

// Admin Statistics Functions
function adminGetSystemStats() {
    $pdo = getConnection();
    try {
        $stats = [];
        
        // Total users
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM user");
        $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total posts
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM post");
        $stats['total_posts'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total comments
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM comment");
        $stats['total_comments'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Most active users
        $stmt = $pdo->query("
            SELECT u.username, COUNT(p.id) as post_count, COUNT(c.id) as comment_count
            FROM user u
            LEFT JOIN post p ON u.id = p.user_id
            LEFT JOIN comment c ON u.id = c.user_id
            GROUP BY u.id
            ORDER BY (COUNT(p.id) + COUNT(c.id)) DESC
            LIMIT 5
        ");
        $stats['most_active_users'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Most popular posts
        $stmt = $pdo->query("
            SELECT p.title, p.view_count, p.like_count, u.username
            FROM post p
            JOIN user u ON p.user_id = u.id
            ORDER BY (p.view_count + p.like_count) DESC
            LIMIT 5
        ");
        $stats['popular_posts'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    } catch (Exception $e) {
        throw $e;
    }
}

if (!function_exists('getPostsByModuleId')) {
    function getPostsByModuleId($moduleId) {
        $pdo = getConnection();
        $stmt = $pdo->prepare("
            SELECT p.*, u.username as author, 
                   GROUP_CONCAT(DISTINCT m.name) as module_names
            FROM post p
            JOIN module_post mp ON p.id = mp.post_id
            JOIN user u ON p.user_id = u.id
            JOIN module m ON mp.module_id = m.id
            WHERE mp.module_id = ?
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ");
        $stmt->bindValue(1, $moduleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('validateModuleIds')) {
    function validateModuleIds($pdo, $moduleIds) {
        if (empty($moduleIds)) {
            return true;
        }

        $placeholders = str_repeat('?,', count($moduleIds) - 1) . '?';
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count, COUNT(DISTINCT id) as unique_count 
            FROM module 
            WHERE id IN ($placeholders)
        ");

        foreach ($moduleIds as $i => $id) {
            $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if all provided moduleIds exist and are unique
        return $result['count'] === count($moduleIds) && 
               $result['count'] === $result['unique_count'];
    }
}

?>  