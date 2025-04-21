<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules-ForumGW</title>
    <link REL="SHORTCUT ICON" HREF="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="templates/module.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link rel="stylesheet" href="templates/module.css">
</head>
<body>
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
            <?php foreach ($data['modules'] as $module): ?>
            <div class="col">
                <form method="POST" action="">
                    <input type="hidden" name="module_id" value="<?php echo $module['id']; ?>">
                    <button type="submit" class="card h-100 w-full text-left <?php echo ($data['selectedModuleId'] == $module['id']) ? 'border-2 border-custom-orange' : ''; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($module['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($module['description']); ?></p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Created at: <?php echo date('Y-m-d', strtotime($module['created_at'])); ?></small>
                        </div>
                    </button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>

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
</body>
</html>