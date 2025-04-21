<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gửi Phản Hồi</title>

</head>
<body>
    <div class="container mx-auto px-4 py-8">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                <?php
                echo htmlspecialchars($_SESSION['message']);
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
                ?>
            </div>
        <?php endif; ?>


        <form action="feedback.php" method="POST" class="max-w-lg mx-auto bg-custom-darkGray p-6 rounded-lg shadow-md">
            <h2 class="text-2xl text-custom-orange mb-6 text-center">Gửi Phản Hồi</h2>
            
            <div class="mb-4">
                <label for="name" class="block text-custom-lightGray mb-2">Tên:</label>
                <input type="text" id="name" name="name" required 
                    class="w-full px-3 py-2 bg-custom-black text-white border border-custom-mediumGray rounded-md focus:outline-none focus:ring-2 focus:ring-custom-orange">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-custom-lightGray mb-2">Email của bạn:</label>
                <input type="email" id="email" name="email" required 
                    class="w-full px-3 py-2 bg-custom-black text-white border border-custom-mediumGray rounded-md focus:outline-none focus:ring-2 focus:ring-custom-orange">
            </div>

            <div class="mb-4">
                <label for="message" class="block text-custom-lightGray mb-2">Tin nhắn:</label>
                <textarea id="message" name="message" required rows="5"
                    class="w-full px-3 py-2 bg-custom-black text-white border border-custom-mediumGray rounded-md focus:outline-none focus:ring-2 focus:ring-custom-orange"></textarea>
            </div>

            <div class="text-center">
                <button type="submit" name="send_email" 
                    class="bg-custom-orange text-black px-6 py-2 rounded-full hover:bg-opacity-90 transition duration-300">
                    Gửi Phản Hồi
                </button>
            </div>
        </form>
    </div>
</body>
</html>