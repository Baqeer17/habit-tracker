@extends('layouts.app')

@push('styles')
<style>
    /* Header Pill Style */
    .header {
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

    .dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; align-items: start; }
    .widget-box { 
        background: white; padding: 20px; border-radius: 15px; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
        transition: all 0.3s ease;
        border: 1px solid white;
    }
    .dark .widget-box { 
        background: #1e1e1e; 
        border-color: #2d3436;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    @media (max-width: 768px) {
        .dashboard-grid { grid-template-columns: 1fr; }
        .header { flex-direction: column; text-align: center; gap: 15px; }
    }
    
    /* Progress Bar Goals */
    .goals-box h4 { font-size: 14px; margin-bottom: 10px; color: #555; transition: color 0.3s; }
    .dark .goals-box h4 { color: #cbd5e1; }
    .progress-bar { width: 100%; background: #eee; height: 10px; border-radius: 10px; overflow: hidden; transition: background 0.3s; }
    .dark .progress-bar { background: #2d3436; }
    .progress-fill { background: #d4a373; height: 100%; border-radius: 10px; transition: width 0.5s ease; }
    .dark .progress-fill { background: #2dd4bf; }
    
    #weeklyScheduleModal.active { display: block !important; }
</style>
@endpush

@section('content')
    <div class="header">
        <div>
            <h1 class="text-xl font-bold tracking-wide">Salat Wajib</h1>
            <nav class="flex items-center text-xs text-gray-300 gap-2 font-medium mt-1">
                <a href="/dashboard" class="hover:text-white transition-colors flex items-center gap-1">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <span class="text-gray-400">/</span>
                <span class="text-[#d4a373] font-semibold">Salat Wajib</span>
            </nav>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="document.getElementById('weeklyScheduleModal').classList.add('active')" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-full text-xs font-medium transition flex items-center gap-2">
                <i class="far fa-calendar-alt"></i> Jadwal Sepekan
            </button>
            <div class="text-[#d4a373] font-serif italic opacity-80">MahabBa</div>
        </div>
    </div>

    <!-- Modal Jadwal Sepekan -->
    <div id="weeklyScheduleModal" class="fixed inset-0 z-50 hidden" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(5px);">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl transform transition-all scale-100" @click.away="document.getElementById('weeklyScheduleModal').classList.remove('active')">
                <div class="flex justify-between items-center mb-6 border-b dark:border-gray-700 pb-3">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">Jadwal Shalat Sepekan</h3>
                    <button onclick="document.getElementById('weeklyScheduleModal').classList.remove('active')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div x-data="weeklySchedule()" x-init="fetchWeekly()" class="overflow-x-auto">
                    <template x-if="loading">
                        <div class="text-center py-8 text-gray-400">Loading data...</div>
                    </template>
                    <table class="w-full text-sm text-left" x-show="!loading">
                        <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-3 py-3 w-32 border-b dark:border-gray-700">Hari, Tanggal</th>
                                <th class="px-3 py-3 border-b dark:border-gray-700">Subuh</th>
                                <th class="px-3 py-3 border-b dark:border-gray-700">Dzuhur</th>
                                <th class="px-3 py-3 border-b dark:border-gray-700">Asar</th>
                                <th class="px-3 py-3 border-b dark:border-gray-700">Maghrib</th>
                                <th class="px-3 py-3 border-b dark:border-gray-700">Isya</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <template x-for="day in schedule" :key="day.date.readable">
                                <tr class="transition-colors" 
                                    :class="isToday(day.date.readable) ? 'bg-[#d4a373]/10 border-l-4 border-[#d4a373]' : 'hover:bg-gray-50/50 dark:hover:bg-gray-700/30'">
                                    <td class="px-3 py-2 font-medium text-gray-700 dark:text-gray-200" x-text="formatDate(day.date.readable)"></td>
                                    <td class="px-3 py-2 text-gray-600 dark:text-gray-400" x-text="day.timings.Fajr.split(' ')[0]"></td>
                                    <td class="px-3 py-2 text-gray-600 dark:text-gray-400" x-text="day.timings.Dhuhr.split(' ')[0]"></td>
                                    <td class="px-3 py-2 text-gray-600 dark:text-gray-400" x-text="day.timings.Asr.split(' ')[0]"></td>
                                    <td class="px-3 py-2 text-gray-600 dark:text-gray-400" x-text="day.timings.Maghrib.split(' ')[0]"></td>
                                    <td class="px-3 py-2 text-gray-600 dark:text-gray-400" x-text="day.timings.Isha.split(' ')[0]"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    @php
        $totalChecks = 0;
        $maxChecks = 35; // 5 waktu * 7 hari

        // Loop 7 hari dalam minggu ini
        for ($i = 0; $i < 7; $i++) {
            $d = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
            $l = $logs[$d] ?? null;
            
            if ($l) {
                if ($l->fajr) $totalChecks++;
                if ($l->dhuhr) $totalChecks++;
                if ($l->asr) $totalChecks++;
                if ($l->maghrib) $totalChecks++;
                if ($l->isha) $totalChecks++;
            }
        }
        
        $percent = $maxChecks > 0 ? min(100, round(($totalChecks / $maxChecks) * 100)) : 0;
    @endphp

    <div class="dashboard-grid" x-data="prayerTracker({{ $totalChecks }})">
        
        <div class="widget-box" style="padding: 0; overflow: hidden; border: none;">
            <div class="bg-white dark:bg-[#1e1e1e] flex flex-col">
                <div class="bg-[#1D3557] dark:bg-[#1a2f23] h-20 flex text-white relative shadow-sm transition-colors duration-300">
                    <div class="w-16 md:w-24 border-r border-blue-400/30 dark:border-teal-400/20 flex flex-col justify-center items-center bg-[#162A45] dark:bg-[#121c16]">
                        <span class="text-[10px] opacity-70 uppercase tracking-widest">Week</span>
                        <span class="text-lg font-bold">{{ $targetDate->weekOfYear }}</span>
                    </div>
                    <div class="flex-1 flex items-center justify-center px-4">
                        <div x-show="!expanded" class="text-xl font-bold tracking-wide flex items-center gap-2">
                            <i class="far fa-calendar-alt opacity-70"></i>
                            {{ $targetDate->translatedFormat('l, d M Y') }}
                        </div>
                        <div x-show="expanded" class="w-full grid grid-cols-7 text-center pl-2">
                            @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Ahd'] as $day)
                                <div class="text-sm font-semibold opacity-90">{{ $day }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex w-full">
                    <div class="w-16 md:w-24 flex flex-col py-6 space-y-4 items-center bg-[#f8f9fa] dark:bg-[#1a1a1a] border-r border-gray-100 dark:border-gray-700 shrink-0">
                        @foreach(['Subuh', 'Dzuhur', 'Asar', 'Maghrib', 'Isya'] as $p)
                            <div class="h-10 flex items-center justify-center font-bold text-[#5A4635] dark:text-gray-200 text-sm">{{ $p }}</div>
                        @endforeach
                    </div>
                    <div class="flex-1 py-6 relative overflow-x-auto dark:bg-[#121212]">
                        <div class="w-full grid transition-all duration-300 px-2 min-w-[300px]" :class="expanded ? 'grid-cols-7' : 'grid-cols-1'">
                            @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $index => $dayName)
                                @php 
                                    $date = $startOfWeek->copy()->addDays($index);
                                    $isActiveDay = $date->isSameDay($targetDate);
                                    $isFuture = $date->isFuture(); 
                                    $dbDate = $date->format('Y-m-d');
                                    $log = $logs[$dbDate] ?? null;
                                @endphp
                                
                                <div class="flex flex-col space-y-4 items-center transition-all duration-300 min-h-[300px]"
                                        x-show="expanded || {{ $isActiveDay ? 'true' : 'false' }}"
                                        :class="{ 
                                        'bg-[#E3D4C1]/30 -my-6 py-6 border-x border-[#D6C7B4]': {{ $isActiveDay ? 'true' : 'false' }} && expanded
                                        }">
                                    
                                    @foreach(['fajr', 'dhuhr', 'asr', 'maghrib', 'isha'] as $prayer)
                                        <div class="h-10 flex items-center justify-center w-full">
                                            @if($isFuture)
                                                <div class="w-8 h-8 rounded-full border-[2px] border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 opacity-50 cursor-not-allowed" title="Belum waktunya"></div>
                                            @else
                                                <label class="cursor-pointer relative block w-8 h-8 hover:scale-110 transition-transform">
                                                    <input type="checkbox" 
                                                            class="peer appearance-none w-full h-full border-[3px] border-[#d4a373] dark:border-teal-600 rounded-full checked:bg-[#d4a373] dark:checked:bg-teal-500 transition-all bg-white dark:bg-gray-800 shadow-sm"
                                                            {{ ($log && $log->$prayer) ? 'checked' : '' }}
                                                            @change="updatePrayer('{{ $dbDate }}', '{{ $prayer }}', $event.target.checked)">
                                                    <i class="fas fa-check text-white text-xs absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                                </label>
                                            @endif
                                        </div>
                                    @endforeach

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <button @click="toggleExpanded()" class="w-full bg-[#f1f2f6] dark:bg-gray-800/80 hover:bg-[#dfe4ea] dark:hover:bg-gray-700/80 py-3 text-[#636e72] dark:text-gray-400 text-xs font-bold tracking-widest uppercase border-t border-gray-200 dark:border-gray-700 transition-colors flex items-center justify-center gap-2">
                    <span x-text="expanded ? 'TUTUP' : 'LIHAT SATU MINGGU'"></span>
                    <i class="fas fa-chevron-down transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
                </button>
            </div>
        </div>

        <div class="flex flex-col gap-5">
            <div class="widget-box">
            <div class="calendar-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div class="flex items-center gap-2">
                    <span class="text-lg font-bold text-[#2d3436] dark:text-gray-100" x-text="calendarTitle"></span>
                    <button @click="toggleCalendarMode()" class="text-[#d4a373] dark:text-teal-400 hover:text-[#b08558] dark:hover:text-teal-300 transition"><i class="fas fa-sync-alt text-sm"></i></button>
                </div>
                <div class="flex gap-2">
                    <button @click="prevMonth()" class="text-[#d4a373] dark:text-teal-400 hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded"><i class="fas fa-chevron-left"></i></button>
                    
                    <button @click="nextMonth()" x-show="!isCurrentMonthView" class="text-[#d4a373] dark:text-teal-400 hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded"><i class="fas fa-chevron-right"></i></button>
                    <button x-show="isCurrentMonthView" class="text-gray-300 dark:text-gray-600 cursor-not-allowed p-1"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="grid grid-cols-7 gap-2 text-center text-xs font-bold text-gray-400 dark:text-gray-500 mb-2 uppercase"><div>Mn</div><div>Sn</div><div>Sl</div><div>Rb</div><div>Km</div><div>Jm</div><div>Sb</div></div>
            <div class="grid grid-cols-7 gap-2 text-center text-sm font-medium text-gray-600 dark:text-gray-300">
                <template x-for="blank in blanks"><div class="p-1"></div></template>
                <template x-for="day in daysInMonth">
                    <div @click="!isFutureDay(day) && goToDate(day)" 
                            class="w-8 h-8 md:w-10 md:h-10 mx-auto flex items-center justify-center rounded-xl transition-all relative group border border-transparent"
                            :class="{
                            'cursor-pointer hover:bg-[#E3D4C1] dark:hover:bg-teal-900/30 hover:text-[#5A4635] dark:hover:text-teal-200 hover:border-[#D6C7B4] dark:hover:border-teal-700': !isFutureDay(day) && !isActive(day),
                            'bg-[#1D3557] dark:bg-teal-600 text-white font-bold shadow-lg scale-105 border-[#1D3557] dark:border-teal-500': isActive(day),
                            'text-gray-300 dark:text-gray-600 cursor-default bg-gray-50 dark:bg-transparent': isFutureDay(day)
                            }">
                        
                        <!-- Main Date (Hijri/Masehi based on mode) -->
                        <span class="text-sm" x-text="getMainDate(day)"></span>
                        
                        <!-- Dual Date (Show Masehi small if in Hijri mode) -->
                        <div x-show="mode === 'hijri'" 
                                class="absolute top-0.5 right-1 text-[8px] opacity-60 font-normal">
                            <span x-text="day"></span>
                        </div>

                        <!-- New Month Badge (Only for Hijri date 1) -->
                        <div x-show="mode === 'hijri' && getHijriDay(day) == 1" 
                                class="absolute -top-2 -left-2 bg-amber-400 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full shadow-sm z-10 whitespace-nowrap">
                            <span x-text="getHijriMonthName(day)"></span>
                        </div>

                        <div x-show="isToday(day)" class="absolute -bottom-1 w-1 h-1 bg-yellow-500 rounded-full"></div>
                    </div>
                </template>
            </div>
            </div>

            <div class="widget-box goals-box border: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h4 style="margin:0; font-weight: 600; color: #5A4635;" class="dark:text-gray-200">Weekly Progress</h4>
                    <span style="font-size: 12px; font-weight: bold; color: #d4a373" class="dark:text-teal-400" x-text="percent + '%'"></span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" :style="'width: ' + percent + '%;' + (percent == 100 ? 'background: #2ecc71;' : '')"></div>
                </div>
                <div style="text-align: right; font-size: 10px; color: #aaa; margin-top: 5px;" class="dark:text-gray-500">
                    Terlaksana: <span style="color: #d4a373; font-weight: bold; font-size: 11px;" class="dark:text-teal-400" x-text="count"></span> / 35 Waktu
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Simple logic for Modal Visibility
    const modal = document.getElementById('weeklyScheduleModal');
    // Allow closing via style manipulation for simplicity with vanilla JS alongside Alpine
    modal.addEventListener('click', (e) => {
        if(e.target === modal) modal.classList.remove('active');
    });

    // Alpine Component for Weekly Data
    function weeklySchedule() {
        return {
            loading: true,
            schedule: [],
            
            async fetchWeekly() {
                const lat = -7.7956; // Default Jogja
                const lng = 110.3695;
                
                // Try to get cached location from Dashboard logic if available
                // Or just use browser geolocation if permission granted
                if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                        (pos) => this.getData(pos.coords.latitude, pos.coords.longitude),
                        () => this.getData(lat, lng) // Fallback
                        );
                } else {
                    this.getData(lat, lng);
                }
            },

            async getData(lat, lng) {
                try {
                    // Method 20 = Kemenag RI
                    const response = await fetch(`https://api.aladhan.com/v1/calendar?latitude=${lat}&longitude=${lng}&method=20&month=${new Date().getMonth()+1}&year=${new Date().getFullYear()}`);
                    const data = await response.json();
                    
                    // Get today and next 6 days
                    const today = new Date().getDate();
                    this.schedule = data.data.filter(d => parseInt(d.date.gregorian.day) >= today).slice(0, 7);
                    
                    this.loading = false;
                } catch (e) {
                    console.error(e);
                    this.loading = false;
                }
            },

            formatDate(dateStr) {
                // dateStr is "DD MMM YYYY"
                const parts = dateStr.split(' ');
                const d = new Date(`${parts[1]} ${parts[0]}, ${parts[2]}`);
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                return `${days[d.getDay()]}, ${parts[0]} ${parts[1]}`;
            },
            
            isToday(dateStr) {
                    const today = new Date();
                    // Format API: "05 Feb 2026"
                    const parts = dateStr.split(' ');
                    return parseInt(parts[0]) === today.getDate() && parts[1] === this.getMonthNameShort(today.getMonth()) && parseInt(parts[2]) === today.getFullYear();
            },

            getMonthNameShort(monthIndex) {
                const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                return months[monthIndex];
            }
        }
    }

    function prayerTracker(initialCount) {
        return {
            count: initialCount,
            percent: 0,
            animationInterval: null,
            
            expanded: false,
            mode: 'gregorian',
            currentMonth: {{ $targetDate->month - 1 }}, 
            currentYear: {{ $targetDate->year }},
            selectedDay: {{ $targetDate->day }}, 
            
            // Logic Real Time (Untuk validasi masa depan)
            realDate: new Date(),

            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            daysInMonth: [], blanks: [], hijriData: [],
            
            init() { 
                this.generateCalendar();
                // Set initial percent instantly
                this.percent = Math.round((this.count / 35) * 100);
            },
            toggleExpanded() { this.expanded = !this.expanded; },
            
            animateProgress() {
                clearInterval(this.animationInterval);
                let target = Math.min(100, Math.round((this.count / 35) * 100));
                
                if (this.percent === target) return;

                this.animationInterval = setInterval(() => {
                    if (this.percent < target) {
                        this.percent++;
                    } else if (this.percent > target) {
                        this.percent--;
                    } else {
                        clearInterval(this.animationInterval);
                    }
                }, 15);
            },

            async updatePrayer(date, prayer, status) {
                try {
                    // Update Local State Immediately
                    if (status) { this.count++; } else { this.count--; }
                    this.animateProgress();

                    // Auto-Save Logic
                    await fetch('/salat-wajib/toggle', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                        },
                        body: JSON.stringify({ 
                            date: date, 
                            prayer: prayer, 
                            status: status ? 1 : 0 
                        })
                    }).then(res => {
                        if (!res.ok) throw new Error('Failed to save');
                    });
                } catch (e) { 
                    console.error("Auto-Save Error:", e);
                    // Optional: Revert UI if save fails
                }
            },


            goToDate(day) {
                let m = this.currentMonth + 1; let d = day;
                if(m < 10) m = '0'+m; if(d < 10) d = '0'+d;
                let fullDate = this.currentYear + '-' + m + '-' + d;
                window.location.href = "?date=" + fullDate;
            },

            isActive(day) { return day === this.selectedDay && this.currentMonth === ({{ $targetDate->month - 1 }}) && this.currentYear === {{ $targetDate->year }}; },
            isToday(day) { const t = new Date(); return day === t.getDate() && this.currentMonth === t.getMonth() && this.currentYear === t.getFullYear(); },
            
            get isCurrentMonthView() {
                return this.currentMonth === this.realDate.getMonth() && this.currentYear === this.realDate.getFullYear();
            },

            isFutureDay(day) {
                if (this.currentYear > this.realDate.getFullYear()) return true;
                if (this.currentYear === this.realDate.getFullYear() && this.currentMonth > this.realDate.getMonth()) return true;
                if (this.isCurrentMonthView && day > this.realDate.getDate()) return true;
                return false;
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
                                // Range Title: "Rajab - Sya'ban 1447"
                                return `${start.month.en} - ${end.month.en} ${end.year}`;
                            } else {
                                // Single Month Title
                                return `${start.month.en} ${start.year}`;
                            }
                        }
                    }
                    return "Loading...";
                }
            },

            // --- HELPER FUNCTIONS ---
            
            // Mengembalikan Angka Tanggal Utama (Tengah)
            getMainDate(day) {
                if (this.mode === 'gregorian') return day;
                return this.getHijriDay(day) || '-';
            },

            // Mengembalikan Tanggal Hijriah (Angka saja) untuk index hari tertentu
            getHijriDay(day) {
                // day is 1-based index (1..31)
                // hijriData array is 0-based index
                return this.hijriData[day - 1]?.hijri.day;
            },

            // Mengembalikan Nama Bulan Hijriah
            getHijriMonthName(day) {
                return this.hijriData[day - 1]?.hijri.month.en;
            },

            getDisplayDate(day) { return this.getMainDate(day); }, // Wrapper for backward compat if needed

            async toggleCalendarMode() { this.mode = (this.mode === 'gregorian') ? 'hijri' : 'gregorian'; if (this.mode === 'hijri' && this.hijriData.length === 0) await this.fetchHijriData(); },
            async fetchHijriData() {
                const m = this.currentMonth + 1; const y = this.currentYear;
                try { const res = await fetch(`https://api.aladhan.com/v1/gToHCalendar/${m}/${y}`); const data = await res.json(); this.hijriData = data.data; } catch (e) { console.error(e); }
            },
            async generateCalendar() {
                let firstDay = new Date(this.currentYear, this.currentMonth, 1).getDay();
                let blankCount = firstDay; 
                this.blanks = Array.from({ length: blankCount }, (_, i) => i);
                let daysCount = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
                this.daysInMonth = Array.from({ length: daysCount }, (_, i) => i + 1);
                if (this.mode === 'hijri') { this.hijriData = []; await this.fetchHijriData(); }
            },
            prevMonth() { this.currentMonth === 0 ? (this.currentMonth = 11, this.currentYear--) : this.currentMonth--; this.generateCalendar(); },
            
            nextMonth() { 
                if (this.isCurrentMonthView) return; 
                if (this.currentMonth === 11) { this.currentMonth = 0; this.currentYear++; } else { this.currentMonth++; } 
                this.generateCalendar(); 
            }
        }
    }
</script>
@endpush