@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FBFC] dark:bg-[#121212] p-4 pb-24 md:p-10 md:pb-10 transition-colors duration-300" 
     x-data="{ 
        activeTab: 'penghasilan',
        pendapatanStr: '', bonusStr: '', hutangStr: '',
        jumlahOrang: 1, hargaBerasStr: '45000',
        
        get pendapatan() { return Number(this.pendapatanStr.replace(/\./g, '')) || 0; },
        get bonus() { return Number(this.bonusStr.replace(/\./g, '')) || 0; },
        get hutang() { return Number(this.hutangStr.replace(/\./g, '')) || 0; },
        get hargaBeras() { return Number(this.hargaBerasStr.replace(/\./g, '')) || 0; },

        get totalPenghasilan() { return this.pendapatan + this.bonus - this.hutang; },
        get wajibZakat() { return this.totalPenghasilan >= 7000000; },
        get zakatPenghasilan() { return this.wajibZakat ? (this.totalPenghasilan * 0.025) : 0; },
        get zakatFitrah() { return Number(this.jumlahOrang) * this.hargaBeras; },
        
        formatInput(val) {
            let num = val.toString().replace(/\D/g, '');
            if (!num) return '';
            return new Intl.NumberFormat('id-ID').format(num);
        },
        formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        },
        terbilang(angka) {
            const huruf = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
            let temp = '';
            let num = Number(angka);
            if (num < 12) {
                temp = ' ' + huruf[num];
            } else if (num < 20) {
                temp = this.terbilang(num - 10) + ' belas';
            } else if (num < 100) {
                temp = this.terbilang(Math.floor(num / 10)) + ' puluh' + this.terbilang(num % 10);
            } else if (num < 200) {
                temp = ' seratus' + this.terbilang(num - 100);
            } else if (num < 1000) {
                temp = this.terbilang(Math.floor(num / 100)) + ' ratus' + this.terbilang(num % 100);
            } else if (num < 2000) {
                temp = ' seribu' + this.terbilang(num - 1000);
            } else if (num < 1000000) {
                temp = this.terbilang(Math.floor(num / 1000)) + ' ribu' + this.terbilang(num % 1000);
            } else if (num < 1000000000) {
                temp = this.terbilang(Math.floor(num / 1000000)) + ' juta' + this.terbilang(num % 1000000);
            } else if (num < 1000000000000) {
                temp = this.terbilang(Math.floor(num / 1000000000)) + ' miliar' + this.terbilang(num % 1000000000);
            } else if (num < 1000000000000000) {
                temp = this.terbilang(Math.floor(num / 1000000000000)) + ' triliun' + this.terbilang(num % 1000000000000);
            }
            return temp;
        },
        getTerbilangText(angka) {
            if (angka === 0) return 'Nol rupiah';
            let result = this.terbilang(Math.floor(angka)).trim() + ' rupiah';
            return result.charAt(0).toUpperCase() + result.slice(1);
        }
     }">

    <header class="bg-[#4D4D4D] dark:bg-black/40 rounded-[30px] p-6 md:p-8 text-white flex justify-between items-center mb-6 md:mb-8 shadow-lg relative overflow-hidden transition-colors duration-300">
        <div class="relative z-10 w-full">
            <h1 class="text-2xl md:text-3xl font-bold mb-2">Kalkulator Zakat</h1>
            <p class="text-xs md:text-sm text-gray-300 dark:text-gray-400 italic">"Ambilah zakat dari sebagian harta mereka, dengan zakat itu kamu membersihkan dan mensucikan mereka."</p>
        </div>
        <i class="fa-solid fa-hand-holding-heart absolute -right-5 -bottom-5 text-8xl text-white/10 dark:text-white/5"></i>
    </header>

    @if (session('success'))
        <div class="mb-4 bg-teal-100 border border-teal-400 text-teal-700 dark:bg-teal-900/40 dark:border-teal-700 dark:text-teal-400 px-4 py-3 rounded-xl relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex flex-wrap gap-2 mb-6 md:mb-8 bg-gray-100 dark:bg-[#1E1E1E] p-1.5 rounded-2xl w-full sm:w-fit transition-colors duration-300">
        <button @click="activeTab = 'penghasilan'" :class="activeTab === 'penghasilan' ? 'bg-[#2D5A43] text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-[#2A2A2A]'" class="flex-1 sm:flex-none px-4 md:px-6 py-2.5 rounded-xl font-medium transition-all text-sm">Zakat Penghasilan</button>
        <button @click="activeTab = 'fitrah'" :class="activeTab === 'fitrah' ? 'bg-[#2D5A43] text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-[#2A2A2A]'" class="flex-1 sm:flex-none px-4 md:px-6 py-2.5 rounded-xl font-medium transition-all text-sm">Zakat Fitrah</button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div x-show="activeTab === 'penghasilan'" class="bg-white dark:bg-[#1A1A1A] p-6 md:p-8 rounded-[40px] shadow-sm border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                <h2 class="text-lg md:text-xl font-bold text-gray-800 dark:text-white mb-6 md:mb-8">Penghasilan Bulanan</h2>
                <div class="space-y-5 md:space-y-6">
                    <div class="group">
                        <label class="block text-xs md:text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Gaji & Tunjangan Rutin</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 font-bold">Rp</span>
                            <input type="text" x-model="pendapatanStr" @input="pendapatanStr = formatInput($event.target.value)" class="w-full pl-12 pr-4 py-3.5 md:py-4 bg-[#F8FBFC] dark:bg-[#222222] dark:text-white border-none rounded-2xl focus:ring-2 focus:ring-[#2D5A43] outline-none transition-all placeholder-gray-300 dark:placeholder-gray-600" placeholder="0">
                        </div>
                    </div>
                    <div class="group">
                        <label class="block text-xs md:text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Bonus, THR, & Lainnya</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 font-bold">Rp</span>
                            <input type="text" x-model="bonusStr" @input="bonusStr = formatInput($event.target.value)" class="w-full pl-12 pr-4 py-3.5 md:py-4 bg-[#F8FBFC] dark:bg-[#222222] dark:text-white border-none rounded-2xl focus:ring-2 focus:ring-[#2D5A43] outline-none transition-all placeholder-gray-300 dark:placeholder-gray-600" placeholder="0">
                        </div>
                    </div>
                    <div class="group">
                        <label class="block text-xs md:text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Hutang / Cicilan (Bulan Ini)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 font-bold">Rp</span>
                            <input type="text" x-model="hutangStr" @input="hutangStr = formatInput($event.target.value)" class="w-full pl-12 pr-4 py-3.5 md:py-4 bg-[#F8FBFC] dark:bg-[#222222] dark:text-white border-none rounded-2xl focus:ring-2 focus:ring-[#2D5A43] outline-none transition-all placeholder-gray-300 dark:placeholder-gray-600" placeholder="0">
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'fitrah'" class="bg-white dark:bg-[#1A1A1A] p-6 md:p-8 rounded-[40px] shadow-sm border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                <h2 class="text-lg md:text-xl font-bold text-gray-800 dark:text-white mb-6 md:mb-8">Zakat Fitrah Keluarga</h2>
                <div class="space-y-5 md:space-y-6">
                    <div>
                        <label class="block text-xs md:text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Jumlah Jiwa</label>
                        <input type="number" x-model="jumlahOrang" min="1" class="w-full px-4 py-3.5 md:py-4 bg-[#F8FBFC] dark:bg-[#222222] dark:text-white border-none rounded-2xl focus:ring-2 focus:ring-[#2D5A43] outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Harga Beras per 2.5kg</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 font-bold">Rp</span>
                            <input type="text" x-model="hargaBerasStr" @input="hargaBerasStr = formatInput($event.target.value)" class="w-full pl-12 pr-4 py-3.5 md:py-4 bg-[#F8FBFC] dark:bg-[#222222] dark:text-white border-none rounded-2xl focus:ring-2 focus:ring-[#2D5A43] outline-none transition-all placeholder-gray-300 dark:placeholder-gray-600" placeholder="45.000">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-[#E6F3F5] dark:bg-[#1A3326]/60 p-6 md:p-8 rounded-[40px] border border-[#2D5A43]/10 dark:border-[#2D5A43]/20 shadow-sm transition-colors duration-300">
                <h3 class="text-[#2D5A43] dark:text-teal-400 font-bold text-base md:text-lg mb-5 md:mb-6">Ringkasan Zakat</h3>
                
                <div class="space-y-4 mb-6 md:mb-8">
                    <div class="flex justify-between text-xs md:text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Jenis Zakat</span>
                        <span class="font-bold text-gray-700 dark:text-white" x-text="activeTab === 'penghasilan' ? 'Zakat Penghasilan' : 'Zakat Fitrah'"></span>
                    </div>
                    <div class="h-[1px] bg-[#2D5A43]/10 dark:bg-black/30"></div>
                    <div>
                        <p class="text-[10px] md:text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Total Wajib Bayar</p>
                        <p class="text-2xl md:text-3xl font-black text-[#2D5A43] dark:text-teal-400" x-text="activeTab === 'penghasilan' ? formatRupiah(zakatPenghasilan) : formatRupiah(zakatFitrah)"></p>
                        <p class="text-[11px] text-[#2D5A43] dark:text-teal-300 font-semibold mt-1 bg-white/50 dark:bg-black/20 px-3 py-1 rounded-full w-fit max-w-full truncate" x-text="activeTab === 'penghasilan' ? getTerbilangText(zakatPenghasilan) : getTerbilangText(zakatFitrah)"></p>
                    </div>
                </div>

                <div x-show="activeTab === 'penghasilan' && !wajibZakat" class="bg-white/60 dark:bg-black/30 p-3 md:p-4 rounded-2xl text-xs text-teal-800 dark:text-teal-300 mb-6 border border-teal-200 dark:border-teal-900 transition-colors duration-300 select-none">
                    <i class="fa-solid fa-circle-info mr-1"></i> Total harta belum mencapai nisab bulanan (Rp 7jt).
                </div>

                <form action="{{ route('zakat.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jenis_zakat" :value="activeTab">
                    <input type="hidden" name="nominal" :value="activeTab === 'penghasilan' ? zakatPenghasilan : zakatFitrah">
                    <button type="submit" class="w-full bg-[#2D5A43] dark:bg-teal-700 text-white py-3.5 md:py-4 rounded-2xl font-bold hover:bg-[#1f4030] dark:hover:bg-teal-600 transition-all shadow-lg shadow-[#2D5A43]/30 dark:shadow-none transform active:scale-95 text-sm md:text-base">
                        Simpan Riwayat Data
                    </button>
                </form>
            </div>

            <div class="bg-white dark:bg-[#1A1A1A] p-6 rounded-[30px] border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">Catatan Terakhir</h4>
                <div class="space-y-2 md:space-y-3">
                    @forelse($histories ?? [] as $history)
                        <div class="flex justify-between items-center text-xs p-2 hover:bg-gray-50 dark:hover:bg-[#222222] rounded-lg transition-colors">
                            <span class="text-gray-500 dark:text-gray-400">{{ $history->created_at->format('d M Y') }}</span>
                            <span class="font-bold text-[#2D5A43] dark:text-teal-400">Rp {{ number_format($history->nominal, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="text-center text-gray-400 dark:text-gray-600 text-xs py-4 italic select-none">Belum ada riwayat.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Tampilan Keutamaan Berzakat (Dalil) -->
    <div class="mt-8 md:mt-10 bg-white dark:bg-[#1A1A1A] border border-[#2D5A43]/10 dark:border-gray-800 rounded-[30px] p-6 lg:p-8 shadow-sm flex flex-col md:flex-row items-center gap-6 relative overflow-hidden transition-colors duration-300">
        <div class="absolute -right-8 -top-8 text-[#2D5A43]/5 dark:text-white/5 text-9xl">
            <i class="fa-solid fa-seedling"></i>
        </div>
        <div class="bg-[#E6F3F5] dark:bg-[#1A3326]/50 text-[#2D5A43] dark:text-teal-400 p-5 rounded-3xl shrink-0 z-10 shadow-inner border border-[#2D5A43]/10 dark:border-teal-800/30">
            <i class="fa-solid fa-quran text-4xl"></i>
        </div>
        <div class="z-10 text-center md:text-left flex-1">
            <h4 class="font-bold text-gray-800 dark:text-white text-base md:text-lg mb-2">Keutamaan Berzakat</h4>
            <p class="text-gray-600 dark:text-gray-400 font-serif italic text-xs md:text-sm leading-relaxed mb-4">
                "Perumpamaan (nafkah yang dikeluarkan oleh) orang-orang yang menafkahkan hartanya di jalan Allah adalah serupa dengan sebutir benih yang menumbuhkan tujuh bulir, pada tiap-tiap bulir seratus biji. Allah melipat gandakan (ganjaran) bagi siapa yang Dia kehendaki. Dan Allah Maha Luas (karunia-Nya) lagi Maha Mengetahui."
            </p>
            <span class="text-[10px] md:text-xs font-bold bg-[#E6F3F5] dark:bg-[#1A3326]/50 text-[#2D5A43] dark:text-teal-400 px-3 py-1.5 rounded-lg border border-[#2D5A43]/20 dark:border-teal-800/40 tracking-wider">— QS. Al-Baqarah: 261</span>
        </div>
    </div>

</div>
@endsection
