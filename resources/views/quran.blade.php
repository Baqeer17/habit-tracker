@extends('layouts.app')

@push('styles')
<style>
    /* Header Pill Style - Specific to Quran Page */
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
        .header { 
            flex-direction: column; text-align: center; gap: 10px;
            padding: 12px 20px; border-radius: 40px; margin-bottom: 20px;
        }
        .header h1 { font-size: 16px; }
    }
</style>
@endpush

@section('content')
    <div x-data="quranApp()">
        <!-- Header -->
        <div class="header">
            <div>
                <h1 class="text-xl font-bold tracking-wide">Al Qur'an</h1>
                <nav class="flex items-center text-xs text-gray-300 gap-2 font-medium mt-1">
                    <a href="/dashboard" class="hover:text-white transition-colors flex items-center gap-1">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <span class="text-gray-400">/</span>
                    <span class="text-[#d4a373] font-semibold">Al Qur'an</span>
                </nav>
            </div>
            <div class="text-[#d4a373] font-serif italic text-xl font-bold">MahabBa</div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Continue Reading & Access -->
            <div class="lg:col-span-1 space-y-8">
                
                <!-- Last Read Card -->
                <div class="bg-white rounded-[30px] p-8 shadow-sm relative overflow-hidden" x-show="lastRead">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#fff1e0] rounded-bl-full -mr-8 -mt-8 z-0"></div>
                    <div class="relative z-10">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Lanjutkan Membaca :</h3>
                        
                        <div class="mb-6">
                            <div class="text-xl font-semibold text-gray-700" x-text="lastRead ? `QS. ${lastRead.surahNama} ayat ${lastRead.ayatNomor}` : ''"></div>
                            <div class="text-gray-400 text-sm mt-1">Terakhir dibaca</div>
                        </div>

                        <button @click="openSurah(lastRead.surahNomor)" class="bg-[#dce6e9] hover:bg-[#b2bec3] text-[#555] font-semibold py-3 px-8 rounded-full w-full transition-colors">
                            Lanjutkan
                        </button>
                    </div>
                </div>

                <!-- Last Read Card Empty State -->
                <div class="bg-white rounded-[30px] p-8 shadow-sm relative overflow-hidden text-center text-gray-400" x-show="!lastRead">
                    <p>Belum ada riwayat bacaan.</p>
                </div>

                <!-- Quick Access -->
                <div>
                    <h3 class="text-[#3c9ea8] font-bold text-lg mb-4">Akses Cepat</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <template x-for="surah in quickAccess" :key="surah.number">
                            <button @click="openSurah(surah.number)" 
                                    class="border border-gray-300 rounded-full py-2 px-4 text-gray-500 hover:bg-[#3c9ea8] hover:text-white hover:border-[#3c9ea8] transition text-sm truncate">
                                <span x-text="surah.name"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Jurnal Tilawah Widget -->
                <div class="bg-white rounded-[30px] p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="font-bold text-lg text-gray-800">Jurnal Tilawah</h3>
                            <p class="text-xs text-gray-400">Progress Mingguan</p>
                        </div>
                        <button @click="openLogModal = true" class="bg-[#3c9ea8] hover:bg-[#2d858e] text-white text-xs font-bold py-2 px-4 rounded-full transition shadow-sm">
                            <i class="fas fa-plus mr-1"></i> Catat
                        </button>
                    </div>

                    <!-- Bar Chart -->
                    <div class="flex items-end justify-between h-32 gap-2 mt-4">
                        <template x-for="day in weeklyProgress" :key="day.date">
                            <div class="flex flex-col items-center gap-2 flex-1 group relative h-full cursor-pointer transition hover:scale-105"
                                    @click="openHistory(day)">
                                <!-- Tooltip -->
                                <div class="absolute -top-10 bg-gray-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-10 pointer-events-none">
                                    <span x-text="day.total + ' Ayat'"></span>
                                    <span x-show="day.total > 0" class="block text-[8px] text-gray-400">Klik untuk detail</span>
                                </div>
                                
                                <!-- Bar -->
                                <div class="w-full bg-gray-100 rounded-t-lg relative overflow-hidden flex-1 flex items-end">
                                    <div class="w-full transition-all duration-1000 ease-out rounded-t-lg relative bottom-0"
                                            :style="`height: ${Math.min((day.total / 50) * 100, 100)}%`"
                                            :class="day.total > 0 ? 'bg-[#d4a373]' : 'bg-gray-100'">
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase" x-text="day.day"></span>
                            </div>
                        </template>
                        <!-- Fallback/Loading State if empty -->
                        <template x-if="weeklyProgress.length === 0">
                                <div class="w-full text-center text-gray-400 text-xs py-10">Belum ada data minggu ini</div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Right Column: Surah List -->
            <div class="lg:col-span-2 space-y-6">


                
                <!-- Search Bar -->
                <div class="bg-white rounded-full px-6 py-3 shadow-sm flex items-center gap-3">
                    <i class="fas fa-search text-gray-300 text-lg"></i>
                    <input type="text" x-model="search" placeholder="Search" class="w-full outline-none text-gray-600 placeholder-gray-300">
                </div>

                <!-- Tabs -->
                <div class="bg-[#dce6e9] p-1 rounded-full flex text-center font-semibold text-sm w-full max-w-sm mx-auto md:mx-0">
                    <button @click="activeTab = 'surah'" class="flex-1 py-2 rounded-full transition" :class="activeTab === 'surah' ? 'shadow-sm bg-[#8daeb5] text-white' : 'text-gray-500 hover:bg-white/50'">Surah</button>
                    <button @click="activeTab = 'juz'" class="flex-1 py-2 rounded-full transition" :class="activeTab === 'juz' ? 'shadow-sm bg-[#8daeb5] text-white' : 'text-gray-500 hover:bg-white/50'">Juz</button>
                </div>

                <!-- List -->
                <div class="bg-white rounded-[30px] shadow-sm overflow-hidden">
                    <div class="max-h-[600px] overflow-y-auto custom-scrollbar">
                        
                        <!-- Surah List -->
                        <div x-show="activeTab === 'surah'">
                            <template x-if="loading">
                                <div class="p-8 text-center text-gray-400">Loading Surah...</div>
                            </template>
        
                            <div class="divide-y divide-gray-100">
                                <template x-for="surah in filteredSurahs" :key="surah.nomor">
                                    <div class="p-4 hover:bg-gray-50 transition cursor-pointer flex items-center justify-between group" @click="openSurah(surah.nomor)">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-[#3c9ea8] font-bold relative group-hover:bg-[#3c9ea8] group-hover:text-white transition">
                                                <i class="fas fa-star text-[10px] absolute top-1 right-1 opacity-0 group-hover:opacity-100 text-yellow-300"></i>
                                                <span x-text="surah.nomor"></span>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800 text-sm" x-text="surah.namaLatin"></h4>
                                                <p class="text-xs text-gray-400 uppercase tracking-wider" x-text="surah.arti + ' • ' + surah.jumlahAyat + ' Ayat'"></p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-serif text-lg text-gray-700" x-text="surah.nama"></div>
                                            <!-- Toggle Pin Button -->
                                            <div class="relative z-50 inline-block mt-1">
                                                <button class="text-xs font-bold transition flex items-center gap-1 ml-auto hover:scale-110 p-2 rounded-full" 
                                                        :class="isPinned(surah.nomor) ? 'text-yellow-500 bg-yellow-50' : 'text-gray-300 hover:text-yellow-500'"
                                                        @click.stop.prevent="togglePin(surah)">
                                                    <i class="fas" :class="isPinned(surah.nomor) ? 'fa-star' : 'fa-star'"></i>
                                                    <span x-text="isPinned(surah.nomor) ? 'Tersimpan' : 'Simpan'"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div x-show="activeTab === 'juz'" style="display: none;">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 p-4">
                                <template x-for="juz in juzList" :key="juz.juz">
                                    <button @click="openJuz(juz.juz)" 
                                            class="border border-gray-100 rounded-xl p-4 hover:bg-[#fff8e1] hover:border-[#ffeaa7] transition text-left group">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">JUZ</span>
                                            <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500 group-hover:bg-[#ffeaa7] group-hover:text-[#d4a373] transition" x-text="juz.juz"></div>
                                        </div>
                                        <div class="text-sm font-semibold text-gray-700 group-hover:text-[#d4a373] transition">
                                            Mulai di QS. <span x-text="juz.surahName"></span>
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            Ayat <span x-text="juz.start.ayat"></span>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    <div x-show="openLogModal" class="fixed inset-0 z-[100] flex items-center justify-center px-4" style="display: none;">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="openLogModal = false" x-transition.opacity></div>
        
        <!-- Modal Content -->
        <div class="bg-white rounded-3xl w-full max-w-md p-6 relative z-10 shadow-2xl scale-100" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90">
            
            <h3 class="text-xl font-bold text-gray-800 mb-1">Catat Tilawah</h3>
            <p class="text-sm text-gray-500 mb-6">Simpan progress bacaan quranmu.</p>
            
            <form @submit.prevent="saveLog">
                <div class="space-y-4">
                    <!-- Tanggal -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tanggal</label>
                        <input type="date" x-model="logForm.date" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#3c9ea8] focus:ring-1 focus:ring-[#3c9ea8]">
                    </div>

                    <!-- Dari -->
                    <div class="grid grid-cols-2 gap-3">
                            <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Dari Surat</label>
                            <select x-model="logForm.start_surah" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#3c9ea8]">
                                <template x-for="s in surahs" :key="s.nomor">
                                    <option :value="s.nomor" x-text="s.nomor + '. ' + s.namaLatin"></option>
                                </template>
                            </select>
                            </div>
                            <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ayat <span class="text-[10px] lowercase font-normal">(max: <span x-text="getMaxAyat(logForm.start_surah)"></span>)</span></label>
                            <input type="number" min="1" :max="getMaxAyat(logForm.start_surah)" 
                                    @input="if($el.value > getMaxAyat(logForm.start_surah)) $el.value = getMaxAyat(logForm.start_surah)"
                                    x-model="logForm.start_ayat" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#3c9ea8]">
                            </div>
                    </div>

                    <!-- Sampai (Auto-fill default same as start) -->
                    <div class="grid grid-cols-2 gap-3">
                            <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Sampai Surat</label>
                            <select x-model="logForm.end_surah" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#3c9ea8]">
                                <template x-for="s in surahs" :key="s.nomor">
                                    <option :value="s.nomor" x-text="s.nomor + '. ' + s.namaLatin"></option>
                                </template>
                            </select>
                            </div>
                            <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ayat <span class="text-[10px] lowercase font-normal">(max: <span x-text="getMaxAyat(logForm.end_surah)"></span>)</span></label>
                            <input type="number" min="1" :max="getMaxAyat(logForm.end_surah)" 
                                    @input="if($el.value > getMaxAyat(logForm.end_surah)) $el.value = getMaxAyat(logForm.end_surah)"
                                    x-model="logForm.end_ayat" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#3c9ea8]">
                            </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" @click="openLogModal = false" class="flex-1 py-3 rounded-full bg-gray-100 text-gray-500 font-bold text-sm hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" class="flex-1 py-3 rounded-full bg-[#3c9ea8] text-white font-bold text-sm hover:bg-[#2d858e] transition shadow-lg shadow-[#3c9ea8]/30">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- History Detail Modal -->
    <div x-show="historyModal.open" class="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="display: none;">
        
        <div class="bg-white rounded-[30px] p-8 w-full max-w-md mx-4 relative shadow-2xl" @click.away="historyModal.open = false">
            <h3 class="text-xl font-bold text-gray-800 mb-1">Riwayat Tilawah</h3>
            <p class="text-sm text-gray-500 mb-6" x-text="new Date(historyModal.date).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></p>
            
            <div class="space-y-3 max-h-[60vh] overflow-y-auto custom-scrollbar pr-2">
                <template x-for="log in historyModal.data" :key="log.id">
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#3c9ea8]/10 flex items-center justify-center text-[#3c9ea8] font-bold text-xs shrink-0">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-gray-800 text-sm" x-text="getSurahName(log.start_surah)"></span>
                                <span class="text-xs text-gray-400" x-text="':' + log.start_ayat"></span>
                                <i class="fas fa-arrow-right text-[10px] text-gray-300"></i>
                                <span class="font-bold text-gray-800 text-sm" x-text="getSurahName(log.end_surah)"></span>
                                <span class="text-xs text-gray-400" x-text="':' + log.end_ayat"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="bg-[#3c9ea8] text-white text-[10px] px-2 py-0.5 rounded-full" x-text="log.total_ayat + ' Ayat'"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-8">
                <button type="button" @click="historyModal.open = false" class="w-full py-3 rounded-full bg-gray-100 text-gray-500 font-bold text-sm hover:bg-gray-200 transition">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Audio Player (Hidden/Overlay) -->
    <div x-show="isPlaying" class="fixed bottom-0 left-0 w-full bg-[#1D3557] text-white p-4 z-50 flex items-center justify-between shadow-lg" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="transform translate-y-full"
            x-transition:enter-end="transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="transform translate-y-0"
            x-transition:leave-end="transform translate-y-full"
            style="display: none;">
        
        <div class="flex items-center gap-4">
            <button @click="isPlaying = false; audio.pause()" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
            <div>
                <h4 class="font-bold text-sm" x-text="currentSurahName"></h4>
                <p class="text-xs text-blue-200">Mishary Rashid Al-Afasy</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <button @click="togglePlay()" class="w-10 h-10 bg-white text-[#1D3557] rounded-full flex items-center justify-center">
                <i :class="isPaused ? 'fas fa-play' : 'fas fa-pause'"></i>
            </button>
        </div>
    </div>

    <!-- Toast Notification -->
    <div x-show="notification.show" 
            class="fixed top-24 right-4 z-[200] flex items-center gap-3 px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-300"
            :class="notification.type === 'success' ? 'bg-[#3c9ea8] text-white' : 'bg-red-500 text-white'"
            x-transition:enter="translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="translate-x-full opacity-0"
            style="display: none;">
        
        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
            <i :class="notification.type === 'success' ? 'fas fa-check' : 'fas fa-exclamation'" class="text-sm"></i>
        </div>
        <div>
            <h4 class="font-bold text-sm" x-text="notification.type === 'success' ? 'Berhasil!' : 'Gagal'"></h4>
            <p class="text-xs text-white/90" x-text="notification.message"></p>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
<script>
    function quranApp() {
        return {
            loading: true,
            search: '',
            surahs: [],
            lastRead: null,
            activeTab: 'surah',
            openLogModal: false,
            weeklyProgress: [],
            logForm: {
                date: new Date().toISOString().split('T')[0],
                start_surah: 1,
                start_ayat: 1,
                end_surah: 1,
                end_ayat: 1
            },

            // Audio State
            audio: new Audio(),
            isPlaying: false,
            isPaused: true,
            currentSurahName: '',
            
            // Toast Notification
            notification: {
                show: false,
                message: '',
                type: 'success'
            },

            // History Modal
            historyModal: {
                open: false,
                data: [],
                date: ''
            },

            openHistory(day) {
                if (day.total === 0) return;
                this.historyModal.data = day.logs;
                this.historyModal.date = day.date;
                this.historyModal.open = true;
            },

            showNotification(message, type = 'success') {
                this.notification.message = message;
                this.notification.type = type;
                this.notification.show = true;
                setTimeout(() => {
                    this.notification.show = false;
                }, 3000);
            },

            quickAccess: [
                { name: 'Al-Mulk', number: 67 },
                { name: 'Al-Baqarah', number: 2 },
                { name: 'Al-Kahfi', number: 18 },
                { name: 'Yasin', number: 36 },
                { name: 'An-Nahl', number: 16 },
                { name: 'Al-Maidah', number: 5 }
            ],

            juzList: [
                { juz: 1, start: { surah: 1, ayat: 1 }, surahName: 'Al-Fatihah', name: 'Juz 1' },
                { juz: 2, start: { surah: 2, ayat: 142 }, surahName: 'Al-Baqarah', name: 'Juz 2' },
                { juz: 3, start: { surah: 2, ayat: 253 }, surahName: 'Al-Baqarah', name: 'Juz 3' },
                { juz: 4, start: { surah: 3, ayat: 93 }, surahName: 'Ali Imran', name: 'Juz 4' },
                { juz: 5, start: { surah: 4, ayat: 24 }, surahName: 'An-Nisa', name: 'Juz 5' },
                { juz: 6, start: { surah: 4, ayat: 148 }, surahName: 'An-Nisa', name: 'Juz 6' },
                { juz: 7, start: { surah: 5, ayat: 82 }, surahName: 'Al-Maidah', name: 'Juz 7' },
                { juz: 8, start: { surah: 6, ayat: 111 }, surahName: 'Al-Anam', name: 'Juz 8' },
                { juz: 9, start: { surah: 7, ayat: 88 }, surahName: 'Al-Araf', name: 'Juz 9' },
                { juz: 10, start: { surah: 8, ayat: 41 }, surahName: 'Al-Anfal', name: 'Juz 10' },
                { juz: 11, start: { surah: 9, ayat: 93 }, surahName: 'At-Taubah', name: 'Juz 11' },
                { juz: 12, start: { surah: 11, ayat: 6 }, surahName: 'Hud', name: 'Juz 12' },
                { juz: 13, start: { surah: 12, ayat: 53 }, surahName: 'Yusuf', name: 'Juz 13' },
                { juz: 14, start: { surah: 15, ayat: 1 }, surahName: 'Al-Hijr', name: 'Juz 14' },
                { juz: 15, start: { surah: 17, ayat: 1 }, surahName: 'Al-Isra', name: 'Juz 15' },
                { juz: 16, start: { surah: 18, ayat: 75 }, surahName: 'Al-Kahfi', name: 'Juz 16' },
                { juz: 17, start: { surah: 21, ayat: 1 }, surahName: 'Al-Anbiya', name: 'Juz 17' },
                { juz: 18, start: { surah: 23, ayat: 1 }, surahName: 'Al-Mukminun', name: 'Juz 18' },
                { juz: 19, start: { surah: 25, ayat: 21 }, surahName: 'Al-Furqan', name: 'Juz 19' },
                { juz: 20, start: { surah: 27, ayat: 56 }, surahName: 'An-Naml', name: 'Juz 20' },
                { juz: 21, start: { surah: 29, ayat: 46 }, surahName: 'Al-Ankabut', name: 'Juz 21' },
                { juz: 22, start: { surah: 33, ayat: 31 }, surahName: 'Al-Ahzab', name: 'Juz 22' },
                { juz: 23, start: { surah: 36, ayat: 28 }, surahName: 'Yasin', name: 'Juz 23' },
                { juz: 24, start: { surah: 39, ayat: 32 }, surahName: 'Az-Zumar', name: 'Juz 24' },
                { juz: 25, start: { surah: 41, ayat: 47 }, surahName: 'Fussilat', name: 'Juz 25' },
                { juz: 26, start: { surah: 46, ayat: 1 }, surahName: 'Al-Ahqaf', name: 'Juz 26' },
                { juz: 27, start: { surah: 51, ayat: 31 }, surahName: 'Az-Zariyat', name: 'Juz 27' },
                { juz: 28, start: { surah: 58, ayat: 1 }, surahName: 'Al-Mujadilah', name: 'Juz 28' },
                { juz: 29, start: { surah: 67, ayat: 1 }, surahName: 'Al-Mulk', name: 'Juz 29' },
                { juz: 30, start: { surah: 78, ayat: 1 }, surahName: 'An-Naba', name: 'Juz 30' }
            ],

            async init() {
                this.loadLastRead();
                this.loadQuickAccess();
                await this.fetchSurahs();
                this.fetchWeeklyProgress(); // Fetch Data
                
                // Audio Listener
                this.audio.addEventListener('ended', () => {
                    this.isPaused = true;
                    this.isPlaying = false;
                });
            },

            async fetchWeeklyProgress() {
                try {
                    const res = await fetch('{{ route("quran-log.weekly") }}');
                    if(res.ok) {
                        this.weeklyProgress = await res.json();
                    }
                } catch (e) {
                    console.error('Failed to load progress', e);
                }
            },

            async saveLog() {
                try {
                    const res = await fetch('{{ route("quran-log.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.logForm)
                    });
                    
                    const data = await res.json();
                    
                    if (res.ok) {
                        this.showNotification(`Alhamdulillah! Tercatat ${data.total} ayat.`, 'success');
                        this.openLogModal = false;
                        this.fetchWeeklyProgress(); // Refresh Chart
                    } else {
                        this.showNotification(data.message || 'Gagal menyimpan.', 'error');
                    }
                } catch (e) {
                    this.showNotification('Terjadi kesalahan sistem.', 'error');
                }
            },

            loadLastRead() {
                const saved = localStorage.getItem('lastRead');
                if (saved) {
                    this.lastRead = JSON.parse(saved);
                }
            },

            loadQuickAccess() {
                const saved = localStorage.getItem('quickAccess');
                if (saved) {
                    // Merge default with saved (or just use saved if you prefer full override)
                    // Here we'll just use saved to allow full customization
                    this.quickAccess = JSON.parse(saved);
                } else {
                    // Default set
                    localStorage.setItem('quickAccess', JSON.stringify(this.quickAccess));
                }
            },

            async fetchSurahs() {
                try {
                    const response = await fetch('https://equran.id/api/v2/surat');
                    const data = await response.json();
                    this.surahs = data.data; // API structure: { code: 200, message: "...", data: [...] }
                    this.loading = false;
                } catch (e) {
                    console.error("Error fetching surah:", e);
                    this.loading = false;
                }
            },

            get filteredSurahs() {
                if (this.search === '') return this.surahs;
                return this.surahs.filter(s => {
                    return s.namaLatin.toLowerCase().includes(this.search.toLowerCase()) || 
                            s.arti.toLowerCase().includes(this.search.toLowerCase());
                });
            },

            getMaxAyat(surahNumber) {
                const s = this.surahs.find(x => x.nomor == surahNumber);
                return s ? s.jumlahAyat : 286;
            },

            openSurah(number, ayat = null) {
                let url = `/quran/${number}`;
                if (ayat) {
                    url += `#ayat-${ayat - 1}`;
                }
                window.location.href = url;
            },

            openJuz(number) {
                window.location.href = `/quran/juz/${number}`;
            },

            togglePin(surah) {
                let quickAccess = JSON.parse(localStorage.getItem('quickAccess') || '[]');
                const existsIndex = quickAccess.findIndex(s => s.number === surah.nomor);
                
                if (existsIndex >= 0) {
                    quickAccess.splice(existsIndex, 1);
                    this.showNotification('Surah dihapus dari Akses Cepat', 'success');
                } else {
                    if (quickAccess.length >= 6) {
                        this.showNotification('Akses Cepat penuh. Hapus yang lain dulu.', 'error');
                        return;
                    }
                    quickAccess.push({ name: surah.namaLatin, number: surah.nomor });
                    this.showNotification('Surah ditambahkan ke Akses Cepat', 'success');
                }
                
                localStorage.setItem('quickAccess', JSON.stringify(quickAccess));
                this.quickAccess = quickAccess; // Update reactive state
            },

            isPinned(number) {
                return this.quickAccess.some(s => s.number === number);
            },

            getSurahName(number) {
                if (!this.surahs || this.surahs.length === 0) return 'Loading...';
                const surah = this.surahs.find(s => s.nomor === number);
                return surah ? surah.namaLatin : 'Surah ' + number;
            }
        }
    }
</script>
@endpush
