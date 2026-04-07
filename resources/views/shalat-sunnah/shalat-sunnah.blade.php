@extends('layouts.app')

@section('content')
    <style>
        /* Header Pill Style (Copied from Dzikir) */
        .ss-header {
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

        /* Card Grid Style */
        .ss-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            padding-bottom: 50px;
        }

        .ss-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-height: 250px;
            transition: transform 0.2s, box-shadow 0.2s, background 0.3s;
            border: 1px solid #e5e7eb;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }
        .dark .ss-card {
            background: #1e1e1e;
            border-color: #2d3436;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .ss-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: #d4a373;
        }
        .dark .ss-card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
            border-color: #d4a373;
        }

        .ss-card-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 24px;
            font-style: italic;
            color: #2d3436;
            margin-bottom: 10px;
            transition: color 0.2s;
        }
        .dark .ss-card-title {
            color: #f3f4f6;
        }
        
        .ss-card:hover .ss-card-title {
            color: #d4a373;
        }
        .dark .ss-card:hover .ss-card-title {
            color: #d4a373;
        }

        .ss-card-subtitle {
            font-size: 14px;
            color: #636e72;
            font-weight: 300;
            transition: color 0.2s;
        }
        .dark .ss-card-subtitle {
            color: #9ca3af;
        }
    </style>

    <div class="py-6 min-h-screen" style="background: transparent;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header (Dzikir Style) -->
            <div class="ss-header">
                <div>
                    <h1 class="text-xl font-bold tracking-wide">Halaman Salat Sunnah</h1>
                    <nav class="flex items-center text-xs text-gray-300 gap-2 font-medium mt-1">
                        <a href="{{ route('dashboard') }}" class="hover:text-white transition-colors flex items-center gap-1">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                        <span class="text-gray-400">/</span>
                        <span class="text-[#d4a373] font-semibold">Salat Sunnah</span>
                    </nav>
                </div>
                <div class="text-[#d4a373] font-serif italic opacity-80 select-none">MahabBa</div>
            </div>

            <!-- Grid Content -->
            <div class="ss-grid">
                
                <a href="{{ route('shalat-sunnah.ghairu-muakkad') }}" class="ss-card group">
                    <div class="absolute inset-0 bg-[#d4a373] opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                    
                    <h2 class="ss-card-title transition-colors">Sunnah Ghairu Muakkad</h2>
                    <p class="ss-card-subtitle group-hover:text-gray-700 transition-colors">Dhuha, Tahajud, Witir...</p>
                    
                    <div class="mt-6 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-4 group-hover:translate-y-0 text-[#d4a373]">
                        <i class="fas fa-arrow-right text-xl"></i>
                    </div>
                </a>

                <a href="{{ route('shalat-sunnah.rawatib') }}" class="ss-card group">
                    <div class="absolute inset-0 bg-[#d4a373] opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                    
                    <h2 class="ss-card-title transition-colors">Sunnah Rawatib</h2>
                    <p class="ss-card-subtitle group-hover:text-gray-700">Salat sunnah sebelum & sesudah salat wajib</p>

                    <div class="mt-6 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-4 group-hover:translate-y-0 text-[#d4a373]">
                        <i class="fas fa-arrow-right text-xl"></i>
                    </div>
                </a>

            </div>
            
        </div>
    </div>
@endsection