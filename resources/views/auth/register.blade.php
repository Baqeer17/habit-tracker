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
    </style>
</head>
<body class="bg-white">

    <div class="h-screen w-full flex flex-col md:flex-row overflow-hidden">

        <!-- Left Panel: Branding -->
        <div class="hidden md:flex md:w-2/5 bg-gradient-to-br from-[#93C5C6] via-[#A6B3D8] to-[#9FB7E3] items-center justify-center p-8 md:rounded-r-[50px] shadow-2xl relative overflow-hidden">
            <div class="absolute top-[-20%] left-[-20%] w-[500px] h-[500px] bg-white opacity-10 rounded-full blur-3xl"></div>
            
            <div class="text-center relative z-10">
                <h1 class="text-white text-5xl lg:text-7xl font-serif-elegant mb-4">MahabBa</h1>
                <p class="text-white/80 font-medium tracking-widest uppercase text-xs">Mulai Perjalanan Spiritualmu</p>
            </div>
        </div>

        <!-- Right Panel: Registration Form -->
        <div class="w-full md:w-3/5 flex flex-col justify-center items-center p-6 md:p-10 bg-white">
            
            <div class="w-full max-w-sm flex flex-col items-center">
                <h2 class="text-2xl font-serif-elegant text-[#1E3A2B] mb-2">MahabBa</h2>
                <h3 class="text-3xl font-bold text-[#1E3A2B] mb-8">Daftar Akun</h3>

                <form id="registerForm" method="POST" class="w-full space-y-4">
                    
                    <div>
                        <input type="text" id="name" 
                               class="w-full px-5 py-3 rounded-full bg-[#F5F5F5] border border-[#E0E0E0] text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A2B] focus:border-transparent transition" 
                               placeholder="Nama Lengkap" required>
                    </div>

                    <div>
                        <input type="email" id="email" 
                               class="w-full px-5 py-3 rounded-full bg-[#F5F5F5] border border-[#E0E0E0] text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A2B] focus:border-transparent transition" 
                               placeholder="Alamat Email" required>
                    </div>

                    <div>
                        <input type="password" id="password" 
                               class="w-full px-5 py-3 rounded-full bg-[#F5F5F5] border border-[#E0E0E0] text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A2B] focus:border-transparent transition" 
                               placeholder="Password (min. 8 karakter)" required>
                    </div>

                    <p id="errorMessage" class="text-red-500 text-xs text-center hidden font-bold"></p>

                    <button type="submit" id="submitBtn" class="w-full py-3 rounded-full bg-[#1E3A2B] text-white font-bold text-lg hover:bg-[#162a1f] transition duration-300 shadow-lg shadow-green-900/10">
                        Buat Akun
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-gray-600 text-sm">Sudah punya akun? <a href="{{ route('login') }}" class="text-[#1E3A2B] font-bold hover:underline">Masuk di sini</a></p>
                </div>

                <p class="text-[10px] text-gray-400 text-center mt-8 leading-relaxed max-w-[250px]">
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

            // 1. Loading State
            submitBtn.innerText = 'Mendaftarkan...';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
            errorMsg.classList.add('hidden');

            try {
                // 2. Kirim Data ke Web Route (POST /register)
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
                    // 3. SUKSES: Redirect ke Dashboard
                    window.location.href = '/dashboard'; 
                } else {
                    // Gagal: Tampilkan detail error
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
                
                // Kembalikan tombol
                submitBtn.innerText = originalBtnText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
            }
        });
    </script>
</body>
</html>
