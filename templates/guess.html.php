<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account-ForumGW</title>
    <link REL="SHORTCUT ICON" HREF="favicon.ico">
    <link rel="stylesheet" href="account_user.css">
    <script src="account_user.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body>
    <!-- Content Section -->
    <section class="bg-custom-darkGray rounded-lg p-6 mb-8 shadow-lg w-full">
        <div class="flex items-start space-x-8">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                <div class="relative">
                    <?php if (!empty($data['user']['avatar'])): ?>
                        <img src="<?php echo htmlspecialchars($data['user']['avatar']); ?>" alt="User  Avatar" 
                            class="w-36 h-36 rounded-full object-cover border-4 border-custom-orange">
                    <?php else: ?>
                        <img src="/api/placeholder/150/150" alt="User  Avatar" 
                            class="w-36 h-36 rounded-full object-cover border-4 border-custom-orange">
                    <?php endif; ?>
                    <button class="absolute bottom-0 right-0 bg-custom-orange p-2 rounded-full hover:bg-opacity-80">
                        <i data-feather="camera" class="w-4 h-4 text-custom-black"></i>
                    </button>
                </div>
            </div>
    
            <!-- Profile Delete Button -->
            <div class="relative flex items-center space-x-2">
                <button 
                    class="profile-delete-btn p-1.5 rounded-full hover:text-custom-orange transition-colors">
                    <i data-feather="trash-2" class="w-100 h-5"></i>
                </button>
            </div>
    
            <!-- User Info -->
            <div class="flex-grow">
                <div class="grid grid-cols-3 gap-8">
                    <div class="space-y-4">
                        <div>
                            <label class="text-custom-lightGray text-sm">Full Name</label>
                            <p class="text-white text-lg font-semibold"><?php echo htmlspecialchars($data['user']['username']); ?></p>
                        </div>
                        <div>
                            <label class="text-custom-lightGray text-sm">Email</label>
                            <p class="text-white text-lg"><?php echo htmlspecialchars($data['user']['email']); ?></p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="text-custom-lightGray text-sm">Phone Number</label>
                            <p class="text-white text-lg"><?php echo htmlspecialchars($data['user']['phone_number']); ?></p>
                        </div>
                        <div>
                            <label class="text-custom-lightGray text-sm">Student ID</label>
                            <p class="text-white text-lg"><?php echo htmlspecialchars($data['user']['student_id']); ?></p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="text-custom-lightGray text-sm">Like</label>
                            <p class="text-white text-lg"><?php echo isset($data['user']['likes']) ? htmlspecialchars($data['user']['likes']) : '0'; ?></p>
                        </div>
                        <div>
                            <label class="text-custom-lightGray text-sm">Dislike</label>
                            <p class="text-white text-lg"><?php echo isset($data['user']['dislikes']) ? htmlspecialchars($data['user']['dislikes']) : '0'; ?></p>
                        </div>
                    </div>
                </div>
                <button class="mt-6 px-4 py-2 bg-custom-orange text-custom-black rounded-lg hover:bg-opacity-80 edit-profile-btn">
                    Edit Profile
                </button>


                <!-- Popup Edit Profile -->
                <div id="editProfileModal" class="fixed inset-0 bg-black/50 hidden z-50 overflow-y-auto">
                    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-[#1E1E1E] rounded-lg p-6 w-[400px]">
                        <h2 class="text-2xl font-bold text-[#FF6B00]">Edit Profile</h2>
                        <form method="post" action="updateaccount.php" class="space-y-4">
                            <div>
                                <label class="block text-white mb-2">Username</label>
                                <input type="text" name="username" value="<?php echo htmlspecialchars($data['user']['username']); ?>" required class="w-full p-2 bg-[#2C2C2C] border border-gray-600 text-white rounded-lg">
                            </div>
                            <div>
                                <label class="block text-white mb-2">Email</label>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($data['user']['email']); ?>" required class="w-full p-2 bg-[#2C2C2C] border border-gray-600 text-white rounded-lg">
                            </div>
                            <div>
                                <label class="block text-white mb-2">Phone Number</label>
                                <input type="text" name="phone_number" value="<?php echo htmlspecialchars($data['user']['phone_number']); ?>" class="w-full p-2 bg-[#2C2C2C] border border-gray-600 text-white rounded-lg">
                            </div>
                            <div>
                                <label class="block text-white mb-2">Current Password</label>
                                <input type="password" name="current_password" required class="w-full p-2 bg-[#2C2C2C] border border-gray-600 text-white rounded-lg">
                            </div>
                            <div>
                                <label class="block text-white mb-2">New Password</label>
                                <input type="password" name="new_password" class="w-full p-2 bg-[#2C2C2C] border border-gray-600 text-white rounded-lg">
                            </div>
                            <div class="flex justify-end">
                                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" onclick="closeEditProfileModal()">Cancel</button>
                                <button type="submit" class="bg-[#FF6B00] text-white px-4 py-2 rounded">Update</button>
                            </div>

                            <?php
                                // Kiểm tra và hiển thị thông báo
                                if (isset($_GET['success'])) {
                                    echo '<div class="bg-green-500 text-white p-4 rounded mb-4">' . 
                                        htmlspecialchars($_GET['success']) . '</div>';
                                }
                                if (isset($_GET['error'])) {
                                    echo '<div class="bg-red-500 text-white p-4 rounded mb-4">' . 
                                        htmlspecialchars($_GET['error']) . '</div>';
                                }
                                ?>
                        </form>
                    </div>
                </div>

                <script>
                    function closeEditProfileModal() {
                        document.getElementById('editProfileModal').classList.add('hidden');
                    }

                    document.querySelector('.edit-profile-btn').addEventListener('click', function() {
                        document.getElementById('editProfileModal').classList.remove('hidden');
                    });
                </script>
            </div>
        </div>
    </section>

    
    <!-- Lower Section Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Account Activity Section -->
        <div class="md:col-span-1">
            <section class="bg-custom-darkGray rounded-lg p-6 shadow-lg">
                <h2 class="text-2xl font-bold mb-6 text-white">Account Activity</h2>
                <div class="space-y-6">
                    <?php if (!empty($data['activities'])): ?>
                        <?php foreach ($data['activities'] as $activity): ?>
                            <div class="border-l-4 border-custom-orange pl-4">
                                <p class="text-white"><?php echo htmlspecialchars($activity['action']); ?></p>
                                <p class="text-custom-lightGray text-sm">IP: <?php echo htmlspecialchars($activity['ip']); ?></p>
                                <p class="text-custom-orange text-sm"><?php echo htmlspecialchars($activity['timestamp']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-custom-lightGray">No recent activity</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <div class="md:col-span-2">
            <div class="space-y-6">
                <h3 class="font-bold text-white text-xl mb-4">Bài viết đã đăng</h3>
                
                <?php foreach ($data['posts'] as $post) { ?>
                <div class="bg-custom-darkGray rounded-lg shadow-md p-4 border border-custom-orange" data-post-id="<?php echo $post['id']; ?>">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
                            <img src="<?php echo htmlspecialchars($post['avatar']); ?>" alt="User  avatar" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="font-bold text-white"><?php echo htmlspecialchars($post['title']); ?></h3>
                            <p class="text-sm text-custom-lightGray"><?php echo htmlspecialchars($post['created_at']); ?></p>
                        </div>

                    </div>
                    <div class="space-y-4">
                        <p class="text-gray-300"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                        <?php if (!empty($post['image'])) { ?>
                        <div class="rounded-lg overflow-hidden">
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post content" class="w-full">
                        </div>
                        <?php } ?>
                    </div>
                    <div class="flex items-center space-x-6 my-4 py-2 border-y border-custom-mediumGray">
                        <button class="flex items-center space-x-2 text-custom-lightGray hover:text-custom-orange transition">
                            <i data-feather="eye" class="w-5 h-5"></i>
                            <span><?php echo $post['view_count']; ?> views</span>
                        </button>
                        <button class="flex items-center space-x-2 text-custom-lightGray hover:text-custom-orange transition">
                            <i data-feather="thumbs-up" class="w-5 h-5"></i>
                            <span><?php echo $post['like_count']; ?> likes</span>
                        </button>
                        <button class="flex items-center space-x-2 text-custom-lightGray hover:text-custom-orange transition">
                            <i data-feather="thumbs-down" class="w-5 h-5"></i>
                            <span><?php echo $post['dislike_count']; ?> dislikes</span>
                        </button>
                        <button class="flex items-center space-x-2 text-custom-lightGray hover:text-custom-orange transition">
                            <i data-feather="share-2" class="w-5 h-5"></i>
                            <span>Share</span>
                        </button>
                    </div>

                    <div class="pt-2">
                        <h4 class="font-semibold text-sm text-gray-300 mb-2">Bình luận:</h4>
                        <?php 
                        $postComments = getPostComments($post['id']);
                        if (!empty($postComments['comments'])) { 
                            foreach ($postComments['comments'] as $comment) { ?>
                                <div class="ml-8 mt-2 p-2 bg-custom-mediumGray rounded">
                                    <div class="flex items-center mb-1">
                                        <div class="w-6 h-6 rounded-full overflow-hidden mr-2">
                                            <img src="<?php echo htmlspecialchars($comment['avatar']); ?>" alt="Commenter avatar" class="w-full h-full object-cover">
                                        </div>
                                        <span class="font-semibold text-sm text-gray-300"><?php echo htmlspecialchars($comment['username']); ?></span>
                                        <span class="text-xs text-custom-lightGray ml-2"><?php echo htmlspecialchars($comment['created_at']); ?></span>
                                        
                                        <?php 
                                        // Chỉ hiển thị icon trash nếu comment của user hiện tại
                                            if (isset($_SESSION['user_id']) && $comment['user_id'] == $_SESSION['user_id']) { ?>
                                                <button class="delete-comment-btn ml-auto" data-comment-id="<?php echo $comment['id']; ?>">
                                                    <i data-feather="trash" class="w-5 h-5 text-custom-lightGray hover:text-custom-orange"></i>
                                                </button>
                                                <button class="edit-comment-btn ml-2" data-comment-id="<?php echo $comment['id']; ?>" data-content="<?php echo htmlspecialchars($comment['content']); ?>">
                                                    <i data-feather="edit" class="w-5 h-5 text-custom-lightGray hover:text-custom-orange"></i>
                                                </button>

                                                <!-- Modal for editing comment -->
                                                <div id="editCommentModal" class="fixed inset-0 bg-black/50 hidden z-50 overflow-y-auto">
                                                    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-[#1E1E1E] rounded-lg p-6 w-[600px] max-h-[90vh] overflow-y-auto">
                                                        <h2 class="text-2xl font-bold text-[#FF6B00]">Edit Comment</h2>
                                                        <form method="post" action="updatecomment.php">
                                                            <input type="hidden" name="comment_id" id="editCommentId">
                                                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                                            <textarea id="editCommentContent" name="content" rows="4" required class="w-full p-3 bg-[#2C2C2C] border border-gray-600 text-white rounded-lg"></textarea>
                                                            <div class="flex justify-end pt-4">
                                                                <button type="submit" class ="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
                                                                <button type="button" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded" onclick="closeModal()">Cancel</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                
                                                <script>
                                                    document.querySelectorAll('.edit-comment-btn').forEach(button => {
                                                        button.addEventListener('click', function() {
                                                            const commentId = this.getAttribute('data-comment-id');
                                                            const content = this.getAttribute('data-content');
                                                            document.getElementById('editCommentId').value = commentId;
                                                            document.getElementById('editCommentContent').value = content;
                                                            document.getElementById('editCommentModal').classList.remove('hidden');
                                                        });
                                                    });
                
                                                    function closeModal() {
                                                        document.getElementById('editCommentModal').classList.add('hidden');
                                                    }
                                                </script>
                                        <?php } ?>
                                    </div>
                                    <p class="text-sm text-gray-400"><?php echo htmlspecialchars($comment['content']); ?></p>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="mt-2 flex items-center space-x-2">
                            <div class="flex-grow">
                                <form action="addcomment.php" method="post" enctype="multipart/form-data" class="flex items-center space-x-2">
                                    <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['id']); ?>">
                                    <input type="text" id="commentInput" name="content" placeholder="Viết bình luận..." 
                                        class="w-full p-2 bg-custom-black border border-custom-mediumGray rounded focus:outline-none focus:ring-2 focus:ring-custom-orange text-white" required>
                                    
                                    <div class="flex items-center space-x-2">
                                        <button type="button" id="imageUploadBtn" class="text-custom-lightGray hover:text-custom-orange">
                                            <i data-feather="image" class="w-6 h-6"></i>
                                        </button>
                                        <input type="file" id="commentImageInput" name="image" class="hidden" accept="image/*">
                                        <button type="submit" id="sendCommentBtn" class="text-custom-lightGray hover:text-custom-orange">
                                            <i data-feather="send" class="w-6 h-6"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Confirm Action Modal -->
    <div id="confirmActionModal" class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-custom-darkGray rounded-lg p-6 w-full max-w-md mx-4">
            <h3 id="modalTitle" class="text-2xl font-bold text-white mb-4 "></h3>
            <p id="modalMessage" class="text-custom-lightGray mb-6"></p>
            <div class="flex justify-end space-x-4">
                <button onclick="toggleModal('confirmActionModal')"
                        class="px-6 py-2 bg-custom-mediumGray text-white rounded-lg hover:bg-opacity-80">
                    Hủy
                </button>
                <button id="confirmActionButton" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-opacity-80">
                    Xóa
                </button>
            </div>
        </div>
    </div>

    <div id="updatePostModal" class="fixed inset-0 bg-black/50 hidden z-50 overflow-y-auto">
        <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-[#1E1E1E] rounded-lg p-6 w-[600px] max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-[#FF6B00]">Chỉnh Sửa Bài Viết</h2>
                <button class="text-gray-400 hover:text-white transition-colors" onclick="closeUpdatePostModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="updatePostForm" method="post" action="updatepost.php" class="space-y-4">
                <input type="hidden" id="updatePostId" name="post_id">
                
                <div>
                    <label class="block text-white mb-2">Tiêu đề *</label>
                    <input type="text" id="updatePostTitle" name="title" placeholder="Nhập tiêu đề" required
                        class="w-full p-3 bg-[#2C2C2C] border border-gray-600 text-white rounded-lg
                        focus:outline-none focus:border-[#FF6B00] focus:ring-1 focus:ring-[#FF6B00]">
                </div>
    
                <div>
                    <label class="block text-white mb-2">Nội dung *</label>
                    <textarea id="updatePostContent" name="content" placeholder="Nhập nội dung" rows="6" required
                        class="w-full p-3 bg-[#2C2C2C] border border-gray-600 text-white rounded-lg
                        focus:outline-none focus:border-[#FF6B00] focus:ring-1 focus:ring-[#FF6B00]"></textarea>
                </div>
    
                <div>
                    <label class="block text-white mb-2" for="modules">Modules:</label>
                    
                    <div id="updateModuleTagContainer" class="flex flex-wrap gap-2 mt-2">
                        <!-- Selected modules will appear here -->
                    </div>
                    
                    <div class="relative">
                        <input 
                            type="text" 
                            id="updateModuleInput" 
                            placeholder="Type # to select modules" 
                            class="w-full p-3 bg-[#2C2C2C] border border-gray-600 text-white rounded-lg 
                                focus:outline-none focus:border-[#FF6B00] focus:ring-1 focus:ring-[#FF6B00]"
                        >
                        
                        <div
                            id="updateModuleDropdown" 
                            class="absolute z-10 w-full bg-[#2C2C2C] border border-gray-600 rounded-lg mt-1 hidden 
                                    max-h-60 overflow-y-auto scrollbar-thin scrollbar-thumb-[#FF6B00] scrollbar-track-[#333333]"
                        >
                            <!-- Module suggestions will appear here -->
                        </div>
                    </div>
                </div>
    
                <div class="mt-4">
                    <h4 class="text-white mb-2">Suggested Modules:</h4>
                    <div id="updateSuggestedModules" class="flex flex-wrap gap-2">
                        <?php foreach (getAllModules() as $module): ?>
                            <span 
                                data-module-id="<?= $module['id'] ?>" 
                                class="suggested-module cursor-pointer px-3 py-1 bg-[#333333] text-white rounded-full hover:bg-[#FF6B00] transition"
                            >
                                #<?= $module['name'] ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
    
                <button type="submit" class="w-full bg-[#FF6B00] text-white py-2 rounded-lg hover:bg-[#FF8C00] transition-colors">
                    Cập nhật bài viết
                </button>
            </form>
        </div>
    </div>
    <script>
    // Initialize Tailwind config
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

    document.querySelectorAll('.delete-comment-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const confirmation = confirm("Bạn có chắc chắn muốn xóa bình luận này không?");
            if (confirmation) {
                window.location.href = `deletecomment.php?commentId=${commentId}`;
            }
        });
    });
</script>
</body>
</html>