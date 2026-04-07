@extends('layouts.app')

@push('styles')
<style>
    /* HEADER */
    .header {
        background: #dce6e9;
        padding: 20px 30px;
        border-radius: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .dark .header {
        background: #1a2f23; /* Dark Forest Green for header in dark mode */
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .header h1 { font-size: 24px; color: #2d3436; font-weight: 800; }
    .header p { font-size: 14px; color: #636e72; margin-top: 5px; font-weight: 600; opacity: 0.8; }
    .dark .header h1 { color: #f3f4f6; }
    .dark .header p { color: #94a3b8; }
    .next-prayer { text-align: right; }
    .next-prayer h3 { font-size: 12px; color: #2d3436; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; opacity: 0.7; }
    .next-prayer .timer { font-size: 28px; font-weight: 800; color: #2d3436; letter-spacing: -1px; }
    .dark .next-prayer h3, .dark .next-prayer .timer { color: #f3f4f6; }

    /* GRID UTAMA */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    /* CARD MENU */
    .menu-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .card {
        background: white;
        padding: 20px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: 0.3s;
        cursor: pointer;
        border: 1px solid #bdc3c7;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 140px;
    }
    .dark .card { background: #1e1e1e; border-color: #2d3436; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); border-color: #d4a373; }
    .dark .card:hover { border-color: #d4a373; box-shadow: 0 8px 25px rgba(0,0,0,0.4); }
    .card-icon {
        font-size: 32px;
        color: #d4a373;
        margin-bottom: 15px;
    }
    .card-title {
        background: rgba(226, 212, 193, 0.3);
        padding: 5px 18px;
        border-radius: 20px;
        font-size: 13px;
        color: #2d3436;
        font-weight: 700;
        letter-spacing: 0.5px;
        transition: all 0.3s;
    }
    .dark .card-title { background: rgba(56, 178, 172, 0.1); color: #f3f4f6; }

    /* WIDGET KANAN */
    .right-widget { display: flex; flex-direction: column; gap: 20px; }
    .widget-box {
        background: white; padding: 25px; border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #bdc3c7;
        transition: all 0.3s;
    }
    .dark .widget-box { background: #1e1e1e; border-color: #2d3436; }
    .calendar-title { font-size: 14px; font-weight: 800; margin-bottom: 15px; display: flex; justify-content: space-between; color: #2d3436; }
    .dark .calendar-title { color: #f3f4f6; }
    
    /* Progress Bar */
    .goals-box h4 { font-size: 14px; margin-bottom: 15px; color: #2d3436; font-weight: 700; }
    .dark .goals-box h4 { color: #f3f4f6; }
    .progress-bar {
        width: 100%; background: #f3f4f6; height: 10px; border-radius: 10px; overflow: hidden;
    }
    .dark .progress-bar { background: #2d3436; }
    .progress-fill {
        width: 35%; background: #d4a373; height: 100%; border-radius: 10px;
    }
    .dark .progress-fill { background: #2dd4bf; }

    /* Mood Widget */
    .mood-box {
        background: #dce6e9; padding: 20px; border-radius: 20px;
        display: flex; align-items: start; gap: 15px; font-weight: 600; color: #2d3436;
        border: 1px solid #bdc3c7;
        transition: all 0.3s;
    }
    .dark .mood-box {
        background: #1a2f23; color: #f3f4f6; border-color: #2d3436;
    }
    .mood-box i { color: #d4a373; font-size: 18px; }
    .dark .mood-box i { color: #2dd4bf; }

    @media (max-width: 768px) {
        .dashboard-grid { grid-template-columns: 1fr; }
        .header { flex-direction: column; text-align: center; gap: 15px; }
        .next-prayer { text-align: center; }
    }
</style>
@endpush

@section('content')
    <!-- PWA INSTALL BANNER -->
    <div id="pwa-install-banner" class="hidden md:flex flex-col sm:flex-row shadow-sm" style="background: #E6F3F5; color: #2D5A43; padding: 15px 20px; border-radius: 15px; margin-bottom: 25px; align-items: center; justify-content: space-between; border: 1px solid rgba(45,90,67,0.2);">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;" class="sm:mb-0">
            <i class="fas fa-home" style="font-size: 24px;"></i>
            <div>
                <h4 style="font-weight: 700; font-size: 15px; margin-bottom: 2px;">Instal MahabBa</h4>
                <p style="font-size: 12px; font-weight: 500; opacity: 0.9;">Akses MahabBa lebih cepat dari layar utama HP-mu.</p>
            </div>
        </div>
        <div style="display: flex; gap: 10px; width: 100%; justify-content: flex-end;" class="sm:w-auto">
            <button onclick="dismissPwaInstall()" style="background: transparent; color: #2D5A43; border: 1px solid #2D5A43; padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='rgba(45,90,67,0.1)'" onmouseout="this.style.background='transparent'">Nanti</button>
            <button onclick="installPwa()" style="background: #2D5A43; color: white; border: none; padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#1f4231'" onmouseout="this.style.background='#2D5A43'">Instal Sekarang</button>
        </div>
    </div>

    <div class="header">
        <div>
            <h1>Assalamualaikum, {{ explode(' ', Auth::user()->name)[0] }}</h1> 
            <p id="current-date"></p>
        </div>
        <div class="next-prayer">
            <h3 id="next-prayer-name">NEXT PRAYER: --:--</h3>
            <div class="timer" id="live-clock">00:00:00</div>
        </div>
    </div>

    <div class="dashboard-grid">
        
        <div class="menu-grid">
            <a href="{{ route('prayers.index') }}" class="card" style="text-decoration: none; color: inherit;">
                <div class="card-icon"><i class="fas fa-hands-praying"></i></div>
                <div class="card-title">Salat Wajib</div>
            </a>
            <a href="/dzikir" class="card" style="text-decoration: none; color: inherit;">
                <div class="card-icon"><i class="fas fa-mosque"></i></div>
                <div class="card-title">Dzikir</div>
            </a>
            <a href="{{ route('shalat-sunnah.halaman-sunnah') }}" class="card" style="text-decoration: none; color: inherit;">
                <div class="card-icon"><i class="fas fa-star-and-crescent"></i></div>
                <div class="card-title">Salat Sunnah</div>
            </a>
            <a href="{{ route('quran.index') }}" class="card" style="text-decoration: none; color: inherit;">
                <div class="card-icon"><i class="fas fa-quran"></i></div>
                <div class="card-title">Qur'an</div>
            </a>
            <a href="{{ route('hadith.index') }}" class="card" style="text-decoration: none; color: inherit;">
                <div class="card-icon"><i class="fas fa-book-reader"></i></div>
                <div class="card-title">One Day One Hadis</div>
            </a>
            <a href="{{ route('zakat.index') }}" class="card" style="text-decoration: none; color: inherit;">
                <div class="card-icon"><i class="fa-solid fa-scale-balanced"></i></div>
                <div class="card-title">Kalkulator Zakat</div>
            </a>
        </div>

        <div class="right-widget">
        
            <div class="widget-box" x-data="dashboardCalendar()" x-cloak>
                <div class="calendar-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <span class="text-sm font-bold text-[#2d3436] dark:text-gray-100" x-text="calendarTitle"></span>
                    <div class="flex gap-2">
                            <button type="button" @click="toggleCalendarMode()" class="text-[#d4a373] dark:text-teal-400 hover:text-[#b08558] dark:hover:text-teal-300 transition" title="Switch Mode">
                            <i class="fas fa-sync-alt text-xs"></i>
                            </button>
                            <div class="flex gap-1">
                            <button type="button" @click="prevMonth()" class="text-[#d4a373] dark:text-teal-400 hover:bg-gray-100 dark:hover:bg-gray-800 p-1 rounded"><i class="fas fa-chevron-left text-xs"></i></button>
                            <button type="button" @click="nextMonth()" class="text-[#d4a373] dark:text-teal-400 hover:bg-gray-100 dark:hover:bg-gray-800 p-1 rounded"><i class="fas fa-chevron-right text-xs"></i></button>
                            </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-gray-400 mb-2 uppercase"><div>Mn</div><div>Sn</div><div>Sl</div><div>Rb</div><div>Km</div><div>Jm</div><div>Sb</div></div>
                
                <div class="grid grid-cols-7 gap-1 text-center text-xs font-medium text-gray-600">
                    <template x-for="blank in blanks"><div class="p-1"></div></template>
                    <template x-for="day in daysInMonth">
                        <div class="w-8 h-8 mx-auto flex items-center justify-center rounded-lg transition-all relative group border border-transparent"
                                :class="{
                                'cursor-default hover:bg-[#E3D4C1] dark:hover:bg-teal-900/40 hover:text-[#5A4635] dark:hover:text-teal-200': !isActive(day),
                                'bg-[#1D3557] dark:bg-teal-500 text-white font-bold shadow-md scale-105 border-[#1D3557] dark:border-teal-400': isActive(day),
                                'text-gray-600 dark:text-gray-400': !isActive(day)
                                }">
                            
                            <!-- Main Date -->
                            <span class="text-xs" x-text="getMainDate(day)"></span>
                            
                            <!-- Dual Date -->
                            <div x-show="mode === 'hijri'" 
                                    class="absolute top-0.5 right-0.5 text-[6px] opacity-60 font-normal leading-none">
                                <span x-text="day"></span>
                            </div>

                            <!-- New Month Badge -->
                            <div x-show="mode === 'hijri' && getHijriDay(day) == 1" 
                                    class="absolute -top-1.5 -left-1.5 bg-amber-400 text-white text-[6px] font-bold px-1 py-0.5 rounded-full shadow-sm z-10 whitespace-nowrap">
                                <span x-text="getHijriMonthName(day)"></span>
                            </div>
                        </div>
                    </template>
                </div>
                </div>

            <div class="widget-box goals-box">
                <h4>Weekly Goals</h4>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <div style="text-align: right; font-size: 12px; margin-top: 5px;">35%</div>
            </div>

            <div class="mood-box">
                <i class="fas fa-quote-left"></i>
                <div id="daily-quote-text" style="font-style: italic; line-height: 1.6; opacity: 0.9;" class="dark:text-gray-200">
                    How is your heart today?
                </div>
            </div>
        </div>

    </div>
    
    <!-- MODAL LOCATION HTML -->
    <div id="location-modal" class="modal-overlay">
        <div class="modal-box">
            <div style="font-size: 40px; color: #7f8c8d; margin-bottom: 15px;">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <h3>Izinkan Lokasi</h3>
            <p>Aktifkan lokasi untuk menentukan waktu sholat yang presisi sesuai posisi Anda saat ini.</p>
            <div class="modal-buttons">
                <button class="btn-nanti" onclick="handleDenyByModal()">Nanti Saja</button>
                <button class="btn-setuju" onclick="approveLocation()">Setuju</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // --- DAILY QUOTES SYSTEM ---
    const islamicQuotes = [
        "Amalan yang paling dicintai Allah adalah yang kontinu meski sedikit. (HR. Muslim)",
        "Istiqomah adalah bukti cinta seorang hamba kepada Sang Pencipta.",
        "Dzikir pagi adalah perisai terbaik untuk menghadapi kerasnya dunia.",
        "Jadikan shalat sebagai tempat istirahatmu, bukan bebanmu.",
        "Siapa yang menjaga shalatnya, maka Allah akan menjaga hidupnya.",
        "Al-Qur'an tidak akan meninggalkanmu selama engkau tidak meninggalkannya.",
        "Kesuksesan sejati dimulai dari sujud di waktu Subuh.",
        "Istiqomah di jalan Allah adalah kemuliaan yang tak ternilai.",
        "Lelahmu dalam beribadah akan menjadi lillah yang berbuah jannah.",
        "Pagi yang berkah dimulai dengan doa dan harapan kepada-Nya.",
        "Satu sujud di keheningan malam lebih baik dari dunia dan seisinya.",
        "Barangsiapa memperbaiki hubungannya dengan Allah, Allah perbaiki urusannya.",
        "Istiqomah itu berat, yang ringan itu istirahat. Tapi balasannya Surga.",
        "Jangan biarkan duniamu membuatmu lupa pada tujuan akhiratmu.",
        "Shalat tepat waktu adalah kunci pembuka pintu rezeki hari ini.",
        "Kunci kebahagiaan adalah syukur atas apa yang ada di tangan kita hari ini.",
        "Dunia ini hanya sementara, maka jadikan setiap detiknya bernilai ibadah.",
        "Bukan kesulitan yang membuat kita berhenti, tapi kurangnya istiqomah.",
        "Membaca Al-Qur'an adalah cara terbaik menenangkan hati yang gelisah.",
        "Jangan menunggu waktu luang untuk beribadah, tapi luangkan waktu untuk-Nya.",
        "Setiap langkah menuju masjid adalah penggugur dosa dan peninggi derajat.",
        "Kebaikan yang kecil namun konsisten lebih baik daripada besar tapi terputus.",
        "Hati yang terpaut pada masjid akan mendapatkan naungan-Nya kelak.",
        "Istiqomah dalam doa adalah tanda bahwa engkau percaya pada takdir-Nya.",
        "Sedekah tidak akan mengurangi hartamu, justru memberkahi setiap rupiahnya.",
        "Perbanyaklah shalawat, agar harimu penuh berkah dan syafaat.",
        "Sabar dalam ketaatan memang pahit, namun buahnya sangat manis.",
        "Jangan bandingkan dirimu dengan orang lain, bandingkan dirimu yang kemarin.",
        "Bangunlah saat orang lain tidur (Tahajjud), mintalah saat orang lain lupa.",
        "Semoga hari ini Allah menguatkan langkah kita untuk tetap istiqomah."
    ];

    function updateDailyQuote() {
        const now = new Date();
        // Logika rolling: Ambil index berdasarkan tanggal (1-31)
        const quoteIndex = (now.getDate() - 1) % islamicQuotes.length;
        const quoteElement = document.getElementById('daily-quote-text');
        if(quoteElement) {
            quoteElement.innerText = islamicQuotes[quoteIndex];
        }
    }

    // --- PWA INSTALLATION LOGIC ---
    let deferredPrompt;
    
    window.addEventListener('beforeinstallprompt', (e) => {
        // Mencegah Chrome 67 dan yang lebih lama menampilkan prompt otomatis
        e.preventDefault();
        // Simpan event sehingga bisa dipicu nanti
        deferredPrompt = e;
        
        // Update UI beri tahu pengguna mereka dapat menginstal PWA
        const banner = document.getElementById('pwa-install-banner');
        if (banner && !localStorage.getItem('pwa_dismissed')) {
            banner.style.display = 'flex';
            // Perbaikan tampilan responsif dipaksa via style JS karena tailwind .hidden bentrok dengan display:flex
            banner.classList.remove('hidden');
        }
    });

    function installPwa() {
        const banner = document.getElementById('pwa-install-banner');
        banner.style.display = 'none';
        
        if (deferredPrompt) {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('User menerima A2HS prompt');
                } else {
                    console.log('User menolak A2HS prompt');
                }
                deferredPrompt = null;
            });
        }
    }

    function dismissPwaInstall() {
        document.getElementById('pwa-install-banner').style.display = 'none';
        localStorage.setItem('pwa_dismissed', 'yes');
    }

    // 1. Variabel Global (Single Source of Truth)
    let jadwalSholat = { subuh: "--:--", dzuhur: "--:--", ashar: "--:--", maghrib: "--:--", isya: "--:--" };
    let hijriData = null; // Menyimpan data Hijriah dari API
    let gregorianData = null; // Menyimpan data Masehi dari API
    // calendarMode HAPUS saja karena sudah dihandle Alpine

    // 2. Logic Startup
    document.addEventListener("DOMContentLoaded", function() {
        const permission = localStorage.getItem('location_permission');
        if (permission === 'granted') {
            getUniversalPrayerTimes();
        } else {
            document.getElementById('location-modal').style.display = 'flex';
        }
        
        // Timer update Jam tiap detik
        setInterval(updateClock, 1000);
        updateClock(); // Run immediately for clock

        // Jalankan Quote Hari Ini
        updateDailyQuote();
    });

    // 3. User Permission Logic
    function approveLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    localStorage.setItem('location_permission', 'granted');
                    document.getElementById('location-modal').style.display = 'none';
                    fetchPrayerTimes(pos.coords.latitude, pos.coords.longitude);
                },
                () => handleDenyByModal()
            );
        } else {
            alert("Browser tidak mendukung geolokasi.");
            handleDenyByModal();
        }
    }

    function handleDenyByModal() {
        document.getElementById('location-modal').style.display = 'none';
        fetchPrayerTimes(-7.7956, 110.3695); // Default Yogyakarta
    }

    function getUniversalPrayerTimes() {
        navigator.geolocation.getCurrentPosition(
            (pos) => fetchPrayerTimes(pos.coords.latitude, pos.coords.longitude),
            () => handleDenyByModal()
        );
    }

    // 4. Fetch Data (API Aladhan is Single Source of Truth)
    async function fetchPrayerTimes(lat, lng) {
        // Gunakan timestamp sekarang agar lebih akurat jika user buka besoknya tanpa refresh
        const now = new Date();
        const dateStr = `${now.getDate()}-${now.getMonth() + 1}-${now.getFullYear()}`;

        try {
            const response = await fetch(`https://api.aladhan.com/v1/timings/${dateStr}?latitude=${lat}&longitude=${lng}&method=20`);
            const data = await response.json();
            
            // A. Simpan Jadwal Sholat
            const timings = data.data.timings;
            jadwalSholat = {
                subuh: timings.Fajr, dzuhur: timings.Dhuhr, ashar: timings.Asr, maghrib: timings.Maghrib, isya: timings.Isha
            };

            // B. Simpan Data Tanggal (Hijri & Masehi)
            hijriData = data.data.date.hijri;
            gregorianData = data.data.date.gregorian;

            // C. Update UI Statis (Header)
            updateStaticData();

            console.log("Data Terupdate:", data.data);
        } catch (error) {
            console.error("Gagal update data:", error);
        }
    }

    // 5. Update Statis (Dipanggil hanya saat Fetch sukses)
    function updateStaticData() {
        if (!hijriData || !gregorianData) return;

        // --- HEADER DASHBOARD (Tetap Hybrid: Hijri | Masehi) ---
        const tglMasehi = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        const dateString = `${hijriData.day} ${hijriData.month.en} ${hijriData.year} H | ${tglMasehi}`;
        document.getElementById('current-date').innerText = dateString;
    }

    // 6. Update Dinamis (Jam & Next Prayer)
    function updateClock() {
        const now = new Date();
        document.getElementById('live-clock').innerText = now.toLocaleTimeString('id-ID', { hour12: false });

        // Logic Next Prayer
        const currentHM = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');
        let nextP = "Loading"; let nextT = "--:--";

        if (jadwalSholat.subuh !== "--:--") {
            if (currentHM < jadwalSholat.subuh) { nextP = "SUBUH"; nextT = jadwalSholat.subuh; }
            else if (currentHM < jadwalSholat.dzuhur) { 
                nextP = (now.getDay() === 5) ? "JUMAT" : "DZUHUR"; 
                nextT = jadwalSholat.dzuhur; 
            }
            else if (currentHM < jadwalSholat.ashar) { nextP = "ASHAR"; nextT = jadwalSholat.ashar; }
            else if (currentHM < jadwalSholat.maghrib) { nextP = "MAGHRIB"; nextT = jadwalSholat.maghrib; }
            else if (currentHM < jadwalSholat.isya) { nextP = "ISYA"; nextT = jadwalSholat.isya; }
            else { nextP = "SUBUH (BESOK)"; nextT = jadwalSholat.subuh; }
            
            document.getElementById('next-prayer-name').innerText = `NEXT PRAYER: ${nextP} - ${nextT}`;
        }
    }

    // 7. ALPINE JS CALENDAR LOGIC (ADAPTED FOR DASHBOARD)
    function dashboardCalendar() {
        return {
            mode: 'hijri', // Default Hijri
            currentMonth: new Date().getMonth(),
            currentYear: new Date().getFullYear(),
            
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            daysInMonth: [], blanks: [], hijriData: [],
            
            init() { this.generateCalendar(); },
            
            // Core Navigation
            prevMonth() { this.currentMonth === 0 ? (this.currentMonth = 11, this.currentYear--) : this.currentMonth--; this.generateCalendar(); },
            nextMonth() { this.currentMonth === 11 ? (this.currentMonth = 0, this.currentYear++) : this.currentMonth++; this.generateCalendar(); },
            
            async toggleCalendarMode() { 
                this.mode = (this.mode === 'gregorian') ? 'hijri' : 'gregorian'; 
                this.generateCalendar(); // Re-render logic handled inside
            },

            // Data Fetching for Calendar (Independent from Main Dashboard Header)
            async fetchHijriData() {
                // Jangan fetch ulang jika data sudah ada untuk bulan/tahun yg sama (opsional optimization)
                // Tapi simplenya fetch ulang aja aman
                const m = this.currentMonth + 1; const y = this.currentYear;
                try { 
                    const res = await fetch(`https://api.aladhan.com/v1/gToHCalendar/${m}/${y}`); 
                    const data = await res.json(); 
                    this.hijriData = data.data; 
                } catch (e) { console.error(e); }
            },

            async generateCalendar() {
                // 1. Setup Grid Masehi (Structure Base)
                // 1. Setup Grid Masehi (Structure Base)
                let firstDay = new Date(this.currentYear, this.currentMonth, 1).getDay();
                let blankCount = firstDay; // Start Sunday (0)
                this.blanks = Array.from({ length: blankCount }, (_, i) => i);
                
                let daysCount = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
                this.daysInMonth = Array.from({ length: daysCount }, (_, i) => i + 1);

                // 2. Fetch Hijri Data if needed
                // Kita selalu fetch hijri data di setiap bulan biar siap switch mode kapan aja/smart title
                await this.fetchHijriData();
            },

            // --- HELPERS from pray_wajib.blade.php ---
            getMainDate(day) {
                if (this.mode === 'gregorian') return day;
                return this.getHijriDay(day) || '-';
            },
            getHijriDay(day) { return this.hijriData[day - 1]?.hijri.day; },
            getHijriMonthName(day) { return this.hijriData[day - 1]?.hijri.month.en; },

            isActive(day) { 
                const today = new Date();
                return day === today.getDate() && 
                       this.currentMonth === today.getMonth() && 
                       this.currentYear === today.getFullYear(); 
            },

            get calendarTitle() {
                if (this.mode === 'gregorian') { 
                    return this.monthNames[this.currentMonth] + ' ' + this.currentYear; 
                } else { 
                    if (this.hijriData.length > 0) {
                        const start = this.hijriData[0]?.hijri;
                        const end = this.hijriData[this.hijriData.length - 1]?.hijri;
                        if (start && end) {
                            if (start.month.number !== end.month.number) {
                                return `${start.month.en} - ${end.month.en} ${end.year}`;
                            } else {
                                return `${start.month.en} ${start.year}`;
                            }
                        }
                    }
                    return "Loading...";
                }
            }
        }
    }
</script>
@endpush