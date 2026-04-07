@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        
        <!-- Flash Message for Search Fallback -->
        @if(session('warning'))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded shadow-md" role="alert">
                <p class="font-bold">Info</p>
                <p>{{ session('warning') }}</p>
            </div>
        @endif

        <!-- Header & Smart Search Section -->
        <div class="relative bg-[#2C3E50] rounded-3xl p-8 mb-8 overflow-hidden shadow-2xl text-center">
            <div class="absolute top-0 right-0 p-8 opacity-10 pointer-events-none">
                <i class="fas fa-quran text-9xl text-white transform rotate-12"></i>
            </div>
            
            <h2 class="text-3xl font-bold text-[#d4a373] mb-2 relative z-10" style="font-family: 'Amiri', serif;">
                One Day One Hadith
            </h2>
            <p class="text-gray-300 relative z-10 mb-6">Temukan hikmah harian dan jelajahi hadits pilihan.</p>

            <!-- Search Bar -->
            <form action="{{ route('hadith.index') }}" method="GET" class="relative z-50 max-w-xl mx-auto mb-4" 
                  x-data="{ 
                      loading: false, 
                      focused: false, 
                      suggestions: {{ $userInterests ?? '[]' }},
                      query: '{{ request('topic') }}'
                  }" 
                  @submit="loading = true"
                  @click.away="focused = false">
                <div class="relative">
                    <input type="text" 
                           name="topic" 
                           x-model="query"
                           @focus="focused = true"
                           placeholder="Apa Topik Hadist yang Ingin Anda cari?" 
                           class="w-full px-5 py-3 rounded-full text-gray-800 bg-white/95 border-2 border-[#d4a373] focus:outline-none focus:ring-2 focus:ring-[#d4a373] shadow-lg transition-all"
                           autocomplete="off">
                    
                    <button type="submit" class="absolute right-2 top-1.5 bg-[#d4a373] text-white p-2 rounded-full w-10 h-10 hover:bg-[#c29363] transition-colors shadow-md flex items-center justify-center">
                        <i class="fas fa-search" x-show="!loading"></i>
                        <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>

                    <!-- Autocomplete Dropdown -->
                    <div x-show="focused && suggestions.length > 0" 
                         x-transition.opacity.duration.200ms
                         class="absolute top-14 left-0 w-full bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden text-left z-50">
                        <div class="px-4 py-2 bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Sering Anda Cari
                        </div>
                        <template x-for="item in suggestions" :key="item">
                            <button type="button" 
                                    @click="query = item; $el.closest('form').submit()"
                                    class="w-full text-left px-4 py-3 hover:bg-[#d4a373]/10 hover:text-[#d4a373] transition-colors flex items-center group">
                                <i class="fas fa-history text-gray-400 mr-3 group-hover:text-[#d4a373]"></i>
                                <span class="font-medium text-gray-700 group-hover:text-[#d4a373]" x-text="item.charAt(0).toUpperCase() + item.slice(1)"></span>
                            </button>
                        </template>
                    </div>
                </div>
                <div x-show="loading" class="text-xs text-[#d4a373] mt-2 font-semibold tracking-wide animate-pulse">
                    Sedang mencari di 300+ hadits...
                </div>
            </form>

            <!-- Smart Tags -->
            <div class="relative z-10 flex flex-wrap justify-center gap-2">
                @php
                    $smartTags = ['Sabar', 'Ilmu', 'Sedekah', 'OrangTua', 'Shalat', 'Syukur', 'Niat'];
                @endphp
                @foreach($smartTags as $tag)
                    <a href="{{ route('hadith.index', ['topic' => strtolower($tag)]) }}" 
                       class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200 transform hover:scale-105 shadow-sm
                              {{ strtolower(request('topic')) == strtolower($tag) 
                                 ? 'bg-[#d4a373] text-white ring-2 ring-[#d4a373] ring-offset-2 ring-offset-[#2C3E50]' 
                                 : 'bg-white/10 text-gray-300 hover:bg-white/20' }}">
                        #{{ $tag }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- SEARCH RESULTS -->
        @if(request('topic') && isset($searchResults))
            <h3 class="text-xl font-bold text-gray-700 border-l-4 border-[#d4a373] pl-3 mb-6">
                Hasil Pencarian: "{{ request('topic') }}"
            </h3>

            @if($searchResults->count() > 0)
                <div class="grid grid-cols-1 gap-6">
                    @foreach($searchResults as $item)
                    <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100">
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-3 py-1 bg-[#d4a373]/10 text-[#d4a373] rounded-full text-sm font-semibold">
                                HR. {{ ucfirst($item->narrator) }} No. {{ $item->number }}
                            </span>
                            <a href="{{ route('hadith.index', ['narrator' => $item->narrator, 'number' => $item->number, 'source' => request('topic')]) }}" 
                               class="text-gray-400 hover:text-[#d4a373]">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>

                        <!-- Arabic Preview (Optional) -->
                        @if($item->arabic)
                        <p class="text-right text-xl text-gray-800 mb-4 leading-loose" style="font-family: 'Amiri', serif;">
                            {{ Str::limit($item->arabic, 150) }}
                        </p>
                        @endif

                        <!-- Content Highlighting -->
                        <p class="text-gray-600 italic leading-relaxed">
                            "{!! preg_replace('/('.preg_quote(request('topic'), '/').')/i', '<span class="bg-yellow-200 font-bold">$1</span>', Str::limit($item->content, 300)) !!}"
                        </p>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $searchResults->appends(['topic' => request('topic')])->links() }}
                </div>
            @else
                <div class="p-10 text-center bg-gray-50 rounded-3xl border border-gray-200">
                    <i class="fas fa-search text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Tidak ditemukan hadits dengan kata kunci "<strong>{{ request('topic') }}</strong>".</p>
                    
                    @if(isset($typoSuggestion))
                        <div class="mt-4 p-4 bg-yellow-50 rounded-xl border border-yellow-100 inline-block">
                            <p class="text-gray-600">
                                <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                                Mungkin maksud Anda: 
                                <a href="{{ route('hadith.index', ['topic' => $typoSuggestion]) }}" class="font-bold text-[#d4a373] hover:underline text-lg">
                                    "{{ ucfirst($typoSuggestion) }}"?
                                </a>
                            </p>
                        </div>
                    @else
                        <p class="text-gray-400 text-sm mt-2">Coba kata kunci lain seperti <em>Sabar, Ikhlas, atau Shalat</em>.</p>
                    @endif
                </div>
            @endif

        @else
            <!-- DEFAULT VIEW: MAIN HADITH CARD -->
            @if($hadith)
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border-t-4 border-[#d4a373]">
                <div class="p-8 md:p-12 text-center">
                    <div class="inline-block px-4 py-1 bg-[#d4a373]/10 text-[#d4a373] rounded-full text-sm font-semibold mb-6">
                        <i class="fas fa-star mr-2"></i> {{ $hadith->source ?? 'Hadits Harian' }}
                    </div>
    
                    <h3 class="text-3xl md:text-4xl text-gray-800 leading-loose mb-8 font-bold" 
                        style="font-family: 'Amiri', serif; direction: rtl; line-height: 2.2;">
                        {{ $hadith->arabic }}
                    </h3>
    
                    <div class="w-24 h-1 bg-[#d4a373] mx-auto rounded-full mb-8 opacity-50"></div>
    
                    <p class="text-gray-600 text-lg italic leading-relaxed mb-6 font-light">
                        "{{ $hadith->translation }}"
                    </p>
    
                    <div class="flex flex-col items-center justify-center space-y-2">
                        <p class="text-[#2C3E50] font-bold text-lg">
                            HR. {{ $hadith->narrator }} No. {{ $hadith->number }}
                        </p>
                        <div class="flex space-x-2">
                            @foreach($hadith->tags as $tag)
                                <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="p-10 text-center bg-red-50 text-red-600 rounded-3xl border border-red-200">
                <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                <p>Maaf, layanan Hadits sedang tidak tersedia saat ini. Silakan coba lagi nanti.</p>
            </div>
            @endif
    
            <!-- Recommendations Section -->
            @if(isset($recommendations) && $recommendations->count() > 0)
            <div class="flex justify-between items-center mt-12 mb-6 border-l-4 border-yellow-400 pl-3">
                <h3 class="text-xl font-bold text-gray-700">
                    Rekomendasi Untuk Anda
                </h3>
                <span class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full"><i class="fas fa-sparkles mr-1"></i> Personal</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach($recommendations as $item)
                <div class="bg-gradient-to-br from-yellow-50 to-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-all border border-yellow-100 flex flex-col h-full group hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-yellow-400 opacity-10 rounded-bl-full -mr-4 -mt-4"></div>
                    
                    <div class="mb-4 relative z-10">
                        <span class="px-2 py-1 bg-white text-yellow-600 text-xs rounded-md shadow-sm font-semibold">
                           <i class="fas fa-thumbs-up mr-1"></i> Pilihan Untukmu
                        </span>
                    </div>
                    <p class="text-right text-lg text-gray-800 mb-3 line-clamp-2" style="font-family: 'Amiri', serif;">
                        {{ Str::limit($item->arabic, 100) }}
                    </p>
                    <p class="text-gray-500 text-sm line-clamp-3 mb-4 flex-grow italic">
                        {{ Str::limit($item->content, 150) }}
                    </p>
                    <a href="{{ route('hadith.index', ['narrator' => strtolower($item->narrator), 'number' => $item->number, 'source' => 'rekomendasi']) }}" 
                       class="text-yellow-600 text-sm font-semibold hover:text-yellow-700 transition-colors mt-auto block text-center bg-white/50 py-2 rounded-lg">
                        Baca Hadits <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Library Section -->
            @if(isset($library) && count($library) > 0)
            <div class="flex justify-between items-center mt-12 mb-6 border-l-4 border-[#d4a373] pl-3">
                <h3 class="text-xl font-bold text-gray-700">
                    {{ isset($is_history) && $is_history ? 'Terakhir Dibaca' : 'Pustaka Lainnya' }}
                </h3>
                @if(isset($is_history) && $is_history)
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">Riwayat Anda</span>
                @endif
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($library as $item)
                <a href="{{ route('hadith.index', ['narrator' => strtolower($item->narrator), 'number' => $item->number]) }}" 
                   class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 flex flex-col h-full group hover:-translate-y-1 block">
                    <div class="mb-4">
                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-md group-hover:bg-[#d4a373]/10 group-hover:text-[#d4a373] transition-colors">
                            {{ isset($item->source) ? $item->source : 'HR. ' . $item->narrator . ' No. ' . $item->number }}
                        </span>
                    </div>
                    <p class="text-right text-lg text-gray-800 mb-3 line-clamp-2" style="font-family: 'Amiri', serif;">
                        {{ $item->arabic }}
                    </p>
                    <p class="text-gray-500 text-sm line-clamp-3 mb-4 flex-grow italic">
                        {{ $item->translation }}
                    </p>
                    <div class="text-[#d4a373] text-sm font-semibold opacity-0 group-hover:opacity-100 transition-opacity mt-auto">
                        Baca Selengkapnya <i class="fas fa-arrow-right ml-1"></i>
                    </div>
                </a>
                @endforeach
            </div>
            @endif
        @endif

    </div>
</div>
@endsection
