<?php
// Database Functions

// Constants for user roles
// Trong DatabaseFunctions.php
if (!defined('ROLE_USER')) {
    define('ROLE_USER', 0);
}

if (!defined('ROLE_ADMIN')) {
    define('ROLE_ADMIN', 1);
}
// Trong DatabaseFunctions.php và DatabaseFunction_admin.php
include_once __DIR__ . '/DatabaseConnection.php';
// Utility function to check ownership
function checkOwnership($pdo, $table, $id, $userId) {
    $stmt = $pdo->prepare("SELECT user_id FROM $table WHERE id = ?");
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result && $result['user_id'] === $userId;
}


// Utility function to check if user is admin


// Search Functions
function searchUsers($keyword) {
    $pdo = getConnection();
    $keyword = "%$keyword%";
    $stmt = $pdo->prepare("
        SELECT id, username, email, phone_number, student_id, avatar, created_at 
        FROM user 
        WHERE username LIKE ? OR email LIKE ? OR student_id LIKE ?
        ORDER BY username ASC
    ");
    $stmt->bindValue(1, $keyword, PDO::PARAM_STR);
    $stmt->bindValue(2, $keyword, PDO::PARAM_STR);
    $stmt->bindValue(3, $keyword, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchPosts($keyword, $moduleId = null) {
    $pdo = getConnection();
    $keyword = "%$keyword%";
    $sql = "
        SELECT p.*, u.username, GROUP_CONCAT(m.name) as modules 
        FROM post p
        JOIN user u ON p.user_id = u.id
        LEFT JOIN module_post mp ON p.id = mp.post_id
        LEFT JOIN module m ON mp.module_id = m.id
        WHERE (p.title LIKE ? OR p.content LIKE ?)
    ";
    if ($moduleId) {
        $sql .= " AND mp.module_id = ?";
    }
    $sql .= " GROUP BY p.id ORDER BY p.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $keyword, PDO::PARAM_STR);
    $stmt->bindValue(2, $keyword, PDO::PARAM_STR);
    if ($moduleId) {
        $stmt->bindValue(3, $moduleId, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchModules($keyword) {
    $pdo = getConnection();
    $keyword = "%$keyword%";
    $stmt = $pdo->prepare("
        SELECT * FROM module 
        WHERE name LIKE ? OR description LIKE ?
        ORDER BY name ASC
    ");
    $stmt->bindValue(1, $keyword, PDO::PARAM_STR);
    $stmt->bindValue(2, $keyword, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// User Functions
function getAllUsers() {
    $pdo = getConnection();
    $query = 'SELECT * FROM user ORDER BY created_at DESC';
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserById($userId) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("
        SELECT id, username, email, phone_number, student_id, avatar, created_at 
        FROM user 
        WHERE id = ?
    ");
    $stmt->bindValue(1, $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function countTotalUsers() {
    $pdo = getConnection();
    $stmt = $pdo->query("SELECT COUNT(*) FROM user");
    return $stmt->fetchColumn();
}

function updateUser ($userId, $currentUserId, $data) {
    $pdo = getConnection();
    
    // Chỉ cho phép người dùng cập nhật profile của chính mình
    if ($userId !== $currentUserId) {
        throw new Exception("Unauthorized: Cannot update other user's profile");
    }

    $allowedFields = ['username', 'email', 'phone_number', 'avatar'];
    $updates = [];
    $values = [];

    // Kiểm tra và xử lý mật khẩu mới
    if (!empty($data['new_password'])) {
        // Lấy mật khẩu hiện tại từ CSDL
        $stmt = $pdo->prepare("SELECT password FROM user WHERE id = ?");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->execute();
        $currentPasswordHash = $stmt->fetchColumn();

        // Kiểm tra mật khẩu hiện tại
        if (!password_verify($data['current_password'], $currentPasswordHash)) {
            throw new Exception("Current password is incorrect");
        }

        // Hash mật khẩu mới
        $data['password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
        $allowedFields[] = 'password'; // Thêm 'password' vào danh sách các trường hợp lệ
    }

    // Xử lý các trường hợp hợp lệ
    foreach ($data as $key => $value) {
        if (in_array($key, $allowedFields) && $value !== null) {
            $updates[] = "$key = ?";
            $values[] = $value;
        }
    }

    if (empty($updates)) {
        throw new Exception("No valid fields to update");
    }

    // Thêm userId vào cuối danh sách giá trị để sử dụng trong câu lệnh SQL
    $values[] = $userId;
    $sql = "UPDATE user SET " . implode(', ', $updates) . " WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    // Gán giá trị cho các tham số trong câu lệnh SQL
    for ($i = 0; $i < count($values); $i++) {
        $stmt->bindValue($i + 1, $values[$i], PDO::PARAM_STR);
    }

    return $stmt->execute(); // Thực thi câu lệnh SQL
}
function deleteUser($userId, $currentUserId) {
    $pdo = getConnection();
    
    // Only allow users to delete their own account
    if ($userId !== $currentUserId) {
        throw new Exception("Unauthorized: Cannot delete other user's account");
    }

    try {
        $pdo->beginTransaction();

        // 1. Delete all related records in the correct order to avoid foreign key constraints

        // Delete notifications related to user's comments and posts
        $stmt = $pdo->prepare("DELETE FROM notification WHERE user_id = ? OR comment_id IN (SELECT id FROM comment WHERE user_id = ?) OR post_id IN (SELECT id FROM post WHERE user_id = ?)");
        $stmt->execute([$userId, $userId, $userId]);

        // Delete comments on user's posts
        $stmt = $pdo->prepare("DELETE FROM comment WHERE post_id IN (SELECT id FROM post WHERE user_id = ?)");
        $stmt->execute([$userId]);

        // Delete comments by the user
        $stmt = $pdo->prepare("DELETE FROM comment WHERE user_id = ?");
        $stmt->execute([$userId]);

        // Delete module_post associations for user's posts
        $stmt = $pdo->prepare("DELETE FROM module_post WHERE post_id IN (SELECT id FROM post WHERE user_id = ?)");
        $stmt->execute([$userId]);

        // Delete user's posts
        $stmt = $pdo->prepare("DELETE FROM post WHERE user_id = ?");
        $stmt->execute([$userId]);

        // Finally, delete the user
        $stmt = $pdo->prepare("DELETE FROM user WHERE id = ?");
        $result = $stmt->execute([$userId]);

        $pdo->commit();
        return $result;

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}
// Module Functions - Read Only for Regular Users
function getAllModules($page = 1, $limit = 15) {
    $pdo = getConnection();
    $offset = ($page - 1) * $limit;
    $stmt = $pdo->prepare("
        SELECT * FROM module 
        ORDER BY name ASC
        LIMIT ? OFFSET ?
    ");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getModuleById($moduleId) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT * FROM module WHERE id = ?");
    $stmt->bindValue(1, $moduleId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Post Functions
function getAllPosts($page = 1, $limit = 10) {
    // Đảm bảo page luôn là số dương
    $page = max(1, $page);
    $offset = ($page - 1) * $limit;
    
    $pdo = getConnection();
    $stmt = $pdo->prepare("
        SELECT p.*, u.username, u.avatar as user_avatar, GROUP_CONCAT(m.name) as modules 
        FROM post p 
        JOIN user u ON p.user_id = u.id 
        LEFT JOIN module_post mp ON p.id = mp.post_id 
        LEFT JOIN module m ON mp.module_id = m.id 
        GROUP BY p.id
        ORDER BY p.created_at DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPostById($postId) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("
        SELECT p.*, u.username, u.avatar, GROUP_CONCAT(m.name) as modules 
        FROM post p 
        JOIN user u ON p.user_id = u.id 
        LEFT JOIN module_post mp ON p.id = mp.post_id 
        LEFT JOIN module m ON mp.module_id = m.id 
        WHERE p.id = ?
        GROUP BY p.id
    ");
    $stmt->bindValue(1, $postId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserPosts($userId, $page = 1, $limit = 10) {
    $pdo = getConnection();
    $offset = ($page - 1) * $limit;
    $stmt = $pdo->prepare(" 
        SELECT p.*, u.username, u.avatar, GROUP_CONCAT(m.name) as modules 
        FROM post p 
        JOIN user u ON p.user_id = u.id
        LEFT JOIN module_post mp ON p.id = mp.post_id 
        LEFT JOIN module m ON mp.module_id = m.id 
        WHERE p.user_id = ? 
        GROUP BY p.id 
        ORDER BY p.created_at DESC 
        LIMIT ? OFFSET ? 
    ");
    $stmt->bindValue(1, $userId, PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->bindValue(3, $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createPost($userId, $title, $content, $image = null, $moduleIds = []) { 
    $pdo = getConnection(); 
    if (empty($title) || empty($content)) { 
        throw new Exception("Title and content are required"); 
    } 

    try { 
        $pdo->beginTransaction(); 

        // Insert post 
        $stmt = $pdo->prepare(" 
            INSERT INTO post (user_id, title, content, image) 
            VALUES (?, ?, ?, ?) 
        "); 
        $stmt->bindValue(1, $userId, PDO::PARAM_INT); 
        $stmt->bindValue(2, $title, PDO::PARAM_STR); 
        $stmt->bindValue(3, $content, PDO::PARAM_STR); 
        
        // Quan trọng: Kiểm tra và gán giá trị image
        if ($image !== null) {
            // Sử dụng đường dẫn tương đối từ gốc dự án
            $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $image);
            $stmt->bindValue(4, $relativePath, PDO::PARAM_STR);
        } else {
            $stmt->bindValue(4, null, PDO::PARAM_NULL);
        }
        
        $stmt->execute(); 
        $postId = $pdo->lastInsertId(); 

        // Link post with modules 
        if (!empty($moduleIds)) { 
            $stmt = $pdo->prepare(" 
                INSERT INTO module_post (module_id, post_id) 
                VALUES (?, ?) 
            "); 
            foreach ($moduleIds as $moduleId) { 
                $stmt->bindValue(1, $moduleId, PDO::PARAM_INT); 
                $stmt->bindValue(2, $postId, PDO::PARAM_INT); 
                $stmt->execute(); 
            } 
        } 

        $pdo->commit(); 
        return $postId; 
    } catch (Exception $e) { 
        $pdo->rollBack(); 
        throw $e; 
    } 
} 

function updatePost($userId, $postId, $title, $content, $image = null, $moduleIds = []) {
    if (empty($title) || empty($content)) {
        throw new Exception("Title and content are required");
    }

    $pdo = getConnection();
    
    // Kiểm tra quyền sở hữu bài viết
    if (!checkOwnership($pdo, 'post', $postId, $userId)) {
        throw new Exception("Unauthorized: Cannot update other user's post");
    }

    try {
        $pdo->beginTransaction();

        // Cập nhật bài viết
        $stmt = $pdo->prepare("
            UPDATE post 
            SET title = ?, content = ?, image = ?
            WHERE id = ? AND user_id = ?
        ");
        $stmt->bindValue(1, $title, PDO::PARAM_STR);
        $stmt->bindValue(2, $content, PDO::PARAM_STR);
        $stmt->bindValue(3, $image, PDO::PARAM_STR);
        $stmt->bindValue(4, $postId, PDO::PARAM_INT);
        $stmt->bindValue(5, $userId, PDO::PARAM_INT);
        
        $stmt->execute();

        // Cập nhật các module
        $stmt = $pdo->prepare("DELETE FROM module_post WHERE post_id = ?");
        $stmt->bindValue(1, $postId, PDO::PARAM_INT);
        $stmt->execute();

        if (!empty($moduleIds)) {
            $stmt = $pdo->prepare("INSERT INTO module_post (module_id, post_id) VALUES (?, ?)");
            foreach ($moduleIds as $moduleId) {
                $stmt->bindValue(1, $moduleId, PDO::PARAM_INT);
                $stmt->bindValue(2, $postId, PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

// Trong một file chung, ví dụ như DatabaseConnection.php hoặc một file functions.php mới
function deletePost($postId, $userId, $isAdmin = false) {
    $pdo = getConnection();
    
    try {
        $pdo->beginTransaction();

        // Nếu không phải admin, kiểm tra quyền sở hữu
        if (!$isAdmin) {
            $stmt = $pdo->prepare("SELECT user_id FROM post WHERE id = ?");
            $stmt->execute([$postId]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$post || $post['user_id'] != $userId) {
                throw new Exception("Unauthorized: Cannot delete other user's post");
            }
        }

        // 1. Xóa thông báo liên quan đến comment của bài post
        $stmt = $pdo->prepare("DELETE FROM notification WHERE comment_id IN (SELECT id FROM comment WHERE post_id = ?)");
        $stmt->bindValue(1, $postId, PDO::PARAM_INT);
        $stmt->execute();

        // 2. Xóa comment của bài post
        $stmt = $pdo->prepare("DELETE FROM comment WHERE post_id = ?");
        $stmt->bindValue(1, $postId, PDO::PARAM_INT);
        $stmt->execute();

        // 3. Xóa thông báo liên quan đến bài post
        $stmt = $pdo->prepare("DELETE FROM notification WHERE post_id = ?");
        $stmt->bindValue(1, $postId, PDO::PARAM_INT);
        $stmt->execute();

        // 4. Xóa liên kết module_post
        $stmt = $pdo->prepare("DELETE FROM module_post WHERE post_id = ?");
        $stmt->bindValue(1, $postId, PDO::PARAM_INT);
        $stmt->execute();

        // 5. Cuối cùng, xóa bài post
        $stmt = $pdo->prepare("DELETE FROM post WHERE id = ?");
        $stmt->bindValue(1, $postId, PDO::PARAM_INT);
        $result = $stmt->execute();
        
        $pdo->commit();
        return $result;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        // Log lỗi để debug
        error_log('Delete post error: ' . $e->getMessage());
        throw $e;
    }
}

/**
 * Get comments for a specific post with pagination
 */
function getPostComments($postId, $page = 1, $limit = 10, $orderBy = 'newest') {
    $pdo = getConnection();
    
    // Verify post exists
    $postCheck = $pdo->prepare("SELECT id FROM post WHERE id = ?");
    $postCheck->bindValue(1, $postId, PDO::PARAM_INT);
    $postCheck->execute();
    if (!$postCheck->fetch()) {
        throw new Exception("Post not found");
    }
    
    // Calculate offset for pagination
    $offset = ($page - 1) * $limit;
    
    // Determine order
    $orderSQL = match($orderBy) {
        'oldest' => 'c.created_at ASC',
        'newest' => 'c.created_at DESC',
        default => 'c.created_at DESC'
    };
    
    // Get comments with user info
    $sql = "SELECT c.*, u.username, u.avatar,
            (SELECT COUNT(*) FROM comment WHERE post_id = ?) as total_comments
            FROM comment c
            JOIN user u ON c.user_id = u.id
            WHERE c.post_id = ?
            ORDER BY $orderSQL
            LIMIT ? OFFSET ?";
            
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $postId, PDO::PARAM_INT);
    $stmt->bindValue(2, $postId, PDO::PARAM_INT);
    $stmt->bindValue(3, $limit, PDO::PARAM_INT);
    $stmt->bindValue(4, $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total = !empty($comments) ? $comments[0]['total_comments'] : 0;
    
    return [
        'comments' => $comments,
        'total' => $total,
        'total_pages' => ceil($total / $limit),
        'current_page' => $page
    ];
}

/**
 * Get comments by a specific user
 */
function getUserComments($userId, $page = 1, $limit = 10) {
    $pdo = getConnection();
    
    // Verify user exists
    $userCheck = $pdo->prepare("SELECT id FROM user WHERE id = ?");
    $userCheck->bindValue(1, $userId, PDO::PARAM_INT);
    $userCheck->execute();
    if (!$userCheck->fetch()) {
        throw new Exception("User not found");
    }
    
    $offset = ($page - 1) * $limit;
    
    $stmt = $pdo->prepare("
        SELECT c.*, p.title as post_title, p.id as post_id,
        (SELECT COUNT(*) FROM comment WHERE user_id = ?) as total_comments
        FROM comment c 
        JOIN post p ON c.post_id = p.id 
        WHERE c.user_id = ? 
        ORDER BY c.created_at DESC
        LIMIT ? OFFSET ?
    ");
    
    $stmt->bindValue(1, $userId, PDO::PARAM_INT);
    $stmt->bindValue(2, $userId, PDO::PARAM_INT);
    $stmt->bindValue(3, $limit, PDO::PARAM_INT);
    $stmt->bindValue(4, $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total = !empty($comments) ? $comments[0]['total_comments'] : 0;
    
    return [
        'comments' => $comments,
        'total' => $total,
        'total_pages' => ceil($total / $limit),
        'current_page' => $page
    ];
}

/**
 * Create a new comment
 */
function createComment($userId, $postId, $content, $image = null) {
    if (empty($content)) {
        throw new Exception("Comment content cannot be empty");
    }
    
    $pdo = getConnection();
    
    try {
        $pdo->beginTransaction();
        
        // Verify post exists
        $postCheck = $pdo->prepare("SELECT user_id FROM post WHERE id = ?");
        $postCheck->bindValue(1, $postId, PDO::PARAM_INT);
        $postCheck->execute();
        $post = $postCheck->fetch(PDO::FETCH_ASSOC);
        
        if (!$post) {
            throw new Exception("Post not found");
        }
        
        // Insert comment
        $stmt = $pdo->prepare("
            INSERT INTO comment (user_id, post_id, content, image) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $postId, PDO::PARAM_INT);
        $stmt->bindValue(3, $content, PDO::PARAM_STR);
        $stmt->bindValue(4, $image, PDO::PARAM_STR);
        $stmt->execute();
        
        $commentId = $pdo->lastInsertId();
        
        // Create notification for post owner
        if ($post['user_id'] != $userId) {
            $stmt = $pdo->prepare("
                INSERT INTO notification (user_id, type, post_id, comment_id, content)
                VALUES (?, 'comment_on_post', ?, ?, ?)
            ");
            $stmt->bindValue(1, $post['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(2, $postId, PDO::PARAM_INT);
            $stmt->bindValue(3, $commentId, PDO::PARAM_INT);
            $stmt->bindValue(4, "New comment on your post", PDO::PARAM_STR);
            $stmt->execute();
        }
        
        $pdo->commit();
        return $commentId;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

/**
 * Update an existing comment
 */
function updateComment($commentId, $userId, $content, $image = null) {
    if (empty($content)) {
        throw new Exception("Comment content cannot be empty");
    }
    
    $pdo = getConnection();
    
    // Check ownership
    if (!checkOwnership($pdo, 'comment', $commentId, $userId)) {
        throw new Exception("Unauthorized: Cannot update other user's comment");
    }
    
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("
            UPDATE comment 
            SET content = ?, 
                image = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ? AND user_id = ?
        ");
        
        $stmt->bindValue(1, $content, PDO::PARAM_STR);
        $stmt->bindValue(2, $image, PDO::PARAM_STR);
        $stmt->bindValue(3, $commentId, PDO::PARAM_INT);
        $stmt->bindValue(4, $userId, PDO::PARAM_INT);
        
        $result = $stmt->execute();
        
        $pdo->commit();
        return $result;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

/**
 * Delete a comment
 */
function deleteComment($commentId, $userId) {
    $pdo = getConnection();
    
    // Check ownership
    if (!checkOwnership($pdo, 'comment', $commentId, $userId)) {
        throw new Exception("Unauthorized: Cannot delete other user's comment");
    }
    
    try {
        $pdo->beginTransaction();
        
        // Delete related notifications first
        $stmt = $pdo->prepare("DELETE FROM notification WHERE comment_id = ?");
        $stmt->bindValue(1, $commentId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Delete the comment
        $stmt = $pdo->prepare("DELETE FROM comment WHERE id = ? AND user_id = ?");
        $stmt->bindValue(1, $commentId, PDO::PARAM_INT);
        $stmt->bindValue(2, $userId, PDO::PARAM_INT);
        $result = $stmt->execute();
        
        $pdo->commit();
        return $result;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

// Trong DatabaseFunctions.php
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

// Trong DatabaseFunctions.php
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


function countTotalPosts() {
    $pdo = getConnection();
    $stmt = $pdo->query("SELECT COUNT(*) FROM post");
    return $stmt->fetchColumn();
}

// Hàm lấy thông báo cho một người dùng với phân trang
function getUserNotifications($userId, $page = 1, $limit = 10) {
    $pdo = getConnection();
    
    $offset = ($page - 1) * $limit;
    
    $stmt = $pdo->prepare("
        SELECT n.*, 
               u.username as sender_username, 
               u.avatar as sender_avatar,
               p.title as post_title,
               c.content as comment_content
        FROM notification n
        LEFT JOIN user u ON n.sender_id = u.id
        LEFT JOIN post p ON n.post_id = p.id
        LEFT JOIN comment c ON n.comment_id = c.id
        WHERE n.user_id = ?
        ORDER BY n.created_at DESC
        LIMIT ? OFFSET ?
    ");
    
    $stmt->bindValue(1, $userId, PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->bindValue(3, $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Đếm tổng số thông báo chưa đọc
    $unreadStmt = $pdo->prepare("
        SELECT COUNT(*) as unread_count 
        FROM notification 
        WHERE user_id = ? AND is_read = 0
    ");
    $unreadStmt->bindValue(1, $userId, PDO::PARAM_INT);
    $unreadStmt->execute();
    $unreadCount = $unreadStmt->fetchColumn();
    
    // Đếm tổng số thông báo
    $totalStmt = $pdo->prepare("
        SELECT COUNT(*) as total_count 
        FROM notification 
        WHERE user_id = ?
    ");
    $totalStmt->bindValue(1, $userId, PDO::PARAM_INT);
    $totalStmt->execute();
    $totalCount = $totalStmt->fetchColumn();
    
    return [
        'notifications' => $notifications,
        'unread_count' => $unreadCount,
        'total_count' => $totalCount,
        'total_pages' => ceil($totalCount / $limit),
        'current_page' => $page
    ];
}

// Hàm đánh dấu thông báo đã đọc
function markNotificationAsRead($notificationId, $userId) {
    $pdo = getConnection();
    
    $stmt = $pdo->prepare("
        UPDATE notification 
        SET is_read = 1 
        WHERE id = ? AND user_id = ?
    ");
    
    $stmt->bindValue(1, $notificationId, PDO::PARAM_INT);
    $stmt->bindValue(2, $userId, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// Hàm đánh dấu tất cả thông báo đã đọc
function markAllNotificationsAsRead($userId) {
    $pdo = getConnection();
    
    $stmt = $pdo->prepare("
        UPDATE notification 
        SET is_read = 1 
        WHERE user_id = ?
    ");
    
    $stmt->bindValue(1, $userId, PDO::PARAM_INT);
    
    return $stmt->execute();
}

function getPostsByFilter($filter, $page = 1, $limit = 10) {
    $pdo = getConnection();
    
    // Tính offset
    $offset = ($page - 1) * $limit;
    
    switch ($filter) {
        case 'most_view':
            $countSql = "SELECT COUNT(*) FROM post";
            $sql = "SELECT p.*, u.username, u.avatar 
                    FROM post p 
                    JOIN user u ON p.user_id = u.id 
                    ORDER BY p.view_count DESC 
                    LIMIT :limit OFFSET :offset";
            break;
        
        case 'most_liked':
            $countSql = "SELECT COUNT(*) FROM post";
            $sql = "SELECT p.*, u.username, u.avatar 
                    FROM post p 
                    JOIN user u ON p.user_id = u.id 
                    ORDER BY p.like_count DESC 
                    LIMIT :limit OFFSET :offset";
            break;
        
        case 'most_disliked':
            $countSql = "SELECT COUNT(*) FROM post";
            $sql = "SELECT p.*, u.username, u.avatar 
                    FROM post p 
                    JOIN user u ON p.user_id = u.id 
                    ORDER BY p.dislike_count DESC 
                    LIMIT :limit OFFSET :offset";
            break;
        
        default:
            return [
                'posts' => [],
                'total_posts' => 0,
                'total_pages' => 0
            ];
    }
    
    // Đếm tổng số bài viết
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute();
    $totalPosts = $countStmt->fetchColumn();
    
    // Lấy bài viết
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    return [
        'posts' => $stmt->fetchAll(PDO::FETCH_ASSOC),
        'total_posts' => $totalPosts,
        'total_pages' => ceil($totalPosts / $limit)
    ];
}

function uploadImage($file, $uploadDir = 'uploads/', $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']) { 
    // Tạo thư mục nếu chưa tồn tại 
    if (!is_dir($uploadDir)) { 
        mkdir($uploadDir, 0777, true); 
    } 

    // Kiểm tra lỗi upload 
    if ($file['error'] !== UPLOAD_ERR_OK) { 
        return null; 
    } 

    // Kiểm tra kích thước file (ví dụ: max 5MB) 
    $maxFileSize = 5 * 1024 * 1024; 
    if ($file['size'] > $maxFileSize) { 
        throw new Exception("Kích thước ảnh quá lớn. Tối đa 5MB. "); 
    } 

    // Kiểm tra định dạng file 
    $fileType = mime_content_type($file['tmp_name']); 
    if (!in_array($fileType, $allowedTypes)) { 
        throw new Exception("Định dạng ảnh không được hỗ trợ. "); 
    } 

    // Tạo tên file duy nhất 
    $fileName = uniqid() . '_' . basename($file['name']); 
    $targetFilePath = $uploadDir . $fileName; 

    // Di chuyển file 
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) { 
        // Trả về đường dẫn tương đối 
        return $targetFilePath; 
    } 

    return null; 
}

function handlePostInteraction($userId, $postId, $type) {
    $pdo = getConnection(); 

    try { 
        // Kiểm tra xem user đã like/dislike bài post này chưa 
        $stmt = $pdo->prepare(" 
            SELECT liked, disliked FROM post 
            WHERE id = ? 
        "); 
        $stmt->execute([$postId]); 
        $post = $stmt->fetch(PDO::FETCH_ASSOC); 

        $pdo->beginTransaction(); 

        // Xác định loại tương tác khác 
        $resetOtherType = $type === 'like' ? 'dislike' : 'like'; 

        // Trường hợp 1: Bỏ like/dislike hiện tại 
        if (($type === 'like' && $post['liked'] == $userId) || 
            ($type === 'dislike' && $post['disliked'] == $userId)) { 
            $updateQuery = " 
                UPDATE post 
                SET {$type}_count = GREATEST({$type}_count - 1, 0), 
                    {$type}d = NULL 
                WHERE id = ? 
            "; 
            $stmt = $pdo->prepare($updateQuery); 
            $stmt->execute([$postId]); 
        } 
        // Trường hợp 2: Chuyển từ like sang dislike hoặc ngược lại 
        else if (($type === 'like' && $post['disliked'] == $userId) || 
                 ($type === 'dislike' && $post['liked'] == $userId)) { 
            $updateQuery = " 
                UPDATE post 
                SET {$type}_count = {$type}_count + 1, 
                    {$resetOtherType}_count = GREATEST({$resetOtherType}_count - 1, 0), 
                    {$type}d = ?, 
                    {$resetOtherType}d = NULL 
                WHERE id = ? 
            "; 
            $stmt = $pdo->prepare($updateQuery); 
            $stmt->execute([$userId, $postId]); 
        } 
        // Trường hợp 3: Like/Dislike lần đầu 
        else { 
            $updateQuery = " 
                UPDATE post 
                SET {$type}_count = {$type}_count + 1, 
                    {$type}d = ? 
                WHERE id = ? 
            "; 
            $stmt = $pdo->prepare($updateQuery); 
            $stmt->execute([$userId, $postId]); 
        } 

        // Lấy số lượng like/dislike mới nhất 
        $stmt = $pdo->prepare(" 
            SELECT like_count, dislike_count 
            FROM post 
            WHERE id = ? 
        "); 
        $stmt->execute([$postId]); 
        $postCounts = $stmt->fetch(PDO::FETCH_ASSOC); 

        $pdo->commit(); 

        return [ 
            'success' => true, 
            'like_count' => $postCounts['like_count'], 
            'dislike_count' => $postCounts['dislike_count'] 
        ]; 
    } catch (Exception $e) { 
        $pdo->rollBack(); 
        return [ 
            'success' => false, 
            'message' => 'Đã xảy ra lỗi: ' . $e->getMessage() 
        ]; 
    } 
}

function authenticateUser($email, $password) {
    $pdo = getConnection();
    
    // Truy vấn để lấy thông tin người dùng theo email
    $stmt = $pdo->prepare("
        SELECT id, username, email, password, role 
        FROM user 
        WHERE email = ?
    ");
    $stmt->bindValue(1, $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra xem người dùng có tồn tại không
    if (!$user) {
        return false; // Trả về false nếu không tìm thấy email
    }

    // Sử dụng password_verify để so sánh mật khẩu
    if (password_verify($password, $user['password'])) {
        // Loại bỏ password trước khi trả về để đảm bảo an toàn
        unset($user['password']);
        return $user;
    }

    return false; // Trả về false nếu mật khẩu không đúng
}