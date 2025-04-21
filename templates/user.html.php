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
                    <img src="<?php 
                        echo htmlspecialchars(
                            strpos($data['user']['avatar'], 'uploads/') === 0 
                            ? $data['user']['avatar'] 
                            : 'uploads/' . $data['user']['avatar']
                        ); 
                    ?>" alt="User Avatar" 
                    class="w-36 h-36 rounded-full object-cover border-4 border-custom-orange">
                    <?php else: ?>
                    <img src="uploads/votri.jpg" alt="Default Avatar" 
                    class="w-36 h-36 rounded-full object-cover border-4 border-custom-orange">
                    <?php endif; ?>
                    <!-- Input file ẩn -->
                    <input type="file" id="avatarUpload" name="avatar" accept="image/*" class="hidden">
                
                    <button id="avatarUploadBtn" class="absolute bottom-0 right-0 bg-custom-orange p-2 rounded-full hover:bg-opacity-80"> 
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
                            <?php if (!empty($data['user']['avatar'])): ?>
                            <img src="<?php 
                                echo htmlspecialchars(
                                    strpos($data['user']['avatar'], 'uploads/') === 0 
                                    ? $data['user']['avatar'] 
                                    : 'uploads/' . $data['user']['avatar']
                                ); 
                            ?>" alt="User Avatar">
                            <?php else: ?>
                            <img src="uploads/votri.jpg" alt="Default Avatar">
                            <?php endif; ?>
                        </div>
                        <div>
                            <h3 class="font-bold text-white"><?php echo htmlspecialchars($post['username']); ?></h3>
                            <p class="text-sm text-custom-lightGray"><?php echo htmlspecialchars($post['created_at']); ?></p>
                        </div>
                        <div class="ml-auto flex items-center space-x-2">
                            <button class="edit-post-btn p-1.5 rounded-full hover:bg-custom-orange hover:text-black transition-colors">
                                <i data-feather="edit-2" class="w-5 h-5 text-custom-lightGray"></i>
                            </button>
                            <button class="delete-post-btn p-1.5 rounded-full hover:bg-custom-orange hover:text-white transition-colors">
                                <i data-feather="trash-2" class="w-5 h-5 text-custom-lightGray"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-4"> 
                        <h2 class="text-xl font-bold text-white mb-2"><?php echo htmlspecialchars($post['title']); ?></h2>
                        <p class="text-gray-300"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                        <?php if (!empty($post['image'])) { ?> 
                            <div class="rounded-lg overflow-hidden mb-4"> 
                                <img src="<?php echo htmlspecialchars($post['image']); ?>" 
                                     alt="Post content" 
                                     class="w-full object-cover max-h-96"> 
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
                                            <?php if (!empty($data['user']['avatar'])): ?>
                                            <img src="<?php 
                                                echo htmlspecialchars(
                                                    strpos($data['user']['avatar'], 'uploads/') === 0 
                                                    ? $data['user']['avatar'] 
                                                    : 'uploads/' . $data['user']['avatar']
                                                ); 
                                            ?>" alt="User Avatar">
                                            <?php else: ?>
                                            <img src="uploads/votri.jpg" alt="Default Avatar">
                                            <?php endif; ?>
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
        document.querySelectorAll('.edit-post-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const postContainer = this.closest('[data-post-id]');
                const postId = postContainer.getAttribute('data-post-id');
                const title = postContainer.querySelector('h3').textContent;
                const content = postContainer.querySelector('.text-gray-300').textContent;
    
                // Điền dữ liệu vào form
                document.getElementById('updatePostId').value = postId;
                document.getElementById('updatePostTitle').value = title.trim();
                document.getElementById('updatePostContent').value = content.trim();
    
                // Xử lý modules
                const moduleTagContainer = document.getElementById('updateModuleTagContainer');
                moduleTagContainer.innerHTML = ''; // Clear existing tags
    
                // Lấy các module hiện tại của bài post
                const moduleElements = postContainer.querySelectorAll('.post-module');
                moduleElements.forEach(moduleEl => {
                    const moduleName = moduleEl.textContent.replace('#', '').trim(); const moduleId = moduleEl.getAttribute('data-module-id');
                    moduleTagContainer.appendChild(createModuleTag(moduleId, moduleName));
                });
    
                // Hiển thị modal
                const modal = document.getElementById('updatePostModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });
    
        function closeUpdatePostModal() {
            const modal = document.getElementById('updatePostModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    
        function createModuleTag(moduleId, moduleName) {
            const tag = document.createElement('div');
            tag.className = 'bg-gray-700 text-white px-3 py-1 rounded-full flex items-center justify-between';
            tag.innerHTML = `
                ${moduleName}
                <button class="ml-2 text-red-500" onclick="removeModuleTag(this)">×</button>
            `;
            tag.setAttribute('data-module-id', moduleId);
            return tag;
        }
    
        function removeModuleTag(button) {
            const tag = button.parentElement;
            tag.remove();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const updateModuleInput = document.getElementById('updateModuleInput');
            const updateModuleDropdown = document.getElementById('updateModuleDropdown');
            const updateModuleTagContainer = document.getElementById('updateModuleTagContainer');
            const updateSuggestedModules = document.getElementById('updateSuggestedModules');
            const selectedModules = new Set();
    
            const allModules = [
                <?php 
                $modules = getAllModules();
                $moduleJson = array_map(function($module) {
                    return "{id: {$module['id']}, name: '{$module['name']}'}";
                }, $modules);
                echo implode(',', $moduleJson);
                ?>
            ];
    
            function createModuleTag(moduleId, moduleName) {
                const tag = document.createElement('span');
                tag.className = 'px-3 py-1 bg-[#FF6B00] text-white rounded-full flex items-center';
                tag.innerHTML = `
                    #${moduleName}
                    <button type="button" class="ml-2 text-white remove-module">
                        &times;
                    </button>
                    <input type="hidden" name="modules[]" value="${moduleId}">
                `;
                
                tag.querySelector('.remove-module').addEventListener('click', () => {
                    selectedModules.delete(moduleId);
                    updateModuleTagContainer.removeChild(tag);
                });
    
                return tag;
            }
    
            updateModuleInput.addEventListener('input', function(e) {
                const value = e.target.value;
                
                if (value.startsWith('#')) {
                    const searchTerm = value.substring(1).toLowerCase();
                    const matchedModules = allModules.filter(module => 
                        module.name.toLowerCase().includes(searchTerm)
                    );
    
                    updateModuleDropdown.innerHTML = matchedModules.map(module => `
                        <div 
                            data-module-id="${module.id}" 
                            class="module-option px-4 py-2 hover:bg-[#FF6B00] cursor-pointer"
                        >
                            #${module.name}
                        </div>
                    `).join('');
    
                    updateModuleDropdown.classList.remove('hidden');
    
                    // Add click event to dropdown options
                    updateModuleDropdown.querySelectorAll('.module-option').forEach(option => {
                        option.addEventListener('click', function() {
                            const moduleId = this.getAttribute('data-module-id');
                            const moduleName = this.textContent.substring(1);
    
                            if (!selectedModules.has(moduleId)) {
                                selectedModules.add(moduleId);
                                updateModuleTagContainer.appendChild(createModuleTag(moduleId, moduleName));
                            }
    
                            updateModuleInput.value = '';
                            updateModuleDropdown.classList.add('hidden');
                        });
                    });
                } else {
                    updateModuleDropdown.classList.add('hidden');
                }
            });
    
            // Suggested modules click handler
            updateSuggestedModules.querySelectorAll('.suggested-module').forEach(module => {
                module.addEventListener('click', function() {
                    const moduleId = this.getAttribute('data-module-id');
                    const moduleName = this.textContent.substring(1);
    
                    if (!selectedModules.has(moduleId)) {
                        selectedModules.add(moduleId);
                        updateModuleTagContainer.appendChild(createModuleTag(moduleId, moduleName));
                    }
                });
            });
    
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!updateModuleInput.contains(e.target) && !updateModuleDropdown.contains(e.target)) {
                    updateModuleDropdown.classList.add('hidden');
                }
            });
        });
    </script>


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

    let currentAction = null; // Variable to store the current action (delete post or delete profile)
    let currentPostId = null; // ID of the current post if deleting a post

    // Function to show/hide modal
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }

    // Function to confirm action
    function confirmAction() {
        if (currentAction === 'deletePost' && currentPostId) {
            // Redirect to the delete post script
            window.location.href = `deletepost.php?postId=${currentPostId}`;
        } else if (currentAction === 'deleteProfile') {
            handleProfileDelete(); // Call the profile delete function
        }
        toggleModal('confirmActionModal'); // Close the modal after confirmation
    }

    // Function to handle delete post button click
    function handleDeletePostClick(postId) {
        currentPostId = postId;
        currentAction = 'deletePost'; // Set current action to delete post
        document.getElementById('modalTitle').innerText = 'Xóa Bài Viết';
        document.getElementById('modalMessage').innerText = 'Bạn có chắc chắn muốn xóa bài viết này không? Hành động này không thể hoàn tác.';
        toggleModal('confirmActionModal'); // Show modal
    }

    // Function to handle delete profile button click
    function handleDeleteProfileClick() {
        currentAction = 'deleteProfile'; // Set current action to delete profile
        document.getElementById('modalTitle').innerText = 'Xóa Hồ Sơ';
        document.getElementById('modalMessage').innerText = 'Bạn có chắc chắn muốn xóa hồ sơ của mình không? Hành động này không thể hoàn tác.';
        toggleModal('confirmActionModal'); // Show modal
    }

    // Add event listeners for delete post buttons
    const deleteButtons = document.querySelectorAll('.delete-post-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const postContainer = this.closest('[data-post-id]');
            const postId = postContainer.dataset.postId;
            handleDeletePostClick(postId);
        });
    });

    // Add event listener for delete profile button
    const deleteProfileBtn = document.querySelector('.profile-delete-btn');
    if (deleteProfileBtn) {
        deleteProfileBtn.addEventListener('click', function(e) {
            e.preventDefault();
            handleDeleteProfileClick();
        });
    }

    // Handle confirm action button click
    document.getElementById('confirmActionButton').addEventListener('click', function(e) {
        confirmAction(); // Call confirm action function
    });

    // Add event listener for cancel button in modal
    const cancelDeleteButton = document.querySelector('#confirmActionModal button[onclick*="toggleModal"]');
    if (cancelDeleteButton) {
        cancelDeleteButton.addEventListener('click', function(e) {
            e.preventDefault();
            toggleModal('confirmActionModal');
            currentPostId = null; // Reset post ID
            currentAction = null; // Reset action
        });
    }

    // Add event listener to close modal when clicking outside
    const modal = document.getElementById('confirmActionModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                toggleModal('confirmActionModal');
                currentPostId = null; // Reset post ID
                currentAction = null; // Reset action
            }
        });
    }

    // Thêm hàm xóa profile
    function handleProfileDelete() {
        // Chuyển hướng đến deleteaccount.php để xử lý việc xóa tài khoản
        window.location.href = 'deleteaccount.php';
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

    // Modify the existing toggleModal function or add this
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }

    // Ensure the cancel button works
    document.querySelector('#confirmActionModal button[onclick*="toggleModal"]').addEventListener('click', function(e) {
        e.preventDefault();
        toggleModal('confirmActionModal');
        currentPostId = null;
        currentAction = null;
    });


        document.addEventListener('DOMContentLoaded', function() {
            const avatarUploadBtn = document.getElementById('avatarUploadBtn');
            const avatarUpload = document.getElementById('avatarUpload');
            const avatarImage = document.getElementById('avatarImage');
        
            // Khi nhấn nút camera, kích hoạt input file
            avatarUploadBtn.addEventListener('click', function() {
                avatarUpload.click();
            });
        
            // Xử lý khi chọn file
            avatarUpload.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    // Kiểm tra kích thước file (ví dụ: max 5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Kích thước ảnh không được vượt quá 5MB');
                        return;
                    }
        
                    // Kiểm tra định dạng file
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    if (!allowedTypes.includes(file.type)) {
                        alert('Chỉ chấp nhận ảnh định dạng JPG, PNG hoặc GIF');
                        return;
                    }
        
                    // Tạo FormData để upload
                    const formData = new FormData();
                    formData.append('avatar', file);
        
                    // Gửi request Ajax
                    fetch('updateavatar.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Cập nhật ảnh avatar
                            avatarImage.src = data.avatarPath;
                            alert('Cập nhật avatar thành công');
                        } else {
                            alert('Lỗi: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi upload ảnh');
                    });
                }
            });
        });


</script>
</body>
</html>