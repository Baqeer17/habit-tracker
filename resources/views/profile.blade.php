@extends('layouts.app')

@section('content')
<div class="p-0 font-sans">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-3">Profilku</h2>
        
        {{-- Profile Header Card --}}
        <div class="bg-white dark:bg-[#1e1e1e] backdrop-blur-sm rounded-[25px] p-4 md:p-5 mb-5 shadow-sm flex flex-col md:flex-row items-center gap-5 border border-white dark:border-gray-700 transition-colors">
            <div class="relative w-16 h-16 flex-shrink-0">
                <div class="w-full h-full rounded-full border-4 border-white dark:border-gray-800 shadow-xl overflow-hidden bg-white dark:bg-gray-800 absolute top-0 left-0 transition-colors">
                    @if($user->avatar)
                        <img src="{{ \Illuminate\Support\Str::startsWith($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400">
                            <i class="fa-solid fa-user text-xl"></i>
                        </div>
                    @endif
                </div>
            </div>
            <div class="text-center md:text-left">
                <h1 style="font-size: 20px;" class="font-black text-gray-800 dark:text-gray-100 leading-tight">{{ $user->name }}</h1>
                <p style="font-size: 12px;" class="text-gray-500 dark:text-gray-400 font-bold opacity-70">{{ strtolower(str_replace(' ', '.', $user->name)) }} • {{ $user->email }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            {{-- Badges Section --}}
            <div class="bg-white dark:bg-[#1e1e1e] backdrop-blur-sm rounded-[25px] p-6 shadow-sm border border-white dark:border-gray-700 transition-colors">
                <h3 style="font-size: 16px;" class="font-black text-gray-800 dark:text-gray-100 mb-5 flex items-center">
                    <i class="fa-solid fa-award mr-2 text-teal-600 dark:text-teal-400"></i> Badges
                </h3>
                <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-3 gap-3">
                    @foreach($badges as $badge)
                        <div class="flex flex-col items-center group">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center mb-1 transition-all duration-300 {{ $badge['unlocked'] ? 'bg-[#0F766E] dark:bg-teal-600 shadow-md shadow-teal-700/20' : 'bg-gray-100 dark:bg-gray-700/50' }}" 
                                 title="{{ $badge['name'] }}">
                                <i class="fa-solid {{ $badge['icon'] }} {{ $badge['unlocked'] ? 'text-white' : 'text-gray-400 dark:text-gray-500' }}" style="font-size: 14px;"></i>
                            </div>
                            <span style="font-size: 8px;" class="font-black text-gray-400 dark:text-gray-500 uppercase tracking-tighter text-center line-clamp-1 {{ $badge['unlocked'] ? 'text-teal-700 dark:text-teal-400' : '' }}">
                                {{ $badge['name'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-5">
                {{-- Streak Section --}}
                <div class="bg-white dark:bg-[#1e1e1e] backdrop-blur-sm rounded-[25px] p-6 shadow-sm border border-white dark:border-gray-700 transition-colors">
                    <h3 style="font-size: 16px;" class="font-black text-gray-800 dark:text-gray-100 mb-4">Consistency</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400 shadow-sm flex-shrink-0">
                            <i class="fa-solid fa-fire text-xl"></i>
                        </div>
                        <div>
                            <span style="font-size: 28px;" class="font-black text-gray-900 dark:text-gray-100 leading-none">{{ $streak }}</span>
                            <span style="font-size: 10px;" class="font-black text-gray-400 ml-1 uppercase tracking-wider">Hari Istiqomah</span>
                        </div>
                    </div>
                </div>

                {{-- Bio / Update Section --}}
                <form action="{{ route('profile.update') }}" method="POST" class="bg-white dark:bg-[#1e1e1e] backdrop-blur-sm rounded-[25px] p-6 shadow-sm border border-white dark:border-gray-700 relative transition-colors">
                    @csrf
                    <h3 style="font-size: 16px;" class="font-black text-gray-800 dark:text-gray-100 mb-4">Profil & Bio</h3>
                    <div class="space-y-3">
                        <div>
                            <label style="font-size: 9px;" class="block font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1 opacity-60">Display Name</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-transparent border-b border-gray-100 dark:border-gray-700 focus:border-teal-600 dark:focus:border-teal-400 outline-none pb-1 font-black text-gray-700 dark:text-gray-200 transition-all" style="font-size: 12px;">
                        </div>
                        <div>
                            <label style="font-size: 9px;" class="block font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1 opacity-60">Personal Bio</label>
                            <textarea name="bio" placeholder="Tuliskan perjalanan spiritualmu..." class="w-full bg-transparent border-b border-gray-100 dark:border-gray-700 focus:border-teal-600 dark:focus:border-teal-400 outline-none pb-1 font-bold text-gray-500 dark:text-gray-400 min-h-[60px] resize-none leading-relaxed transition-all" style="font-size: 12px;">{{ $user->bio }}</textarea>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="bg-[#0F766E] dark:bg-teal-600 text-white dark:text-gray-100 px-5 py-2 rounded-lg font-black hover:bg-teal-800 dark:hover:bg-teal-500 transition-all shadow-md shadow-teal-900/10 active:scale-95 uppercase tracking-widest" style="font-size: 10px;">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
