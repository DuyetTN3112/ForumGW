<?php
session_start();

// Hủy tất cả các session
session_unset();     // Xóa tất cả các biến session
session_destroy();   // Hủy session

// Chuyển hướng về trang đăng nhập
header("Location: index.php");
exit;