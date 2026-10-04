@extends('layouts.app')

@push('styles')
<style>
    /* Font Arab Khusus */
    .font-arab { font-family: 'Amiri', serif; line-height: 2.2; }

    /* Header Pill Style */
    .dzikir-header {
        background: #4A4A4A;
        color: white;
        padding: 15px 30px;
        border-radius: 50px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    /* Tabs Style */
    .tabs-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 30px;
        background: #e5e7eb;
        padding: 5px;
        border-radius: 50px;
        width: fit-content;
        margin-left: auto;
        margin-right: auto;
        transition: background 0.3s;
    }
    .dark .tabs-container { background: #1e1e1e; }
    .tab-btn {
        padding: 8px 25px;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        color: #7f8c8d;
    }
    .dark .tab-btn { color: #9ca3af; }
    .tab-btn.active {
        background: #E3D4C1;
        color: #5d4037;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .dark .tab-btn.active { background: #0F766E; color: white; }

    /* Dzikir Card Grid */
    .dzikir-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 25px;
        padding-bottom: 50px;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .dzikir-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 250px;
        transition: transform 0.2s, background 0.3s, border-color 0.3s;
        border: 1px solid #e5e7eb;
    }
    .dark .dzikir-card {
        background: #1e1e1e;
        border-color: #2d3436;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    .dzikir-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        border-color: #E3D4C1;
    }
    .dark .dzikir-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        border-color: #d4a373;
    }
    
    .card-header { text-align: center; margin-bottom: 15px; }
    .card-title { font-weight: 700; font-size: 16px; color: #2d3436; transition: color 0.3s; }
    .dark .card-title { color: #f3f4f6; }
    
    .card-body { text-align: center; flex: 1; display: flex; flex-direction: column; justify-content: center; margin-bottom: 20px; }
    
    .counter-btn {
        width: 45px; height: 45px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: bold;
        font-size: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    /* Style Tombol Belum Selesai */
    .btn-todo { background: #95a5a6; color: white; }
    .btn-todo:hover { background: #7f8c8d; transform: scale(1.1); }
    
    /* Style Tombol Selesai (Check) */
    .btn-done { background: #2ecc71; color: white; pointer-events: none; }

    /* Info Box Style */
    .info-box { background: #f8f9fa; border-left: 3px solid #d4a373; padding: 10px; margin-top: 10px; text-align: left; border-radius: 0 8px 8px 0; transition: background 0.3s; }
    .dark .info-box { background: #2d3436; }
    .info-label { font-size: 10px; font-weight: bold; color: #d4a373; text-transform: uppercase; letter-spacing: 0.5px; }
    .info-text { font-size: 11px; color: #636e72; margin-bottom: 5px; line-height: 1.4; }
    .dark .info-text { color: #9ca3af; }

    @media (max-width: 768px) {
        .dzikir-header { padding: 12px 20px; border-radius: 40px; margin-bottom: 16px; }
        .dzikir-header h1 { font-size: 16px; }
        .dzikir-grid { grid-template-columns: 1fr; gap: 16px; padding-bottom: 30px; }
        .dzikir-card { padding: 18px; min-height: 200px; border-radius: 16px; }
        .tabs-container { width: 100%; justify-content: space-between; padding: 4px; margin-bottom: 20px; }
        .tab-btn { padding: 7px 14px; font-size: 12px; }
        .card-title { font-size: 14px; }
    }

    @media (max-width: 380px) {
        .dzikir-header { padding: 10px 16px; }
        .tab-btn { padding: 6px 10px; font-size: 11px; }
        .dzikir-card { padding: 14px; min-height: 180px; }
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div x-data="dzikirApp()">
        
        <!-- Back to Top Button (Inside Scope) -->
        <div x-show="showBackToTop" 
             x-transition 
             @click="scrollToTop"
             class="fixed bottom-6 right-6 z-50 bg-[#d4a373] text-white w-12 h-12 rounded-full shadow-lg flex items-center justify-center cursor-pointer hover:bg-[#c08d5d] transition-transform hover:scale-110"
             style="display: none;">
            <i class="fas fa-arrow-up"></i>
        </div>
        
        <div class="dzikir-header">
            <div>
                <h1 class="text-xl font-bold tracking-wide">Halaman Dzikir</h1>
                <nav class="flex items-center text-xs text-gray-300 gap-2 font-medium mt-1">
                    <a href="/dashboard" class="hover:text-white transition-colors flex items-center gap-1">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <span class="text-gray-400">/</span>
                    <span class="text-[#d4a373] font-semibold">Dzikir</span>
                </nav>
            </div>
            <div class="text-[#d4a373] font-serif italic opacity-80">MahabBa</div>
        </div>

        <div class="tabs-container">
            <div @click="activeTab = 'pagi'" :class="{'active': activeTab === 'pagi'}" class="tab-btn">Pagi</div>
            <div @click="activeTab = 'petang'" :class="{'active': activeTab === 'petang'}" class="tab-btn">Petang</div>
            <div @click="activeTab = 'salat'" :class="{'active': activeTab === 'salat'}" class="tab-btn">Setelah Salat</div>
        </div>

        <div class="dzikir-grid">
            
            <template x-for="(item, index) in currentList" :key="index">
        <div class="dzikir-card">
                    <div class="card-header border-b border-gray-100 dark:border-gray-700 pb-3 mb-4">
                        <h3 class="card-title text-lg" x-text="item.title"></h3>
                    </div>

                    <div class="card-body text-center">
                        <!-- Teks Arab Utama -->
                        <p class="font-arab text-2xl font-normal text-[#2d3436] dark:text-gray-200 leading-[2.5] mb-4 px-4" x-text="item.arab"></p>
                        
                        <!-- Toggle Button -->
                         <div x-data="{ show: false }">
                            <button @click="show = !show" class="text-xs font-semibold text-[#d4a373] hover:underline mb-2 flex items-center justify-center gap-1 mx-auto">
                                <i class="fas" :class="show ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                <span>Lihat Arti & Keutamaan</span>
                            </button>

                            <!-- Hidden Content -->
                            <div x-show="show" x-transition.opacity class="mt-4 transition-all">
                                
                                <!-- Latin -->
                                 <p class="text-xs text-gray-400 dark:text-gray-500 italic mb-2" x-text="item.latin"></p>
                                
                                <!-- Arti -->
                                 <p class="text-sm text-[#2d3436] dark:text-gray-200 font-medium leading-relaxed pb-2 border-b border-gray-100 dark:border-gray-700 italic">
                                    "<span x-text="item.arti"></span>"
                                </p>

                                <!-- Info Box (Dalil & Faedah) -->
                                <div class="info-box">
                                    <div class="mb-2">
                                        <div class="info-label"><i class="fas fa-bookmark mr-1"></i> DALIL</div>
                                        <div class="info-text" x-text="item.ref"></div>
                                    </div>
                                    <div>
                                        <div class="info-label"><i class="fas fa-star mr-1"></i> KEUTAMAAN</div>
                                        <div class="info-text" x-text="item.faedah"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-center">
                        <template x-if="item.target > 1">
                            <div class="text-center group cursor-pointer" @click="decrement(item)">
                                <div class="counter-btn relative mb-1" 
                                     :class="item.current <= 0 ? 'bg-[#2ecc71] text-white' : 'bg-[#ecf0f1] text-[#2d3436] group-hover:bg-[#d4a373] group-hover:text-white'">
                                    
                                    <span x-show="item.current > 0" x-text="item.current" class="font-bold text-lg"></span>
                                    <i x-show="item.current <= 0" class="fas fa-check"></i>
                                </div>
                                <span class="text-[10px] text-gray-400">Ketuk untuk hitung</span>
                            </div>
                        </template>

                        <template x-if="item.target === 1">
                            <div class="text-center cursor-pointer" @click="decrement(item)">
                                <div class="counter-btn"
                                     :class="item.current <= 0 ? 'bg-[#2ecc71] text-white' : 'bg-[#ecf0f1] text-[#95a5a6] hover:bg-[#2ecc71] hover:text-white'">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span class="text-[10px] text-gray-400 mt-1 block" x-show="item.current > 0">Sudah dibaca?</span>
                                <span class="text-[10px] text-[#2ecc71] mt-1 block font-bold" x-show="item.current <= 0">Selesai</span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Tombol Selesai Dzikir -->
            <div class="mt-8 mb-4 px-4 flex justify-center w-full">
                <button @click="markAsCompleted()" 
                        :disabled="isCurrentTabCompleted"
                        :class="isCurrentTabCompleted ? 'bg-gray-300 cursor-not-allowed text-gray-500 shadow-none' : 'bg-[#0d9488] hover:bg-[#0f766e] text-white shadow-lg transform hover:-translate-y-1'"
                        class="w-full max-w-lg py-4 rounded-xl font-bold text-lg flex items-center justify-center gap-3 transition-all duration-300">
                    <i class="fas" :class="isCurrentTabCompleted ? 'fa-check-circle text-green-500' : 'fa-flag-checkered'"></i>
                    <span x-text="isCurrentTabCompleted ? 'Dzikir Selesai' : 'Tandai Dzikir Selesai'"></span>
                </button>
            </div>
            
            <!-- Source Credit -->
            <div x-show="activeTab === 'pagi' || activeTab === 'petang'" class="text-center mt-2 pb-8 text-xs text-gray-400 italic">
                source: rumaysho.com
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function dzikirApp() {
        return {
            activeTab: 'pagi',
            showBackToTop: false,
            pagiCompleted: {{ isset($pagiCompleted) && $pagiCompleted ? 'true' : 'false' }},
            petangCompleted: {{ isset($petangCompleted) && $petangCompleted ? 'true' : 'false' }},
            salatCompleted: {{ isset($salatCompleted) && $salatCompleted ? 'true' : 'false' }},

            get isCurrentTabCompleted() {
                if (this.activeTab === 'pagi') return this.pagiCompleted;
                if (this.activeTab === 'petang') return this.petangCompleted;
                return this.salatCompleted;
            },

            markAsCompleted() {
                if (this.isCurrentTabCompleted) return;
                
                const payload = {
                    type: this.activeTab,
                    details: this.currentList.map(item => ({ title: item.title, current: item.current, target: item.target })),
                    _token: '{{ csrf_token() }}'
                };
                
                fetch('{{ route('dzikir.log') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        if (this.activeTab === 'pagi') this.pagiCompleted = true;
                        if (this.activeTab === 'petang') this.petangCompleted = true;
                        if (this.activeTab === 'salat') this.salatCompleted = true;
                    }
                });
            },

            init() {
                // Listen to scroll on the main content container
                const mainContent = document.querySelector('.main-content');
                if(mainContent) {
                    mainContent.addEventListener('scroll', () => {
                        this.showBackToTop = mainContent.scrollTop > 300;
                    });
                }
            },

            scrollToTop() {
                const mainContent = document.querySelector('.main-content');
                if(mainContent) mainContent.scrollTo({ top: 0, behavior: 'smooth' });
            },
            
            data: {
                pagi: [
                    { title: "1. Ta'awudz", arab: "أَعُوذُ بِاللَّهِ مِنَ الشَّيْطَانِ الرَّجِيمِ", arti: "Aku berlindung kepada Allah dari godaan syaitan yang terkutuk.", latin: "A'udzu billahi minasy syaithonir rojiim.", ref: "QS. An-Nahl: 98", faedah: "Memohon perlindungan sebelum memulai dzikir.", target: 1, current: 1 },
                    { title: "2. Ayat Kursi", arab: "ٱللَّهُ لَآ إِلَٰهَ إِلَّا هُوَ ٱلْحَىُّ ٱلْقَيُّومُ ۚ لَا تَأْخُذُهُۥ سِنَةٌ وَلَا نَوْمٌ ۚ لَّهُۥ مَا فِى ٱلسَّمَٰوَٰتِ وَمَا فِى ٱلْأَرْضِ ۗ مَن ذَا ٱلَّذِى يَشْفَعُ عِندَهُۥٓ إِلَّا بِإِذْنِهِۦ ۚ يَعْلَمُ مَا بَيْنَ أَيْدِيهِمْ وَمَا خَلْفَهُمْ ۖ وَلَا يُحِيطُونَ بِشَىْءٍ مِّنْ عِلْمِهِۦٓ إِلَّا بِمَا شَآءَ ۚ وَسِعَ كُرْسِيُّهُ ٱلسَّمَٰوَٰتِ وَٱلْأَرْضَ ۖ وَلَا يَئُودُهُۥ حِفْظُهُمَا ۚ وَهُوَ ٱلْعَلِىُّ ٱلْعَظِيمُ ۝٢٥٥", arti: "Allah, tidak ada ilah (yang berhak disembah) melainkan Dia Yang Hidup kekal lagi terus menerus mengurus (makhluk-Nya), tidak mengantuk dan tidak tidur. Kepunyaan-Nya apa yang di langit dan di bumi. Tiada yang dapat memberi syafa'at di sisi Allah tanpa izin-Nya. Allah mengetahui apa-apa yang di hadapan mereka dan di belakang mereka, dan mereka tidak mengetahui apa-apa dari ilmu Allah melainkan apa yang dikehendaki-Nya. Kursi Allah meliputi langit dan bumi. Dan Allah tidak merasa berat memelihara keduanya, dan Allah Maha Tinggi lagi Maha Besar.", latin: "Allahu laa ilaaha illa huwal hayyul qayyum. Laa ta'khudzuhuu sinatuw wa laa naum. Lahuu maa fis-samaawaati wa maa fil ardh. Man dzal-ladzii yasyfa'u 'indahuu illaa bi-idznih. Ya'lamu maa baina aidiihim wa maa khalfahum. Wa laa yuhiithuuna bi-syai-im min 'ilmihii illaa bi maa syaa-a. Wasi'a kursiyyuhus-samaawaati wal ardh. Wa laa ya-uuduhuu hifzhuhumaa. Wa huwal 'aliyyul 'azhiim.", ref: "QS. Al-Baqarah: 255. (HR. An-Nasa'i & Al Hakim)", faedah: "Siapa yang membacanya ketika pagi, maka ia akan dilindungi dari gangguan jin hingga sore.", target: 1, current: 1 },
                    { title: "3. Surah Al-Ikhlas (3x)", arab: "قُلْ هُوَ ٱللَّهُ أَحَدٌ ۝١ ٱللَّهُ ٱلصَّمَدُ ۝٢ لَمْ يَلِدْ وَلَمْ يُولَدْ ۝٣ وَلَمْ يَكُن لَّهُۥ كُفُوًا أَحَدٌۢ ۝٤", arti: "Katakanlah: Dialah Allah, Yang Maha Esa. Allah adalah Ilah yang bergantung kepada-Nya segala urusan. Dia tiada beranak dan tiada pula diperanakkan. Dan tidak ada seorang pun yang setara dengan Dia.", latin: "Qul huwallahu ahad (1) Allahus shomad (2) Lam yalid wa lam yuulad (3) Wa lam yakul lahuu kufuwan ahad (4)", ref: "QS. Al Ikhlas: 1-4", faedah: "Mencukupkan dari segala sesuatu (HR. Abu Daud & Tirmidzi).", target: 3, current: 3 },
                    { title: "4. Surah Al-Falaq (3x)", arab: "قُلْ أَعُوذُ بِرَبِّ ٱلْفَلَقِ ۝١ مِن شَرِّ مَا خَلَقَ ۝٢ وَمِن شَرِّ غَاسِقٍ إِذَا وَقَبَ ۝٣ وَمِن شَرِّ ٱلنَّفَّٰثَٰتِ فِى ٱلْعُقَدِ ۝٤ وَمِن شَرِّ حَاسِدٍ إِذَا حَسَدَ ۝٥", arti: "Katakanlah: Aku berlindung kepada Rabb yang menguasai Shubuh, dari kejahatan makhluk-Nya, dan dari kejahatan malam apabila telah gelap gulita, dan dari kejahatan wanita-wanita tukang sihir yang menghembus pada buhul-buhul, dan dari kejahatan orang yang dengki apabila ia dengki.", latin: "Qul a'udzu bi rabbil-falaq (1) Min syarri maa kholaq (2) Wa min syarri ghaasiqin idza waqab (3) Wa min syarrin naffaatsaati fil 'uqad (4) Wa min syarri haasidin idza hasad (5)", ref: "QS. Al Falaq: 1-5", faedah: "Perlindungan dari kejahatan makhluk.", target: 3, current: 3 },
                    { title: "5. Surah An-Nas (3x)", arab: "قُلْ أَعُوذُ بِرَبِّ ٱلنَّاسِ ۝١ مَلِكِ ٱلنَّاسِ ۝٢ إِلَٰهِ ٱلنَّاسِ ۝٣ مِن شَرِّ ٱلْوَسْوَاسِ ٱلْخَنَّاسِ ۝٤ ٱلَّذِى يُوَسْوِسُ فِى صُدُورِ ٱلنَّاسِ ۝٥ مِنَ ٱلْجِنَّةِ وَٱلنَّاسِ ۝٦", arti: "Katakanlah: Aku berlindung kepada Rabb manusia, Raja manusia, Sembahan manusia, dari kejahatan (bisikan) syaitan yang biasa bersembunyi, yang membisikkan (kejahatan) ke dalam dada manusia, dari jin dan manusia.", latin: "Qul a'udzu bi rabbin-naas (1) Malikin-naas (2) Ilaahin-naas (3) Min syarril waswaasil khannaas (4) Alladzii yuwaswisu fii shuduurin-naas (5) Minal jinnati wan-naas (6)", ref: "QS. An Naas: 1-6", faedah: "Perlindungan dari bisikan syaitan.", target: 3, current: 3 },
                    { title: "6. Doa Pagi Hari", arab: "أَصْبَحْنَا وَأَصْبَحَ الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ، لاَ إِلَـهَ إِلاَّ اللهُ وَحْدَهُ لاَ شَرِيْكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيْرُ. رَبِّ أَسْأَلُكَ خَيْرَ مَا فِيْ هَذَا الْيَوْمِ وَخَيْرَ مَا بَعْدَهُ، وَأَعُوْذُ بِكَ مِنْ شَرِّ مَا فِيْ هَذَا الْيَوْمِ وَشَرِّ مَا بَعْدَهُ، رَبِّ أَعُوْذُ بِكَ مِنَ الْكَسَلِ وَسُوْءِ الْكِبَرِ، رَبِّ أَعُوْذُ بِكَ مِنْ عَذَابٍ فِي النَّارِ وَعَذَابٍ فِي الْقَبْرِ", arti: "Kami telah memasuki waktu pagi dan kerajaan hanya milik Allah, segala puji bagi Allah. Tidak ada ilah (yang berhak disembah) kecuali Allah semata, tiada sekutu bagi-Nya. Milik Allah kerajaan dan bagi-Nya pujian. Dia-lah Yang Maha Kuasa atas segala sesuatu. Wahai Rabbku, aku mohon kepada-Mu kebaikan di hari ini dan kebaikan sesudahnya. Aku berlindung kepada-Mu dari kejahatan hari ini dan kejahatan sesudahnya. Wahai Rabbku, aku berlindung kepada-Mu dari kemalasan dan kejelekan di hari tua. Wahai Rabbku, aku berlindung kepada-Mu dari siksaan di neraka dan siksaan di alam kubur.", latin: "Ash-bahnaa wa ash-bahal mulku lillah, walhamdulillah, laa ilaha illallah wahdahu laa syarika lah, lahul mulku walahul hamdu wa huwa 'ala kulli syai-in qodiir. Robbi as-aluka khoiro maa fii haadzal yaumi wa khoiro maa ba'dahu, wa a'udzu bika min syarri maa fii haadzal yaumi wa syarri maa ba'dahu, Robbi a'udzu bika minal kasali wa suu-il kibari, Robbi a'udzu bika min 'adzaabin fin naari wa 'adzaabin fil qobri.", ref: "HR. Muslim No. 2723", faedah: "Meminta kebaikan hari ini dan perlindungan dari kemalasan...", target: 1, current: 1 },
                    { title: "7. Doa Syukur Pagi", arab: "اَللَّهُمَّ بِكَ أَصْبَحْنَا، وَبِكَ أَمْسَيْنَا، وَبِكَ نَحْيَا، وَبِكَ نَمُوْتُ وَإِلَيْكَ النُّشُوْرُ", arti: "Ya Allah, dengan rahmat dan pertolongan-Mu kami memasuki waktu pagi, dan dengan rahmat dan pertolongan-Mu kami memasuki waktu petang. Dengan rahmat dan pertolongan-Mu kami hidup dan dengan kehendak-Mu kami mati. Dan kepada-Mu kebangkitan.", latin: "Allahumma bika ash-bahnaa wa bika amsaynaa wa bika nahyaa wa bika namuutu wa ilaikan nusyuur.", ref: "HR. Tirmidzi No. 3391", faedah: "Bentuk penyerahan diri total kepada Allah di awal hari.", target: 1, current: 1 },
                    { title: "8. Sayyidul Istighfar", arab: "اَللَّهُمَّ أَنْتَ رَبِّيْ لاَ إِلَـهَ إِلاَّ أَنْتَ، خَلَقْتَنِيْ وَأَنَا عَبْدُكَ، وَأَنَا عَلَى عَهْدِكَ وَوَعْدِكَ مَا اسْتَطَعْتُ، أَعُوْذُ بِكَ مِنْ شَرِّ مَا صَنَعْتُ، أَبُوْءُ لَكَ بِنِعْمَتِكَ عَلَيَّ، وَأَبُوْءُ بِذَنْبِيْ فَاغْفِرْ لِيْ فَإِنَّهُ لاَ يَغْفِرُ الذُّنُوْبَ إِلاَّ أَنْتَ", arti: "Ya Allah, Engkau adalah Rabbku, tidak ada ilah (yang berhak disembah) kecuali Engkau, Engkaulah yang menciptakanku. Aku adalah hamba-Mu. Aku akan setia pada perjanjianku dengan-Mu semampuku. Aku berlindung kepada-Mu dari kejelekan yang kuperbuat. Aku mengakui nikmat-Mu kepadaku dan aku mengakui dosaku, oleh karena itu, ampunilah aku. Sesungguhnya tiada yang mengampuni dosa kecuali Engkau.", latin: "Allahumma anta robbii laa ilaha illa anta, kholaqtanii wa anaa 'abduka, wa anaa 'alaa 'ahdika wa wa'dika mas-tatho'tu, a'udzu bika min syarri maa shona'tu, abuu-u laka bi ni'matika 'alayya, wa abuu-u bi dzambii faghfir lii fa-innahu laa yaghfirudz-dzunuuba illa anta.", ref: "HR. Bukhari No. 6306", faedah: "Barangsiapa mengucapkannya di siang hari dengan yakin lalu mati...", target: 1, current: 1 },
                    { title: "9. Doa 4 Malaikat (4x)", arab: "اَللَّهُمَّ إِنِّيْ أَصْبَحْتُ أُشْهِدُكَ وَأُشْهِدُ حَمَلَةَ عَرْشِكَ، وَمَلاَئِكَتَكَ وَجَمِيْعَ خَلْقِكَ، أَنَّكَ أَنْتَ اللهُ لاَ إِلَـهَ إِلاَّ أَنْتَ وَحْدَكَ لاَ شَرِيْكَ لَكَ، وَأَنَّ مُحَمَّدًا عَبْدُكَ وَرَسُوْلُكَ", arti: "Ya Allah, sesungguhnya aku di waktu pagi ini mempersaksikan Engkau, malaikat pemikul 'Arsy-Mu, malaikat-malaikat-Mu dan seluruh makhluk-Mu, bahwa sesungguhnya Engkau adalah Allah, tiada ilah (yang berhak disembah) kecuali Engkau semata, tiada sekutu bagi-Mu dan sesungguhnya Muhammad adalah hamba dan utusan-Mu.", latin: "Allahumma inni ash-bahtu usy-hiduka wa usy-hidu hamalata 'arsyika, wa malaa-ikataka wa jamii'a kholqika, annaka antallahu laa ilaha illa anta wahdaka laa syariika laka, wa anna Muhammadan 'abduka wa rosuuluk.", ref: "HR. Abu Daud No. 5069", faedah: "Barangsiapa membacanya 4x pagi & petang, Allah bebaskan tubuhnya dari api neraka.", target: 4, current: 4 },
                    { title: "10. Doa Perlindungan (Afiyat)", arab: "اَللَّهُمَّ إِنِّيْ أَسْأَلُكَ الْعَفْوَ وَالْعَافِيَةَ فِي الدُّنْيَا وَاْلآخِرَةِ، اَللَّهُمَّ إِنِّيْ أَسْأَلُكَ الْعَفْوَ وَالْعَافِيَةَ فِي دِيْنِيْ وَدُنْيَايَ وَأَهْلِيْ وَمَالِيْ، اَللَّهُمَّ اسْتُرْ عَوْرَاتِيْ وَآمِنْ رَوْعَاتِيْ، اَللَّهُمَّ احْفَظْنِيْ مِنْ بَيْنِ يَدَيَّ، وَمِنْ خَلْفِيْ، وَعَنْ يَمِيْنِيْ وَعَنْ شِمَالِيْ، وَمِنْ فَوْقِيْ، وَأَعُوْذُ بِعَظَمَتِكَ أَنْ أُغْتَالَ مِنْ تَحْتِيْ", arti: "Ya Allah, sesungguhnya aku memohon kebajikan dan keselamatan di dunia dan akhirat. Ya Allah, sesungguhnya aku memohon kebajikan dan keselamatan dalam agama, dunia, keluarga dan hartaku. Ya Allah, tutupilah auratku (aib) dan tenteramkanlah aku dari rasa takut. Ya Allah, peliharalah aku dari muka, belakang, kanan, kiri dan atasku. Aku berlindung dengan kebesaran-Mu, agar aku tidak disambar dari bawahku.", latin: "Allahumma innii as-alukal 'afwa wal 'aafiyah fid-dunya wal aakhiroh, allahumma innii as-alukal 'afwa wal 'aafiyah fii diinii wa dunya-ya wa ahlii wa maalii, allahumas-tur 'awrootii wa aamin row'aatii, allahummahfazhnii min baini yadayya, wa min kholfii, wa 'an yamiinii wa 'an syimaalii, wa min fauqii, wa a'udzu bi 'azhomatika an ughtaala min tahtii.", ref: "HR. Abu Daud & Ibnu Majah", faedah: "Doa perlindungan yang tidak pernah ditinggalkan Rasulullah SAW...", target: 1, current: 1 },
                    { title: "11. Doa Penyerahan Diri", arab: "اَللَّهُمَّ عَالِمَ الْغَيْبِ وَالشَّهَادَةِ فَاطِرَ السَّمَاوَاتِ وَاْلأَرْضِ، رَبَّ كُلِّ شَيْءٍ وَمَلِيْكَهُ، أَشْهَدُ أَنْ لاَ إِلَـهَ إِلاَّ أَنْتَ، أَعُوْذُ بِكَ مِنْ شَرِّ نَفْسِيْ، وَمِنْ شَرِّ الشَّيْطَانِ وَشِرْكِهِ، وَأَنْ أَقْتَرِفَ عَلَى نَفْسِيْ سُوْءًا أَوْ أَجُرَّهُ إِلَى مُسْلِمٍ", arti: "Ya Allah, Yang Maha Mengetahui yang ghaib dan yang nyata, wahai Pencipta langit dan bumi, Rabb segala sesuatu dan yang merajainya. Aku bersaksi bahwa tidak ada ilah (yang berhak disembah) kecuali Engkau. Aku berlindung kepada-Mu dari kejahatan diriku, kejahatan syaitan dan balatentaranya, dan aku berlindung kepada-Mu dari berbuat kejelekan terhadap diriku atau menyeretnya kepada seorang muslim.", latin: "Allahumma 'aalimal ghoybi wasy syahaadah faathiros-samaawaati wal ardh, robba kulli syai-in wa maliikah, asyhadu an laa ilaha illa anta, a'udzu bika min syarri nafsii, wa min syarrisy-syaithooni wa syirkihi, wa an aqtaroifa 'alaa nafsii suu-an au ajurrohuu ilaa muslim.", ref: "HR. Tirmidzi & Abu Daud", faedah: "Diajarkan Rasulullah SAW kepada Abu Bakar RA...", target: 1, current: 1 },
                    { title: "12. Bismillahilladzi (3x)", arab: "بِسْمِ اللَّهِ الَّذِى لاَ يَضُرُّ مَعَ اسْمِهِ شَىْءٌ فِى الأَرْضِ وَلاَ فِى السَّمَاءِ وَهُوَ السَّمِيعُ الْعَلِيمُ", arti: "Dengan nama Allah yang bila disebut, segala sesuatu di bumi dan langit tidak akan berbahaya, Dia-lah Yang Maha Mendengar lagi Maha Mengetahui.", latin: "Bismillahilladzi laa yadhurru ma'asmihii syai-un fil ardhi wa laa fis-samaa' wa huwas-samii'ul 'aliim.", ref: "HR. Abu Daud, Tirmidzi, Ibnu Majah", faedah: "Tidak ada sesuatupun yang membahayakannya.", target: 3, current: 3 },
                    { title: "13. Doa Ridho (3x)", arab: "رَضِيْتُ بِاللهِ رَبًّا، وَبِاْلإِسْلاَمِ دِيْنًا، وَبِمُحَمَّدٍ صَلَّى اللهُ عَلَيْهِ وَسَلَّمَ نَبِيًّا", arti: "Aku ridha Allah sebagai Rabb, Islam sebagai agama dan Muhammad shallallahu 'alaihi wa sallam sebagai nabi.", latin: "Rodhiitu billaahi robbaa, wa bil-islaami diinaa, wa bi Muhammadin shallallahu 'alaihi wa sallama nabiyyaa.", ref: "HR. Abu Daud, Tirmidzi, An-Nasa'i", faedah: "Pantas baginya mendapatkan ridha Allah.", target: 3, current: 3 },
                    { title: "14. Yaa Hayyu Yaa Qoyyum", arab: "يَا حَيُّ يَا قَيُّوْمُ بِرَحْمَتِكَ أَسْتَغِيْثُ، أَصْلِحْ لِيْ شَأْنِيْ كُلَّهُ وَلاَ تَكِلْنِيْ إِلَى نَفْسِيْ طَرْفَةَ عَيْنٍ", arti: "Wahai Rabb Yang Maha Hidup, wahai Rabb Yang Berdiri Sendiri tidak butuh segala sesuatu, dengan rahmat-Mu aku minta pertolongan, perbaikilah segala urusanku dan jangan diserahkan kepadaku sekali pun sekejap mata.", latin: "Yaa Hayyu Yaa Qoyyum, bi-rohmatika as-taghiits, ash-lih lii sya'nii kullahu wa laa takilnii ilaa nafsii thorfata 'ain.", ref: "HR. Al Hakim", faedah: "Diajarkan oleh Nabi supaya diamalkan pagi dan petang.", target: 1, current: 1 },
                    { title: "15. Dzikir Fitrah", arab: "أَصْبَحْنَا عَلَى فِطْرَةِ اْلإِسْلاَمِ وَعَلَى كَلِمَةِ اْلإِخْلاَصِ، وَعَلَى دِيْنِ نَبِيِّنَا مُحَمَّدٍ صَلَّى اللهُ عَلَيْهِ وَسَلَّمَ، وَعَلَى مِلَّةِ أَبِيْنَا إِبْرَاهِيْمَ، حَنِيْفًا مُسْلِمًا وَمَا كَانَ مِنَ الْمُشْرِكِيْنَ", arti: "Di waktu pagi kami memegang agama Islam, kalimat ikhlas, agama Nabi kami Muhammad shallallahu 'alaihi wa sallam, dan agama bapak kami Ibrahim, yang berdiri di atas jalan yang lurus, muslim dan tidak tergolong orang-orang musyrik.", latin: "Ash-bahnaa 'ala fithrotil islaam, wa 'alaa kalimatil ikhlaash, wa 'alaa diini nabiyyinaa Muhammadin shallallahu 'alaihi wa sallam, wa 'alaa millati abiinaa Ibroohiima haniifam muslimaw wa maa kaana minal musyrikiin.", ref: "HR. Ahmad No. 15360", faedah: "Meneguhkan tauhid di pagi hari.", target: 1, current: 1 },
                    { title: "16. Tasbih Pagi (100x)", arab: "سُبْحَانَ اللهِ وَبِحَمْدِهِ", arti: "Maha suci Allah, aku memuji-Nya.", latin: "Subhanallah wa bi-hamdih", ref: "HR. Muslim No. 2692", faedah: "Pahala besar di hari kiamat.", target: 100, current: 100 },
                    { title: "17. Tahlil (10x atau 100x)", arab: "لاَ إِلَـهَ إِلاَّ اللهُ وَحْدَهُ لاَ شَرِيْكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيْرُ", arti: "Tidak ada ilah yang berhak disembah selain Allah semata, tidak ada sekutu bagi-Nya. Bagi-Nya kerajaan dan segala pujian. Dia-lah yang berkuasa atas segala sesuatu.", latin: "Laa ilaha illallah wahdahu laa syarika lah, lahul mulku wa lahul hamdu wa huwa 'ala kulli syai-in qodiir.", ref: "HR. Bukhari & Muslim", faedah: "Mendapat pahala semisal memerdekakan 10 budak...", target: 10, current: 10 },
                    { title: "18. Dzikir Juwairiyah (3x)", arab: "سُبْحَانَ اللهِ وَبِحَمْدِهِ: عَدَدَ خَلْقِهِ، وَرِضَا نَفْسِهِ، وَزِنَةَ عَرْشِهِ وَمِدَادَ كَلِمَاتِهِ", arti: "Maha Suci Allah, aku memujiNya sebanyak makhluk-Nya, sejauh kerelaan-Nya, seberat timbangan 'Arsy-Nya dan sebanyak tinta tulisan kalimat-Nya.", latin: "Subhanallah wa bi-hamdih, 'adada kholqih, wa ridhoo nafsih, wa zinata 'arsyih, wa midaada kalimaatih.", ref: "HR. Muslim No. 2726", faedah: "Pahalanya mengalahkan dzikir yang dibaca dari Shubuh sampai waktu Dhuha.", target: 3, current: 3 },
                    { title: "19. Ilmu Bermanfaat", arab: "اَللَّهُمَّ إِنِّيْ أَسْأَلُكَ عِلْمًا نَافِعًا، وَرِزْقًا طَيِّبًا، وَعَمَلاً مُتَقَبَّلاً", arti: "Ya Allah, sungguh aku memohon kepada-Mu ilmu yang bermanfaat (bagi diriku dan orang lain), rizki yang halal dan amal yang diterima (di sisi-Mu dan mendapatkan ganjaran yang baik).", latin: "Allahumma innii as-aluka 'ilman naafi'aw wa rizqon thoyyibaw wa 'amalan mutaqobbalaa.", ref: "HR. Ibnu Majah No. 925", faedah: "Dibaca setelah salam shalat Shubuh.", target: 1, current: 1 },
                    { title: "20. Istighfar (100x)", arab: "أَسْتَغْفِرُ اللهَ وَأَتُوْبُ إِلَيْهِ", arti: "Aku memohon ampun kepada Allah dan bertobat kepada-Nya.", latin: "Astagh-firullah wa atuubu ilaih.", ref: "HR. Bukhari & Muslim", faedah: "Rasulullah SAW beristighfar 100 kali dalam sehari.", target: 100, current: 100 }
                ],
                petang: [
                    { title: "1. Ta'awudz", arab: "أَعُوذُ بِاللَّهِ مِنَ الشَّيْطَانِ الرَّجِيمِ", arti: "Aku berlindung kepada Allah dari godaan syaitan yang terkutuk.", latin: "A'udzu billahi minasy syaithonir rojiim.", ref: "QS. An-Nahl: 98", faedah: "Memohon perlindungan sebelum memulai dzikir.", target: 1, current: 1 },
                    { title: "2. Ayat Kursi", arab: "ٱللَّهُ لَآ إِلَٰهَ إِلَّا هُوَ ٱلْحَىُّ ٱلْقَيُّومُ ۚ لَا تَأْخُذُهُۥ سِنَةٌ وَلَا نَوْمٌ ۚ لَّهُۥ مَا فِى ٱلسَّمَٰوَٰتِ وَمَا فِى ٱلْأَرْضِ ۗ مَن ذَا ٱلَّذِى يَشْفَعُ عِندَهُۥٓ إِلَّا بِإِذْنِهِۦ ۚ يَعْلَمُ مَا بَيْنَ أَيْدِيهِمْ وَمَا خَلْفَهُمْ ۖ وَلَا يُحِيطُونَ بِشَىْءٍ مِّنْ عِلْمِهِۦٓ إِلَّا بِمَا شَآءَ ۚ وَسِعَ كُرْسِيُّهُ ٱلسَّمَٰوَٰتِ وَٱلْأَرْضَ ۖ وَلَا يَئُودُهُۥ حِفْظُهُمَا ۚ وَهُوَ ٱلْعَلِىُّ ٱلْعَظِيمُ ۝٢٥٥", arti: "Allah, tidak ada ilah (yang berhak disembah) melainkan Dia Yang Hidup kekal lagi terus menerus mengurus (makhluk-Nya), tidak mengantuk dan tidak tidur. Kepunyaan-Nya apa yang di langit dan di bumi. Tiada yang dapat memberi syafa'at di sisi Allah tanpa izin-Nya. Allah mengetahui apa-apa yang di hadapan mereka dan di belakang mereka, dan mereka tidak mengetahui apa-apa dari ilmu Allah melainkan apa yang dikehendaki-Nya. Kursi Allah meliputi langit dan bumi. Dan Allah tidak merasa berat memelihara keduanya, dan Allah Maha Tinggi lagi Maha Besar.", latin: "Allahu laa ilaaha illa huwal hayyul qayyum. Laa ta'khudzuhuu sinatuw wa laa naum. Lahuu maa fis-samaawaati wa maa fil ardh. Man dzal-ladzii yasyfa'u 'indahuu illaa bi-idznih. Ya'lamu maa baina aidiihim wa maa khalfahum. Wa laa yuhiithuuna bi-syai-im min 'ilmihii illaa bi maa syaa-a. Wasi'a kursiyyuhus-samaawaati wal ardh. Wa laa ya-uuduhuu hifzhuhumaa. Wa huwal 'aliyyul 'azhiim.", ref: "QS. Al-Baqarah: 255", faedah: "Siapa yang membacanya ketika sore, maka ia akan dilindungi dari gangguan jin hingga pagi.", target: 1, current: 1 },
                    { title: "3. Surah Al-Ikhlas (3x)", arab: "قُلْ هُوَ ٱللَّهُ أَحَدٌ ۝١ ٱللَّهُ ٱلصَّمَدُ ۝٢ لَمْ يَلِدْ وَلَمْ يُولَدْ ۝٣ وَلَمْ يَكُن لَّهُۥ كُفُوًا أَحَدٌۢ ۝٤", arti: "Katakanlah: Dialah Allah, Yang Maha Esa. Allah adalah Ilah yang bergantung kepada-Nya segala urusan. Dia tiada beranak dan tiada pula diperanakkan. Dan tidak ada seorang pun yang setara dengan Dia.", latin: "Qul huwallahu ahad (1) Allahus shomad (2) Lam yalid wa lam yuulad (3) Wa lam yakul lahuu kufuwan ahad (4)", ref: "QS. Al Ikhlas: 1-4", faedah: "Mencukupkan dari segala sesuatu.", target: 3, current: 3 },
                    { title: "4. Surah Al-Falaq (3x)", arab: "قُلْ أَعُوذُ بِرَبِّ ٱلْفَلَقِ ۝١ مِن شَرِّ مَا خَلَقَ ۝٢ وَمِن شَرِّ غَاسِقٍ إِذَا وَقَبَ ۝٣ وَمِن شَرِّ ٱلنَّفَّٰثَٰتِ فِى ٱلْعُقَدِ ۝٤ وَمِن شَرِّ حَاسِدٍ إِذَا حَسَدَ ۝٥", arti: "Katakanlah: Aku berlindung kepada Rabb yang menguasai Shubuh, dari kejahatan makhluk-Nya, dan dari kejahatan malam apabila telah gelap gulita, dan dari kejahatan wanita-wanita tukang sihir yang menghembus pada buhul-buhul, dan dari kejahatan orang yang dengki apabila ia dengki.", latin: "Qul a'udzu bi rabbil-falaq (1) Min syarri maa kholaq (2) Wa min syarri ghaasiqin idza waqab (3) Wa min syarrin naffaatsaati fil 'uqad (4) Wa min syarri haasidin idza hasad (5)", ref: "QS. Al Falaq: 1-5", faedah: "Perlindungan dari kejahatan makhluk.", target: 3, current: 3 },
                    { title: "5. Surah An-Nas (3x)", arab: "قُلْ أَعُوذُ بِرَبِّ ٱلنَّاسِ ۝١ مَلِكِ ٱلنَّاسِ ۝٢ إِلَٰهِ ٱلنَّاسِ ۝٣ مِن شَرِّ ٱلْوَسْوَاسِ ٱلْخَنَّاسِ ۝٤ ٱلَّذِى يُوَسْوِسُ فِى صُدُورِ ٱلنَّاسِ ۝٥ مِنَ ٱلْجِنَّةِ وَٱلنَّاسِ ۝٦", arti: "Katakanlah: Aku berlindung kepada Rabb manusia, Raja manusia, Sembahan manusia, dari kejahatan (bisikan) syaitan yang biasa bersembunyi, yang membisikkan (kejahatan) ke dalam dada manusia, dari jin dan manusia.", latin: "Qul a'udzu bi rabbin-naas (1) Malikin-naas (2) Ilaahin-naas (3) Min syarril waswaasil khannaas (4) Alladzii yuwaswisu fii shuduurin-naas (5) Minal jinnati wan-naas (6)", ref: "QS. An Naas: 1-6", faedah: "Perlindungan dari bisikan syaitan.", target: 3, current: 3 },
                    { title: "6. Doa Sore Hari", arab: "أَمْسَيْنَا وَأَمْسَى الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ، لاَ إِلَـهَ إِلاَّ اللهُ وَحْدَهُ لاَ شَرِيْكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيْرُ. رَبِّ أَسْأَلُكَ خَيْرَ مَا فِيْ هَذِهِ اللَّيْلَةِ وَخَيْرَ مَا بَعْدَهَا، وَأَعُوْذُ بِكَ مِنْ شَرِّ مَا فِيْ هَذِهِ اللَّيْلَةِ وَشَرِّ مَا بَعْدَهَا، رَبِّ أَعُوْذُ بِكَ مِنَ الْكَسَلِ وَسُوْءِ الْكِبَرِ، رَبِّ أَعُوْذُ بِكَ مِنْ عَذَابٍ فِي النَّارِ وَعَذَابٍ فِي الْقَبْرِ", arti: "Kami telah memasuki waktu petang dan kerajaan hanya milik Allah, segala puji bagi Allah. Tidak ada ilah (yang berhak disembah) kecuali Allah semata, tiada sekutu bagi-Nya. Milik Allah kerajaan dan bagi-Nya pujian. Dia-lah Yang Maha Kuasa atas segala sesuatu. Wahai Rabbku, aku mohon kepada-Mu kebaikan di malam ini dan kebaikan sesudahnya. Aku berlindung kepada-Mu dari kejahatan malam ini dan kejahatan sesudahnya. Wahai Rabbku, aku berlindung kepada-Mu dari kemalasan dan kejelekan di hari tua. Wahai Rabbku, aku berlindung kepada-Mu dari siksaan di neraka dan siksaan di alam kubur.", latin: "Amsaynaa wa amsal mulku lillah, walhamdulillah, laa ilaha illallah wahdahu laa syarika lah, lahul mulku walahul hamdu wa huwa 'ala kulli syai-in qodiir. Robbi as-aluka khoiro maa fii haadzihil lailati wa khoiro maa ba'dahaa, wa a'udzu bika min syarri maa fii haadzihil lailati wa syarri maa ba'dahaa, Robbi a'udzu bika minal kasali wa suu-il kibari, Robbi a'udzu bika min 'adzaabin fin naari wa 'adzaabin fil qobri.", ref: "HR. Muslim No. 2723", faedah: "Meminta kebaikan malam ini...", target: 1, current: 1 },
                    { title: "7. Doa Syukur Sore", arab: "اَللَّهُمَّ بِكَ أَمْسَيْنَا، وَبِكَ أَصْبَحْنَا، وَبِكَ نَحْيَا، وَبِكَ نَمُوْتُ وَإِلَيْكَ الْمَصِيْرُ", arti: "Ya Allah, dengan rahmat-Mu kami memasuki waktu petang dan dengan rahmat-Mu kami memasuki waktu pagi, dengan rahmat-Mu kami hidup dan dengan rahmat-Mu kami mati, dan kepada-Mu tempat kembali.", latin: "Allahumma bika amsaynaa wa bika ash-bahnaa wa bika nahyaa wa bika namuutu wa ilaikal mashiir.", ref: "HR. Tirmidzi No. 3391", faedah: "Bentuk penyerahan diri di waktu petang.", target: 1, current: 1 },
                    { title: "8. Sayyidul Istighfar", arab: "اَللَّهُمَّ أَنْتَ رَبِّيْ لاَ إِلَـهَ إِلاَّ أَنْتَ، خَلَقْتَنِيْ وَأَنَا عَبْدُكَ، وَأَنَا عَلَى عَهْدِكَ وَوَعْدِكَ مَا اسْتَطَعْتُ، أَعُوْذُ بِكَ مِنْ شَرِّ مَا صَنَعْتُ، أَبُوْءُ لَكَ بِنِعْمَتِكَ عَلَيَّ، وَأَبُوْءُ بِذَنْبِيْ فَاغْفِرْ لِيْ فَإِنَّهُ لاَ يَغْفِرُ الذُّنُوْبَ إِلاَّ أَنْتَ", arti: "Ya Allah, Engkau adalah Rabbku, tidak ada ilah (yang berhak disembah) kecuali Engkau, Engkaulah yang menciptakanku. Aku adalah hamba-Mu. Aku akan setia pada perjanjianku dengan-Mu semampuku. Aku berlindung kepada-Mu dari kejelekan yang kuperbuat. Aku mengakui nikmat-Mu kepadaku dan aku mengakui dosaku, oleh karena itu, ampunilah aku. Sesungguhnya tiada yang mengampuni dosa kecuali Engkau.", latin: "Allahumma anta robbii laa ilaha illa anta, kholaqtanii wa anaa 'abduka, wa anaa 'alaa 'ahdika wa wa'dika mas-tatho'tu, a'udzu bika min syarri maa shona'tu, abuu-u laka bi ni'matika 'alayya, wa abuu-u bi dzambii faghfir lii fa-innahu laa yaghfirudz-dzunuuba illa anta.", ref: "HR. Bukhari No. 6306", faedah: "PENGHUNI SURGA.", target: 1, current: 1 },
                    { title: "9. Doa 4 Malaikat (4x)", arab: "اَللَّهُمَّ إِنِّيْ أَمْسَيْتُ أُشْهِدُكَ وَأُشْهِدُ حَمَلَةَ عَرْشِكَ، وَمَلاَئِكَتَكَ وَجَمِيْعَ خَلْقِكَ، أَنَّكَ أَنْتَ اللهُ لاَ إِلَـهَ إِلاَّ أَنْتَ وَحْدَكَ لاَ شَرِيْكَ لَكَ، وَأَنَّ مُحَمَّدًا عَبْدُكَ وَرَسُوْلُكَ", arti: "Ya Allah, sesungguhnya aku di waktu petang ini mempersaksikan Engkau, malaikat pemikul 'Arsy-Mu, malaikat-malaikat-Mu dan seluruh makhluk-Mu, bahwa sesungguhnya Engkau adalah Allah, tiada ilah (yang berhak disembah) kecuali Engkau semata, tiada sekutu bagi-Mu dan sesungguhnya Muhammad adalah hamba dan utusan-Mu.", latin: "Allahumma inni amsaytu usy-hiduka wa usy-hidu hamalata 'arsyika, wa malaa-ikataka wa jamii'a kholqika, annaka antallahu laa ilaha illa anta wahdaka laa syariika laka, wa anna Muhammadan 'abduka wa rosuuluk.", ref: "HR. Abu Daud No. 5069", faedah: "Allah akan membebaskan dirinya dari siksa neraka.", target: 4, current: 4 },
                    { title: "10. Doa Perlindungan (Afiyat)", arab: "اَللَّهُمَّ إِنِّيْ أَسْأَلُكَ الْعَفْوَ وَالْعَافِيَةَ فِي الدُّنْيَا وَاْلآخِرَةِ، اَللَّهُمَّ إِنِّيْ أَسْأَلُكَ الْعَفْوَ وَالْعَافِيَةَ فِي دِيْنِيْ وَدُنْيَايَ وَأَهْلِيْ وَمَالِيْ، اَللَّهُمَّ اسْتُرْ عَوْرَاتِيْ وَآمِنْ رَوْعَاتِيْ، اَللَّهُمَّ احْفَظْنِيْ مِنْ بَيْنِ يَدَيَّ، وَمِنْ خَلْفِيْ، وَعَنْ يَمِيْنِيْ وَعَنْ شِمَالِيْ، وَمِنْ فَوْقِيْ، وَأَعُوْذُ بِعَظَمَتِكَ أَنْ أُغْتَالَ مِنْ تَحْتِيْ", arti: "Ya Allah, sesungguhnya aku memohon kebajikan dan keselamatan di dunia dan akhirat. Ya Allah, sesungguhnya aku memohon kebajikan dan keselamatan dalam agama, dunia, keluarga dan hartaku. Ya Allah, tutupilah auratku (aib) dan tenteramkanlah aku dari rasa takut. Ya Allah, peliharalah aku dari muka, belakang, kanan, kiri dan atasku. Aku berlindung dengan kebesaran-Mu, agar aku tidak disambar dari bawahku.", latin: "Allahumma innii as-alukal 'afwa wal 'aafiyah fid-dunya wal aakhiroh, allahumma innii as-alukal 'afwa wal 'aafiyah fii diinii wa dunya-ya wa ahlii wa maalii, allahumas-tur 'awrootii wa aamin row'aatii, allahummahfazhnii min baini yadayya, wa min kholfii, wa 'an yamiinii wa 'an syimaalii, wa min fauqii, wa a'udzu bi 'azhomatika an ughtaala min tahtii.", ref: "HR. Abu Daud & Ibnu Majah", faedah: "Perlindungan dari berbagai arah.", target: 1, current: 1 },
                    { title: "11. Doa Penyerahan Diri", arab: "اَللَّهُمَّ عَالِمَ الْغَيْبِ وَالشَّهَادَةِ فَاطِرَ السَّمَاوَاتِ وَاْلأَرْضِ، رَبَّ كُلِّ شَيْءٍ وَمَلِيْكَهُ، أَشْهَدُ أَنْ لاَ إِلَـهَ إِلاَّ أَنْتَ، أَعُوْذُ بِكَ مِنْ شَرِّ نَفْسِيْ، وَمِنْ شَرِّ الشَّيْطَانِ وَشِرْكِهِ، وَأَنْ أَقْتَرِفَ عَلَى نَفْسِيْ سُوْءًا أَوْ أَجُرَّهُ إِلَى مُسْلِمٍ", arti: "Ya Allah, Yang Maha Mengetahui yang ghaib dan yang nyata, wahai Pencipta langit dan bumi, Rabb segala sesuatu dan yang merajainya. Aku bersaksi bahwa tidak ada ilah (yang berhak disembah) kecuali Engkau. Aku berlindung kepada-Mu dari kejahatan diriku, kejahatan syaitan dan balatentaranya, dan aku berlindung kepada-Mu dari berbuat kejelekan terhadap diriku atau menyeretnya kepada seorang muslim.", latin: "Allahumma 'aalimal ghoybi wasy syahaadah faathiros-samaawaati wal ardh, robba kulli syai-in wa maliikah, asyhadu an laa ilaha illa anta, a'udzu bika min syarri nafsii, wa min syarrisy-syaithooni wa syirkihi, wa an aqtaroifa 'alaa nafsii suu-an au ajurrohuu ilaa muslim.", ref: "HR. Tirmidzi & Abu Daud", faedah: "Dibaca pagi, petang, dan saat beranjak tidur.", target: 1, current: 1 },
                    { title: "12. Bismillahilladzi (3x)", arab: "بِسْمِ اللَّهِ الَّذِى لاَ يَضُرُّ مَعَ اسْمِهِ شَىْءٌ فِى الأَرْضِ وَلاَ فِى السَّمَاءِ وَهُوَ السَّمِيعُ الْعَلِيمُ", arti: "Dengan nama Allah yang bila disebut, segala sesuatu di bumi dan langit tidak akan berbahaya, Dia-lah Yang Maha Mendengar lagi Maha Mengetahui.", latin: "Bismillahilladzi laa yadhurru ma'asmihii syai-un fil ardhi wa laa fis-samaa' wa huwas-samii'ul 'aliim.", ref: "HR. Abu Daud & Tirmidzi", faedah: "Tidak akan ada bahaya.", target: 3, current: 3 },
                    { title: "13. Doa Ridho (3x)", arab: "رَضِيْتُ بِاللهِ رَبًّا، وَبِاْلإِسْلاَمِ دِيْنًا، وَبِمُحَمَّدٍ صَلَّى اللهُ عَلَيْهِ وَسَلَّمَ نَبِيًّا", arti: "Aku ridha Allah sebagai Rabb, Islam sebagai agama dan Muhammad shallallahu 'alaihi wa sallam sebagai nabi.", latin: "Rodhiitu billaahi robbaa, wa bil-islaami diinaa, wa bi Muhammadin shallallahu 'alaihi wa sallama nabiyyaa.", ref: "HR. Abu Daud & Tirmidzi", faedah: "Pantas baginya mendapatkan ridha Allah.", target: 3, current: 3 },
                    { title: "14. Yaa Hayyu Yaa Qoyyum", arab: "يَا حَيُّ يَا قَيُّوْمُ بِرَحْمَتِكَ أَسْتَغِيْثُ، أَصْلِحْ لِيْ شَأْنِيْ كُلَّهُ وَلاَ تَكِلْنِيْ إِلَى نَفْسِيْ طَرْفَةَ عَيْنٍ", arti: "Wahai Rabb Yang Maha Hidup, wahai Rabb Yang Berdiri Sendiri tidak butuh segala sesuatu, dengan rahmat-Mu aku minta pertolongan, perbaikilah segala urusanku dan jangan diserahkan kepadaku sekali pun sekejap mata.", latin: "Yaa Hayyu Yaa Qoyyum, bi-rohmatika as-taghiits, ash-lih lii sya'nii kullahu wa laa takilnii ilaa nafsii thorfata 'ain.", ref: "HR. Al Hakim", faedah: "Memperbaiki segala urusan.", target: 1, current: 1 },
                    { title: "15. Tasbih Sore (100x)", arab: "سُبْحَانَ اللهِ وَبِحَمْدِهِ", arti: "Maha suci Allah, aku memuji-Nya.", latin: "Subhanallah wa bi-hamdih", ref: "HR. Muslim No. 2692", faedah: "Pahala besar di hari kiamat.", target: 100, current: 100 },
                    { title: "16. Tahlil (10x atau 100x)", arab: "لاَ إِلَـهَ إِلاَّ اللهُ وَحْدَهُ لاَ شَرِيْكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيْرُ", arti: "Tidak ada ilah yang berhak disembah selain Allah semata, tidak ada sekutu bagi-Nya. Bagi-Nya kerajaan dan segala pujian. Dia-lah yang berkuasa atas segala sesuatu.", latin: "Laa ilaha illallah wahdahu laa syarika lah, lahul mulku wa lahul hamdu wa huwa 'ala kulli syai-in qodiir.", ref: "HR. Bukhari & Muslim", faedah: "Pahala memerdekakan budak.", target: 10, current: 10 },
                    { title: "17. A'udzu Bikalimatillah (3x)", arab: "أَعُوْذُ بِكَلِمَاتِ اللهِ التَّامَّاتِ مِنْ شَرِّ مَا خَلَقَ", arti: "Aku berlindung dengan kalimat-kalimat Allah yang sempurna dari kejahatan makhluk yang diciptakan-Nya.", latin: "A’udzu bikalimaatillahit-taammaati min syarri maa kholaq.", ref: "HR. Ahmad 2: 290", faedah: "Terlindung dari racun/binatang buas.", target: 3, current: 3 }
                ],
               salat: [
                    { title: "1. Istighfar (3x)", arab: "أَسْتَغْفِرُ اللهَ", arti: "Aku memohon ampun kepada Allah.", latin: "Astaghfirullah", ref: "HR. Muslim No. 591", faedah: "Sunnah Nabi SAW setiap selesai salam shalat fardhu.", target: 3, current: 3 },
                    { title: "2. Allahumma Antas Salam", arab: "اَللَّهُمَّ أَنْتَ السَّلاَمُ وَمِنْكَ السَّلاَمُ تَبَارَكْتَ يَا ذَا الْجَلاَلِ وَاْلإِكْرَامِ", arti: "Ya Allah, Engkau-lah As-Salam (Yang Maha Sejahtera) dan dari-Mu-lah kesejahteraan. Maha Suci Engkau, wahai Rabb Yang memiliki kebesaran dan kemuliaan.", latin: "Allahumma antas salaam wa minkas salaam tabaarokta yaa dzal jalaali wal ikroom.", ref: "HR. Muslim No. 591", faedah: "Memohon keberkahan dan keselamatan.", target: 1, current: 1 },
                    { title: "3. Tahlil & Doa Laa Mani'a", arab: "لاَ إِلَـهَ إِلاَّ اللهُ وَحْدَهُ لاَ شَرِيْكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيْرُ، اَللَّهُمَّ لاَ مَانِعَ لِمَا أَعْطَيْتَ، وَلاَ مُعْطِيَ لِمَا مَنَعْتَ، وَلاَ يَنْفَعُ ذَا الْجَدِّ مِنْكَ الْجَدُّ", arti: "Tiada Tuhan selain Allah, Yang Maha Esa, tiada sekutu bagi-Nya. Bagi-Nya kerajaan dan pujian. Dia Maha Kuasa atas segala sesuatu. Ya Allah, tidak ada yang dapat mencegah apa yang Engkau berikan dan tidak ada yang dapat memberi apa yang Engkau cegah. Tidak berguna kekayaan dan kemuliaan itu bagi pemiliknya (selain iman dan amal shalihnya). Dari Engkau-lah segala kekayaan dan kemuliaan.", latin: "Laa ilaha illallah wahdahu laa syarika lah, lahul mulku wa lahul hamdu wa huwa 'ala kulli syai-in qodiir. Allahumma laa maani'a limaa a'thoyta, wa laa mu'thiya limaa mana'ta, wa laa yanfa'u dzal jaddi minkal jaddu.", ref: "HR. Bukhari No. 844", faedah: "Pengakuan tauhid dan kepasrahan total.", target: 1, current: 1 },
                    { title: "4. Tahlil & Doa Laa Haula", arab: "لاَ إِلَـهَ إِلاَّ اللهُ وَحْدَهُ لاَ شَرِيْكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيْرُ. لاَ حَوْلَ وَلاَ قُوَّةَ إِلاَّ بِاللهِ، لاَ إِلَـهَ إِلاَّ اللهُ، وَلاَ نَعْبُدُ إِلاَّ إِيَّاهُ، لَهُ النِّعْمَةُ وَلَهُ الْفَضْلُ وَلَهُ الثَّنَاءُ الْحَسَنُ، لاَ إِلَـهَ إِلاَّ اللهُ مُخْلِصِيْنَ لَهُ الدِّيْنَ وَلَوْ كَرِهَ الْكَافِرُوْنَ", arti: "Tidak ada ilah yang berhak disembah selain Allah semata, tidak ada sekutu bagi-Nya. Milik-Nya lah segala kerajaan dan ujian. Dia Maha Kuasa atas segala sesuatu. Tidak ada daya dan upaya kecuali dengan pertolongan Allah. Tidak ada ilah yang berhak disembah kecuali Allah. Kami tidak menyembah kecuali kepada-Nya. Milik-Nya lah segala kenikmatan, karunia, dan sanjungan yang baik. Tidak ada ilah yang berhak disembah kecuali Allah, dengan memurnikan ibadah hanya kepada-Nya, sekalipun orang-orang kafir membencinya.", latin: "Laa ilaha illallah wahdahu laa syarika lah, lahul mulku wa lahul hamdu wa huwa 'ala kulli syai-in qodiir. Laa haula wa laa quwwata illa billah, laa ilaha illallah, wa laa na'budu illaa iyyaah, lahun ni'matu wa lahul fadhlu wa lahuts tsanaa-ul hasan, laa ilaha illallah mukhlishiina lahud diina walau karihal kaafiruun.", ref: "HR. Muslim No. 594", faedah: "Meneguhkan keimanan.", target: 1, current: 1 },
                    { title: "5. Tasbih (33x)", arab: "سُبْحَانَ اللهِ", arti: "Maha Suci Allah", latin: "Subhanallah", ref: "HR. Muslim No. 597", faedah: "Menyucikan Allah.", target: 33, current: 33 },
                    { title: "6. Tahmid (33x)", arab: "الْحَمْدُ لِلَّهِ", arti: "Segala Puji bagi Allah", latin: "Alhamdulillah", ref: "HR. Muslim No. 597", faedah: "Memuji Allah.", target: 33, current: 33 },
                    { title: "7. Takbir (33x)", arab: "اللهُ أَكْبَرُ", arti: "Allah Maha Besar", latin: "Allahu Akbar", ref: "HR. Muslim No. 597", faedah: "Mengagungkan Allah.", target: 33, current: 33 },
                    { title: "8. Tahlil Penutup (Genap 100)", arab: "لاَ إِلَـهَ إِلاَّ اللهُ وَحْدَهُ لاَ شَرِيْكَ لَهُ، لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ وَهُوَ عَلَى كُلِّ شَيْءٍ قَدِيْرُ", arti: "Tidak ada ilah yang berhak disembah selain Allah semata, tidak ada sekutu bagi-Nya. Bagi-Nya kerajaan dan segala pujian. Dia-lah yang berkuasa atas segala sesuatu.", latin: "Laa ilaha illallah wahdahu laa syarika lah, lahul mulku wa lahul hamdu wa huwa 'ala kulli syai-in qodiir.", ref: "HR. Muslim No. 597", faedah: "Diampuni dosa-dosanya walaupun sebanyak buih di lautan.", target: 1, current: 1 },
                    { title: "9. Ayat Kursi", arab: "ٱللَّهُ لَآ إِلَٰهَ إِلَّا هُوَ ٱلْحَىُّ ٱلْقَيُّومُ ۚ لَا تَأْخُذُهُۥ سِنَةٌ وَلَا نَوْمٌ ۚ لَّهُۥ مَا فِى ٱلسَّمَٰوَٰتِ وَمَا فِى ٱلْأَرْضِ ۗ مَن ذَا ٱلَّذِى يَشْفَعُ عِندَهُۥٓ إِلَّا بِإِذْنِهِۦ ۚ يَعْلَمُ مَا بَيْنَ أَيْدِيهِمْ وَمَا خَلْفَهُمْ ۖ وَلَا يُحِيطُونَ بِشَىْءٍ مِّنْ عِلْمِهِۦٓ إِلَّا بِمَا شَآءَ ۚ وَسِعَ كُرْسِيُّهُ ٱلسَّمَٰوَٰتِ وَٱلْأَرْضَ ۖ وَلَا يَئُودُهُۥ حِفْظُهُمَا ۚ وَهُوَ ٱلْعَلِىُّ ٱلْعَظِيمُ ۝٢٥٥", arti: "Allah, tidak ada ilah (yang berhak disembah) melainkan Dia Yang Hidup kekal lagi terus menerus mengurus (makhluk-Nya), tidak mengantuk dan tidak tidur. Kepunyaan-Nya apa yang di langit dan di bumi. Tiada yang dapat memberi syafa'at di sisi Allah tanpa izin-Nya. Allah mengetahui apa-apa yang di hadapan mereka dan di belakang mereka, dan mereka tidak mengetahui apa-apa dari ilmu Allah melainkan apa yang dikehendaki-Nya. Kursi Allah meliputi langit dan bumi. Dan Allah tidak merasa berat memelihara keduanya, dan Allah Maha Tinggi lagi Maha Besar.", latin: "Allahu laa ilaaha illa huwal hayyul qayyum. Laa ta'khudzuhuu sinatuw wa laa naum. Lahuu maa fis-samaawaati wa maa fil ardh. Man dzal-ladzii yasyfa'u 'indahuu illaa bi-idznih. Ya'lamu maa baina aidiihim wa maa khalfahum. Wa laa yuhiithuuna bi-syai-im min 'ilmihii illaa bi maa syaa-a. Wasi'a kursiyyuhus-samaawaati wal ardh. Wa laa ya-uuduhuu hifzhuhumaa. Wa huwal 'aliyyul 'azhiim.", ref: "HR. An-Nasa'i", faedah: "Tidak ada yang menghalanginya masuk surga selain kematian.", target: 1, current: 1 },
                    { title: "10. Surah Al-Ikhlas", arab: "قُلْ هُوَ ٱللَّهُ أَحَدٌ ۝١ ٱللَّهُ ٱلصَّمَدُ ۝٢ لَمْ يَلِدْ وَلَمْ يُولَدْ ۝٣ وَلَمْ يَكُن لَّهُۥ كُفُوًا أَحَدٌۢ ۝٤", arti: "Katakanlah: Dialah Allah, Yang Maha Esa. Allah adalah Ilah yang bergantung kepada-Nya segala urusan. Dia tiada beranak dan tiada pula diperanakkan. Dan tidak ada seorang pun yang setara dengan Dia.", latin: "Qul huwallahu ahad (1) Allahus shomad (2) Lam yalid wa lam yuulad (3) Wa lam yakul lahuu kufuwan ahad (4)", ref: "HR. Abu Daud No. 1523", faedah: "Dibaca 1x setiap selesai shalat.", target: 1, current: 1 },
                    { title: "11. Surah Al-Falaq", arab: "قُلْ أَعُوذُ بِرَبِّ ٱلْفَلَقِ ۝١ مِن شَرِّ مَا خَلَقَ ۝٢ وَمِن شَرِّ غَاسِقٍ إِذَا وَقَبَ ۝٣ وَمِن شَرِّ ٱلنَّفَّٰثَٰتِ فِى ٱلْعُقَدِ ۝٤ وَمِن شَرِّ حَاسِدٍ إِذَا حَسَدَ ۝٥", arti: "Katakanlah: Aku berlindung kepada Rabb yang menguasai Shubuh, dari kejahatan makhluk-Nya, dan dari kejahatan malam apabila telah gelap gulita, dan dari kejahatan wanita-wanita tukang sihir yang menghembus pada buhul-buhul, dan dari kejahatan orang yang dengki apabila ia dengki.", latin: "Qul a'udzu bi rabbil-falaq (1) Min syarri maa kholaq (2) Wa min syarri ghaasiqin idza waqab (3) Wa min syarrin naffaatsaati fil 'uqad (4) Wa min syarri haasidin idza hasad (5)", ref: "HR. Abu Daud No. 1523", faedah: "Dibaca 1x setiap selesai shalat.", target: 1, current: 1 },
                    { title: "12. Surah An-Nas", arab: "قُلْ أَعُوذُ بِرَبِّ ٱلنَّاسِ ۝١ مَلِكِ ٱلنَّاسِ ۝٢ إِلَٰهِ ٱلنَّاسِ ۝٣ مِن شَرِّ ٱلْوَسْوَاسِ ٱلْخَنَّاسِ ۝٤ ٱلَّذِى يُوَسْوِسُ فِى صُدُورِ ٱلنَّاسِ ۝٥ مِنَ ٱلْجِنَّةِ وَٱلنَّاسِ ۝٦", arti: "Katakanlah: Aku berlindung kepada Rabb manusia, Raja manusia, Sembahan manusia, dari kejahatan (bisikan) syaitan yang biasa bersembunyi, yang membisikkan (kejahatan) ke dalam dada manusia, dari jin dan manusia.", latin: "Qul a'udzu bi rabbin-naas (1) Malikin-naas (2) Ilaahin-naas (3) Min syarril waswaasil khannaas (4) Alladzii yuwaswisu fii shuduurin-naas (5) Minal jinnati wan-naas (6)", ref: "HR. Abu Daud No. 1523", faedah: "Dibaca 1x setiap selesai shalat.", target: 1, current: 1 }
                ]
            },

            get currentList() { return this.data[this.activeTab]; },

            decrement(item) { if (item.current > 0) { item.current--; } }
        }
    }
</script>
@endpush
