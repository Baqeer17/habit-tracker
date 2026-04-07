@extends('layouts.app')

@push('styles')
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .custom-checkbox { width: 1.1rem; height: 1.1rem; border-radius: 0.25rem; accent-color: #0d9488; }
    /* Smooth progress bar animation */
    .progress-bar { transition: width 1s ease-in-out; }

    /* Dark mode fixes for Tracker */
    .dark .tracker-card {
        background: #1e1e1e !important;
        border-color: #2d3436 !important;
        color: #f3f4f6;
    }
    .dark .tracker-card .text-gray-800 { color: #f3f4f6 !important; }
    .dark .tracker-card .text-gray-700 { color: #d1d5db !important; }
    .dark .tracker-card .text-gray-500 { color: #9ca3af !important; }
    .dark .tracker-card .bg-gray-50 { background: rgba(31,41,55,0.5) !important; }
    .dark .tracker-card .bg-gray-100 { background: #2d3436 !important; }
    .dark .tracker-card .border-gray-100 { border-color: #2d3436 !important; }
    .dark .tracker-card .hover\:bg-teal-50:hover { background: rgba(45,212,191,0.08) !important; }
    .dark .tracker-card .hover\:border-teal-100:hover { border-color: rgba(45,212,191,0.2) !important; }

    .dark .chart-section {
        background: #1e1e1e !important;
        border-color: #2d3436 !important;
    }
    .dark .chart-section .text-gray-800 { color: #f3f4f6 !important; }
    .dark .chart-section .border-b { border-color: #2d3436 !important; }
    .dark .chart-section .bg-gray-100 { background: #2d3436 !important; }
    .dark .chart-section .text-gray-500 { color: #9ca3af !important; }
    .dark .chart-section .bg-gray-50 { background: rgba(31,41,55,0.5) !important; }
    .dark .chart-section .border-dashed { border-color: #374151 !important; }
    .dark .chart-section .bg-teal-50 { background: rgba(15,118,110,0.15) !important; }
    .dark .chart-section .text-teal-800 { color: #2dd4bf !important; }
    .dark .chart-section .hover\:bg-teal-100:hover { background: rgba(15,118,110,0.25) !important; }

    .dark .filter-dropdown {
        background: #1e1e1e !important;
        border-color: #374151 !important;
        color: #d1d5db !important;
    }
    .dark .filter-dropdown:hover { background: #2d3436 !important; }
    .dark .report-title { color: #2dd4bf !important; }
</style>
<!-- Chart.js MUST load before Alpine.js defer runs -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="font-sans pb-10 px-1 sm:px-0">

    {{-- ====================================================== --}}
    {{-- HEADER BANNER                                          --}}
    {{-- ====================================================== --}}
    <header class="bg-[#4D4D4D] rounded-2xl sm:rounded-[40px] px-5 py-6 sm:p-8 text-white mb-6 sm:mb-8 relative overflow-hidden shadow-lg border border-gray-600">
        <div class="relative z-10">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-light leading-snug">
                Assalamualaikum, <span class="font-bold">{{ explode(' ', $user->name)[0] }}</span>
            </h2>
            @php $tanggal = \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y'); @endphp
            <p class="mt-1.5 text-sm text-gray-300">Pusat Pemantauan Ibadah &bull; {{ $tanggal }}</p>
        </div>
        <div class="absolute top-0 right-0 w-40 h-40 sm:w-64 sm:h-64 bg-white opacity-5 -mr-12 -mt-12 sm:-mr-20 sm:-mt-20 rounded-full"></div>
    </header>

    {{-- ====================================================== --}}
    {{-- COMPREHENSIVE REPORTS (selalu full-width di mobile)    --}}
    {{-- ====================================================== --}}
    <section class="mb-5">
        {{-- Header + Filter --}}
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <h3 class="text-teal-800 dark:text-teal-400 report-title font-bold text-lg sm:text-xl">Comprehensive Reports</h3>
            <form method="GET" action="{{ route('tracker.index') }}" class="flex items-center gap-1.5">
                <label for="period" class="text-xs text-gray-500 font-medium whitespace-nowrap">Filter:</label>
                <select name="period" id="period" onchange="this.form.submit()"
                    class="bg-white dark:bg-[#1e1e1e] filter-dropdown border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-xs rounded-xl p-2 outline-none font-semibold cursor-pointer shadow-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    <option value="weekly"    {{ $period === 'weekly'    ? 'selected' : '' }}>Mingguan</option>
                    <option value="monthly"   {{ $period === 'monthly'   ? 'selected' : '' }}>Bulanan</option>
                    <option value="3_months"  {{ $period === '3_months'  ? 'selected' : '' }}>3 Bulan</option>
                    <option value="6_months"  {{ $period === '6_months'  ? 'selected' : '' }}>6 Bulan</option>
                    <option value="yearly"    {{ $period === 'yearly'    ? 'selected' : '' }}>Tahunan</option>
                    <option value="3_years"   {{ $period === '3_years'   ? 'selected' : '' }}>3 Tahun</option>
                    <option value="5_years"   {{ $period === '5_years'   ? 'selected' : '' }}>5 Tahun</option>
                    <option value="all_time"  {{ $period === 'all_time'  ? 'selected' : '' }}>Semua</option>
                </select>
            </form>
        </div>

        {{-- Category Cards: horizontal scroll di mobile, grid di tablet+ --}}
        <div class="grid grid-cols-3 gap-3 sm:gap-4">
            @foreach($weeklyCategories as $cat)
        <div class="bg-white dark:bg-[#1e1e1e] border dark:border-[#2d3436] tracker-card px-3 py-3 sm:px-4 sm:py-4 rounded-2xl shadow-md hover:shadow-lg transition-shadow flex flex-col justify-between">
                <div class="flex justify-between items-center mb-2 sm:mb-3">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-{{ $cat['bgClass'] }} flex items-center justify-center text-{{ $cat['colorClass'] }}">
                        <i class="fa-solid {{ $cat['icon'] }} text-sm sm:text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-500">{{ $cat['percentage'] }}%</span>
                </div>
                <span class="text-xs sm:text-sm font-bold text-gray-800 block mb-2">{{ $cat['name'] }}</span>
                <div class="w-full h-2 sm:h-2.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full progress-bar" style="width: {{ $cat['percentage'] }}%; background-color: {{ $cat['colorCode'] }};"></div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ====================================================== --}}
    {{-- MIDDLE ROW: Sunnah Checklist + Daily Quote             --}}
    {{-- Mobile: Sunnah atas, Quote bawah                       --}}
    {{-- Tablet+: side by side 60/40                            --}}
    {{-- Desktop: tetap 2 kolom, Quote lebih kecil             --}}
    {{-- ====================================================== --}}
    <section class="mb-5 grid grid-cols-1 sm:grid-cols-5 gap-4 sm:gap-5">

        {{-- Sunnah Checklist (full on mobile, 3/5 on tablet) --}}
        <div class="sm:col-span-3 bg-white dark:bg-[#1e1e1e] dark:border-[#2d3436] tracker-card px-4 py-4 sm:px-5 sm:py-4 rounded-2xl shadow-md border border-gray-100">
            <div class="flex items-center mb-3">
                <i class="fa-solid fa-clipboard-check text-teal-600 dark:text-teal-400 text-base mr-2"></i>
                <h4 class="text-gray-800 dark:text-gray-100 font-bold text-sm">Sunnah Checklist Hari Ini</h4>
            </div>
            <div class="grid grid-cols-3 gap-2 sm:gap-3">
                <label class="flex items-center gap-2 p-2 sm:p-2.5 rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-teal-50 dark:hover:bg-teal-900/20 cursor-pointer transition border border-transparent hover:border-teal-100 dark:hover:border-teal-800">
                    <input type="checkbox" disabled class="custom-checkbox shrink-0" {{ $sunnahStatus['dhuha'] ? 'checked' : '' }}>
                    <span class="font-semibold text-xs text-gray-700 dark:text-gray-300 {{ $sunnahStatus['dhuha'] ? 'line-through text-gray-400 dark:text-gray-600' : '' }}">Dhuha</span>
                </label>
                <label class="flex items-center gap-2 p-2 sm:p-2.5 rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-teal-50 dark:hover:bg-teal-900/20 cursor-pointer transition border border-transparent hover:border-teal-100 dark:hover:border-teal-800">
                    <input type="checkbox" disabled class="custom-checkbox shrink-0" {{ $sunnahStatus['tahajud'] ? 'checked' : '' }}>
                    <span class="font-semibold text-xs text-gray-700 dark:text-gray-300 {{ $sunnahStatus['tahajud'] ? 'line-through text-gray-400 dark:text-gray-600' : '' }}">Tahajud</span>
                </label>
                <label class="flex items-center gap-2 p-2 sm:p-2.5 rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-teal-50 dark:hover:bg-teal-900/20 cursor-pointer transition border border-transparent hover:border-teal-100 dark:hover:border-teal-800">
                    <input type="checkbox" disabled class="custom-checkbox shrink-0" {{ $sunnahStatus['witir'] ? 'checked' : '' }}>
                    <span class="font-semibold text-xs text-gray-700 dark:text-gray-300 {{ $sunnahStatus['witir'] ? 'line-through text-gray-400 dark:text-gray-600' : '' }}">Witir</span>
                </label>
            </div>
        </div>

        {{-- Daily Quote (full on mobile, 2/5 on tablet) --}}
        <div class="sm:col-span-2 bg-[#1D3557] px-5 py-5 sm:px-6 sm:py-5 rounded-2xl shadow-lg flex flex-col justify-center items-center text-white relative overflow-hidden min-h-[110px] sm:min-h-[120px]">
            <i class="fa-solid fa-quote-left absolute top-4 left-4 text-2xl text-white opacity-10"></i>
            <div class="z-10 text-center px-2">
                <h4 class="font-bold text-teal-300 mb-2 tracking-widest uppercase text-[10px] sm:text-xs">Pesan Hari Ini</h4>
                <p class="text-xs sm:text-sm italic leading-relaxed text-gray-200">"{{ $dailyQuote }}"</p>
            </div>
            <div class="w-20 h-20 border border-teal-500 rounded-full opacity-20 absolute -bottom-4 -right-4"></div>
            <div class="w-12 h-12 border border-teal-300 rounded-full opacity-10 absolute bottom-4 -right-1"></div>
        </div>
    </section>

    {{-- ====================================================== --}}
    {{-- CHART: Full width, responsive height                   --}}
    {{-- ====================================================== --}}
    <section class="bg-white dark:bg-[#1e1e1e] dark:border-[#2d3436] chart-section p-4 sm:p-6 rounded-2xl shadow-md border border-gray-100" x-data="trackerChartApp()">

        {{-- Chart Header: Title + Main Tabs --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 border-b dark:border-gray-700 pb-3 gap-3">
            <h3 class="font-bold text-gray-800 dark:text-gray-100 text-base sm:text-xl">Tren Ibadah</h3>
            {{-- Main Tabs (scrollable on phone if needed) --}}
            <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-1 flex text-xs font-semibold overflow-x-auto no-scrollbar w-full sm:w-auto">
                <button @click="setMainTab('shalat')"
                    :class="mainTab === 'shalat' ? 'bg-white dark:bg-[#0F766E] shadow text-rose-600 dark:text-white' : 'text-gray-500 dark:text-gray-400'"
                    class="flex-1 sm:flex-none px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg transition whitespace-nowrap">Shalat</button>
                <button @click="setMainTab('tilawah')"
                    :class="mainTab === 'tilawah' ? 'bg-white dark:bg-[#0F766E] shadow text-blue-600 dark:text-white' : 'text-gray-500 dark:text-gray-400'"
                    class="flex-1 sm:flex-none px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg transition whitespace-nowrap">Tilawah</button>
                <button @click="setMainTab('dzikir')"
                    :class="mainTab === 'dzikir' ? 'bg-white dark:bg-[#0F766E] shadow text-green-600 dark:text-white' : 'text-gray-500 dark:text-gray-400'"
                    class="flex-1 sm:flex-none px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg transition whitespace-nowrap">Dzikir</button>
                <button @click="setMainTab('semua')"
                    :class="mainTab === 'semua' ? 'bg-white dark:bg-[#0F766E] shadow text-teal-800 dark:text-white' : 'text-gray-500 dark:text-gray-400'"
                    class="flex-1 sm:flex-none px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg transition whitespace-nowrap">Semua</button>
            </div>
        </div>

        {{-- Sub-tabs Shalat (Wajib/Sunnah/Gabungan) --}}
        <div x-show="mainTab === 'shalat'" class="flex justify-center mb-3 gap-2 text-[11px] font-bold" style="display:none;">
            <button @click="setSubTab('wajib')"
                :class="subTab==='wajib' ? 'bg-rose-100 text-rose-700' : 'bg-gray-50 text-gray-400 hover:bg-gray-100'"
                class="px-3 py-1.5 rounded-full transition">Wajib</button>
            <button @click="setSubTab('sunnah')"
                :class="subTab==='sunnah' ? 'bg-rose-100 text-rose-700' : 'bg-gray-50 text-gray-400 hover:bg-gray-100'"
                class="px-3 py-1.5 rounded-full transition">Sunnah</button>
            <button @click="setSubTab('gabungan')"
                :class="subTab==='gabungan' ? 'bg-rose-100 text-rose-700' : 'bg-gray-50 text-gray-400 hover:bg-gray-100'"
                class="px-3 py-1.5 rounded-full transition">Gabungan</button>
        </div>

        {{-- Description text --}}
        <p class="text-[11px] text-gray-400 dark:text-gray-500 mb-3 text-center" x-text="descriptionText"></p>

        {{-- Canvas container: adaptive height --}}
        <div class="w-full relative bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-3 sm:p-4 border border-dashed border-gray-200 dark:border-gray-700"
             style="height: 220px;" id="chartWrapper"
             x-init="$el.style.height = window.innerWidth < 640 ? '200px' : (window.innerWidth < 1024 ? '280px' : '340px')">
            <canvas id="trackerChart"></canvas>
        </div>

        {{-- CTA Button --}}
        <a href="{{ route('prayers.index') }}"
           class="mt-4 sm:mt-6 text-center w-full block py-2.5 sm:py-3 bg-teal-50 dark:bg-teal-900/20 hover:bg-teal-100 dark:hover:bg-teal-900/40 text-teal-800 dark:text-teal-300 font-bold rounded-xl transition duration-300 text-sm">
            Lengkapi Jurnal Ibadah <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </section>

</div>
@endsection

@push('scripts')
<script>
    const serverChartData = @json($chartData);

    function trackerChartApp() {
        return {
            mainTab: 'semua',
            subTab: 'gabungan',
            chartInstance: null,

            get descriptionText() {
                if (this.mainTab === 'shalat')  return `Tren shalat ${this.subTab} dalam periode terpilih.`;
                if (this.mainTab === 'tilawah') return 'Tren keaktifan tilawah Al-Quran.';
                if (this.mainTab === 'dzikir')  return 'Tren dzikir pagi dan petang harian.';
                return 'Komparasi seluruh aktivitas ibadah utama.';
            },

            init() {
                this.$watch('mainTab', () => this.renderChart());
                this.$watch('subTab',  () => this.renderChart());
                // Resize observer: update chart height bucket when window resizes
                window.addEventListener('resize', () => {
                    const w = document.getElementById('chartWrapper');
                    if (w) w.style.height = window.innerWidth < 640 ? '200px' : (window.innerWidth < 1024 ? '280px' : '340px');
                    if (this.chartInstance) this.chartInstance.resize();
                });
                setTimeout(() => this.renderChart(), 120);
            },

            setMainTab(tab) { this.mainTab = tab; },
            setSubTab(tab)  { this.subTab  = tab; },

            renderChart() {
                const ctx = document.getElementById('trackerChart');
                if (!ctx) return;
                if (this.chartInstance) this.chartInstance.destroy();

                let datasets = [];

                if (this.mainTab === 'shalat') {
                    let dataArray = this.subTab === 'wajib'  ? serverChartData.shalatWajib  :
                                   this.subTab === 'sunnah' ? serverChartData.shalatSunnah : serverChartData.shalatSemua;
                    let label    = this.subTab === 'wajib'  ? 'Shalat Wajib' :
                                   this.subTab === 'sunnah' ? 'Shalat Sunnah' : 'Shalat Gabungan';
                    datasets = [{ label, data: dataArray, borderColor:'#e11d48', backgroundColor:'rgba(225,29,72,0.1)', borderWidth:2.5, tension:0.4, fill:true, pointBackgroundColor:'#e11d48', pointRadius: window.innerWidth < 640 ? 1 : 2 }];
                }
                else if (this.mainTab === 'tilawah') {
                    datasets = [{ label:'Tilawah Quran', data:serverChartData.tilawah, borderColor:'#2563eb', backgroundColor:'rgba(37,99,235,0.1)', borderWidth:2.5, tension:0.4, fill:true, pointBackgroundColor:'#2563eb', pointRadius: window.innerWidth < 640 ? 1 : 2 }];
                }
                else if (this.mainTab === 'dzikir') {
                    datasets = [{ label:'Dzikir Harian', data:serverChartData.dzikir, borderColor:'#16a34a', backgroundColor:'rgba(22,163,74,0.1)', borderWidth:2.5, tension:0.4, fill:true, pointBackgroundColor:'#16a34a', pointRadius: window.innerWidth < 640 ? 1 : 2 }];
                }
                else {
                    datasets = [
                        { label:'Shalat', data:serverChartData.shalatSemua, borderColor:'#e11d48', borderWidth:2, tension:0.4, pointRadius:1, backgroundColor:'transparent' },
                        { label:'Tilawah', data:serverChartData.tilawah,    borderColor:'#2563eb', borderWidth:2, tension:0.4, pointRadius:1, backgroundColor:'transparent' },
                        { label:'Dzikir',  data:serverChartData.dzikir,     borderColor:'#16a34a', borderWidth:2, tension:0.4, pointRadius:1, backgroundColor:'transparent' }
                    ];
                }

                const isMobile = window.innerWidth < 640;
                this.chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: { labels: serverChartData.labels, datasets },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: this.mainTab === 'semua',
                                position: 'bottom',
                                labels: { boxWidth: 10, font: { size: 9 }, padding: 8 }
                            },
                            tooltip: {
                                mode: 'index', intersect: false,
                                callbacks: { label: c => c.dataset.label + ': ' + c.parsed.y + '%' }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true, max: 100,
                                ticks: { stepSize: isMobile ? 50 : 25, callback: v => v+'%', font: { size: isMobile ? 8 : 9 } },
                                grid: { color: '#f3f4f6' },
                                border: { display: false }
                            },
                            x: {
                                ticks: { font: { size: isMobile ? 8 : 9 }, maxTicksLimit: isMobile ? 5 : 7 },
                                grid: { display: false },
                                border: { display: false }
                            }
                        },
                        interaction: { mode: 'nearest', axis: 'x', intersect: false }
                    }
                });
            }
        }
    }
</script>
@endpush
