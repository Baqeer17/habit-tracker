@extends('layouts.app')

@push('styles')
<style>
    /* HEADER STYLE (Dark Pill) */
    .ss-header {
        background: #4A4A4A;
        color: white;
        padding: 15px 30px;
        border-radius: 50px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    @media (max-width: 1024px) {
        .ss-header { flex-direction: column; text-align: center; gap: 10px; border-radius: 25px; padding: 20px; }
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div x-data="ghairuLogic()">
        
        <!-- Header -->
        <div class="ss-header">
            <div>
                <h1 class="text-xl md:text-2xl font-bold tracking-wide">Salat Sunnah</h1>
                <nav class="flex items-center justify-center md:justify-start text-xs text-gray-300 gap-2 font-medium mt-1">
                    <a href="/dashboard" class="hover:text-white transition-colors flex items-center gap-1">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('shalat-sunnah.halaman-sunnah') }}" class="hover:text-white transition-colors">Salat Sunnah</a>
                    <span class="text-gray-400">/</span>
                    <span class="text-[#d4a373] font-serif italic">Ghairu Muakkad</span>
                </nav>
            </div>
            <div class="text-[#d4a373] font-serif italic opacity-80 select-none text-lg md:text-xl">MahabBa</div>
        </div>

        <p class="text-gray-600 dark:text-gray-400 text-lg md:text-xl font-light mb-8 text-center md:text-left">
            Salat Sunnah Ghairu Muakkad
        </p>

        <div class="flex flex-col xl:flex-row gap-8 items-start">
            
            <!-- Left Column: Prayer List -->
            <div class="w-full flex-1 bg-white dark:bg-[#1e1e1e] rounded-[40px] p-8 shadow-sm border border-gray-50 dark:border-gray-800 transition-colors">
                <div class="space-y-10">
                    
                    <!-- Loop Sunnah -->
                    <template x-for="item in listSunnah" :key="item.id">
                        <div class="flex flex-col md:flex-row gap-6 items-start">
                            <!-- Toggle Button -->
                            <div class="w-12 h-12 rounded-full flex-shrink-0 mt-1 cursor-pointer transition-all flex items-center justify-center shadow-sm"
                                    :class="item.done ? 'bg-[#2ecc71] text-white scale-110' : 'bg-[#9e8248] text-[#9e8248] hover:scale-105'"
                                    @click="toggleCheck(item)">
                                <i class="fas fa-check text-xl" x-show="item.done"></i>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 flex flex-col md:flex-row gap-8 w-full">
                                <div class="md:w-1/3">
                                    <h3 class="text-xl text-gray-800 dark:text-gray-100 font-normal" x-text="item.title"></h3>
                                    <!-- Subtitle Logic -->
                                    <p class="text-gray-500 dark:text-gray-400 font-light mt-1 text-sm" x-show="item.key === 'dhuha'">(2,4,6,8,12 Rakaat)</p>
                                    <p class="text-gray-500 dark:text-gray-400 font-light mt-1 text-sm" x-show="item.key === 'tahajud'">(2 Rakaat, dst....)</p>
                                    <p class="text-gray-500 dark:text-gray-400 font-light mt-1 text-sm" x-show="item.key === 'witir'">(1, 3, - 11 Rakaat)</p>
                                </div>
                                
                                <div class="flex-1">
                                    <h4 class="text-[#d4a373] text-lg font-light mb-2">Keutamaan</h4>
                                    <!-- Keutamaan Logic -->
                                    <p class="text-gray-400 text-sm leading-relaxed" x-show="item.key === 'dhuha'">
                                        "Siapa yang membiasakan (menjaga) shalat dhuha, dosanya akan diampuni meskipun sebanyak buih di lautan." (HR At-Tirmidzi dan Ibnu Majah)
                                    </p>
                                    <p class="text-gray-400 text-sm leading-relaxed" x-show="item.key === 'tahajud'">
                                        "Dan pada sebagian malam hari shalat tahajudlah kamu sebagai suatu ibadah tambahan bagimu; mudah-mudahan Tuhan-mu mengangkat kamu ke tempat yang terpuji." (QS. Al-Isra: 79)
                                    </p>
                                    <p class="text-gray-400 text-sm leading-relaxed" x-show="item.key === 'witir'">
                                        "Sesungguhnya Allah itu witir (ganjil) dan menyukai yang ganjil, maka shalat witirlah wahai ahli Al-Quran." (HR. Abu Dawud)
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>

                </div>
            </div>

            <!-- Right Column: Stats & Calendar -->
            <div class="w-full xl:w-80 flex-shrink-0 space-y-6">
                
                <!-- Daily Progress Widget (New) -->
                <div class="bg-white dark:bg-[#1e1e1e] rounded-[30px] md:rounded-[40px] p-6 md:p-8 shadow-sm border border-gray-50 dark:border-gray-800 text-center max-w-md mx-auto xl:max-w-none transition-colors">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h4 style="margin:0; font-weight: 600;" class="text-[#5A4635] dark:text-gray-200">Daily Progress</h4>
                        <span style="font-size: 12px; font-weight: bold; color: #d4a373" x-text="percent + '%'"></span>
                    </div>
                    <!-- Progress Bar -->
                    <div class="w-full bg-[#eee] h-[10px] rounded-full overflow-hidden">
                        <div class="h-full bg-[#9e8248] rounded-full transition-all duration-500 ease-out"
                                :style="'width: ' + percent + '%;' + (percent == 100 ? 'background: #2ecc71;' : '')"></div>
                    </div>
                    <div style="text-align: right; font-size: 10px; color: #aaa; margin-top: 5px;">
                        Terlaksana: <span style="color: #d4a373; font-weight: bold; font-size: 11px;" x-text="count"></span> / 3 Sunnah
                    </div>
                </div>

                <!-- Minimalist Calendar -->
                <div class="bg-white dark:bg-[#1e1e1e] rounded-[30px] md:rounded-[40px] p-6 md:p-8 shadow-sm border border-gray-50 dark:border-gray-800 text-center max-w-md mx-auto xl:max-w-none transition-colors" x-data="calendarWidget()" x-cloak>
                    
                    <div class="calendar-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <span class="text-sm font-bold text-[#2d3436] dark:text-gray-100" x-text="calendarTitle"></span>
                        <div class="flex gap-2">
                                <button @click="toggleCalendarMode()" class="text-[#d4a373] hover:text-[#b08558] transition" title="Switch Mode">
                                <i class="fas fa-sync-alt text-xs"></i>
                                </button>
                                <div class="flex gap-1">
                                <button @click="prevMonth()" class="text-[#d4a373] hover:bg-gray-100 p-1 rounded"><i class="fas fa-chevron-left text-xs"></i></button>
                                <button @click="nextMonth()" class="text-[#d4a373] hover:bg-gray-100 p-1 rounded"><i class="fas fa-chevron-right text-xs"></i></button>
                                </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-gray-400 mb-2 uppercase"><div>Mn</div><div>Sn</div><div>Sl</div><div>Rb</div><div>Km</div><div>Jm</div><div>Sb</div></div>
                    
                    <div class="grid grid-cols-7 gap-1 text-center text-xs font-medium text-gray-600 dark:text-gray-400">
                        <template x-for="blank in blanks"><div class="p-1"></div></template>
                        <template x-for="day in daysInMonth">
                            <div class="w-8 h-8 mx-auto flex items-center justify-center rounded-lg transition-all relative group border border-transparent"
                                    :class="{
                                    'cursor-default hover:bg-[#E3D4C1] hover:text-[#5A4635]': !isActive(day),
                                    'bg-[#1D3557] text-white font-bold shadow-md scale-105 border-[#1D3557]': isActive(day)
                                    }">
                                
                                <!-- Main Date -->
                                <span class="text-xs" x-text="getMainDate(day)"></span>
                                
                                <!-- Dual Date -->
                                <div x-show="mode === 'hijri'" 
                                        class="absolute top-0.5 right-0.5 text-[6px] opacity-60 font-normal leading-none text-white">
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
            </div>

        </div>
        
        <div style="height: 100px;"></div>
    </div>
@endsection

@push('scripts')
<script>
    function ghairuLogic() {
        return {
            // Init data dari Backend
            todayLog: {!! json_encode($log ?? []) !!}, // Ensure $log is passed from controller or handle null
            
            count: 0,
            percent: 0,
            
            listSunnah: [
                { id: 1, key: 'dhuha', title: 'Dhuha', done: false },
                { id: 2, key: 'tahajud', title: 'Tahajud', done: false },
                { id: 3, key: 'witir', title: 'Witir', done: false }
            ],

            init() {
                // Map database values to UI
                if(this.todayLog && Object.keys(this.todayLog).length > 0) {
                    this.listSunnah.forEach(item => {
                        if(this.todayLog[item.key]) {
                            item.done = true;
                        }
                    });
                }
                this.calculateProgress();
            },

            calculateProgress() {
                let totalDone = 0;
                this.listSunnah.forEach(i => { if(i.done) totalDone++; });
                this.count = totalDone;
                this.percent = Math.round((totalDone / 3) * 100);
            },

            async toggleCheck(item) {
                // Optimistic update
                item.done = !item.done;
                this.calculateProgress();

                // Kirim ke Backend
                try {
                    let response = await fetch("{{ route('prayers.toggle') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            date: "{{ isset($targetDate) ? $targetDate->format('Y-m-d') : date('Y-m-d') }}", 
                            prayer: item.key, // dhuha, dll
                            status: item.done
                        })
                    });

                    let result = await response.json();
                    if (!result.success) {
                        alert("Gagal menyimpan: " + result.message);
                        item.done = !item.done; // Revert
                        this.calculateProgress();
                    }
                } catch (error) {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan koneksi.");
                    item.done = !item.done; // Revert
                    this.calculateProgress();
                }
            }
        }
    }

    function calendarWidget() {
        return {
            mode: 'hijri', // Default Hijri
            currentMonth: new Date().getMonth(),
            currentYear: new Date().getFullYear(),
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            daysInMonth: [], blanks: [], hijriData: [],

            init() { this.generateCalendar(); },
            
            prevMonth() { this.currentMonth === 0 ? (this.currentMonth = 11, this.currentYear--) : this.currentMonth--; this.generateCalendar(); },
            nextMonth() { this.currentMonth === 11 ? (this.currentMonth = 0, this.currentYear++) : this.currentMonth++; this.generateCalendar(); },

            async toggleCalendarMode() { 
                this.mode = (this.mode === 'gregorian') ? 'hijri' : 'gregorian'; 
                this.generateCalendar(); 
            },

            async fetchHijriData() {
                const m = this.currentMonth + 1; const y = this.currentYear;
                try { 
                    const res = await fetch(`https://api.aladhan.com/v1/gToHCalendar/${m}/${y}`); 
                    const data = await res.json(); 
                    this.hijriData = data.data; 
                } catch (e) { console.error(e); }
            },

            async generateCalendar() {
                let firstDay = new Date(this.currentYear, this.currentMonth, 1).getDay();
                let blankCount = firstDay; 
                this.blanks = Array.from({ length: blankCount }, (_, i) => i);
                let daysCount = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
                this.daysInMonth = Array.from({ length: daysCount }, (_, i) => i + 1);

                // Fetch Hijri Data
                await this.fetchHijriData();
            },

            getMainDate(day) {
                if (this.mode === 'gregorian') return day;
                return this.getHijriDay(day) || '-';
            },
            getHijriDay(day) { return this.hijriData[day - 1]?.hijri.day; },
            getHijriMonthName(day) { return this.hijriData[day - 1]?.hijri.month.en; },

            isActive(day) {
                const today = new Date();
                return day === today.getDate() && this.currentMonth === today.getMonth() && this.currentYear === today.getFullYear();
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
