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
    <div x-data="rawatibLogic()">
        
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
                    <span class="text-[#d4a373] font-semibold">Sunnah Rawatib</span>
                </nav>
            </div>
            <div class="text-[#d4a373] font-serif italic opacity-80 select-none text-lg md:text-xl">MahabBa</div>
        </div>

        <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base font-light mb-8 text-center md:text-left leading-relaxed">
            Rawatib adalah salat yang dikerjakan sebelum (Qobliyah) atau sesudah (Ba'diyyah) salat fardu.
        </p>

        <div class="flex flex-col xl:flex-row gap-8 items-start">
            
            <!-- Left Column: Prayer List -->
            <div class="w-full flex-1 space-y-6 md:space-y-8">
                <template x-for="(waktu, index) in listRawatib" :key="index">
                    <div class="rounded-3xl p-4 md:p-6 bg-white/50 dark:bg-gray-800/30 border border-gray-100/50 dark:border-gray-700/50 transition-colors"> 
                        <h3 class="text-lg md:text-xl text-gray-700 dark:text-gray-300 font-light mb-3 md:mb-4 ml-1" x-text="waktu.name"></h3>
                        
                        <div class="space-y-3 md:space-y-4">
                            <template x-for="item in waktu.items" :key="item.id">
                                <div class="bg-white dark:bg-[#1e1e1e] rounded-2xl md:rounded-3xl p-4 md:p-6 flex items-center gap-4 md:gap-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all cursor-pointer group"
                                        @click="toggleCheck(item)">
                                    
                                    <!-- Green/Gold Circle Icon -->
                                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-full flex-shrink-0 flex items-center justify-center transition-all duration-300"
                                            :class="item.done ? 'bg-[#2ecc71] scale-110' : 'bg-[#9e8248] hover:scale-105'">
                                            <i class="fas fa-check text-white text-sm md:text-lg transition-transform duration-300" 
                                            :class="item.done ? 'scale-100' : 'scale-0'"></i>
                                    </div>
                                    
                                    <div class="flex flex-col">
                                        <span class="text-base md:text-xl text-gray-800 dark:text-gray-100 font-normal group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors" x-text="item.title"></span>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs md:text-sm font-light" x-text="item.rakaat"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
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
                        Terlaksana: <span style="color: #d4a373; font-weight: bold; font-size: 11px;" x-text="count"></span> / 6 Sunnah
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
    function rawatibLogic() {
        return {
            // Init data dari Backend
            todayLog: {!! json_encode($log ?? []) !!},
            
            count: 0,
            percent: 0,
            
            listRawatib: [
                {
                    name: 'Subuh',
                    items: [
                        { id: 1, key: 'q_subuh', title: 'Qabliyah Subuh', rakaat: '(2 rakaat)', done: false }
                    ]
                },
                {
                    name: 'Dzuhur',
                    items: [
                        { id: 2, key: 'q_dhuhr', title: 'Qabliyah Dzuhur', rakaat: '(2 atau 4 Rakaat)', done: false },
                        { id: 3, key: 'b_dhuhr', title: "Ba'diyah Dzuhur", rakaat: '(2 atau 4 rakaat)', done: false }
                    ]
                },
                {
                    name: 'Maghrib',
                    items: [
                        { id: 4, key: 'b_maghrib', title: "Ba'diyah Maghrib", rakaat: '(2 rakaat)', done: false }
                    ]
                },
                {
                    name: 'Isya',
                    items: [
                        { id: 5, key: 'q_isha', title: 'Qabliyah Isya', rakaat: '(2 rakaat)', done: false },
                        { id: 6, key: 'b_isha', title: "Ba'diyah Isya", rakaat: '(2 rakaat)', done: false }
                    ]
                }
            ],

            init() {
                // Map database values to UI
                if(this.todayLog) {
                    this.listRawatib.forEach(group => {
                        group.items.forEach(item => {
                            if(this.todayLog[item.key]) {
                                item.done = true;
                            }
                        });
                    });
                }
                this.calculateProgress();
            },

            calculateProgress() {
                let totalDone = 0;
                this.listRawatib.forEach(g => g.items.forEach(i => { if(i.done) totalDone++; }));
                this.count = totalDone;
                this.percent = Math.round((totalDone / 6) * 100);
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
                            date: "{{ isset($targetDate) ? $targetDate->format('Y-m-d') : date('Y-m-d') }}", // Selalu kirim tanggal target
                            prayer: item.key, // q_subuh, dll
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