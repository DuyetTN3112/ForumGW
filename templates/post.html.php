<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules-ForumGW</title>
    <!-- <link rel="stylesheet" href="post_user.css"> -->
    <!-- <script src="post_user.js" defer></script> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>

<body>
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
    </script>
    <style>
        /* Thêm CSS cho modal và các thành phần khác */
        .modal-open {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }
        
        @keyframes modalFade {
            from {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }                  
                  /* Cho trình duyệt WebKit (Chrome, Safari) */
        #moduleDropdown::-webkit-scrollbar {
            width: 8px;
        }

        #moduleDropdown::-webkit-scrollbar-track {
            background: #333333;
        }

        #moduleDropdown::-webkit-scrollbar-thumb {
            background: #FF6B00;
            border-radius: 4px;
        }

        /* Cho Firefox */
        #moduleDropdown {
            scrollbar-width: thin;
            scrollbar-color: #FF6B00 #333333;
        }

                /* Tùy chỉnh thanh cuộn */
        #createPostModal::-webkit-scrollbar {
            width: 10px;
        }

        #createPostModal::-webkit-scrollbar-track {
            background: #2C2C2C;
            border-radius: 10px;
        }

        #createPostModal::-webkit-scrollbar-thumb {
            background: #FF6B00;
            border-radius: 10px;
            border: 3px solid #2C2C2C;
        }

        #createPostModal::-webkit-scrollbar-thumb:hover {
            background: #FF8533;
        }

        /* Firefox scrollbar */
        #createPostModal {
            scrollbar-width: thin;
            scrollbar-color: #FF6B00 #2C2C2C;
        }
    </style>





    <div class="container">
        <h1>Add Post</h1>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <div class="mb-8">
            <div class="bg-custom-darkGray rounded-lg shadow-lg p-4 border border-custom-mediumGray hover:border-custom-orange transition-colors duration-200 cursor-pointer" onclick="openModal()">
                
                
                <div class="flex items-center space-x-4">
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
                    <div class="flex-grow bg-custom-black rounded-full px-4 py-2.5 text-custom-lightGray hover:bg-gray-900 transition-colors duration-200">
                        What do you think?
                    </div>
                </div>
            </div>
        </div>

        <div id="createPostModal" class="fixed inset-0 bg-black/50 hidden z-50 overflow-y-auto">
        <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-[#1E1E1E] rounded-lg p-6 w-[600px] max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-[#FF6B00]">Create New Post</h2>
                    <button class="text-gray-400 hover:text-white transition-colors" onclick="closeModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form method="post" action="addpost.php" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block text-white mb-2">Title *</label>
                        <input type="text" name="title" placeholder="Enter title" required
                            class="w-full p-3 bg-[#2C2C2C] border border-gray-600 text-white rounded-lg
                            focus:outline-none focus:border-[#FF6B00] focus:ring-1 focus:ring-[#FF6B00]">
                    </div>
        
                    <div>
                        <label class="block text-white mb-2">Content *</label>
                        <textarea id="content" name="content" placeholder="Enter description" rows="6" required
                            class="w-full p-3 bg-[#2C2C2C] border border-gray-600 text-white rounded-lg
                            focus:outline-none focus:border-[#FF6B00] focus:ring-1 focus:ring-[#FF6B00]"></textarea>
                    </div>
        
                    <div>
                        <label class="block text-white mb-2">Image</label>
                        <input type="file" name="image" accept="image/*" 
                            class="w-full p-3 bg-[#2C2C2C] border border-gray-600 text-white rounded-lg 
                            file:mr-4 file:rounded-full file:border-0 file:bg-[#FF6B00] file:text-white file:px-4 file:py-2
                            hover:file:bg-[#FF8533]">
                    </div>
        
                    <div>
                    <label class="block text-white mb-2" for="modules">Modules:</label>
                    
                    <div class="relative">
                        <input 
                            type="text" 
                            id="moduleInput" 
                            placeholder="Type # to select modules" 
                            class="w-full p-3 bg-[#2C2C2C] border border-gray-600 text-white rounded-lg 
                                focus:outline-none focus:border-[#FF6B00] focus:ring-1 focus:ring-[#FF6B00]"
                        >
                        
                        <div id="moduleTagContainer" class="flex flex-wrap gap-2 mt-2">
                            <!-- Selected modules will appear here -->
                        </div>
                        
                        <div
                            id="moduleDropdown" 
                            class="absolute z-10 w-full bg-[#2C2C2C] border border-gray-600 rounded-lg mt-1 hidden 
                                    max-h-60 overflow-y-auto scrollbar-thin scrollbar-thumb-[#FF6B00] scrollbar-track-[#333333]"
                        >
                            <!-- Module suggestions will appear here -->
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h4 class="text-white mb-2">Suggested Modules:</h4>
                    <div id="suggestedModules" class="flex flex-wrap gap-2">
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

                <div class="flex justify-end pt-4">
                    <button type="submit" 
                        class="bg-[#FF6B00] text-white px-6 py-2 rounded-lg
                        hover:bg-[#FF8533] transition-colors duration-300">
                        Post
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>


    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-white">Posts</h2>

        

        <div class="flex space-x-4">
            <a href="?filter=most_view" class="flex items-center space-x-2 px-4 py-2 text-sm text-custom-lightGray hover:text-custom-orange transition-colors duration-200 group <?php echo ($data['current_filter'] == 'most_view') ? 'text-custom-orange' : ''; ?>">
                <i data-feather="eye" class="w-4 h-4 group-hover:text-custom-orange"></i>
                <span>Most View</span>
            </a>
            <a href="?filter=most_liked" class="flex items-center space-x-2 px-4 py-2 text-sm text-custom-lightGray hover:text-custom-orange transition-colors duration-200 group <?php echo ($data['current_filter'] == 'most_liked') ? 'text-custom-orange' : ''; ?>">
                <i data-feather="thumbs-up" class="w-4 h-4 group-hover:text-custom-orange"></i>
                <span>Most Liked</span>
            </a>
            <a href="?filter=most_disliked" class="flex items-center space-x-2 px-4 py-2 text-sm text-custom-lightGray hover:text-custom-orange transition-colors duration-200 group <?php echo ($data['current_filter'] == 'most_disliked') ? 'text-custom-orange' : ''; ?>">
                <i data-feather="thumbs-down" class="w-4 h-4 group-hover:text-custom-orange"></i>
                <span>Most Disliked</span>
            </a>
            <?php if ($data['current_filter']): ?>
            <a href="?" class="flex items-center space-x-2 px-4 py-2 text-sm text-custom-lightGray hover:text-custom-orange transition-colors duration-200 group">
                <i data-feather="x" class="w-4 h-4 group-hover:text-custom-orange"></i>
                <span>Clear Filter</span>
            </a>
            <?php endif; ?>
        </div>

        <?php if ($data['current_filter']): ?>

<?php endif; ?>

<nav aria-label="Phân trang bài viết" class="flex justify-center items-center space-x-2 mt-6">
    <?php 
    // Chỉ hiển thị phân trang nếu không có filter
    if (!$data['current_filter']): 
    ?>
        <!-- Mã phân trang cũ của bạn -->
    <?php endif; ?>
</nav>
    </div>

    <div class="space-y-6">

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
            
                <div class="flex items-center space-x-6 my-4 py-2 border-y border-custom-mediumGray" data-post-id="<?php echo $post['id']; ?>">
                    <button class="flex items-center space-x-2 text-custom-lightGray hover:text-custom-orange transition">
                        <i data-feather="eye" class="w-5 h-5"></i>
                        <span><?php echo $post['view_count']; ?> views</span>
                    </button>
                    <button class="like-button flex items-center space-x-2 text-custom-lightGray hover:text-custom-orange transition">
                        <i data-feather="thumbs-up" class="w-5 h-5"></i>
                        <span class="like-count"><?php echo $post['like_count']; ?> likes</span>
                    </button>
                    <button class="dislike-button flex items-center space-x-2 text-custom-lightGray hover:text-custom-orange transition">
                        <i data-feather="thumbs-down" class="w-5 h-5"></i>
                        <span class="dislike-count"><?php echo $post['dislike_count']; ?> dislikes</span>
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

        feather.replace();

        function openModal() {
            const modal = document.getElementById('createPostModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('createPostModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function closeModalOnOutsideClick(event) {
            const modal = document.getElementById('createPostModal');
            if (event.target === modal) {
                closeModal();
            }
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

        document.addEventListener('DOMContentLoaded', function() {
                    const moduleInput = document.getElementById('moduleInput');
                    const moduleDropdown = document.getElementById('moduleDropdown');
                    const moduleTagContainer = document.getElementById('moduleTagContainer');
                    const suggestedModules = document.getElementById('suggestedModules');
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
                moduleTagContainer.removeChild(tag);
            });

            return tag;
        }

        moduleInput.addEventListener('input', function(e) {
            const value = e.target.value;
            
            if (value.startsWith('#')) {
                const searchTerm = value.substring(1).toLowerCase();
                const matchedModules = allModules.filter(module => 
                    module.name.toLowerCase().includes(searchTerm)
                );

                moduleDropdown.innerHTML = matchedModules.map(module => `
                    <div 
                        data-module-id="${module.id}" 
                        class="module-option px-4 py-2 hover:bg-[#FF6B00] cursor-pointer"
                    >
                        #${module.name}
                    </div>
                `).join('');

                moduleDropdown.classList.remove('hidden');

                // Add click event to dropdown options
                moduleDropdown.querySelectorAll('.module-option').forEach(option => {
                    option.addEventListener('click', function() {
                        const moduleId = this.getAttribute('data-module-id');
                        const moduleName = this.textContent.substring(1);

                        if (!selectedModules.has(moduleId)) {
                            selectedModules.add(moduleId);
                            moduleTagContainer.appendChild(createModuleTag(moduleId, moduleName));
                        }

                        moduleInput.value = '';
                        moduleDropdown.classList.add('hidden');
                    });
                });
            } else {
                moduleDropdown.classList.add('hidden');
            }
        });

        // Suggested modules click handler
        suggestedModules.querySelectorAll('.suggested-module').forEach(module => {
            module.addEventListener('click', function() {
                const moduleId = this.getAttribute('data-module-id');
                const moduleName = this.textContent.substring(1);

                if (!selectedModules.has(moduleId)) {
                    selectedModules.add(moduleId);
                    moduleTagContainer.appendChild(createModuleTag(moduleId, moduleName));
                }
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!moduleInput.contains(e.target) && !moduleDropdown.contains(e.target)) {
                moduleDropdown.classList.add('hidden');
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

    document.addEventListener('DOMContentLoaded', function() {
        function handleLikeDislike(postId, type, button) {
            fetch('likedislike.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `post_id=${postId}&type=${type}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Cập nhật số lượng like/dislike
                    const likeCountElement = button.closest('[data-post-id]').querySelector('.like-count');
                    const dislikeCountElement = button.closest('[data-post-id]').querySelector('.dislike-count');
    
                    likeCountElement.textContent = `${data.like_count} likes`;
                    dislikeCountElement.textContent = `${data.dislike_count} dislikes`;
    
                    // Cập nhật màu sắc nút
                    const likeButton = button.closest('[data-post-id]').querySelector('.like-button');
                    const dislikeButton = button.closest('[data-post-id]').querySelector('.dislike-button');
    
                    // Reset all buttons first
                    likeButton.classList.remove('text-blue-500', 'text-red-500');
                    dislikeButton.classList.remove('text-blue-500', 'text-red-500');
                    likeButton.classList.add('text-custom-lightGray');
                    dislikeButton.classList.add('text-custom-lightGray');
    
                    // Apply specific styling based on interaction
                    if (type === 'like') {
                        likeButton.classList.remove('text-custom-lightGray');
                        likeButton.classList.add('text-blue-500');
                    } else {
                        dislikeButton.classList.remove('text-custom-lightGray');
                        dislikeButton.classList.add('text-red-500');
                    }
                } else {
                    console.error('Like/Dislike failed:', data.message);
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your request');
            });
        }
    
        // Xử lý like 
        document.querySelectorAll('.like-button').forEach(likeButton => {
            likeButton.addEventListener('click', function() {
                const postId = this.closest('[data-post-id]').getAttribute('data-post-id');
                handleLikeDislike(postId, 'like', this);
            });
        });
    
        // Xử lý dislike 
        document.querySelectorAll('.dislike-button').forEach(dislikeButton => {
            dislikeButton.addEventListener('click', function() {
                const postId = this.closest('[data-post-id]').getAttribute('data-post-id');
                handleLikeDislike(postId, 'dislike', this);
            });
        });
    });

    

    </script>
</body>
</html>