<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - ForumGW</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-custom-black text-white">
    <style>
        .user-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .user-card:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(255, 153, 0, 0.2);
        }
    </style>

    <div class="container mx-auto p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-custom-orange">Users</h1>
            <div class="relative">
                <input 
                    type="text" 
                    id="searchUsers" 
                    placeholder="Search users..." 
                    class="w-full py-2 px-4 pl-10 bg-custom-darkGray text-white rounded-full focus:outline-none focus:ring-2 focus:ring-custom-orange"
                >
                <i data-feather="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-custom-orange w-5 h-5"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($data['user'] as $user): ?>
                <div class="user-card bg-custom-darkGray rounded-lg p-6 border border-custom-mediumGray">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 rounded-full overflow-hidden mr-4">
                            <img 
                                src="<?php echo !empty($user['avatar']) ? htmlspecialchars($user['avatar']) : 'assets/default-avatar.png'; ?>" 
                                alt="<?php echo htmlspecialchars($user['username']); ?> avatar" 
                                class="w-full h-full object-cover"
                            >
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white"><?php echo htmlspecialchars($user['username']); ?></h3>
                            <p class="text-custom-lightGray text-sm"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-custom-lightGray text-xs mb-1">Student ID</label>
                            <p class="text-white"><?php echo htmlspecialchars($user['student_id'] ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="block text-custom-lightGray text-xs mb-1">Phone Number</label>
                            <p class="text-white"><?php echo htmlspecialchars($user['phone_number'] ?? 'N/A'); ?></p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-custom-mediumGray pt-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-1">
                                <i data-feather="calendar" class="w-4 h-4 text-custom-orange"></i>
                                <span class="text-sm text-custom-lightGray">
                                    Joined: <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                                </span>
                            </div>
                        </div>
                        <button class="px-4 py-2 bg-custom-orange text-black rounded-lg hover:opacity-80 transition">
                            View Profile
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>


    <script>
        // Tìm kiếm người dùng
        document.getElementById('searchUsers').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const userCards = document.querySelectorAll('.user-card');

            userCards.forEach(card => {
                const username = card.querySelector('h3').textContent.toLowerCase();
                if (username.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>