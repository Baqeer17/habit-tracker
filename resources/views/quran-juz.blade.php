<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juz {{ $nomor }} - MahabBa</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f3f4f6; display: flex; height: 100vh; overflow: hidden; }

        /* Arabic Font */
        .font-arabic { font-family: 'Amiri', serif; line-height: 2.5; }

        /* SIDEBAR & LAYOUT */
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

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #dce6e9; border-radius: 100px; }
        ::-webkit-scrollbar-thumb:hover { background: #b0bec5; }
    </style>
</head>
<body x-data="quranJuz({{ $nomor }})">

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

    <div class="main-content" id="mainContent" @scroll="checkScroll($el)">
        
        <!-- Header -->
        <div class="header" id="topHeader">
            <div>
                <h1 class="text-xl font-bold tracking-wide">Juz {{ $nomor }}</h1>
                 <nav class="flex items-center text-xs text-gray-300 gap-2 font-medium mt-1">
                    <a href="/dashboard" class="hover:text-white transition-colors flex items-center gap-1">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('quran.index') }}" class="hover:text-white transition-colors">Al Qur'an</a>
                    <span class="text-gray-400">/</span>
                    <span class="text-[#d4a373] font-semibold">Juz {{ $nomor }}</span>
                </nav>
            </div>
            
            <div class="flex items-center gap-4">
                 <button @click="toggleAudio()" class="bg-[#d4a373] hover:bg-[#b08558] text-white w-10 h-10 rounded-full flex items-center justify-center transition shadow-lg">
                    <i :class="isPlaying ? 'fas fa-pause' : 'fas fa-play'"></i>
                </button>
                <div class="text-[#d4a373] font-serif italic text-xl font-bold">MahabBa</div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-[30px] p-6 md:p-10 shadow-sm min-h-[500px]">
             
            <template x-if="loading">
                <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                    <i class="fas fa-circle-notch fa-spin text-3xl mb-3"></i>
                    <p>Memuat Juz {{ $nomor }}...</p>
                </div>
            </template>

            <div x-show="!loading">
                
                <template x-for="(group, index) in groupedAyahs" :key="index">
                    <div class="mb-12">
                        
                        <!-- Surah Header (Mirip quran-detail Start) -->
                        <div class="text-center mb-8 pt-8 border-t border-gray-100 first:border-0 first:pt-0">
                            <h2 class="font-arabic text-3xl md:text-4xl text-[#1D3557] mb-2" x-text="group.surah.name"></h2>
                            <p class="text-[#3c9ea8] font-bold tracking-widest uppercase text-sm" x-text="group.surah.englishName"></p>
                            <div class="inline-flex items-center gap-2 mt-2 px-3 py-1 bg-gray-50 rounded-full text-xs font-medium text-gray-400">
                                <span x-text="group.surah.revelationType"></span> &bull; <span x-text="group.surah.numberOfAyahs + ' Ayat'"></span>
                            </div>
                             <!-- Bismillah if not At-Taubah and start of surah -->
                             <template x-if="group.surah.number != 9 && group.ayahs[0].numberInSurah == 1">
                                <div class="text-center font-arabic text-3xl md:text-4xl text-[#1D3557] my-8">
                                    بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيم
                                </div>
                            </template>
                        </div>

                        <!-- Ayahs List (Mirip quran-detail) -->
                        <div class="space-y-0">
                            <template x-for="ayat in group.ayahs" :key="ayat.number">
                                <div :id="'ayat-' + ayat.numberInSurah" 
                                     class="border-b border-gray-100 pb-8 pt-8 last:border-0 relative group transition-colors duration-500 rounded-xl px-4 flex gap-4 md:gap-8 items-start"
                                     :class="{ 'bg-[#fff8e1] scale-[1.01] shadow-sm border-transparent': activeAudioId === ayat.audioId }">
                                    
                                    <!-- Left Column: Actions & Number -->
                                    <div class="flex flex-col gap-3 min-w-[32px] pt-1">
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs text-gray-500 font-bold" x-text="ayat.numberInSurah"></div>
                                        
                                        <div class="opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col gap-2 items-center" :class="{ 'opacity-100': activeAudioId === ayat.audioId }">
                                             <button @click="playAyat(ayat)" class="w-8 h-8 rounded-full bg-[#3c9ea8] text-white flex items-center justify-center text-xs hover:bg-[#2d858e] transition shadow-md">
                                                <i :class="activeAudioId === ayat.audioId && isPlaying ? 'fas fa-pause' : 'fas fa-play'"></i>
                                             </button>
                                        </div>
                                    </div>

                                    <!-- Right Column: Content -->
                                    <div class="flex-1">
                                        <div class="text-right font-arabic text-3xl md:text-4xl text-[#2d3436] leading-[2.6] mb-6" dir="rtl">
                                            <span x-text="ayat.text"></span>
                                        </div>
                                        <div class="text-gray-600 text-base md:text-lg leading-relaxed text-justify">
                                            <span x-text="ayat.translation"></span>
                                        </div>
                                    </div>

                                </div>
                            </template>
                        </div>

                    </div>
                </template>

            </div>
        </div>

        <!-- Back to Top Button -->
        <button x-show="showBackToTop" 
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

    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }

        function quranJuz(nomor) {
            return {
                nomor: nomor,
                loading: true,
                groupedAyahs: [],
                showBackToTop: false,
                
                // Audio State
                audio: new Audio(),
                isPlaying: false,
                activeAudioId: null, 
                playlist: [], 

                async init() {
                    await this.fetchJuzData();
                    
                    this.audio.addEventListener('ended', () => {
                        this.playNext();
                    });
                },

                async fetchJuzData() {
                    try {
                        const [resArabic, resIndo] = await Promise.all([
                            fetch(`http://api.alquran.cloud/v1/juz/${this.nomor}/quran-uthmani`),
                            fetch(`http://api.alquran.cloud/v1/juz/${this.nomor}/id.indonesian`)
                        ]);

                        const dataArabic = await resArabic.json();
                        const dataIndo = await resIndo.json();

                        if (dataArabic.status === 'OK' && dataIndo.status === 'OK') {
                            this.processData(dataArabic.data.ayahs, dataIndo.data.ayahs);
                        } else {
                            alert('Gagal memuat data Juz.');
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Terjadi kesalahan koneksi.');
                    } finally {
                        this.loading = false;
                    }
                },

                processData(arabicAyahs, indoAyahs) {
                    const groups = [];
                    let currentSurahNum = 0;
                    let currentGroup = null;

                    arabicAyahs.forEach((ayah, index) => {
                        const surah = ayah.surah;
                        const indoText = indoAyahs[index].text;

                        if (surah.number !== currentSurahNum) {
                            if (currentGroup) groups.push(currentGroup);
                            currentGroup = {
                                surah: surah,
                                ayahs: []
                            };
                            currentSurahNum = surah.number;
                        }
                        // Use Global Verse Number for Audio (More reliable with AlQuran.cloud API)
                        // https://cdn.islamic.network/quran/audio/128/ar.alafasy/{global_number}.mp3
                        const audioUrl = `https://cdn.islamic.network/quran/audio/128/ar.alafasy/${ayah.number}.mp3`;
                        const audioId = `${surah.number}-${ayah.numberInSurah}`;

                        // Strip Basmalah from the beginning of the text if it's the first verse (except Fatihah & Taubah)
                        let text = ayah.text;
                        if (ayah.numberInSurah === 1 && surah.number !== 1 && surah.number !== 9) {
                            text = text.replace(/^بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ\s*/, '');
                        }

                        const processedAyah = {
                            ...ayah,
                            text: text, // Use cleaned text
                            translation: indoText,
                            audioUrl: audioUrl,
                            audioId: audioId,
                            title: `QS. ${surah.englishName} : ${ayah.numberInSurah}`
                        };

                        currentGroup.ayahs.push(processedAyah);
                        this.playlist.push(processedAyah);
                    });

                    if (currentGroup) groups.push(currentGroup);
                    this.groupedAyahs = groups;
                },

                playAyat(ayat) {
                    if (this.activeAudioId === ayat.audioId && this.isPlaying) {
                        this.audio.pause();
                        this.isPlaying = false;
                    } else if (this.activeAudioId === ayat.audioId && !this.isPlaying) {
                        this.audio.play();
                        this.isPlaying = true;
                    } else {
                        // Stop any current audio
                        this.audio.pause();
                        
                        this.activeAudioId = ayat.audioId;
                        this.audio.src = ayat.audioUrl;
                        this.audio.play();
                        this.isPlaying = true;
                    }
                },

                playNext() {
                    const currentIndex = this.playlist.findIndex(a => a.audioId === this.activeAudioId);
                    if (currentIndex !== -1 && currentIndex < this.playlist.length - 1) {
                        this.playAyat(this.playlist[currentIndex + 1]);
                    } else {
                         this.isPlaying = false;
                         this.activeAudioId = null;
                    }
                },

                toggleAudio() {
                     if (this.activeAudioId) {
                        if (this.isPlaying) this.audio.pause();
                        else this.audio.play();
                        this.isPlaying = !this.isPlaying;
                     } else {
                         // Default start from first ayat if nothing selected
                         if(this.playlist.length > 0) this.playAyat(this.playlist[0]);
                     }
                },

                checkScroll(el) {
                    this.showBackToTop = el.scrollTop > 300;
                },

                scrollToTop() {
                    document.getElementById('mainContent').scrollTo({ top: 0, behavior: 'smooth' });
                }
            }
        }
    </script>
</body>
</html>
