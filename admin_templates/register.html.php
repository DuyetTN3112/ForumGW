<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ForumGW - Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        custom: {
                            black: '#000000',
                            orange: '#FF9000',
                            darkGray: '#222222',
                            mediumGray: '#333333',
                        },
                    },
                },
            },
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"></script>
</head>
<body class="bg-custom-black min-h-screen">
    <!-- Main Container -->
    <div class="container mx-auto px-4 py-8 flex">
        <!-- Left Section -->
        <div class="w-1/2 pr-8 flex flex-col items-start">
            <div class="mb-48"></div>
            <div class="text-4xl font-bold mb-8 self-start">
                <span class="text-white">Forum</span>
                <span class="bg-custom-orange text-custom-black px-2 py-1 rounded">GW</span>
            </div>
            <h1 class="text-5xl font-bold text-white text-left mb-12">
                THERE'S A LOT MORE TO FORUMGW THAN YOU THINK!
            </h1>
            <div class="flex justify-between items-center w-full">
                <div class="flex flex-col items-center">
                    <span class="text-white text-sm">Ask Questions</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-white text-sm">Connect with Communities</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-white text-sm">Comment on Post</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-white text-sm">Contact</span>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="w-1/2 pl-12 flex justify-center mt-[3cm]">
            <!-- Sign Up Form -->
            <div id="signupForm" class="bg-custom-black p-6 rounded-lg border border-gray-700" style="width: 70%;">
                <h2 class="text-4xl font-bold text-white mb-2 text-center">Sign up for free</h2>
                <p class="text-center text-white mb-6">and enhance your experience</p>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form action="register.php" method="POST" class="space-y-4">
                    <div>
                        <input type="text" name="username" placeholder="Username" 
                               class="w-full p-2 rounded bg-custom-darkGray text-white border border-gray-700" required>
                    </div>
                    <div>
                        <input type="email" name="email" placeholder="Email" 
                               class="w-full p-2 rounded bg-custom-darkGray text-white border border-gray-700" required>
                    </div>
                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="Password" 
                               class="w-full p-2 rounded bg-custom-darkGray text-white border border-gray-700" required>
                        <button type="button" id="togglePassword" class="absolute right-2 top-2 text-white">
                            <i data-feather="eye"></i>
                        </button>
                    </div>
                    <div>
                        <input type="text" name="phone_number" placeholder="Phone Number (Optional)" 
                               class="w-full p-2 rounded bg-custom-darkGray text-white border border-gray-700">
                    </div>
                    <div>
                        <input type="text" name="student_id" placeholder="Student ID (Optional)" 
                               class="w-full p-2 rounded bg-custom-darkGray text-white border border-gray-700">
                    </div>
                    
                    <div id="passwordStrength" class="text-white text-sm hidden">
                        Password Strength: <span id="strengthValue">0</span>
                    </div>
                    
                    <button type="submit" class="w-full bg-custom-orange text-custom-black p-2 rounded font-bold hover:bg-opacity-90">
                        Sign Up!
                    </button>
                    
                    <div class="text-center text-white">
                        <span>Or </span>
                        <button type="button" id="showLogin" class="text-custom-orange hover:underline">
                            Log in
                        </button>
                    </div>
                </form>

                <p class="text-white text-sm text-center mt-4">
                    By signing up, you agree to our
                    <span class="text-custom-orange hover:underline cursor-pointer">
                        Terms and Conditions
                    </span>.
                </p>
            </div>

            <!-- Login Popup -->
            <div id="loginPopup" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
                <div class="bg-custom-darkGray p-8 rounded-lg relative" style="width: 400px;">
                    <button id="closeLogin" class="absolute right-4 top-4 text-white">
                        <i data-feather="x"></i>
                    </button>

                    <div class="text-4xl font-bold mb-4 text-center">
                        <span class="text-white">Forum</span>
                        <span class="bg-custom-orange text-custom-black px-2 py-1 rounded">GW</span>
                    </div>

                    <h2 class="text-3xl font-bold text-white mb-2 text-center">Member Sign in</h2>
                    <p class="text-center text-white mb-6">Access your ForumGW account</p>

                    <div id="loginError" class="hidden bg-red-600 text-white p-2 rounded mb-4"></div>

                    <form action="login.php" method="POST" class="space-y-4">
                        <div>
                            <input type="email" name="email" placeholder="Email" 
                                   class="w-full p-2 rounded bg-custom-darkGray text-white border border-gray-700" required>
                        </div>
                        <div class="relative">
                            <input type="password" name="password" placeholder="Password" 
                                   class="w-full p-2 rounded bg-custom-darkGray text-white border border-gray-700" required>
                            <button type="button" class="togglePassword absolute right-2 top-2 text-white">
                                <i data-feather="eye"></i>
                            </button>
                        </div>

                        <button type="submit" class="w-full bg-custom-orange text-custom-black p-2 rounded font-bold hover:bg-opacity-90">
                            Sign in
                        </button>
                    </form>

                    <div class="text-center text-white mt-4">
                        Don't have an account yet?
                        <button id="showSignup" class="text-custom-orange hover:underline">
                            Sign Up
                        </button>
                        here.
                    </div>

                    <div class="flex justify-center items-center mt-4">
                        <a href="#" class="text-custom-orange hover:underline">
                            Forgot Password?
                        </a>
                        <div class="mx-2 h-4 w-px bg-gray-400"></div>
                        <a href="#" class="text-custom-orange hover:underline">
                            Resend confirmation email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Feather icons
        feather.replace();

        // DOM Elements
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const passwordStrength = document.querySelector('#passwordStrength');
        const strengthValue = document.querySelector('#strengthValue');
        const loginPopup = document.querySelector('#loginPopup');
        const showLogin = document.querySelector('#showLogin');
        const showSignup = document.querySelector('#showSignup');
        const closeLogin = document.querySelector('#closeLogin');
        const signupForm = document.querySelector('#signupForm form');
        const loginForm = document.querySelector('#loginPopup form');
        const errorMessage = document.querySelector('#errorMessage');
        const loginError = document.querySelector('#loginError');

        // Toggle password visibility
        document.querySelectorAll('.togglePassword, #togglePassword').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                const icon = this.querySelector('i');
                icon.setAttribute('data-feather', type === 'password' ? 'eye' : 'eye-off');
                feather.replace();
            });
        });

        // Password strength indicator
        password.addEventListener('input', function() {
            if (this.value.length > 0) {
                passwordStrength.classList.remove('hidden');
                strengthValue.textContent = this.value.length;
            } else {
                passwordStrength.classList.add('hidden');
            }
        });

        // Show/Hide Login Popup
        showLogin.addEventListener('click', () => {
            loginPopup.classList.remove('hidden');
            loginPopup.classList.add('flex');
        });

        closeLogin.addEventListener('click', () => {
            loginPopup.classList.add('hidden');
            loginPopup.classList.remove('flex');
        });

        showSignup.addEventListener('click', () => {
            loginPopup.classList.add('hidden');
            loginPopup.classList.remove('flex');
        });

        // Form submissions
        signupForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            try {
                const formData = new FormData(this);
                const response = await fetch('register.php', {
                    method: 'POST',
                    body: formData
                });
                
                // Kiểm tra nếu response là redirect (thành công)
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
        
                // Nếu response là JSON (có lỗi)
                if (response.headers.get('content-type')?.includes('application/json')) {
                    const data = await response.json();
                    errorMessage.textContent = data.error || 'Registration failed';
                    errorMessage.classList.remove('hidden');
                } else {
                    const text = await response.text();
                    // Nếu response chứa HTML form với error message
                    if (text.includes('alert-danger')) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = text;
                        const error = tempDiv.querySelector('.alert-danger')?.textContent;
                        errorMessage.textContent = error || 'Registration failed';
                        errorMessage.classList.remove('hidden');
                    } else if (text.includes('dashboard')) {
                        // Nếu response chứa trang dashboard
                        window.location.href = '/dashboard';
                    }
                }
            } catch (err) {
                console.error(err);
                errorMessage.textContent = 'An error occurred during registration';
                errorMessage.classList.remove('hidden');
            }
        });
        
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            try {
                const formData = new FormData(this);
                const response = await fetch('login.php', {
                    method: 'POST',
                    body: formData
                });
        
                // Kiểm tra nếu response là redirect (thành công)
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
        
                // Nếu response là JSON (có lỗi)
                if (response.headers.get('content-type')?.includes('application/json')) {
                    const data = await response.json();
                    loginError.textContent = data.error || 'Login failed';
                    loginError.classList.remove('hidden');
                } else {
                    const text = await response.text();
                    // Nếu response chứa HTML form với error message
                    if (text.includes('alert-danger')) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = text;
                        const error = tempDiv.querySelector('.alert-danger')?.textContent;
                        loginError.textContent = error || 'Login failed';
                        loginError.classList.remove('hidden');
                    } else if (text.includes('dashboard')) {
                        // Nếu response chứa trang dashboard
                        window.location.href = '/dashboard';
                    }
                }
            } catch (err) {
                console.error(err);
                loginError.textContent = 'An error occurred during login';
                loginError.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>