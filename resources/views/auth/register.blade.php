<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun - MahabBa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .font-serif-elegant { font-family: 'Lora', serif; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.6s ease-out forwards; }
        .animate-fade-in-delay { animation: fadeInUp 0.6s ease-out 0.15s forwards; opacity: 0; }
        .animate-fade-in-delay-2 { animation: fadeInUp 0.6s ease-out 0.3s forwards; opacity: 0; }

        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(30, 58, 43, 0.12);
        }

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
    </style>
</head>
<body class="bg-white min-h-screen">

    <div class="min-h-screen w-full flex flex-col md:flex-row">

        <!-- Left Panel: Branding -->
        <div class="hidden md:flex md:w-2/5 bg-gradient-to-br from-[#93C5C6] via-[#A6B3D8] to-[#9FB7E3] items-center justify-center p-8 md:rounded-r-[50px] shadow-2xl relative overflow-hidden">
            <div class="absolute top-[-20%] left-[-20%] w-[500px] h-[500px] bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[300px] h-[300px] bg-white opacity-5 rounded-full blur-2xl"></div>
            <div class="text-center relative z-10">
                <h1 class="text-white text-5xl lg:text-7xl font-serif-elegant mb-3">MahabBa</h1>
                <p class="text-white/70 text-sm tracking-widest uppercase">Mulai Perjalanan Spiritualmu</p>
            </div>
        </div>

        <!-- Right Panel: Registration Form -->
        <div class="w-full md:w-3/5 flex flex-col justify-center items-center px-6 py-10 md:p-10 bg-white">

            <div class="w-full max-w-sm flex flex-col items-center">

                <!-- Mobile-only brand -->
                <div class="md:hidden mb-6 animate-fade-in">
                    <h2 class="text-3xl font-serif-elegant text-[#1E3A2B] text-center">MahabBa</h2>
                    <p class="text-gray-400 text-xs text-center tracking-widest uppercase mt-1">Daily Routine Muslim</p>
                </div>

                <!-- Desktop brand -->
                <h2 class="hidden md:block text-2xl font-serif-elegant text-[#1E3A2B] mb-1 animate-fade-in">MahabBa</h2>

                <h3 class="text-2xl md:text-3xl font-bold text-[#1E3A2B] mb-6 animate-fade-in">Daftar Akun</h3>

                <form id="registerForm" method="POST" class="w-full space-y-3.5 animate-fade-in-delay">
                    @csrf

                    <div>
                        <input type="text" id="name" name="name"
                               class="input-glow w-full px-5 py-3 rounded-2xl bg-[#F7F8FA] border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A2B]/20 focus:border-[#1E3A2B]/40 transition text-sm"
                               placeholder="Nama Lengkap" required autocomplete="name">
                    </div>

                    <div>
                        <input type="email" id="email" name="email"
                               class="input-glow w-full px-5 py-3 rounded-2xl bg-[#F7F8FA] border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A2B]/20 focus:border-[#1E3A2B]/40 transition text-sm"
                               placeholder="Alamat Email" required autocomplete="email">
                    </div>

                    <div>
                        <input type="password" id="password" name="password"
                               class="input-glow w-full px-5 py-3 rounded-2xl bg-[#F7F8FA] border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A2B]/20 focus:border-[#1E3A2B]/40 transition text-sm"
                               placeholder="Password (min. 8 karakter)" required autocomplete="new-password">
                    </div>

                    <p id="errorMessage" class="text-red-500 text-xs text-center hidden font-medium py-1"></p>

                    <button type="submit" id="submitBtn" class="btn-lift w-full py-3 rounded-2xl bg-[#1E3A2B] text-white font-bold text-sm hover:bg-[#162a1f] transition duration-300 shadow-lg shadow-[#1E3A2B]/15">
                        Buat Akun
                    </button>
                </form>

                <div class="mt-5 text-center animate-fade-in-delay-2">
                    <p class="text-gray-500 text-sm">Sudah punya akun? <a href="{{ route('login') }}" class="text-[#1E3A2B] font-bold hover:underline">Masuk di sini</a></p>
                </div>

                <p class="text-[10px] text-gray-400 text-center mt-6 leading-relaxed max-w-[260px] animate-fade-in-delay-2">
                    Dengan mendaftar, Anda menyetujui MahabBa<br>
                    <a href="#" class="font-bold text-gray-500">Syarat Layanan</a> dan <a href="#" class="font-bold text-gray-500">Kebijakan Privasi</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        const registerForm = document.getElementById('registerForm');
        const submitBtn = document.getElementById('submitBtn');
        const errorMsg = document.getElementById('errorMessage');
        const originalBtnText = submitBtn.innerText;

        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Loading state
            submitBtn.innerText = 'Mendaftarkan...';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
            errorMsg.classList.add('hidden');

            try {
                const response = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ name, email, password })
                });

                const result = await response.json();

                if (response.ok) {
                    window.location.href = '/dashboard';
                } else {
                    let message = result.message;
                    if (result.errors) {
                        message = Object.values(result.errors).flat().join(' ');
                    }
                    throw new Error(message || 'Registrasi gagal.');
                }
            } catch (error) {
                console.error('Registration Error:', error);
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
