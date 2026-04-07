<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MahabBa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .font-serif-elegant { font-family: 'Lora', serif; }
    </style>
</head>
<body class="bg-white">

    <div class="h-screen w-full flex flex-col md:flex-row overflow-hidden">

        <div class="hidden md:flex md:w-2/5 bg-gradient-to-br from-[#9FB7E3] via-[#A6B3D8] to-[#93C5C6] items-center justify-center p-8 md:rounded-r-[50px] shadow-2xl relative overflow-hidden">
            <div class="absolute top-[-20%] left-[-20%] w-[500px] h-[500px] bg-white opacity-10 rounded-full blur-3xl"></div>
            
            <h1 class="text-white text-5xl lg:text-7xl font-serif-elegant relative z-10">MahabBa</h1>
        </div>

        <div class="w-full md:w-3/5 flex flex-col justify-center items-center p-6 md:p-10 bg-white">
            
            <div class="w-full max-w-sm flex flex-col items-center">
                <h2 class="text-2xl font-serif-elegant text-[#1E3A2B] mb-2">MahabBa</h2>

                <h3 class="text-3xl font-bold text-[#1E3A2B] mb-4">Login</h3>

                <form id="loginForm" class="w-full space-y-4">
                    
                    <div>
                        <input type="email" id="email" 
                               class="w-full px-5 py-3 rounded-full bg-[#F5F5F5] border border-[#E0E0E0] text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A2B] focus:border-transparent transition" 
                               placeholder="email address" required>
                    </div>

                    <div>
                        <input type="password" id="password" 
                               class="w-full px-5 py-3 rounded-full bg-[#F5F5F5] border border-[#E0E0E0] text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1E3A2B] focus:border-transparent transition" 
                               placeholder="password" required>
                    </div>

                    <p id="errorMessage" class="text-red-500 text-sm text-center hidden"></p>

                    <button type="submit" id="submitBtn" class="w-full py-3 rounded-full bg-[#8E8E8E] text-white font-bold text-lg hover:bg-[#757575] transition duration-300 shadow-sm">
                        Login
                    </button>
                </form>
                
                <div class="mt-2 text-center">
                    <p class="text-gray-600 text-sm">Don't have an account? <a href="{{ route('register') }}" class="text-[#1E3A2B] font-bold hover:underline">Sign Up</a></p>
                </div>

                <div class="mt-4 flex flex-col items-center w-full">
                    <button type="button" id="location-btn" onclick="mintaIzinLokasi()" class="transition-all duration-500 hover:scale-[1.02]"
                        style="width: 100%; margin-bottom: 15px; background: #f0f2f5; border: 1px solid #dce6e9; 
                               padding: 10px; border-radius: 10px; cursor: pointer; color: #636e72; 
                               font-weight: 600; font-size: 13px;">
                        <i class="fas fa-map-marker-alt" style="color: #ff4757; margin-right: 8px;"></i> 
                        Aktifkan Jadwal Sholat Akurat
                    </button>

                    <p class="text-[#1E3A2B] mb-4 text-sm">or login with</p>
                        <div class="flex justify-center gap-4">
                            <!-- Google Icon -->
                            <a href="{{ route('google.login') }}" class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition bg-white">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.84z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                            </a>
                        
                        <!-- Other Icon (Placeholder) -->
                        <div class="w-12 h-12 rounded-full bg-[#8E8E8E]"></div>
                    </div>
                </div>

                <p class="text-xs text-gray-500 text-center mt-4 leading-relaxed">
                    by logging in you agree to MahabBa<br>
                    <a href="#" class="font-bold text-[#1E3A2B]">Term of Services</a> and <a href="#" class="font-bold text-[#1E3A2B]">Privacy Policy</a>
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

            // 1. Ubah tampilan tombol jadi loading
            submitBtn.innerText = 'Processing...';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
            errorMsg.classList.add('hidden');

            try {
                // 2. Tembak API Login
                // Pastikan URL ini benar (http, bukan https jika di localhost)
                
                    const response = await fetch('/api/login', {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const result = await response.json();

                if (response.ok) {
                    // 3. SUKSES: Simpan Token
                    localStorage.setItem('auth_token', result.access_token);
                    
                    // Optional: Simpan data user juga jika dikirim backend
                    if(result.user) {
                        localStorage.setItem('user_data', JSON.stringify(result.user));
                    }
                    
                    // Redirect ke Dashboard
                    window.location.href = '/dashboard'; 

                } else {
                    // 4. GAGAL: Tampilkan error dari backend
                    throw new Error(result.message || 'Login failed, please check your credentials.');
                }
            } catch (error) {
                console.error('Login Error:', error);
                errorMsg.textContent = error.message;
                errorMsg.classList.remove('hidden');
                
                // Kembalikan tombol seperti semula
                submitBtn.innerText = originalBtnText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
            }
        });
    </script>
    <script>
        function mintaIzinLokasi() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        // Simpan status izin ke LocalStorage
                        localStorage.setItem('location_permission', 'granted');

                        const btn = document.getElementById('location-btn');
                        btn.innerHTML = '<i class="fas fa-check-circle"></i> Lokasi Aktif';
                        btn.style.backgroundColor = '#2ecc71';
                        btn.style.color = 'white';
                        btn.style.borderColor = '#27ae60';
                        btn.style.transform = 'scale(1.02)';
                    },
                    (error) => {
                        alert("Akses lokasi ditolak. Jadwal sholat mungkin tidak akurat.");
                    }
                );
            }
        }
    </script>
</body>
</html>