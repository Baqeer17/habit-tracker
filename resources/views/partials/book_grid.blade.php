{{-- resources/views/partials/book_grid.blade.php --}}
@if($allResults->isEmpty())
    <div class="flex flex-col items-center justify-center py-24 bg-white dark:bg-[#1e1e1e] rounded-[40px] shadow-sm border border-dashed border-gray-300 dark:border-gray-700 w-full col-span-full">
        <div class="w-24 h-24 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-6 shadow-inner">
            <i class="fa-solid fa-search text-4xl text-gray-200 dark:text-gray-600"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-800 dark:text-gray-100 mb-2">Pencarian Tidak Akurat</h3>
        <p class="text-gray-400 dark:text-gray-500 text-center max-w-xs px-6 font-medium">Buku tidak ditemukan dengan awalan "{{ $search }}". Pastikan ejaan benar.</p>
    </div>
@else
    @foreach($allResults as $item)
    <div class="book-card bg-white dark:bg-[#1e1e1e] rounded-[35px] overflow-hidden shadow-[0_4px_25px_rgba(0,0,0,0.02)] dark:shadow-[0_4px_25px_rgba(0,0,0,0.2)] flex flex-col group h-full border border-gray-50 dark:border-gray-800 transition-colors">
        {{-- Cover Container --}}
        <div class="relative aspect-[1.3/1] overflow-hidden bg-[#E8EFF1]">
            {{-- Actual Image --}}
            <img src="{{ $item['cover_url'] }}" 
                 alt="{{ $item['title'] }}" 
                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 text-transparent"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            
            {{-- Better Placeholder (Hidden by default, shown if image fails) --}}
            <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center hidden bg-gradient-to-br from-gray-100 to-gray-200">
                <i class="fa-solid fa-book text-5xl text-gray-300 mb-4 scale-125"></i>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-tighter leading-none opacity-50">{{ $item['title'] }}</p>
            </div>

            {{-- Category Tag --}}
            <div class="absolute top-5 left-5 z-20">
                <span class="px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-[0.1em] backdrop-blur-xl shadow-lg border border-white/20
                      {{ $item['category'] === 'Panduan Mualaf' ? 'bg-amber-400/80 text-amber-900 border-amber-300/30' : 'bg-teal-500/80 text-white border-teal-400/30' }}">
                    {{ $item['category'] }}
                </span>
            </div>
            
            {{-- Darken Overlay on Hover --}}
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors pointer-events-none duration-500"></div>
        </div>

        {{-- Book Info --}}
        <div class="px-4 py-4 flex-grow flex flex-col bg-white dark:bg-[#1e1e1e] transition-colors">
            <h4 class="font-black text-gray-900 dark:text-gray-100 text-[15px] leading-tight mb-1 line-clamp-1 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                {{ $item['title'] }}
            </h4>
            <p class="text-[11px] text-gray-400 dark:text-gray-500 font-bold mb-4 flex items-center tracking-tight">
                <i class="fa-solid fa-user-circle mr-1.5 text-[10px] opacity-40"></i>
                {{ $item['author'] }}
            </p>

            {{-- CTA --}}
            <div class="mt-auto">
                <a href="{{ $item['source_url'] }}" target="_blank" 
                   class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-[#1F2937] text-white font-black text-[11px] rounded-xl hover:bg-teal-600 hover:shadow-lg hover:shadow-teal-500/30 transform active:scale-95 transition-all duration-300">
                    BACA SEKARANG
                    <i class="fa-solid fa-arrow-right-long ml-3 text-[9px] opacity-60"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
@endif
