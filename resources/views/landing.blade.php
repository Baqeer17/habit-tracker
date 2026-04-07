@extends('layouts.guest')

@section('content')

<!-- Header / Navigation (Floating Glass) -->
<header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="main-nav">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
        <div class="bg-white/70 backdrop-blur-md border border-white/50 rounded-2xl px-4 md:px-6 py-2.5 md:py-3 flex justify-between items-center shadow-sm">
            <!-- Logo -->
            <a href="{{ route('landing') }}" class="text-xl md:text-2xl font-bold text-[#2D5A43] tracking-tight font-serif flex items-center gap-2">
                MahabBa
            </a>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-1 md:gap-3">
                <a href="{{ route('login') }}" class="px-3 md:px-5 py-1.5 md:py-2 rounded-xl text-[#2D5A43] font-semibold hover:bg-[#E6F3F5] transition-all text-sm md:text-base">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="px-4 md:px-5 py-1.5 md:py-2 rounded-xl bg-[#2D5A43] text-white font-semibold hover:bg-[#1f4231] transition-all text-sm md:text-base shadow-lg shadow-[#2D5A43]/20">
                    <span class="hidden md:inline">Mulai </span>Daftar
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Hero Section -->
<section class="relative pt-32 pb-20 md:pt-48 md:pb-32 overflow-hidden bg-gradient-to-br from-white via-[#F8FBFC] to-[#E6F3F5] min-h-[95vh] flex flex-col justify-center">
    
    <!-- Moving Quranic Verses Background -->
    <div class="absolute inset-x-0 top-0 h-full overflow-hidden z-0 opacity-[0.10] pointer-events-none flex flex-col justify-evenly py-10" dir="rtl">
        <!-- Row 1: Moves Right -->
        <div class="flex whitespace-nowrap animate-[marqueeRight_60s_linear_infinite]">
            <span class="text-4xl md:text-7xl font-arabic text-[#2D5A43] px-8">وَقَالَ رَبُّكُمُ ادْعُونِي أَسْتَجِبْ لَكُمْ ۚ إِنَّ الَّذِينَ يَسْتَكْبِرُونَ عَنْ عِبَادَتِي سَيَدْخُلُونَ جَهَنَّمَ دَاخِرِينَ   •   فَإِذَا قَضَيْتُمُ الصَّلَاةَ فَاذْكُرُوا اللَّهَ قِيَامًا وَقُعُودًا وَعَلَىٰ جُنُوبِكُمْ   •   وَقَالَ رَبُّكُمُ ادْعُونِي أَسْتَجِبْ لَكُمْ ۚ إِنَّ الَّذِينَ يَسْتَكْبِرُونَ عَنْ عِبَادَتِي سَيَدْخُلُونَ جَهَنَّمَ دَاخِرِينَ   •   فَإِذَا قَضَيْتُمُ الصَّلَاةَ فَاذْكُرُوا اللَّهَ قِيَامًا وَقُعُودًا وَعَلَىٰ جُنُوبِكُمْ</span>
        </div>
        <!-- Row 2: Moves Left -->
        <div class="flex whitespace-nowrap animate-[marqueeLeft_50s_linear_infinite]">
            <span class="text-5xl md:text-8xl font-arabic text-[#2D5A43] px-8">فَاذْكُرُونِي أَذْكُرْكُمْ وَاشْكُرُوا لِي وَلَا تَكْفُرُونِ   •   يَا أَيُّهَا الَّذِينَ آمَنُوا اسْتَعِينُوا بِالصَّبْرِ وَالصَّلَاةِ ۚ إِنَّ اللَّهَ مَعَ الصَّابِرِينَ   •   فَاذْكُرُونِي أَذْكُرْكُمْ وَاشْكُرُوا لِي وَلَا تَكْفُرُونِ   •   يَا أَيُّهَا الَّذِينَ آمَنُوا اسْتَعِينُوا بِالصَّبْرِ وَالصَّلَاةِ ۚ إِنَّ اللَّهَ مَعَ الصَّابِرِينَ   •   فَاذْكُرُونِي أَذْكُرْكُمْ وَاشْكُرُوا لِي وَلَا تَكْفُرُونِ</span>
        </div>
        <!-- Row 3: Moves Right -->
        <div class="flex whitespace-nowrap animate-[marqueeRight_70s_linear_infinite]">
            <span class="text-4xl md:text-7xl font-arabic text-[#2D5A43] px-8">إِنَّ اللَّهَ وَمَلَائِكَتَهُ يُصَلُّونَ عَلَى النَّبِيِّ ۚ يَا أَيُّهَا الَّذِينَ آمَنُوا صَلُّوا عَلَيْهِ وَسَلِّمُوا تَسْلِيمًا   •   وَمَا خَلَقْتُ الْجِنَّ وَالْإِنسَ بِإِلَّا لِيَعْبُدُونِ   •   إِنَّ اللَّهَ وَمَلَائِكَتَهُ يُصَلُّونَ عَلَى النَّبِيِّ ۚ يَا أَيُّهَا الَّذِينَ آمَنُوا صَلُّوا عَلَيْهِ وَسَلِّمُوا تَسْلِيمًا   •   وَمَا خَلَقْتُ الْجِنَّ وَالْإِنسَ بِإِلَّا لِيَعْبُدُونِ</span>
        </div>
    </div>
    
    <!-- Decorative Glowing Orbs -->
    <div class="absolute top-20 -left-20 w-96 h-96 bg-[#2D5A43]/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-10 right-0 w-[30rem] h-[30rem] bg-[#B89E58]/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10 w-full">
        <!-- Calligraphy & Headline -->
        <div class="text-center max-w-4xl mx-auto mb-16" data-aos="fade-down">
            <!-- Premium Minimalist MahabBa Text -->
            <div class="mb-8 flex flex-col items-center justify-center">
                <div class="flex items-center gap-3 md:gap-6 w-full justify-center">
                    <div class="h-[1px] w-8 md:w-28 bg-gradient-to-r from-transparent to-[#B89E58]/70"></div>
                    <span class="inline-block text-3xl md:text-5xl italic font-light tracking-[0.2em] font-arabic drop-shadow-sm" style="background: linear-gradient(to bottom right, #B89E58, #E2C974, #B89E58); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; color: transparent;">
                        MahabBa
                    </span>
                    <div class="h-[1px] w-8 md:w-28 bg-gradient-to-l from-transparent to-[#B89E58]/70"></div>
                </div>
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-7xl leading-tight md:leading-tight lg:leading-tight text-gray-900 mb-6 font-serif tracking-tight px-2">
                Bangun hari yang lebih <span class="text-[#2D5A43] italic relative">dekat<svg class="absolute w-full h-[0.3em] -bottom-1 left-0 text-[#B89E58]/40" fill="currentColor" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 5 Q 50 10 100 5"></path></svg></span> dengan-Nya.
            </h1>
            
            <!-- Arabic Phrase in Hero Body -->
            <p class="text-3xl md:text-4xl text-[#2D5A43]/80 font-arabic mb-6 tracking-wide drop-shadow-sm" dir="rtl">
                اجعلنا محبتك يا الله
            </p>

            <p class="text-gray-500 text-lg md:text-xl max-w-2xl mx-auto font-light leading-relaxed">
                Jadikan setiap detak waktumu bernilai ibadah. MahabBa membantumu menjaga istiqomah dengan pendekatan spiritual yang dekat dan menenangkan.
            </p>
        </div>

        <!-- Call to Action -->
        <div class="text-center mb-20" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-[#2D5A43] text-white font-medium hover:bg-[#1f4231] transition duration-300 shadow-xl shadow-[#2D5A43]/30 transform hover:-translate-y-1">
                <span>Mulai Perjalanan Hijrahmu</span>
                <i class="fa-solid fa-arrow-right-long text-sm"></i>
            </a>
            <p class="mt-4 text-sm text-gray-400 font-medium">100% Gratis • Bebas Iklan • Legal</p>
        </div>

        <!-- CSS Mockups Showcase (Enhanced with Floating Elements) -->
        <div class="relative flex flex-col md:flex-row justify-center items-center gap-8 md:gap-4 lg:mb-12" data-aos="zoom-in" data-aos-delay="400">
            
            <!-- Floating Badge 1 -->
            <div class="hidden lg:flex absolute -left-12 top-20 bg-white/90 backdrop-blur-md p-4 rounded-2xl shadow-xl border border-white/50 items-center gap-4 z-20 animate-[bounce_4s_infinite]">
                <div class="w-10 h-10 bg-[#E6F3F5] text-[#2D5A43] rounded-full flex items-center justify-center text-xl">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Shalat Wajib</p>
                    <p class="text-sm font-bold text-gray-800">100% Selesai</p>
                </div>
            </div>

            <!-- Floating Badge 2 -->
            <div class="hidden lg:flex absolute -right-8 bottom-10 bg-white/90 backdrop-blur-md p-4 rounded-2xl shadow-xl border border-white/50 items-center gap-4 z-20 animate-[bounce_5s_infinite_reverse]">
                <div class="w-10 h-10 bg-[#fef3c7] text-[#B89E58] rounded-full flex items-center justify-center text-xl">
                    <i class="fa-solid fa-book-open-reader"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Tilawah</p>
                    <p class="text-sm font-bold text-gray-800">Al-Baqarah: 148</p>
                </div>
            </div>

            <!-- Mockup 1: Dashboard -->
            <div class="bg-white/70 backdrop-blur-xl border border-white rounded-3xl p-4 shadow-[0_30px_60px_rgba(45,90,67,0.1)] w-full max-w-md transform transition duration-500 hover:-translate-y-2 z-10">
                <div class="flex gap-4">
                    <!-- Sidebar Mock -->
                    <div class="w-16 flex flex-col gap-3 py-2">
                        <div class="w-full h-8 bg-[#E6F3F5] rounded-xl mb-2 flex items-center justify-center text-[#2D5A43] text-xs"><i class="fa-solid fa-moon"></i></div>
                        <div class="w-full h-4 bg-gray-100 rounded-md"></div>
                        <div class="w-full h-4 bg-[#2D5A43]/20 rounded-md"></div>
                        <div class="w-full h-4 bg-gray-100 rounded-md"></div>
                        <div class="w-full h-4 bg-gray-100 rounded-md"></div>
                        <div class="w-full h-4 bg-gray-100 rounded-md mt-auto"></div>
                    </div>
                    <!-- Main Content Mock -->
                    <div class="flex-1 bg-gray-50/80 rounded-2xl p-4 border border-white">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <div class="h-2 w-20 bg-gray-400 rounded mb-2"></div>
                                <div class="h-3 w-32 bg-[#2D5A43] rounded"></div>
                            </div>
                            <div class="text-right">
                                <div class="h-2 w-16 bg-[#B89E58]/50 rounded mb-2 ml-auto"></div>
                                <div class="h-4 w-24 bg-gray-800 rounded ml-auto"></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div class="border border-white shadow-sm rounded-xl p-4 bg-white flex flex-col items-center justify-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#E6F3F5] text-[#2D5A43] flex items-center justify-center"><i class="fa-solid fa-hands-praying"></i></div>
                                <div class="h-2 w-16 bg-gray-200 rounded"></div>
                            </div>
                            <div class="border border-white shadow-sm rounded-xl p-4 bg-white flex flex-col items-center justify-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#fef3c7] text-[#B89E58] flex items-center justify-center"><i class="fa-solid fa-mosque"></i></div>
                                <div class="h-2 w-16 bg-gray-200 rounded"></div>
                            </div>
                        </div>
                        <div class="w-full h-16 bg-white border border-white shadow-sm rounded-xl flex items-center px-4 gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100"></div>
                            <div class="flex-1">
                                <div class="h-2 w-24 bg-gray-200 rounded mb-1"></div>
                                <div class="h-2 w-16 bg-gray-100 rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mockup 2: Al-Quran (Tilted slightly for depth) -->
            <div class="bg-white/70 backdrop-blur-xl border border-white rounded-3xl p-4 shadow-[0_30px_60px_rgba(45,90,67,0.15)] w-full max-w-md transform md:translate-x-[-10%] md:translate-y-8 lg:translate-x-[-15%] lg:translate-y-12 md:rotate-2 transition duration-500 hover:rotate-0 hover:-translate-y-2 hover:z-20">
                <div class="flex gap-4">
                    <!-- Main Content Mock -->
                    <div class="flex-1 bg-white rounded-2xl border border-white overflow-hidden shadow-sm">
                        <!-- Curved dark header -->
                        <div class="bg-gray-800 p-5 rounded-b-[2rem] relative shadow-inner">
                            <div class="absolute right-4 top-4 text-white/10 text-4xl"><i class="fa-solid fa-book-quran"></i></div>
                            <div class="h-4 w-24 bg-white/90 rounded mb-2 relative z-10"></div>
                            <div class="h-2 w-16 bg-gray-400 rounded relative z-10"></div>
                        </div>
                        <div class="p-5 pt-6">
                            <!-- Search bar mock -->
                            <div class="w-full h-10 bg-gray-50 border border-gray-100 rounded-full flex items-center px-4 gap-2 mb-6">
                                <i class="fa-solid fa-search text-gray-300 text-sm"></i>
                                <div class="h-2 w-20 bg-gray-200 rounded"></div>
                            </div>
                            
                            <!-- List mock -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full bg-[#E6F3F5] text-[#2D5A43] flex items-center justify-center text-xs font-bold font-serif">1</div>
                                    <div class="flex-1 border-b border-gray-50 pb-3 flex justify-between items-center">
                                        <div>
                                            <div class="h-2 w-20 bg-gray-800 rounded mb-1"></div>
                                            <div class="h-1 w-16 bg-gray-300 rounded"></div>
                                        </div>
                                        <div class="h-3 w-16 bg-[#B89E58]/30 rounded"></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full bg-[#E6F3F5] text-[#2D5A43] flex items-center justify-center text-xs font-bold font-serif">2</div>
                                    <div class="flex-1 border-b border-gray-50 pb-3 flex justify-between items-center">
                                        <div>
                                            <div class="h-2 w-24 bg-gray-800 rounded mb-1"></div>
                                            <div class="h-1 w-20 bg-gray-300 rounded"></div>
                                        </div>
                                        <div class="h-3 w-20 bg-[#B89E58]/30 rounded"></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 opacity-50">
                                    <div class="w-8 h-8 rounded-full bg-[#E6F3F5] text-[#2D5A43] flex items-center justify-center text-xs font-bold font-serif">3</div>
                                    <div class="flex-1 pb-1 flex justify-between items-center">
                                        <div>
                                            <div class="h-2 w-16 bg-gray-800 rounded mb-1"></div>
                                            <div class="h-1 w-12 bg-gray-300 rounded"></div>
                                        </div>
                                        <div class="h-3 w-12 bg-[#B89E58]/30 rounded"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ========================================== -->
<!-- FITUR UTAMA SECTION (From User Prompt)     -->
<!-- ========================================== -->
<section class="py-20 px-6 max-w-7xl mx-auto bg-[#F8FBFC]">
    <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-[#2D5A43] text-3xl md:text-4xl font-bold mb-4 font-serif">Fitur Utama MahabBa</h2>
        <p class="text-gray-500 max-w-2xl mx-auto italic text-sm md:text-base">Membantumu menjaga istiqomah dalam ibadah dengan pendekatan teknologi yang menenangkan.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Tracker -->
        <div class="bg-white p-8 rounded-[40px] shadow-sm hover:shadow-xl transition-all duration-500 group border border-gray-50 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="100">
            <div class="w-16 h-16 bg-[#E6F3F5] rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#2D5A43] transition-colors duration-300">
                <i class="fa-solid fa-chart-line text-[#2D5A43] group-hover:text-white text-2xl transition-colors duration-300"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-3 font-serif">Spiritual Tracker</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Pantau grafik perkembangan shalat dan tilawahmu setiap hari dengan visual yang elegan.</p>
        </div>

        <!-- Library -->
        <div class="bg-white p-8 rounded-[40px] shadow-sm hover:shadow-xl transition-all duration-500 group border-t-4 border-[#2D5A43] hover:-translate-y-2" data-aos="fade-up" data-aos-delay="200">
            <div class="w-16 h-16 bg-[#E6F3F5] rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#2D5A43] transition-colors duration-300">
                <i class="fa-solid fa-book-quran text-[#2D5A43] group-hover:text-white text-2xl transition-colors duration-300"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-3 font-serif">Library Mualaf</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Akses buku panduan dasar islam, doa harian, dan literatur pilihan secara gratis dan legal.</p>
        </div>

        <!-- Reminder -->
        <div class="bg-white p-8 rounded-[40px] shadow-sm hover:shadow-xl transition-all duration-500 group border border-gray-50 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="300">
            <div class="w-16 h-16 bg-[#E6F3F5] rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#2D5A43] transition-colors duration-300">
                <i class="fa-solid fa-bell text-[#2D5A43] group-hover:text-white text-2xl transition-colors duration-300"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-3 font-serif">Smart Reminder</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Pengingat waktu Tahajud, Dhuha, dan Tilawah yang disesuaikan dengan rutinitas harianmu.</p>
        </div>
    </div>
</section>

<!-- ========================================== -->
<!-- QUOTE SECTION (From User Prompt)           -->
<!-- ========================================== -->
<section class="py-24 bg-[#4D4D4D] relative overflow-hidden text-white">
    <!-- Watermark Typography Background -->
    <div class="absolute inset-x-0 -inset-y-40 z-0 opacity-[0.04] pointer-events-none flex flex-col justify-center gap-12 overflow-hidden select-none -rotate-2 transform scale-110">
        <!-- Row 1 -->
        <div class="flex whitespace-nowrap gap-16 -ml-20">
            @for ($i = 0; $i < 12; $i++)
                <span class="text-6xl md:text-8xl font-serif italic font-light tracking-widest text-[#E6F3F5]">MahabBa</span>
                <span class="text-6xl md:text-8xl font-arabic italic text-[#E6F3F5] tracking-wide" dir="rtl">مَحَبَّة</span>
            @endfor
        </div>
        <!-- Row 2 -->
        <div class="flex whitespace-nowrap gap-16 -ml-52">
            @for ($i = 0; $i < 12; $i++)
                <span class="text-6xl md:text-8xl font-arabic italic text-[#E6F3F5] tracking-wide" dir="rtl">مَحَبَّة</span>
                <span class="text-6xl md:text-8xl font-serif italic font-light tracking-widest text-[#E6F3F5]">MahabBa</span>
            @endfor
        </div>
        <!-- Row 3 -->
        <div class="flex whitespace-nowrap gap-16 ml-10">
            @for ($i = 0; $i < 12; $i++)
                <span class="text-6xl md:text-8xl font-serif italic font-light tracking-widest text-[#E6F3F5]">MahabBa</span>
                <span class="text-6xl md:text-8xl font-arabic italic text-[#E6F3F5] tracking-wide" dir="rtl">مَحَبَّة</span>
            @endfor
        </div>
        <!-- Row 4 -->
        <div class="flex whitespace-nowrap gap-16 -ml-40 lg:hidden">
            @for ($i = 0; $i < 12; $i++)
                <span class="text-6xl md:text-8xl font-arabic italic text-[#E6F3F5] tracking-wide" dir="rtl">مَحَبَّة</span>
                <span class="text-6xl md:text-8xl font-serif italic font-light tracking-widest text-[#E6F3F5]">MahabBa</span>
            @endfor
        </div>
    </div>
    
    <div class="max-w-4xl mx-auto px-6 text-center relative z-10" data-aos="fade-up">
        <h2 class="text-2xl md:text-3xl italic leading-relaxed mb-8 font-serif">
            "Maka berlomba-lombalah kamu dalam kebaikan. Di mana saja kamu berada, pasti Allah akan mengumpulkan kamu semuanya."
        </h2>
        <p class="text-[#E6F3F5] font-medium text-lg tracking-wide">— QS. Al-Baqarah: 148</p>
        
        <div class="mt-12 h-[1px] w-24 bg-white/30 mx-auto"></div>
        
        <p class="mt-12 text-white/70 italic text-sm md:text-base font-light">
            "Amalan yang paling dicintai Allah adalah amalan yang rutin dilakukan meskipun sedikit."<br/> (HR. Bukhari & Muslim)
        </p>
    </div>
</section>

<!-- ========================================== -->
<!-- FOOTER (From User Prompt)                  -->
<!-- ========================================== -->
<footer class="bg-white pt-20 pb-10 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
        
        <!-- Brand -->
        <div class="col-span-1 md:col-span-1" data-aos="fade-right">
            <h2 class="text-[#2D5A43] text-2xl font-bold mb-4 font-serif">MahabBa</h2>
            <p class="text-gray-400 text-sm italic pr-4">Membangun hari yang lebih dekat dengan-Nya melalui cinta dan teknologi.</p>
        </div>
        
        <!-- Links -->
        <div data-aos="fade-up" data-aos-delay="100">
            <h4 class="font-bold text-gray-800 mb-6 font-serif">Akses Cepat</h4>
            <ul class="text-gray-500 text-sm space-y-4">
                <li><a href="{{ route('register') }}" class="hover:text-[#2D5A43] transition-colors">Mulai Daftar</a></li>
                <li><a href="{{ route('login') }}" class="hover:text-[#2D5A43] transition-colors">Login Akun</a></li>
            </ul>
        </div>
        
        <!-- Support -->
        <div data-aos="fade-up" data-aos-delay="200">
            <h4 class="font-bold text-gray-800 mb-6 font-serif">Bantuan</h4>
            <ul class="text-gray-500 text-sm space-y-4">
                <li><a href="#" class="hover:text-[#2D5A43] transition-colors">FAQ</a></li>
                <li><a href="#" class="hover:text-[#2D5A43] transition-colors">Privasi</a></li>
            </ul>
        </div>
        
        <!-- Socials -->
        <div data-aos="fade-up" data-aos-delay="300">
            <h4 class="font-bold text-gray-800 mb-6 font-serif">Media Sosial</h4>
            <div class="flex gap-4">
                <a href="#" class="w-10 h-10 bg-[#E6F3F5] rounded-full flex items-center justify-center text-[#2D5A43] hover:bg-[#2D5A43] hover:text-white transition duration-300 transform hover:scale-110">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="#" class="w-10 h-10 bg-[#E6F3F5] rounded-full flex items-center justify-center text-[#2D5A43] hover:bg-[#2D5A43] hover:text-white transition duration-300 transform hover:scale-110">
                    <i class="fa-brands fa-tiktok"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="text-center pt-10 border-t border-gray-50 text-gray-400 text-xs">
        &copy; {{ date('Y') }} MahabBa Project. Dedicated for Ummah.
    </div>
</footer>

@endsection

@push('scripts')
<script>
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const nav = document.getElementById('main-nav');
        if (window.scrollY > 20) {
            nav.classList.add('py-2');
            nav.classList.remove('py-4');
        } else {
            nav.classList.add('py-4');
            nav.classList.remove('py-2');
        }
    });
</script>
@endpush
