<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - MahabBa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .font-serif-elegant { font-family: 'Lora', serif; }

        /* Smooth entrance animation */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.6s ease-out forwards; }
        .animate-fade-in-delay { animation: fadeInUp 0.6s ease-out 0.15s forwards; opacity: 0; }
        .animate-fade-in-delay-2 { animation: fadeInUp 0.6s ease-out 0.3s forwards; opacity: 0; }

        /* Input focus glow */
        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(30, 58, 43, 0.12);
        }

        /* Button hover lift */
        .btn-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-lift:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 58, 43, 0.25);
        }
        .btn-lift:active:not(:disabled) {
            transform: translateY(0);
        }

        /* Google button hover */
        .google-btn {
            transition: all 0.3s ease;
        }
        .google-btn:hover {
            background: #f8f9fa;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transform: translateY(-1px);
        }

        /* Divider line */
        .divider-line {
            display: flex; align-items: center; gap: 12px;
            color: #9ca3af; font-size: 13px; font-weight: 500;
        }
        .divider-line::before, .divider-line::after {
            content: ''; flex: 1; height: 1px; background: #e5e7eb;
        }
    </style>
</head>
<body class="bg-white min-h-screen">

    <div class="min-h-screen w-full flex flex-col md:flex-row">

        <!-- Left Panel: Branding (hidden on mobile) -->
        <div class="hidden md:flex md:w-2/5 bg-gradient-to-br from-[#9FB7E3] via-[#A6B3D8] to-[#93C5C6] items-center justify-center p-8 md:rounded-r-[50px] shadow-2xl relative overflow-hidden">
            <div class="absolute top-[-20%] left-[-20%] w-[500px] h-[500px] bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[300px] h-[300px] bg-white opacity-5 rounded-full blur-2xl"></div>
            <div class="text-center relative z-10">
                <h1 class="text-white text-5xl lg:text-7xl font-serif-elegant mb-3">MahabBa</h1>
                <p class="text-white/70 text-sm tracking-widest uppercase">Daily Routine Muslim</p>
            </div>
        </div>

        <!-- Right Panel: Login Form -->
        <div class="w-full md:w-3/5 flex flex-col justify-center items-center px-6 py-10 md:p-10 bg-white">

            <div class="w-full max-w-sm flex flex-col items-center">

                <!-- Mobile-only brand -->
                <div class="md:hidden mb-6 animate-fade-in">
                    <h2 class="text-3xl font-serif-elegant text-[#1E3A2B] text-center">MahabBa</h2>
                    <p class="text-gray-400 text-xs text-center tracking-widest uppercase mt-1">Daily Routine Muslim</p>
                </div>

                <!-- Desktop brand -->
                <h2 class="hidden md:block text-2xl font-serif-elegant text-[#1E3A2B] mb-1 animate-fade-in">MahabBa</h2>

                <h3 class="text-2xl md:text-3xl font-bold text-[#1E3A2B] mb-6 animate-fade-in">Masuk</h3>

                <!-- Google Login First (Primary CTA) -->
                <div class="w-full mb-5 animate-fade-in-delay">
                    <a href="{{ route('google.login') }}" class="google-btn w-full flex items-center justify-center gap-3 py-3 px-5 rounded-2xl border border-gray-200 bg-white text-gray-700 font-semibold text-sm">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.84z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Masuk dengan Google
                    </a>
                </div>

                <!-- Divider -->
                <div class="divider-line w-full mb-5 animate-fade-in-delay">atau masuk dengan email</div>

                <!-- Email/Password Form -->
                <form id="loginForm" method="POST" class="w-full space-y-3.5 animate-fade-in-delay-2">
                    @csrf

                    <div>
                        <input type="email" id="email" name="email"
                               class="input-glow w-full px-5 py-3 rounded-2xl bg-[#F7F8FA] border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A2B]/20 focus:border-[#1E3A2B]/40 transition text-sm"
                               placeholder="Alamat email" required autocomplete="email">
                    </div>

                    <div>
                        <input type="password" id="password" name="password"
                               class="input-glow w-full px-5 py-3 rounded-2xl bg-[#F7F8FA] border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A2B]/20 focus:border-[#1E3A2B]/40 transition text-sm"
                               placeholder="Password" required autocomplete="current-password">
                    </div>

                    <p id="errorMessage" class="text-red-500 text-xs text-center hidden font-medium py-1"></p>

                    <button type="submit" id="submitBtn" class="btn-lift w-full py-3 rounded-2xl bg-[#1E3A2B] text-white font-bold text-sm hover:bg-[#162a1f] transition duration-300 shadow-lg shadow-[#1E3A2B]/15">
                        Masuk
                    </button>
                </form>

                <div class="mt-5 text-center">
                    <p class="text-gray-500 text-sm">Belum punya akun? <a href="{{ route('register') }}" class="text-[#1E3A2B] font-bold hover:underline">Daftar</a></p>
                </div>

                <p class="text-[10px] text-gray-400 text-center mt-6 leading-relaxed max-w-[260px]">
                    Dengan masuk, Anda menyetujui MahabBa<br>
                    <a href="#" class="font-bold text-gray-500">Syarat Layanan</a> dan <a href="#" class="font-bold text-gray-500">Kebijakan Privasi</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const errorMsg = document.getElementById('errorMessage');
        const originalBtnText = submitBtn.innerText;

        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Loading state
            submitBtn.innerText = 'Memproses...';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
            errorMsg.classList.add('hidden');

            try {
                // POST to web route (creates session + token)
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ email, password })
                });

                const result = await response.json();

                if (response.ok) {
                    // Save token for API calls
                    localStorage.setItem('auth_token', result.access_token);
                    if (result.user) {
                        localStorage.setItem('user_data', JSON.stringify(result.user));
                    }
                    // Redirect to dashboard (session is now active)
                    window.location.href = '/dashboard';
                } else {
                    throw new Error(result.message || 'Login gagal, periksa email & password Anda.');
                }
            } catch (error) {
                console.error('Login Error:', error);
                errorMsg.textContent = error.message;
                errorMsg.classList.remove('hidden');

                submitBtn.innerText = originalBtnText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
            }
        });
    </script>
</body>
</html>