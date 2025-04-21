<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules-ForumGW</title>
    <!-- <link rel="stylesheet" href="admin_layout.css"> -->
    <!-- <script src="post_user.js" defer></script> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-custom-black text-white">
    <style>
        .post-box {
            height: 2cm;
            max-height: 2cm; /* Giới hạn chiều cao tối đa */
            margin-bottom: 1rem; /* Tạo khoảng cách với post đầu tiên */
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 0.5rem;
            background-color: #333; /* Màu nền */
            border-radius: 0.5rem; /* Bo tròn các góc */
            transition: background-color 0.3s ease;
        }
        
        .post-box:hover {
            background-color: #444; /* Hiệu ứng khi hover */
        }
        .notification-panel {
            display: none;
            position: absolute;
            top: 100%; /* Thay đổi từ 16px thành 100% */
            right: 4px;
            width: 380px;
            max-height: 85vh;
            background-color: #000000;
            border: 2px solid #FF9900;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            overflow: hidden;
            margin-top: 8px; /* Thêm margin-top để tạo khoảng cách */
        }
        
        .notification-header {
            position: sticky;
            top: 0;
            background-color: #000000;
            padding: 16px 20px;
            border-bottom: 2px solid #333333;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2;
        }
        
        .notification-list {
            max-height: calc(85vh - 60px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #FF9900 #333333;
        }
        
        .notification-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .notification-list::-webkit-scrollbar-track {
            background: #333333;
            border-radius: 3px;
        }
        
        .notification-list::-webkit-scrollbar-thumb {
            background: #FF9900;
            border-radius: 3px;
        }
        
        .notification-item {
            padding: 16px 20px;
            border-bottom: 1px solid #333333;
            transition: background-color 0.2s ease;
            cursor: pointer;
        }
        
        .notification-item:hover {
            background-color: #222222;
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        .notification-icon {
            width: 40px;
            height: 40px;
            background-color: #222222;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .notification-content {
            padding: 0 12px;
            flex: 1;
        }
        
        .notification-message {
            color: #CCCCCC;
            font-size: 0.95rem;
            line-height: 1.4;
            margin-bottom: 4px;
        }
        
        .notification-time {
            color: #FF9900;
            font-size: 0.8rem;
        }
        
        .unread-dot {
            width: 8px;
            height: 8px;
            background-color: #FF9900;
            border-radius: 50%;
            margin-left: 8px;
            flex-shrink: 0;
        }
        
        .notification-empty {
            padding: 40px 20px;
            text-align: center;
            color: #666666;
            font-size: 0.95rem;
        }
        
        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #FF9900;
            color: #000000;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        
        .sidebar-item:hover {
            background-color: #FF9900;
            color: #000000;
        }
        .sidebar-item:hover i {
            color: #000000;
        }
        .post-image {
            max-height: 500px;
            object-fit: cover;
        }
        .avatar-image {
            width: 40px;
            height: 40px;
            object-fit: cover;
        }
    </style>
    <!-- Header Section -->
    <header class="flex items-center justify-between px-4 h-16 bg-custom-darkGray shadow-lg">
        <div>
            <span class="text-white text-5xl font-bold">Forum<span class="text-custom-orange">GW</span></span>
        </div>

        <!-- Search Bar -->
        <div class="relative flex-grow max-w-2xl mx-auto">
            <input id="search" type="text" placeholder="Search on ForumGW" 
                   class="w-full py-2 px-4 pl-10 bg-custom-black text-white rounded-full focus:outline-none focus:ring-2 focus:ring-custom-orange text-lg"
                   onfocus="this.placeholder=''">
            <i data-feather="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-custom-orange w-5 h-5"></i>
        </div>
        

        <!-- Module, Notification, and Account Icons -->
        <div class="flex items-center space-x-4">
            <div class="rounded-full bg-custom-darkGray p-3 hover:bg-custom-mediumGray">
                <i data-feather="grid" class="text-custom-orange icon-lg"></i>
            </div>
            
            <!-- Notification Button and Panel -->
            <div class="relative">
                <div class="rounded-full bg-custom-darkGray p-3 hover:bg-custom-mediumGray cursor-pointer" id="notificationBtn">
                    <i data-feather="bell" class="text-custom-orange icon-lg"></i>
                    <span class="notification-count">0</span>
                </div>

                <!-- Notification Panel -->
                <div id="notificationPanel" class="notification-panel">
                    <div class="notification-header">
                        <h2 class="text-xl font-bold text-white">Thông báo</h2>
                        <span class="text-custom-orange text-sm cursor-pointer" id="markAllRead">Mark all as read</span>
                    </div>
                    <div id="notificationList" class="notification-list">
                        <!-- Example static notifications -->
                        <div class="notification-item">
                            <div class="notification-content">
                                <div class="notification-avatar">
                                    <img src="assets/avatar1.jpg" alt="User avatar" class="w-10 h-10 rounded-full">
                                </div>
                                <div class="notification-text">
                                    <p>Nguyễn Văn A đã bình luận về bài viết của bạn</p>
                                    <span class="text-sm text-custom-lightGray">2 giờ trước</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex items-center space-x-2">
                <div class="rounded-full bg-custom-darkGray p-3 hover:bg-custom-mediumGray" onclick="window.location.href='admin_index.php?page=user'">
                    <i data-feather="user" class="text-custom-orange icon-lg"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar and Main Content -->
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 h-screen bg-custom-black flex flex-col items-start py-6 space-y-4">
            <a href="admin_index.php" class="sidebar-item flex items-center space-x-4 rounded-full bg-custom-darkGray p-3 hover:bg-custom-orange transition w-48 ml-4">
                <div class="w-8 h-8 flex items-center justify-center">
                    <i data-feather="home" class="text-custom-orange w-5 h-5"></i>
                </div>
                <span class="text-sm">Home</span>
            </a>

            <a href="admin_index.php?page=users" class="sidebar-item flex items-center space-x-4 rounded-full bg-custom-darkGray p-3 hover:bg-custom-orange transition w-48 ml-4">
                <div class="w-8 h-8 flex items-center justify-center">
                    <i data-feather="user" class="text-custom-orange w-5 h-5"></i>
                </div>
                <span class="text-sm">Users</span>
            </a>

            <a href="admin_index.php" class="sidebar-item flex items-center space-x-4 rounded-full bg-custom-darkGray p-3 hover:bg-custom-orange transition w-48 ml-4">
                <div class="w-8 h-8 flex items-center justify-center">
                    <i data-feather="file-plus" class="text-custom-orange w-5 h-5"></i>
                </div>
                <span class="text-sm">Posts</span>
            </a>

            <a href="admin_index.php?page=modules" class="sidebar-item flex items-center space-x-4 rounded-full bg-custom-darkGray p-3 hover:bg-custom-orange transition w-48 ml-4">
                <div class="w-8 h-8 flex items-center justify-center">
                    <i data-feather="hash" class="text-custom-orange w-5 h-5"></i>
                </div>
                <span class="text-sm">Module</span>
            </a>

            <a href="logout.php" class="sidebar-item flex items-center space-x-4 rounded-full bg-custom-darkGray p-3 hover:bg-custom-orange transition w-48 ml-4">
                <div class="w-8 h-8 flex items-center justify-center">
                    <i data-feather="log-out" class="text-custom-orange w-5 h-5"></i>
                </div>
                <span class="text-sm">Logout</span>
            </a>

        </aside>

        

        <!-- Main Content -->
        <main class="flex-1 p-8 bg-custom-black">
            <div class="w-full">
                <?php if (isset($_SESSION['message'])): ?>
                    <?php 
                        $messageType = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'success';
                        $alertClass = 'bg-' . ($messageType == 'success' ? 'green-500' : 'red-500');
                    ?>
                    <div class="<?php echo $alertClass; ?> text-white px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo htmlspecialchars($_SESSION['message']); ?></span>
                        <button type="button" class="absolute top-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                            <span class="sr-only">Close</span>
                            <svg class="h-4 w-4 fill-current" role="button" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20">
                                <path d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414l-2.934 2.935a1 1 0 01-1.414-1.414l2.935-2.935-2.935-2.934a1 1 0 011.414-1.414L10 8.586l2.934-2.935a1 1 0 011.414 1.414l-2.935 2.934 2.935 2.935a1 1 0 010 1.414z" />
                            </svg>
                        </button>
                    </div>
                    <?php 
                        unset($_SESSION['message']);
                        unset($_SESSION['message_type']);
                    ?>
                <?php endif; ?>

                <?php
                if (file_exists($content)) {
                    include $content;
                } else {
                    echo '<div class="text-center text-gray-500">Page not found</div>';
                }
                ?>
            </div>
        </main>
    </div>

    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        custom: {
                            black: '#000000',
                            orange: '#FF9900',
                            darkGray: '#222222',
                            mediumGray: '#333333',
                            lightGray: '#CCCCCC',
                        },
                    },
                },
            },
        }
        
        feather.replace();

        // Notification related code
        document.addEventListener('DOMContentLoaded', function() {
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationPanel = document.getElementById('notificationPanel');
            const notificationList = document.getElementById('notificationList');
            const markAllReadBtn = document.getElementById('markAllRead');
            let notifications = [];
        
            // Sample notifications for testing
            function addSampleNotifications() {
                for(let i = 1; i <= 10; i++) {
                    notifications.push({
                        id: i,
                        type: i % 2 === 0 ? 'post' : 'mention',
                        content: i % 2 === 0 
                            ? 'Bài viết của bạn đã được đăng thành công!'
                            : 'Người dùng @username đã tag bạn trong một bài viết',
                        timestamp: 'Vừa xong',
                        read: false
                    });
                }
                renderNotifications();
                updateNotificationCount();
            }
        
            // Toggle notification panel
            notificationBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationPanel.style.display = notificationPanel.style.display === 'none' ? 'block' : 'none';
                updateNotificationCount();
            });
        
            // Close panel when clicking outside
            document.addEventListener('click', function(e) {
                if (!notificationPanel.contains(e.target) && e.target !== notificationBtn) {
                    notificationPanel.style.display = 'none';
                }
            });
        
            // Mark all as read
            markAllReadBtn.addEventListener('click', function() {
                notifications.forEach(notification => notification.read = true);
                renderNotifications();
                updateNotificationCount();
            });
        
            function renderNotifications() {
                if (notifications.length === 0) {
                    notificationList.innerHTML = `
                        <div class="notification-empty">
                            <i data-feather="bell-off" class="w-6 h-6 text-custom-orange mx-auto mb-3"></i>
                            <p>Không có thông báo nào</p>
                        </div>
                    `;
                } else {
                    notificationList.innerHTML = notifications.map(notification => `
                        <div class="notification-item" data-id="${notification.id}">
                            <div class="flex items-start">
                                <div class="notification-icon">
                                    <i data-feather="${notification.type === 'post' ? 'check-circle' : 'at-sign'}" 
                                        class="w-5 h-5 text-custom-orange"></i>
                                </div>
                                <div class="notification-content">
                                    <p class="notification-message">${notification.content}</p>
                                    <span class="notification-time">${notification.timestamp}</span>
                                </div>
                                ${!notification.read ? '<div class="unread-dot"></div>' : ''}
                            </div>
                        </div>
                    `).join('');
                }
                feather.replace();
            }
        
            // Handle notification click
            notificationList.addEventListener('click', function(e) {
                const notificationItem = e.target.closest('.notification-item');
                if (notificationItem) {
                    const notificationId = parseInt(notificationItem.dataset.id);
                    const notification = notifications.find(n => n.id === notificationId);
                    if (notification && !notification.read) {
                        notification.read = true;
                        renderNotifications();
                        updateNotificationCount();
                    }
                }
            });
        
                // Add sample notifications for testing
            addSampleNotifications();
        // Example function to simulate new notifications (for testing)
        
            });
            
        // Initialize the modules when the page loads
        window.onload = initializeModules;
    </script>
</body>
</html>