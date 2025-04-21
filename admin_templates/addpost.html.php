<h1>Add Post</h1>
<?php if (isset($error)): ?>
    <div class="error"><?= $error ?></div>
<?php endif; ?>
<div class="mb-8">
    <div class="bg-custom-darkGray rounded-lg shadow-lg p-4 border border-custom-mediumGray hover:border-custom-orange transition-colors duration-200 cursor-pointer" onclick="openModal()">
        
        
        <div class="flex items-center space-x-4">
            <div class="w-10 h-10 rounded-full overflow-hidden">
                <img src="assets/avatar1.jpg" alt="Your avatar" class="w-full h-full object-cover">
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
        
        <form method="post" action="addpost.php" class="space-y-4">
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

            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#FF6B00]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828  0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-white">Add Image</span>
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
    const modalContent = modal.querySelector('.fixed.top-1/2');
    
    // Check if the click is on the modal background (not the content)
    if (event.target === modal) {
        closeModal();
    }
}

// Add the event listener to the modal
document.getElementById('createPostModal').addEventListener('click', closeModalOnOutsideClick);      
        
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

    // Thêm sự kiện click cho modal để đóng khi click ra ngoài
    document.querySelector('#createPostModal button[onclick="closeModal()"]').addEventListener('click', closeModal);


    </script>
