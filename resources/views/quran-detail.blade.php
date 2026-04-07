<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baca Al Qur'an - MahabBa</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f3f4f6; display: flex; height: 100vh; overflow: hidden; }

        /* Arabic Font */
        .font-arabic { font-family: 'Amiri', serif; line-height: 2.5; }

        /* SIDEBAR & LAYOUT (Copied) */
        .sidebar { width: 250px; background: #dce6e9; padding: 30px 20px; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.3s ease; z-index: 100; }
        .logo h2 { color: #2d3436; font-size: 20px; margin-bottom: 5px; font-weight: 700; }
        .logo-sub { font-family: 'Playfair Display', serif; color: #d4a373; font-size: 16px; margin-bottom: 35px; letter-spacing: 2px; }
        .menu a { display: flex; align-items: center; text-decoration: none; color: #636e72; padding: 12px 15px; margin-bottom: 10px; border-radius: 10px; transition: 0.3s; font-size: 14px; }
        .menu a.active, .menu a:hover { background: #b2bec3; color: white; }
        .menu a i { margin-right: 15px; width: 20px; text-align: center; }
        .logout-btn button { background: none; border: none; cursor: pointer; display: flex; align-items: center; color: #ff7675; font-weight: 600; font-size: 14px; }
        
        .main-content { flex: 1; padding: 30px 40px; overflow-y: auto; background-image: url('https://www.transparenttextures.com/patterns/cubes.png'); position: relative; }
        
        /* Header Pill Style */
        .header {
            background: #4A4A4A;
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .sidebar { position: fixed; left: -260px; height: 100%; box-shadow: 5px 0 15px rgba(0,0,0,0.1); }
            .sidebar.active { left: 0; }
            .header { flex-direction: column; text-align: center; gap: 15px; }
            .main-content { padding: 80px 20px 20px; }
            .hamburger-btn { display: block; }
        }
        .hamburger-btn { display: none; position: absolute; top: 20px; left: 20px; z-index: 102; background: white; padding: 10px; border-radius: 8px; border: none; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .sidebar-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 99; display: none; }
        .sidebar-overlay.active { display: block; }
    </style>
</head>
<body x-data="quranDetail({{ $nomor }})">

    <div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <button class="hamburger-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars fa-lg" style="color: #2d3436;"></i>
    </button>

    <div class="sidebar" id="sidebar">
        <div>
            <div class="logo">
                <h2>Daily Routine<br>Muslim</h2>
                <div class="logo-sub">MahabBa</div>
            </div>
            <div class="menu">
                <a href="/dashboard"><i class="fas fa-home"></i> Dashboard</a>
                <a href="#"><i class="fas fa-chart-line"></i> Tracker</a>
                <a href="{{ route('quran.index') }}" class="active"><i class="fas fa-book-open"></i> Al Qur'an</a>
                <a href="#"><i class="fas fa-cog"></i> Settings</a>
                <a href="#"><i class="fas fa-user"></i> Profile</a>
            </div>
        </div>
        <div class="logout-btn menu">
            <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button></form>
        </div>
    </div>

    <div class="main-content" @scroll="checkScroll($el)">
        <!-- Header -->
        <div class="header">
            <div>
                <h1 class="text-xl font-bold tracking-wide" x-text="surah ? surah.namaLatin : 'Loading...'"></h1>
                <nav class="flex items-center text-xs text-gray-300 gap-2 font-medium mt-1">
                    <a href="/dashboard" class="hover:text-white transition-colors flex items-center gap-1">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('quran.index') }}" class="hover:text-white transition-colors">Al Qur'an</a>
                    <span class="text-gray-400">/</span>
                    <span class="text-[#d4a373] font-semibold" x-text="surah ? surah.namaLatin : 'Surah ' + nomor"></span>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <!-- Pin Button -->
                <button @click="togglePin()" title="Pin ke Akses Cepat" class="text-white/80 hover:text-yellow-400 transition" x-show="surah">
                    <i class="fas fa-thumbtack" :class="isPinned() ? 'text-yellow-400' : ''"></i>
                </button>

                <button @click="toggleAudio()" class="bg-[#d4a373] hover:bg-[#b08558] text-white w-10 h-10 rounded-full flex items-center justify-center transition shadow-lg">
                    <i :class="isPlaying ? 'fas fa-pause' : 'fas fa-play'"></i>
                </button>
                <div class="text-[#d4a373] font-serif italic text-xl font-bold">MahabBa</div>
            </div>
        </div>

        <!-- Toast Notification -->
    <div x-show="notification.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed top-24 right-4 z-[110] bg-white border-l-4 rounded shadow-lg p-4 max-w-sm flex items-start gap-3"
         :class="notification.type === 'success' ? 'border-[#3c9ea8]' : 'border-red-500'"
         style="display: none;">
        <div :class="notification.type === 'success' ? 'text-[#3c9ea8]' : 'text-red-500'">
            <i class="fas" :class="notification.type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'"></i>
        </div>
        <div>
            <h4 class="font-bold text-sm text-gray-800" x-text="notification.type === 'success' ? 'Berhasil' : 'Gagal'"></h4>
            <p class="text-xs text-gray-600 mt-1" x-text="notification.message"></p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-[30px] p-6 md:p-10 shadow-sm min-h-[500px]">
            
            <template x-if="loading">
                <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                    <i class="fas fa-circle-notch fa-spin text-3xl mb-3"></i>
                    <p>Memuat Ayat...</p>
                </div>
            </template>

            <div x-show="!loading && surah" class="space-y-12">
                
                <!-- Bismillah (Centered) if not At-Taubah -->
                <template x-if="surah && surah.nomor != 9">
                    <div class="text-center font-arabic text-3xl md:text-4xl text-[#1D3557] mb-12 mt-4">
                        بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيم
                    </div>
                </template>

                <!-- Ayat List -->
                <template x-for="(ayat, index) in surah.ayat" :key="ayat.nomorAyat">
                    <div :id="'ayat-' + index" 
                         class="border-b border-gray-100 pb-8 pt-8 last:border-0 relative group transition-colors duration-500 rounded-xl px-4 flex gap-4 md:gap-8 items-start"
                         :class="{ 'bg-[#fff8e1] scale-[1.01] shadow-sm border-transparent': activeAyatIndex === index }">
                        
                        <!-- Left Column: Actions & Number -->
                        <div class="flex flex-col gap-3 min-w-[32px] pt-1">
                             <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs text-gray-500 font-bold" x-text="ayat.nomorAyat"></div>
                             
                             <div class="opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col gap-2 items-center" :class="{ 'opacity-100': activeAyatIndex === index }">
                                 <button @click="playAyat(index)" class="w-8 h-8 rounded-full bg-[#3c9ea8] text-white flex items-center justify-center text-xs hover:bg-[#2d858e] transition shadow-md">
                                    <i :class="activeAyatIndex === index && isPlaying ? 'fas fa-pause' : 'fas fa-play'"></i>
                                 </button>
                                 
                                 <button @click="saveLastRead(ayat)" title="Tandai Terakhir Baca" class="w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center text-xs hover:text-[#d4a373] hover:border-[#d4a373] transition shadow-sm">
                                    <i class="fas fa-bookmark"></i>
                                 </button>
                             </div>
                        </div>

                        <!-- Right Column: Content -->
                        <div class="flex-1 min-w-0">
                            <!-- Arabic -->
                            <div class="text-right font-arabic text-3xl md:text-4xl text-[#2d3436] leading-[2.5] mb-6">
                                <span x-text="ayat.teksArab"></span>
                                <span class="font-bold text-[#d4a373] text-2xl mx-1" x-text="'۝' + toArabicNumber(ayat.nomorAyat)"></span>
                            </div>

                            <!-- Latin & Translation -->
                            <div class="space-y-2">
                                <p class="text-[#d4a373] font-medium text-sm md:text-base italic">
                                    <span x-text="ayat.teksLatin"></span>
                                    <span class="text-xs font-bold text-gray-400 ms-1" x-text="'(' + ayat.nomorAyat + ')'"></span>
                                </p>
                                <p class="text-gray-600 text-sm md:text-base">
                                    <span x-text="ayat.teksIndonesia"></span>
                                    <span class="text-xs font-bold text-gray-400 ms-1" x-text="'(' + ayat.nomorAyat + ')'"></span>
                                </p>
                            </div>
                        </div>

                    </div>
                </template>

            </div>

        </div>
    </div>

    </div>

    <!-- Back to Top Button -->
    <button x-show="showScrollTop" 
            @click="scrollToTop()"
            class="fixed bottom-24 right-8 bg-[#d4a373] text-white w-12 h-12 rounded-full shadow-lg flex items-center justify-center hover:bg-[#b08558] hover:-translate-y-1 transition z-40"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-10"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-10"
            style="display: none;">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Floating Audio Bar -->
    <div x-show="isPlaying" class="fixed bottom-0 left-0 w-full bg-[#1D3557] text-white p-4 z-50 flex items-center justify-between shadow-[0_-5px_15px_rgba(0,0,0,0.1)]" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full">
        
        <div class="flex items-center gap-4">
            <button @click="stopAudio()" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
            <div class="hidden md:block">
                <h4 class="font-bold text-sm" x-text="surah ? surah.namaLatin : ''"></h4>
                <p class="text-xs text-blue-200">Mishary Rashid Al-Afasy</p>
            </div>
        </div>
        
        <!-- Progress Bar Placeholder -->
        <div class="flex-1 mx-4 md:mx-10 bg-white/10 h-1 rounded-full overflow-hidden">
            <div class="bg-[#d4a373] h-full w-0" id="audioProgress"></div>
        </div>

        <div class="flex items-center gap-4">
           <button @click="toggleAudio()" class="w-10 h-10 bg-white text-[#1D3557] rounded-full flex items-center justify-center shadow-lg hover:scale-105 transition">
                <i :class="isPlaying ? 'fas fa-pause' : 'fas fa-play'"></i>
            </button>
        </div>
    </div>

    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('active'); document.getElementById('sidebarOverlay').classList.toggle('active'); }

        function quranDetail(nomor) {
            return {
                nomor: nomor,
                loading: true,
                surah: null,
                audio: new Audio(),
                isPlaying: false,
                activeAyatIndex: -1, // -1 means no ayat active
                showScrollTop: false,

                checkScroll(el) {
                    this.showScrollTop = el.scrollTop > 300;
                },

                scrollToTop() {
                    document.querySelector('.main-content').scrollTo({ top: 0, behavior: 'smooth' });
                },

                toArabicNumber(n) {
                    return n.toString().replace(/\d/g, d => '٠١٢٣٤٥٦٧٨٩'[d]);
                },

                async init() {
                    await this.fetchDetail();
                    
                    // Audio Listeners
                    this.audio.addEventListener('ended', () => {
                        this.playNextAyat();
                    });

                    // Check URL hash for scrollTo
                    if (window.location.hash) {
                        const ayatId = window.location.hash.substring(1); // remove #
                        // Allow DOM to render
                        setTimeout(() => {
                            const element = document.getElementById(ayatId);
                            if (element) {
                                element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                
                                // Optional: Highlight briefly
                                const index = parseInt(ayatId.replace('ayat-', ''));
                                if (!isNaN(index)) {
                                    this.activeAyatIndex = index;
                                    // Remove highlight after 2s if needed, or keep it as "selected"
                                     setTimeout(() => { this.activeAyatIndex = -1; }, 2000);
                                }
                            }
                        }, 500);
                    }
                    this.audio.addEventListener('timeupdate', () => {
                         // Optional: Per-verse progress if needed
                    });
                },

                async fetchDetail() {
                    try {
                        const response = await fetch(`https://equran.id/api/v2/surat/${this.nomor}`);
                        const data = await response.json();
                        this.surah = data.data; 
                        this.loading = false;
                    } catch (e) {
                        console.error("Error fetching detail:", e);
                        this.loading = false;
                    }
                },

                toggleAudio() {
                    if (this.isPlaying) {
                        this.stopAudio();
                    } else {
                        this.playAyat(0); // Start from beginning
                    }
                },

                playAyat(index) {
                    if (!this.surah || !this.surah.ayat[index]) {
                        this.stopAudio();
                        return;
                    }

                    this.activeAyatIndex = index;
                    this.isPlaying = true;

                    // Get Audio URL (Mishary - 05)
                    const audioUrl = this.surah.ayat[index].audio['05'];
                    this.audio.src = audioUrl;
                    this.audio.play();

                    // Auto Scroll to Verse
                    setTimeout(() => {
                        const el = document.getElementById(`ayat-${index}`);
                        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 100);
                },

                playNextAyat() {
                    const nextIndex = this.activeAyatIndex + 1;
                    if (nextIndex < this.surah.ayat.length) {
                        this.playAyat(nextIndex);
                    } else {
                        this.stopAudio();
                    }
                },

                stopAudio() {
                    this.audio.pause();
                    this.audio.currentTime = 0;
                    this.isPlaying = false;
                    this.activeAyatIndex = -1;
                },

                saveLastRead(ayat) {
                    const lastReadData = {
                        surahNomor: this.surah.nomor,
                        surahNama: this.surah.namaLatin,
                        ayatNomor: ayat.nomorAyat,
                        timestamp: new Date().toISOString()
                    };
                    localStorage.setItem('lastRead', JSON.stringify(lastReadData));
                    this.showNotification('Ayat ditandai sebagai terakhir dibaca!', 'success');
                },

                togglePin() {
                    let quickAccess = JSON.parse(localStorage.getItem('quickAccess') || '[]');
                    const existsIndex = quickAccess.findIndex(s => s.number === this.surah.nomor);
                    
                    if (existsIndex >= 0) {
                        quickAccess.splice(existsIndex, 1);
                        this.showNotification('Surah dihapus dari Akses Cepat', 'success');
                    } else {
                        if (quickAccess.length >= 6) {
                            this.showNotification('Akses Cepat penuh. Hapus yang lain dulu.', 'error');
                            return;
                        }
                        quickAccess.push({ name: this.surah.namaLatin, number: this.surah.nomor });
                        this.showNotification('Surah ditambahkan ke Akses Cepat', 'success');
                    }
                    
                    localStorage.setItem('quickAccess', JSON.stringify(quickAccess));
                },

                isPinned() {
                    if (!this.surah) return false;
                    const quickAccess = JSON.parse(localStorage.getItem('quickAccess') || '[]');
                    return quickAccess.some(s => s.number === this.surah.nomor);
                },

                // Notification State
                notification: {
                    show: false,
                    message: '',
                    type: 'success'
                },

                showNotification(message, type = 'success') {
                    this.notification.message = message;
                    this.notification.type = type;
                    this.notification.show = true;
                    setTimeout(() => {
                        this.notification.show = false;
                    }, 3000);
                }
            }
        }
    </script>
</body>
</html>
