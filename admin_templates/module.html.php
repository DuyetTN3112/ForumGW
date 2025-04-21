<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules-ForumGW</title>
    <link REL="SHORTCUT ICON" HREF="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body>
    <style>
        .card {
            position: relative;
            padding: 16px;
            width: 280px;
            height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
            cursor: pointer;
            transition: transform 0.3s;
            border: 2px solid #FF9900;
            border-radius: 8px;
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .card-content {
            font-size: 0.9rem;
            margin-bottom: 12px;
            flex-grow: 1;
        }
        .card-stats {
            font-size: 0.8rem;
            display: flex;
            justify-content: space-between;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .grid {
            gap: 16px;
        }
        .icon-lg {
            width: 32px;
            height: 32px;
        }
    </style>
    <!-- Main Content -->
    <main class="flex-grow p-8 space-y-6">
        <h1 class="text-4xl font-bold text-custom-orange">Modules</h1>
        <h2 class="text-xl text-custom-lightGray">A hashtag is a keyword or label that categorizes your post with other, similar posts. Using the right hashtags makes it easier for others to find and answer your post.</h2>

        <!-- Module Search Bar -->
        <div class="relative my-6 max-w-2xl">
            <input id="searchModules" type="text" placeholder="Search modules" 
                   class="w-full py-2 px-4 pl-10 bg-custom-darkGray text-white rounded-full focus:outline-none focus:ring-2 focus:ring-custom-orange text-lg"
                   onfocus="this.placeholder=''">
            <i data-feather="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-custom-orange w-5 h-5"></i>
        </div>


        <!-- Modules Cards -->
        <div id="moduleGrid" class="grid grid-cols-4 gap-10">
            <!-- Card thêm module mới -->
            <div class="col">
                <div class="card h-100 w-full text-center flex items-center justify-center cursor-pointer hover:bg-custom-mediumGray transition duration-300" id="addModuleCard" onclick="openAddModulePopup()">
                    <div class="text-center">
                        <i data-feather="plus-circle" class="w-16 h-16 text-custom-orange mx-auto mb-4"></i>
                        <h5 class="card-title text-custom-orange">Add New Module</h5>
                        <p class="text-custom-lightGray">Create a new module category</p>
                    </div>
                </div>
            </div>

            <!-- Các module hiện có -->
            <?php foreach ($data['modules'] as $module): ?>
            <div class="col">

                    <form method="POST" action="">
                        <input type="hidden" name="module_id" value="<?php echo $module['id']; ?>">

                        <button type="submit" class="card h-100 w-full text-left relative group <?php echo ($data['selectedModuleId'] == $module['id']) ? 'border-2 border-custom-orange' : ''; ?>">
                            <div class="card-body flex-grow">
                                <h5 class="card-title"><?php echo htmlspecialchars($module['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($module['description']); ?></p>
                            </div>

                            <div class="card-footer flex justify-between items-center">
                                <small class="text-muted">Created at: <?php echo date('Y-m-d', strtotime($module['created_at'])); ?></small>
                                
                                <!-- Nút xóa module được đưa ra ngoài để dễ click -->
                                <i 
                                data-feather="trash-2" 
                                class="absolute top-2 right-2 transition-colors delete-module-btn opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-custom-orange" 
                                data-module-id="<?php echo $module['id']; ?>"
                                data-module-name="<?php echo htmlspecialchars($module['name']); ?>"></i>
                                ></i>

                                <i 
                                data-feather="edit" 
                                class="transition-colors edit-module-btn opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-custom-orange" 
                                data-module-id="<?php echo $module['id']; ?>"
                                data-module-name="<?php echo htmlspecialchars($module['name']); ?>"
                                data-module-description="<?php echo htmlspecialchars($module['description']); ?>"></i>
                            </div>


                            <!-- Trong module.html.php -->

                        </button>
                    </form>
            </div>
            <?php endforeach; ?>
        </div>


        <!-- Popup cập nhật module -->
        <div id="updateModulePopup" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
            <div class="bg-custom-darkGray p-8 rounded-lg w-96">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-custom-orange">Update Module</h2>
                    <button onclick="closeUpdateModulePopup()" class="text-custom-lightGray hover:text-white">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
                
                <form id="popupUpdateModuleForm" method="POST" action="" class="space-y-4">
                    <input type="hidden" id="updateModuleId" name="module_id">
                    
                    <div>
                        <label for="popupUpdateModuleName" class="block text-custom-lightGray mb-2">Module Name</label>
                        <input 
                            type="text" 
                            id="popupUpdateModuleName" 
                            name="name" 
                            required 
                            placeholder="Enter module name" 
                            class="w-full bg-custom-mediumGray text-white p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-custom-orange"
                        >
                    </div>

                    <div>
                        <label for="popupUpdateModuleDescription" class="block text-custom-lightGray mb-2">Module Description</label>
                        <textarea 
                            id="popupUpdateModuleDescription" 
                            name="description" 
                            rows="4" 
                            placeholder="Enter module description" 
                            class="w-full bg-custom-mediumGray text-white p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-custom-orange"
                        ></textarea>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button 
                            type="button" 
                            onclick="closeUpdateModulePopup()" 
                            class="bg-custom-mediumGray text-custom-lightGray py-2 px-4 rounded-lg hover:bg-gray-700"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="bg-custom-orange text-black py-2 px-4 rounded-lg hover:bg-orange-600 transition duration-300"
                        >
                            Update Module
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-module-btn');
        const updatePopup = document.getElementById('updateModulePopup');
        const updateForm = document.getElementById('popupUpdateModuleForm');
        const updateModuleId = document.getElementById('updateModuleId');
        const updateModuleName = document.getElementById('popupUpdateModuleName');
        const updateModuleDescription = document.getElementById('popupUpdateModuleDescription');
        
        // Hàm hiển thị popup update
        function showUpdatePopup(moduleId, moduleName, moduleDescription) {
            updateModuleId.value = moduleId;
            updateModuleName.value = moduleName;
            updateModuleDescription.value = moduleDescription;
            updatePopup.classList.remove('hidden');
        }

        // Hàm ẩn popup update
        function closeUpdateModulePopup() {
            updatePopup.classList.add('hidden');
        }

        // Gắn sự kiện cho các nút edit
        editButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const moduleId = this.getAttribute('data-module-id');
                const moduleName = this.getAttribute('data-module-name');
                const moduleDescription = this.getAttribute('data-module-description');

                showUpdatePopup(moduleId, moduleName, moduleDescription);
            });
        });

        // Xử lý đóng popup khi click nút cancel
        const cancelButton = updatePopup.querySelector('button[type="button"]');
        if (cancelButton) {
            cancelButton.addEventListener('click', closeUpdateModulePopup);
        }

        // Đóng popup khi click ra ngoài
        updatePopup.addEventListener('click', function(e) {
            if (e.target === updatePopup) {
                closeUpdateModulePopup();
            }
        });

        // Đóng popup bằng nút X
        const closeButton = updatePopup.querySelector('button[onclick="closeUpdateModulePopup()"]');
        if (closeButton) {
            closeButton.addEventListener('click', closeUpdateModulePopup);
        }

        // Xử lý submit form update
        updateForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/admin/updatemodule.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    location.reload();
                } else {
                    // Hiển thị lỗi
                    const errorMessage = document.getElementById('errorMessage');
                    const errorNotification = document.getElementById('errorNotification');
                    
                    errorMessage.textContent = result.message || 'Update module failed';
                    errorNotification.classList.remove('hidden');
                    
                    // Tự động ẩn thông báo sau 5 giây
                    setTimeout(() => {
                        errorNotification.classList.add('hidden');
                    }, 5000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorMessage = document.getElementById('errorMessage');
                const errorNotification = document.getElementById('errorNotification');
                
                errorMessage.textContent = 'An error occurred';
                errorNotification.classList.remove('hidden');
                
                // Tự động ẩn thông báo sau 5 giây
                setTimeout(() => {
                    errorNotification.classList.add('hidden');
                }, 5000);
            });
        });
    });
</script>


        <!-- Popup xác nhận xóa module -->
        <div id="deleteModulePopup" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
            <div class="bg-custom-darkGray p-8 rounded-lg w-96">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-red-500">Xóa Module</h2>
                    <button onclick="closeDeleteModulePopup()" class="text-custom-lightGray hover:text-white">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
                
                <p class="text-custom-lightGray mb-6">
                    Bạn có chắc chắn muốn xóa module <span id="deleteModuleName" class="font-bold"></span> không?
                </p>

                <div class="flex justify-end space-x-4">
                    <button 
                        type="button" 
                        onclick="closeDeleteModulePopup()" 
                        class="bg-custom-mediumGray text-custom-lightGray py-2 px-4 rounded-lg hover:bg-gray-700"
                    >
                        Hủy
                    </button>
                    <button 
    id="confirmDeleteModuleBtn"
    data-module-id="" 
    class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition duration-300"
>
    Xóa
</button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteButtons = document.querySelectorAll('.delete-module-btn');
                const deletePopup = document.getElementById('deleteModulePopup');
                const deleteModuleName = document.getElementById('deleteModuleName');
                const confirmDeleteBtn = document.getElementById('confirmDeleteModuleBtn');
                const errorNotification = document.getElementById('errorNotification');
                const errorMessage = document.getElementById('errorMessage');

                // Hàm hiển thị popup xác nhận xóa
                function showDeleteConfirmation(moduleId, moduleName) {
                    deleteModuleName.textContent = moduleName;
                    confirmDeleteBtn.setAttribute('data-module-id', moduleId);
                    deletePopup.classList.remove('hidden');
                }

                // Hàm ẩn popup xác nhận xóa
                function hideDeleteConfirmation() {
                    deletePopup.classList.add('hidden');
                }

                // Hàm xử lý xóa module
                function handleDeleteModule(moduleId) {
                    fetch('/admin/deletemodule.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `module_id=${moduleId}`
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errorData => {
                                throw new Error(errorData.message || 'Xóa module thất bại');
                            });
                        }
                        return response.json();
                    })
                    .then(result => {
                        if (result.status === 'success') {
                            location.reload(); // Reload trang sau khi xóa
                        } else {
                            throw new Error(result.message || 'Xóa module thất bại');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        
                        if (errorMessage) {
                            errorMessage.textContent = error.message;
                        }
                        
                        if (errorNotification) {
                            errorNotification.classList.remove('hidden');
                        }
                    });
                }

                // Gắn sự kiện cho các nút xóa module
                deleteButtons.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const moduleId = this.getAttribute('data-module-id');
                        const moduleName = this.getAttribute('data-module-name');

                        showDeleteConfirmation(moduleId, moduleName);
                    });
                });

                // Gắn sự kiện cho nút xác nhận xóa
                if (confirmDeleteBtn) {
                    confirmDeleteBtn.addEventListener('click', function() {
                        const moduleId = this.getAttribute('data-module-id');
                        handleDeleteModule(moduleId);
                        hideDeleteConfirmation();
                    });
                }

                // Gắn sự kiện cho nút hủy
                const cancelDeleteBtn = deletePopup.querySelector('button[onclick="closeDeleteModulePopup()"]');
                if (cancelDeleteBtn) {
                    cancelDeleteBtn.addEventListener('click', hideDeleteConfirmation);
                }
            });
        </script>

        <!-- Thêm ngay trước phần popup -->
        <div id="errorNotification" class="fixed top-4 right-4 z-50 hidden">
            <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="errorMessage"></span>
                </div>
            </div>
        </div>

        <!-- Popup thêm module (ẩn ban đầu) -->
        <div id="addModulePopup" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
            <div class="bg-custom-darkGray p-8 rounded-lg w-96">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-custom-orange">Create New Module</h2>
                    <button onclick="closeAddModulePopup()" class="text-custom-lightGray hover:text-white">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
                
                <form id="popupAddModuleForm" method="POST" action="" class="space-y-4">
                    <div>
                        <label for="popupModuleName" class="block text-custom-lightGray mb-2">Module Name</label>
                        <input 
                            type="text" 
                            id="popupModuleName" 
                            name="name" 
                            required 
                            placeholder="Enter module name" 
                            class="w-full bg-custom-mediumGray text-white p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-custom-orange"
                        >
                    </div>

                    <div>
                        <label for="popupModuleDescription" class="block text-custom-lightGray mb-2">Module Description</label>
                        <textarea 
                            id="popupModuleDescription" 
                            name="description" 
                            rows="4" 
                            placeholder="Enter module description" 
                            class="w-full bg-custom-mediumGray text-white p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-custom-orange"
                        ></textarea>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button 
                            type="button" 
                            onclick="closeAddModulePopup()" 
                            class="bg-custom-mediumGray text-custom-lightGray py-2 px-4 rounded-lg hover:bg-gray-700"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="bg-custom-orange text-black py-2 px-4 rounded-lg hover:bg-orange-600 transition duration-300"
                        >
                            Create Module
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openAddModulePopup() {
                document.getElementById('addModulePopup').classList.remove('hidden');
            }

            function closeAddModulePopup() {
                document.getElementById('addModulePopup').classList.add('hidden');
                // Reset form
                document.getElementById('popupAddModuleForm').reset();
            }

            document.getElementById('popupAddModuleForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Ngăn submit mặc định
            
            // Ẩn thông báo lỗi trước
            const errorNotification = document.getElementById('errorNotification');
            const errorMessage = document.getElementById('errorMessage');
            errorNotification.classList.add('hidden');
            
            // Lấy dữ liệu form
            const formData = new FormData(this);
            
            // Gửi AJAX request
            fetch('/admin/addmodule.php', {  // Thay đổi URL hoàn chỉnh
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Content-Type:', response.headers.get('content-type'));
                
                // Kiểm tra content type
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // Nếu không phải JSON, log toàn bộ response
                    return response.text().then(text => {
                        console.error('Non-JSON response:', text);
                        throw new Error('Expected JSON, got: ' + text);
                    });
                }
            })
            .then(result => {
                if (result.status === 'success') {
                    // Thành công thì reload trang
                    location.reload();
                } else {
                    // Hiển thị lỗi trên màn hình
                    errorMessage.textContent = result.message || 'An unknown error occurred';
                    errorNotification.classList.remove('hidden');
                    
                    // Tự động ẩn thông báo sau 5 giây
                    setTimeout(() => {
                        errorNotification.classList.add('hidden');
                    }, 5000);
                }
            })
            .catch(error => {
                console.error('Full error:', error);
                
                // Hiển thị lỗi kết nối
                errorMessage.textContent = 'Error: ' + error.message;
                errorNotification.classList.remove('hidden');
                
                // Tự động ẩn thông báo sau 5 giây
                setTimeout(() => {
                    errorNotification.classList.add('hidden');
                }, 5000);
            });
        });
        </script>
        


        <!-- User Posts Section -->
        <?php if (!empty($data['selectedModulePosts']) || $data['selectedModuleId']): ?>
        <div class="user-posts">
            <h3 class="text-3xl font-bold text-custom-orange">Posts in Selected Module</h3>
            <div class="posts-list">
                <?php foreach ($data['selectedModulePosts'] as $post): ?>
                <div class="post-item bg-custom-darkGray p-4 rounded-lg mb-4">
                    <h4 class="post-title text-xl font-semibold"><?php echo htmlspecialchars($post['title'] ?? ''); ?></h4>
                    <p class="post-content"><?php echo htmlspecialchars($post['content'] ?? ''); ?></p>
                    <small class="text-muted">
                        Posted by: <?php echo htmlspecialchars($post['author'] ?? 'Unknown'); ?> 
                        on <?php echo date('Y-m-d', strtotime($post['created_at'] ?? 'now')); ?>
                        in Modules: <?php echo htmlspecialchars($post['module_names'] ?? ''); ?>
                    </small>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </main>


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

        
        function createModuleCard(module, index) {
            const card = document.createElement('div');
            card.classList.add('card');
            card.style.backgroundColor = index % 2 === 0 ? '#FF9900' : '#000000';
            
            card.innerHTML = `
                <div class="card-title">${module.title}</div>
                <div class="card-content">${module.content}</div>
                <div class="card-stats">
                    <span>${module.stats.questions.toLocaleString()} questions</span>
                    <span>${module.stats.askedToday} asked today, ${module.stats.askedThisWeek} this week</span>
                </div>
            `;
            
            return card;
        }
        
        function initializeModules() {
            const grid = document.getElementById('moduleGrid');
            moduleData.forEach((module, index) => {
                grid.appendChild(createModuleCard(module, index));
            });
            feather.replace();
        }
    </script>
</body>
</html>