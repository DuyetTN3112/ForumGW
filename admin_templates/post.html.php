<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules-ForumGW</title>
    <link rel="stylesheet" href="admin_templates/post.css">
    <!-- <script src="post_user.js" defer></script> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>

<body>

    <div class="container">
<!-- Import file addpost.html.php -->
        <?php include_once 'addpost.html.php'; ?>
    </div>


    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-white">Posts</h2>
        <div class="flex space-x-4">
            <button class="flex items-center space-x-2 px-4 py-2 text-sm text-custom-lightGray hover:text-custom-orange transition-colors duration-200 group">
                <i data-feather="eye" class="w-4 h-4 group-hover:text-custom-orange"></i>
                <span>Most View</span>
            </button>
            <button class="flex items-center space-x-2 px-4 py-2 text-sm text-custom-lightGray hover:text-custom-orange transition-colors duration-200 group">
                <i data-feather="thumbs-up" class="w-4 h-4 group-hover:text-custom-orange"></i>
                <span>Most Liked</span>
            </button>
            <button class="flex items-center space-x-2 px-4 py-2 text-sm text-custom-lightGray hover:text-custom-orange transition-colors duration-200 group">
                <i data-feather="thumbs-down" class="w-4 h-4 group-hover:text-custom-orange"></i>
                <span>Most Disliked</span>
            </button>

        </div>
    </div>

    <div class="space-y-6">

            <?php foreach ($data['posts'] as $post) { ?>

                <div class="bg-custom-darkGray rounded-lg shadow-md p-4 border border-custom-orangedata-post-id=" data-post-id="<?php echo $post['id']; ?>">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
                            <img src="<?php echo htmlspecialchars($post['avatar']); ?>" alt="User avatar" class="w-full h-full object-cover">
                    </div>
                    
                    <div>
                        <h3 class="font-bold text-white"><?php echo htmlspecialchars($post['username']); ?></h3>
                        <p class="text-sm text-custom-lightGray"><?php echo htmlspecialchars($post['created_at']); ?></p>
                    </div>
                    <div class="ml-auto flex items-center space-x-2">
                    <?php 
            // Hiển thị nút xóa nếu là admin hoặc chủ sở hữu bài post
                    if ($isAdmin || (isset($_SESSION['user_id']) && $post['user_id'] == $_SESSION['user_id'])): 
                    ?>
                            <button class="edit-post-btn p-1.5 rounded-full hover:bg-custom-orange hover:text-black transition-colors">
                                <i data-feather="edit-2" class="w-5 h-5 text-custom-lightGray"></i>
                            </button>
                            <button class="delete-post-btn p-1.5 rounded-full hover:bg-custom-orange hover:text-white transition-colors">
                                <i data-feather="trash-2" class="w-5 h-5 text-custom-lightGray"></i>
                            </button>
                    </div>
                    <?php endif; ?>
                    
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
                <!-- Modal chỉnh sửa bài post cho admin -->
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
            
            <form id="updatePostForm" method="post" action="admin/updateallpost.php" class="space-y-4">
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

                // Tìm container của bài post 
                const postContainer = this.closest('.bg-custom-darkGray');

                // Lấy thông tin bài post 
                const postId = postContainer.getAttribute('data-post-id');
                const title = postContainer.querySelector('h3').textContent.trim(); // Đây là chỗ sai
                const content = postContainer.querySelector('.text-gray-300').textContent.trim();

                // Điền dữ liệu vào form chỉnh sửa 
                document.getElementById('updatePostId').value = postId;
                document.getElementById('updatePostTitle').value = title; // Đang lấy username thay vì title
                document.getElementById('updatePostContent').value = content;

                // Điền dữ liệu vào form chỉnh sửa 
                const updatePostIdInput = document.getElementById('updatePostId');
                if (updatePostIdInput) {
                    updatePostIdInput.value = postId; 
                } else {
                    console.error('Could not find updatePostId input');
                }

                document.getElementById('updatePostTitle').value = title; 
                document.getElementById('updatePostContent').value = content; 

                // Xử lý modules (nếu có) 
                const moduleTagContainer = document.getElementById('updateModuleTagContainer'); 
                moduleTagContainer.innerHTML = ''; // Xóa các tag cũ 

                // Lấy các module hiện tại của bài post 
                const moduleElements = postContainer.querySelectorAll('.post-module'); 
                moduleElements.forEach(moduleEl => { 
                    const moduleName = moduleEl.textContent.replace('#', '').trim(); 
                    const moduleId = moduleEl.getAttribute('data-module-id'); 
                    moduleTagContainer.appendChild(createModuleTag(moduleId, moduleName)); 
                }); 

                // Hiển thị modal 
                const modal = document.getElementById('updatePostModal'); 
                modal.classList.remove('hidden'); 
                modal.classList.add('flex'); 
            }); 
        }); 

                // Tìm container của bài post 
        

        function createModuleTag(moduleId, moduleName) { 
            // Loại bỏ các ký tự '#' nếu có 
            moduleName = moduleName.replace(/^#+/, ''); 

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
                tag.remove(); 
            }); 

            return tag; 
        } 

        function closeUpdatePostModal() { 
            const modal = document.getElementById('updatePostModal'); 
            if (modal) { 
                modal.classList.add('hidden'); 
                modal.classList.remove('flex'); 
            } 
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
                            const moduleName = this.textContent.replace(/^#+/, '').trim(); 

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
                    const moduleName = this.textContent.substring(1).trim(); 

                    const existingModule = Array.from(updateModuleTagContainer.children).find( 
                        tag => tag.getAttribute('data-module-id') === moduleId 
                    ); 

                    if (!existingModule) { 
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

        document.getElementById('updatePostForm').addEventListener('submit', function(e) { 
            e.preventDefault(); 

            const formData = new FormData(this); 

            // Log toàn bộ dữ liệu form
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            fetch('/admin/updateallpost.php', { 
                method: 'POST', 
                body: formData 
            }) 
            .then(response => { 
                if (!response.ok) { 
                    // Xử lý lỗi HTTP 
                    return response.json().then(errorData => { 
                        throw new Error(errorData.message || 'Có lỗi xảy ra'); 
                    }); 
                } 
                return response.json(); 
            }) 
            .then(data => { 
                if (data.success) { 
                    alert(data.message || 'Bài viết đã được cập nhật thành công'); 
                    closeUpdatePostModal(); 

                    // Cập nhật nội dung bài viết trực tiếp trên trang 
                    const postContainer = document.querySelector(`[data-post-id="${data.data.post_id}"]`); 
                    if (postContainer) { 
                        const titleElement = postContainer.querySelector('h3'); 
                        const contentElement = postContainer.querySelector('.text-gray-300'); 

                        if (titleElement) titleElement.textContent = data.data.title; 
                        if (contentElement) contentElement.textContent = data.data.content; 
                    } 
                } 
            })
            .catch(error => { 
                console.error('Lỗi:', error); 
                alert('Có lỗi xảy ra trong quá trình cập nhật bài viết'); 
            }); 
        });

    </script>
            
            
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
                    $commentsData = getPostComments($post['id']); // Gọi hàm để lấy bình luận của bài viết
                    if (!empty($commentsData['comments'])) {
                        foreach ($commentsData['comments'] as $comment) {
                    ?>
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
                            <?php } ?>
                        </div>
                        <p class="text-sm text-gray-400"><?php echo htmlspecialchars($comment['content']); ?></p>
                    </div>
                    <?php 
                        }
                        // Thêm điều khiển phân trang nếu cần
                        if ($commentsData['total_pages'] > 1) {
                    ?>
                        <nav aria-label="Phân trang bình luận">
                            <ul class="pagination">
                                <?php for ($i = 1; $i <= $commentsData['total_pages']; $i++) { ?>
                                    <li class="page-item <?php echo $i === $commentsData['current_page'] ? 'active' : ''; ?>">
                                        <a class="page-link" href="?post_id=<?php echo $post['id']; ?>&page=<?php echo $i; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </nav>
                    <?php
                        }
                    } else {
                    ?>
                        <p class="text-muted">Chưa có bình luận nào.</p>
                    <?php
                    }
                    ?>
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


        <nav aria-label="Phân trang bài viết" class="flex justify-center items-center space-x-2 mt-6">
            <?php if ($page > 1): ?>
                <a href="?page=1" 
                   class="px-4 py-2 bg-custom-darkGray text-white rounded-md hover:bg-custom-orange transition-colors">
                    First
                </a>
                <a href="?page=<?php echo $page - 1; ?>" 
                   class="px-4 py-2 bg-custom-darkGray text-white rounded-md hover:bg-custom-orange transition-colors">
                    Previous
                </a>
            <?php endif; ?>
        
            <?php
            // Hàm render trang
            function renderPageLink($pageNum, $currentPage) {
                return sprintf(
                    '<a href="?page=%d" class="px-4 py-2 rounded-md transition-colors %s">%d</a>',
                    $pageNum,
                    $pageNum === $currentPage 
                        ? 'bg-custom-orange text-white' 
                        : 'bg-custom-darkGray text-custom-lightGray hover:bg-custom-mediumGray',
                    $pageNum
                );
            }
        
            // Logic hiển thị trang
            $range = 2; // Số trang hiển thị hai bên trang hiện tại
            $dots = '<span class="px-4 py-2 text-custom-lightGray">...</span>';
        
            // Các trường hợp hiển thị
            if ($totalPages <= 5) {
                // Nếu tổng số trang ít hơn hoặc bằng 5
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo renderPageLink($i, $page);
                }
            } else {
                // Hiển thị trang đầu
                if ($page > $range + 1) {
                    echo renderPageLink(1, $page);
                    
                    // Hiển thị dấu ... nếu cách xa trang đầu
                    if ($page > $range + 2) {
                        echo $dots;
                    }
                }
        
                // Các trang xung quanh trang hiện tại
                $start = max(1, $page - $range);
                $end = min($totalPages, $page + $range);
        
                for ($i = $start; $i <= $end; $i++) {
                    echo renderPageLink($i, $page);
                }
        
                // Hiển thị trang cuối
                if ($page < $totalPages - $range) {
                    // Hiển thị dấu ... nếu cách xa trang cuối
                    if ($page < $totalPages - $range - 1) {
                        echo $dots;
                    }
                    
                    echo renderPageLink($totalPages, $page);
                }
            }
            ?>
        
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>" 
                   class="px-4 py-2 bg-custom-darkGray text-white rounded-md hover:bg-custom-orange transition-colors">
                    Next
                </a>
                <a href="?page=<?php echo $totalPages; ?>" 
                   class="px-4 py-2 bg-custom-darkGray text-white rounded-md hover:bg-custom-orange transition-colors">
                    Last
                </a>
            <?php endif; ?>
        </nav>
    
    </div>
    

    <script>



        document.querySelectorAll('.delete-comment-btn').forEach(button => {
            button.addEventListener('click', function() {
                const commentId = this.getAttribute('data-comment-id');
                const confirmation = confirm("Bạn có chắc chắn muốn xóa bình luận này không?");
                if (confirmation) {
                    window.location.href = `deletecomment.php?commentId=${commentId}`;
                }
            });
        });



    document.addEventListener('DOMContentLoaded', function() {
        const commentInput = document.getElementById('commentInput');
        const sendCommentBtn = document.getElementById('sendCommentBtn');
        const imageUploadBtn = document.getElementById('imageUploadBtn');
        const commentImageInput = document.getElementById('commentImageInput');
        
        // Xử lý nút upload ảnh
        imageUploadBtn.addEventListener('click', function() {
            commentImageInput.click();
        });
    
        // Xử lý gửi comment
        sendCommentBtn.addEventListener('click', function() {
            const postContainer = this.closest('[data-post-id]');
            const postId = postContainer.getAttribute('data-post-id');
            const content = commentInput.value.trim();
            
            if (content === '') {
                alert('Vui lòng nhập nội dung bình luận');
                return;
            }
    
            // Tạo form để submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'addcomment.php';
            form.style.display = 'none';
    
            // Thêm input post_id
            const postIdInput = document.createElement('input');
            postIdInput.type = 'hidden';
            postIdInput.name = 'post_id';
            postIdInput.value = postId;
            form.appendChild(postIdInput);
    
            // Thêm input content
            const contentInput = document.createElement('input');
            contentInput.type = 'hidden';
            contentInput.name = 'content';
            contentInput.value = content;
            form.appendChild(contentInput);
    
            // Nếu có ảnh, thêm vào form
            if (commentImageInput.files.length > 0) {
                form.appendChild(commentImageInput);
            }
    
            // Thêm form vào body và submit
            document.body.appendChild(form);
            form.submit();
        });
    
        // Xử lý preview ảnh khi chọn
        commentImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                console.log('Đã chọn ảnh:', file.name);
            }
        });
    });

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
    
    // Xử lý xóa bài post
    document.querySelectorAll('.delete-post-btn').forEach(button => {
    button.addEventListener('click', function() {
        const postId = this.closest('.bg-custom-darkGray').getAttribute('data-post-id');
        
        // Tạo modal xác nhận
        const confirmModal = document.createElement('div');
        confirmModal.innerHTML = `
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div class="bg-custom-darkGray rounded-lg p-6 max-w-sm w-full">
                    <h2 class="text-xl font-bold text-white mb-4">Xác nhận xóa bài viết</h2>
                    <p class="text-custom-lightGray mb-6">Bạn có chắc chắn muốn xóa bài viết này? Hành động này không thể hoàn tác.</p>
                    <div class="flex justify-end space-x-4">
                        <button id="cancel-delete" class="px-4 py-2 bg-custom-mediumGray text-white rounded hover:bg-gray-600">Hủy</button>
                        <button id="confirm-delete" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Xóa</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(confirmModal);
        
        // Xử lý nút hủy
        document.getElementById('cancel-delete').addEventListener('click', () => {
            document.body.removeChild(confirmModal);
        });
        
        // Xử lý nút xác nhận xóa
        document.getElementById('confirm-delete').addEventListener('click', () => {
            // Thêm hiệu ứng loading
            const confirmDeleteBtn = document.getElementById('confirm-delete');
            confirmDeleteBtn.innerHTML = 'Đang xóa...';
            confirmDeleteBtn.disabled = true;

            fetch(`/admin/deleteallpost.php?postId=${postId}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Thêm hiệu ứng chuyển trang mượt mà
                    document.body.style.opacity = '0';
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                } else {
                    // Hiển thị thông báo lỗi
                    alert(data.message || 'Không thể xóa bài viết. Vui lòng thử lại.');
                    
                    // Khôi phục nút
                    confirmDeleteBtn.innerHTML = 'Xóa';
                    confirmDeleteBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
                
                // Khôi phục nút
                confirmDeleteBtn.innerHTML = 'Xóa';
                confirmDeleteBtn.disabled = false;
            });
        });
    });
});

    </script>
</body>
</html>