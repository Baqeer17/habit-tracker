@extends('layouts.app')

@push('styles')
<style>
    [x-cloak] { display: none !important; }

    .settings-wrap {
        display: flex;
        height: 100%;
        width: 100%;
        overflow: hidden;
    }

    /* ── LEFT PANEL ── */
    .settings-sidebar {
        width: 280px;
        min-width: 280px;
        height: 100%;
        overflow-y: auto;
        padding: 36px 24px;
        display: flex;
        flex-direction: column;
        gap: 0;
        border-right: 1px solid #e5e7eb;
        background: rgba(255,255,255,0.6);
        backdrop-filter: blur(12px);
        transition: background 0.3s;
    }
    .dark .settings-sidebar { border-right-color: #1f2937; background: rgba(18,18,18,0.7); }
    .settings-sidebar::-webkit-scrollbar { width: 0; }

    .settings-user-card {
        display: flex; align-items: center; gap: 12px;
        padding: 16px; border-radius: 18px; background: #f3f4f6; margin-bottom: 28px;
    }
    .dark .settings-user-card { background: #1e1e1e; }
    .settings-user-card .avatar {
        width: 48px; height: 48px; border-radius: 50%; background: #d1d5db;
        display: flex; align-items: center; justify-content: center; color: #9ca3af;
        overflow: hidden; flex-shrink: 0; border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .dark .settings-user-card .avatar { background: #374151; border-color: #2d3436; }
    .settings-user-card .user-info h3 { font-size: 15px; font-weight: 800; color: #1f2937; line-height: 1.2; }
    .dark .settings-user-card .user-info h3 { color: #f3f4f6; }
    .settings-user-card .user-info p { font-size: 12px; color: #6b7280; font-weight: 500; }
    .dark .settings-user-card .user-info p { color: #9ca3af; }

    .settings-nav-btn {
        display: flex; align-items: center; gap: 14px; width: 100%;
        padding: 13px 16px; border-radius: 14px; margin-bottom: 6px;
        border: none; cursor: pointer; font-family: 'Poppins', sans-serif;
        font-size: 13px; font-weight: 700; letter-spacing: 0.02em; text-align: left;
        transition: all 0.2s ease; color: #6b7280; background: transparent;
    }
    .dark .settings-nav-btn { color: #9ca3af; }
    .settings-nav-btn:hover { background: rgba(15,118,110,0.08); color: #0F766E; }
    .dark .settings-nav-btn:hover { background: rgba(45,212,191,0.08); color: #2dd4bf; }
    .settings-nav-btn.active { background: #0F766E; color: white; box-shadow: 0 4px 15px rgba(15,118,110,0.25); }
    .dark .settings-nav-btn.active { background: #0F766E; }
    .settings-nav-btn i { width: 18px; text-align: center; font-size: 15px; opacity: 0.8; flex-shrink: 0; }

    /* ── RIGHT PANEL ── */
    .settings-main { flex: 1; height: 100%; overflow-y: auto; display: flex; flex-direction: column; }
    .settings-main::-webkit-scrollbar { width: 0; }
    .settings-content { flex: 1; padding: 36px 40px; }

    /* Section card */
    .settings-card {
        background: rgba(255,255,255,0.55); backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.8); border-radius: 24px;
        padding: 32px; margin-bottom: 20px; transition: all 0.3s;
    }
    .dark .settings-card { background: rgba(30,30,30,0.7); border-color: #2d3436; }
    .settings-card-title {
        font-size: 10px; font-weight: 900; letter-spacing: 0.18em;
        text-transform: uppercase; color: #9ca3af; margin-bottom: 24px;
    }
    .dark .settings-card-title { color: #6b7280; }

    /* Form Fields */
    .field-group { margin-bottom: 28px; }
    .field-group:last-child { margin-bottom: 0; }
    .field-label {
        display: block; font-size: 10px; font-weight: 900; letter-spacing: 0.15em;
        text-transform: uppercase; color: #9ca3af; margin-bottom: 10px; transition: color 0.2s;
    }
    .dark .field-label { color: #6b7280; }
    .field-group:focus-within .field-label { color: #0F766E; }
    .dark .field-group:focus-within .field-label { color: #2dd4bf; }
    .field-input {
        width: 100%; background: transparent; border: none;
        border-bottom: 2px solid #e5e7eb; outline: none; padding-bottom: 10px;
        font-family: 'Poppins', sans-serif; font-weight: 700; color: #1f2937;
        transition: border-color 0.2s; font-size: 15px;
    }
    .dark .field-input { color: #f3f4f6; border-bottom-color: #374151; }
    .field-input:focus { border-bottom-color: #0F766E; }
    .dark .field-input:focus { border-bottom-color: #2dd4bf; }
    .field-input::placeholder { color: #d1d5db; font-weight: 600; }
    .dark .field-input::placeholder { color: #4b5563; }
    select.field-input { cursor: pointer; background: transparent; }
    .dark select.field-input option { background: #1e1e1e; color: #f3f4f6; }

    /* Toggle Switch */
    .toggle-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 14px 0; border-bottom: 1px solid #f3f4f6;
    }
    .dark .toggle-row { border-bottom-color: #1f2937; }
    .toggle-row:last-child { border-bottom: none; padding-bottom: 0; }
    .toggle-label { font-size: 14px; font-weight: 700; color: #374151; }
    .dark .toggle-label { color: #d1d5db; }
    .toggle-sublabel { font-size: 11px; color: #9ca3af; font-weight: 500; margin-top: 2px; }
    .toggle-switch { position: relative; display: inline-flex; align-items: center; cursor: pointer; }
    .toggle-switch input { position: absolute; opacity: 0; width: 0; height: 0; }
    .toggle-track { width: 44px; height: 24px; background: #d1d5db; border-radius: 99px; transition: background 0.2s; position: relative; }
    .dark .toggle-track { background: #374151; }
    .toggle-switch input:checked ~ .toggle-track { background: #0F766E; }
    .toggle-thumb { position: absolute; top: 3px; left: 3px; width: 18px; height: 18px; background: white; border-radius: 50%; transition: transform 0.2s; box-shadow: 0 1px 4px rgba(0,0,0,0.2); }
    .toggle-switch input:checked ~ .toggle-track .toggle-thumb { transform: translateX(20px); }

    /* Theme Segmented */
    .theme-control { display: flex; background: #f3f4f6; border-radius: 14px; padding: 4px; gap: 2px; border: 1px solid #e5e7eb; }
    .dark .theme-control { background: #1e1e1e; border-color: #374151; }
    .theme-btn { flex: 1; padding: 9px 12px; border-radius: 10px; border: none; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 11px; font-weight: 800; letter-spacing: 0.05em; color: #9ca3af; background: transparent; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 6px; }
    .dark .theme-btn { color: #6b7280; }
    .theme-btn.active { background: white; color: #0F766E; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    .dark .theme-btn.active { background: #0F766E; color: white; box-shadow: 0 2px 12px rgba(15,118,110,0.3); }

    /* Report period selector */
    .period-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px; margin-bottom: 24px; }
    .period-btn {
        padding: 14px 12px; border-radius: 16px; text-align: center; cursor: pointer;
        border: 2px solid #e5e7eb; background: transparent; font-family: 'Poppins', sans-serif;
        font-size: 12px; font-weight: 700; color: #6b7280;
        transition: all 0.2s; display: flex; flex-direction: column; align-items: center; gap: 6px;
    }
    .dark .period-btn { border-color: #374151; color: #9ca3af; }
    .period-btn:hover { border-color: #0F766E; color: #0F766E; background: rgba(15,118,110,0.05); }
    .period-btn.selected { border-color: #0F766E; background: rgba(15,118,110,0.08); color: #0F766E; }
    .dark .period-btn.selected { background: rgba(45,212,191,0.08); color: #2dd4bf; border-color: #2dd4bf; }
    .period-btn i { font-size: 18px; }

    /* Danger Card */
    .danger-card { background: rgba(254,242,242,0.6); backdrop-filter: blur(12px); border: 1px solid rgba(254,202,202,0.6); border-radius: 24px; padding: 28px 32px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
    .dark .danger-card { background: rgba(127,29,29,0.08); border-color: rgba(127,29,29,0.25); }

    /* Modal */
    .modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 24px; }
    .modal-box { background: white; border-radius: 24px; padding: 36px; width: 100%; max-width: 460px; box-shadow: 0 25px 50px rgba(0,0,0,0.25); transition: all 0.3s; }
    .dark .modal-box { background: #1e1e1e; border: 1px solid #2d3436; }
    .modal-title { font-size: 20px; font-weight: 900; color: #1f2937; margin-bottom: 6px; }
    .dark .modal-title { color: #f3f4f6; }
    .modal-sub { font-size: 13px; color: #6b7280; margin-bottom: 28px; line-height: 1.6; }
    .dark .modal-sub { color: #9ca3af; }

    /* Error bubble */
    .error-msg { display: flex; align-items: center; gap: 8px; padding: 12px 16px; background: #fef2f2; border-left: 4px solid #dc2626; border-radius: 10px; font-size: 12px; font-weight: 700; color: #991b1b; margin-bottom: 16px; }

    /* Notif row */
    .notif-row { display: flex; justify-content: space-between; align-items: center; padding: 14px 16px; border-radius: 12px; background: rgba(243,244,246,0.6); margin-bottom: 8px; }
    .dark .notif-row { background: rgba(31,41,55,0.5); }
    .notif-row:last-child { margin-bottom: 0; }
    .notif-row-label { font-size: 13px; font-weight: 700; color: #374151; }
    .dark .notif-row-label { color: #d1d5db; }
    .notif-time-input { background: transparent; border: none; outline: none; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 800; color: #0F766E; cursor: pointer; }
    .dark .notif-time-input { color: #2dd4bf; }

    /* Avatar picker */
    .avatar-picker { position: relative; display: inline-block; margin-bottom: 8px; }
    .avatar-picker .avatar-img { width: 100px; height: 100px; border-radius: 50%; background: #e5e7eb; display: flex; align-items: center; justify-content: center; color: #9ca3af; overflow: hidden; border: 3px solid white; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .dark .avatar-picker .avatar-img { background: #374151; border-color: #2d3436; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
    .avatar-picker .avatar-badge { position: absolute; bottom: 2px; right: 2px; width: 32px; height: 32px; background: #0F766E; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; border: 2px solid white; cursor: pointer; box-shadow: 0 2px 8px rgba(15,118,110,0.3); transition: all 0.2s; }
    .dark .avatar-picker .avatar-badge { border-color: #121212; }
    .avatar-picker .avatar-badge:hover { transform: scale(1.1); background: #0c5e58; }

    /* Sticky footer */
    .settings-footer { position: sticky; bottom: 0; padding: 16px 40px; border-top: 1px solid #e5e7eb; background: rgba(255,255,255,0.92); backdrop-filter: blur(12px); display: flex; justify-content: flex-end; align-items: center; gap: 12px; z-index: 20; transition: background 0.3s; }
    .dark .settings-footer { border-top-color: #1f2937; background: rgba(18,18,18,0.92); }
    .btn-save { background: #0F766E; color: white; border: none; padding: 12px 32px; border-radius: 14px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 15px rgba(15,118,110,0.3); }
    .btn-save:hover { background: #0c5e58; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(15,118,110,0.35); }

        @media (max-width: 768px) {
            .settings-wrap { flex-direction: column; overflow: auto; }
            .settings-sidebar {
                width: 100%; min-width: 0; height: auto;
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
                padding: 16px;
                flex-direction: column;
                gap: 0;
            }
            .dark .settings-sidebar { border-bottom-color: #1f2937; }
            .settings-user-card { width: 100%; margin-bottom: 16px; }
            /* Nav buttons: 2-column grid on mobile */
            .settings-nav-buttons-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 6px;
            }
            .settings-nav-btn {
                width: 100%;
                margin-bottom: 0;
                padding: 10px 12px;
                font-size: 12px;
                gap: 8px;
            }
            .settings-main { height: auto; }
            .settings-content { padding: 16px; }
            .settings-footer { padding: 12px 16px; }
            .settings-card { padding: 20px 16px; border-radius: 18px; }
            .period-grid { grid-template-columns: repeat(2, 1fr) !important; }
            .theme-control { flex-wrap: wrap; }
            .theme-btn { padding: 8px 6px; font-size: 10px; gap: 4px; }
            /* Settings wrap should fill screen below top-nav */
            .settings-wrap {
                margin: -20px -16px !important;
                height: auto !important;
                min-height: calc(100dvh - 80px);
            }
        }
</style>
@endpush

@section('content')
<div class="settings-wrap -m-[30px] md:-m-[30px]" style="margin: -30px -40px; height: calc(100vh - 0px);"
    x-data="{
        activeTab: '{{ session('activeTab', 'akun') }}',
        theme: localStorage.getItem('theme') || 'auto',
        showPasswordModal: {{ session('open_password_modal') ? 'true' : 'false' }},
        showDeleteModal: false,
        selectedPeriod: 'bulan_ini',
        avatarPreview: null,
        removeAvatar: false,
        setTheme(val) {
            this.theme = val;
            localStorage.setItem('theme', val);
            const isDark = val === 'dark' || (val === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', isDark);
        },
        openReport() {
            window.open('{{ route('settings.report') }}?period=' + this.selectedPeriod, '_blank');
        }
    }">

    {{-- ◉ MODAL UBAH PASSWORD --}}
    <div x-show="showPasswordModal" x-cloak class="modal-backdrop"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         @click.self="showPasswordModal = false">
        <div class="modal-box"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

            <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;">
                <div style="width:44px;height:44px;background:rgba(15,118,110,0.1);color:#0F766E;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:18px;">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div>
                    <h2 class="modal-title" style="margin-bottom:2px;">Ubah Password</h2>
                    <p class="modal-sub" style="margin-bottom:0;">Minimal 8 karakter</p>
                </div>
            </div>

            {{-- Error --}}
            @if($errors->has('current_password'))
            <div class="error-msg">
                <i class="fas fa-circle-exclamation"></i> {{ $errors->first('current_password') }}
            </div>
            @endif
            @if($errors->has('new_password'))
            <div class="error-msg">
                <i class="fas fa-circle-exclamation"></i> {{ $errors->first('new_password') }}
            </div>
            @endif

            <form action="{{ route('settings.change-password') }}" method="POST">
                @csrf
                <div class="field-group">
                    <label class="field-label">Password Lama</label>
                    <input class="field-input" type="password" name="current_password" placeholder="Masukkan password lama" required autofocus>
                </div>
                <div class="field-group">
                    <label class="field-label">Password Baru</label>
                    <input class="field-input" type="password" name="new_password" placeholder="Min. 8 karakter" required>
                </div>
                <div class="field-group" style="margin-bottom:0;">
                    <label class="field-label">Konfirmasi Password Baru</label>
                    <input class="field-input" type="password" name="new_password_confirmation" placeholder="Ketik ulang password baru" required>
                </div>
                <div style="display:flex;gap:10px;margin-top:28px;">
                    <button type="button" @click="showPasswordModal = false"
                        style="flex:1;padding:12px;background:transparent;border:2px solid #e5e7eb;color:#6b7280;border-radius:14px;font-family:'Poppins',sans-serif;font-size:12px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
                        Batal
                    </button>
                    <button type="submit"
                        style="flex:2;padding:12px;background:#0F766E;color:white;border:none;border-radius:14px;font-family:'Poppins',sans-serif;font-size:12px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;box-shadow:0 4px 15px rgba(15,118,110,0.3);">
                        <i class="fas fa-key" style="margin-right:8px;"></i> Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ◉ MODAL HAPUS AKUN --}}
    <div x-show="showDeleteModal" x-cloak class="modal-backdrop"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         @click.self="showDeleteModal = false">
        <div class="modal-box"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

            <div style="width:52px;height:52px;background:#fef2f2;color:#dc2626;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:16px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h2 class="modal-title" style="color:#991b1b;">Hapus Akun Secara Permanen</h2>
            <p class="modal-sub">
                Tindakan ini <strong>tidak dapat dibatalkan</strong>. Semua data ibadah, shalat, tilawah, dzikir, dan preferensi akun akan dihapus <strong>selamanya</strong>.
            </p>
            <p style="font-size:13px;font-weight:700;color:#374151;margin-bottom:12px;" class="dark:text-gray-300">
                Ketik <code style="background:#fef2f2;color:#dc2626;padding:2px 8px;border-radius:6px;font-family:monospace;">HAPUS</code> untuk konfirmasi:
            </p>

            @if($errors->has('confirm_delete'))
            <div class="error-msg">
                <i class="fas fa-circle-exclamation"></i> {{ $errors->first('confirm_delete') }}
            </div>
            @endif

            <form action="{{ route('settings.delete-account') }}" method="POST">
                @csrf
                @method('DELETE')
                <input class="field-input" type="text" name="confirm_delete" placeholder="HAPUS" autocomplete="off"
                       style="border-bottom-color: #fca5a5; margin-bottom: 24px;">
                <div style="display:flex;gap:10px;">
                    <button type="button" @click="showDeleteModal = false"
                        style="flex:1;padding:12px;background:transparent;border:2px solid #e5e7eb;color:#6b7280;border-radius:14px;font-family:'Poppins',sans-serif;font-size:12px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
                        Batal
                    </button>
                    <button type="submit"
                        style="flex:2;padding:12px;background:#dc2626;color:white;border:none;border-radius:14px;font-family:'Poppins',sans-serif;font-size:12px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;box-shadow:0 4px 15px rgba(220,38,38,0.35);">
                        <i class="fas fa-trash" style="margin-right:8px;"></i> Ya, Hapus Akun Saya
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════ LEFT SIDEBAR ═══════════════════ --}}
    <nav class="settings-sidebar">
        <div class="settings-user-card">
            <div class="avatar">
                @if(Auth::user()->avatar)
                    <img src="{{ \Illuminate\Support\Str::startsWith(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <i class="fa-solid fa-user" style="font-size:20px;"></i>
                @endif
            </div>
            <div class="user-info" style="overflow:hidden;">
                <h3 style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->name }}</h3>
                <p style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->email }}</p>
            </div>
        </div>

        {{-- Nav buttons inside a responsive grid wrapper on mobile --}}
        <div class="settings-nav-buttons-grid" style="display:flex;flex-direction:column;gap:0;">
        @php
            $navItems = [
                ['id' => 'akun',          'label' => 'Akun & Profil',   'icon' => 'fa-user-circle'],
                ['id' => 'personalisasi', 'label' => 'Personalisasi',   'icon' => 'fa-palette'],
                ['id' => 'notifikasi',    'label' => 'Notifikasi',      'icon' => 'fa-bell'],
                ['id' => 'privasi',       'label' => 'Data & Privasi',  'icon' => 'fa-shield-halved'],
                ['id' => 'laporan',       'label' => 'Laporan',         'icon' => 'fa-chart-bar'],
            ];
        @endphp
        @foreach($navItems as $item)
            <button class="settings-nav-btn"
                    :class="activeTab === '{{ $item['id'] }}' ? 'active' : ''"
                    @click="activeTab = '{{ $item['id'] }}'">
                <i class="fa-solid {{ $item['icon'] }}"></i>
                <span>{{ $item['label'] }}</span>
                @if($item['id'] === 'laporan')
                    <span style="margin-left:auto;font-size:9px;background:rgba(15,118,110,0.15);color:#0F766E;padding:2px 8px;border-radius:99px;font-weight:900;letter-spacing:0.08em;">PDF</span>
                @endif
            </button>
        @endforeach
        </div>
    </nav>

    {{-- ═══════════════════ MAIN CONTENT ═══════════════════ --}}
    <main class="settings-main">
        <form action="{{ route('settings.update') }}" method="POST" id="settings-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="activeTab" :value="activeTab">

            <div class="settings-content">

                {{-- Flash message --}}
                @if(session('success'))
                <div style="background:rgba(209,250,229,0.8);border-left:4px solid #0F766E;color:#065f46;padding:14px 18px;border-radius:12px;margin-bottom:24px;font-size:13px;font-weight:700;display:flex;align-items:center;gap:10px;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
                @endif

                {{-- ─────────── TAB: AKUN ─────────── --}}
                <div x-show="activeTab === 'akun'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-3"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    {{-- Avatar + Name + Bio --}}
                    <div class="settings-card" style="display:flex;gap:32px;align-items:flex-start;flex-wrap:wrap;">
                        <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
                            <label class="avatar-picker" style="cursor:pointer;">
                                <div class="avatar-img" style="position:relative;">
                                    <img x-show="avatarPreview" :src="avatarPreview" alt="Avatar Preview" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;" x-cloak>
                                    
                                    <div x-show="!avatarPreview" style="display:flex;width:100%;height:100%;align-items:center;justify-content:center;">
                                        @if(Auth::user()->avatar)
                                            <img x-show="!removeAvatar" src="{{ \Illuminate\Support\Str::startsWith(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                                            <i x-show="removeAvatar" class="fa-solid fa-user" style="font-size:36px;" x-cloak></i>
                                        @else
                                            <i class="fa-solid fa-user" style="font-size:36px;"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="avatar-badge" title="Ganti/Ambil Foto Profil">
                                    <i class="fa-solid fa-camera"></i>
                                </div>
                                <input x-ref="avatarFile" type="file" name="avatar" accept="image/*" capture="user" style="display:none;" 
                                    @change="
                                        let file = $event.target.files[0];
                                        if (file) {
                                            if (file.size > 2097152) {
                                                $refs.avatarError.innerHTML = '<i class=\'fa-solid fa-circle-exclamation mr-1\'></i> Gagal! Ukuran <b>' + file.size.toLocaleString() + ' bytes</b>. Maks <b>2MB</b>.';
                                                $refs.avatarError.style.display = 'block';
                                                $event.target.value = '';
                                                avatarPreview = null;
                                                document.getElementById('saveSettingsBtn').disabled = true;
                                                document.getElementById('saveSettingsBtn').style.opacity = '0.5';
                                            } else {
                                                $refs.avatarError.style.display = 'none';
                                                avatarPreview = URL.createObjectURL(file);
                                                removeAvatar = false;
                                                document.getElementById('saveSettingsBtn').disabled = false;
                                                document.getElementById('saveSettingsBtn').style.opacity = '1';
                                            }
                                        }
                                    ">
                            </label>
                            <div style="display:flex; flex-direction:column; align-items:center;">
                                <span style="font-size:10px;font-weight:800;letter-spacing:0.12em;text-transform:uppercase;color:#9ca3af;">Foto Profil (Max 2MB)</span>
                                <div x-ref="avatarError" style="display:none; color:#ef4444; font-size:10px; font-weight:700; margin-top:4px; text-align:center; max-width: 150px; line-height: 1.2;"></div>
                                <div x-show="(('{{ Auth::user()->avatar }}' != '') || avatarPreview) && !removeAvatar" style="margin-top:4px;" x-cloak>
                                    <button type="button" @click.prevent="removeAvatar = true; avatarPreview = null; $refs.avatarFile.value = ''; $refs.avatarError.style.display = 'none'; document.getElementById('saveSettingsBtn').disabled = false; document.getElementById('saveSettingsBtn').style.opacity = '1';" style="font-size:10px; font-weight:700; color:#ef4444; background:rgba(239,68,68,0.1); padding:2px 6px; border-radius:4px; border:none; cursor:pointer;" class="dark:bg-red-900/30 hover:bg-red-100 transition-colors"><i class="fa-solid fa-trash-can" style="margin-right:4px;"></i>Hapus</button>
                                </div>
                            </div>
                            <input type="hidden" name="remove_avatar" :value="removeAvatar ? '1' : '0'">
                        </div>

                        <div style="flex:1;min-width:200px;">
                            <div class="field-group">
                                <label class="field-label">Nama Tampilan</label>
                                <input class="field-input" style="font-size:22px;font-weight:900;" type="text" name="name" value="{{ $user->name }}">
                            </div>
                            <div class="field-group" style="margin-bottom:0;">
                                <label class="field-label">Bio</label>
                                <input class="field-input" type="text" name="bio" value="{{ $user->bio }}" placeholder="Ceritakan sedikit tentang dirimu...">
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Akun --}}
                    <div class="settings-card">
                        <p class="settings-card-title">Informasi Akun</p>
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:24px 40px;">
                            <div class="field-group" style="margin-bottom:0;">
                                <label class="field-label">Email</label>
                                <input class="field-input" type="email" name="email" value="{{ $user->email }}">
                            </div>
                            <div class="field-group" style="margin-bottom:0;">
                                <label class="field-label">Nomor Telepon</label>
                                <input class="field-input" type="tel" name="phone" value="{{ $user->phone }}" placeholder="+62 8xx-xxxx-xxxx">
                            </div>
                            <div class="field-group" style="margin-bottom:0;grid-column:1/-1;">
                                <label class="field-label">Password</label>
                                <div style="display:flex;align-items:center;gap:16px;">
                                    <input class="field-input" type="password" value="••••••••" readonly style="flex:1;color:#9ca3af;letter-spacing:0.1em;cursor:default;">
                                    <button type="button"
                                            @click="showPasswordModal = true"
                                            style="font-size:11px;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:#0F766E;border:none;background:transparent;cursor:pointer;white-space:nowrap;padding-bottom:10px;transition:opacity 0.2s;"
                                            onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                                        <i class="fas fa-pencil" style="margin-right:4px;"></i>Ubah Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─────────── TAB: PERSONALISASI ─────────── --}}
                <div x-show="activeTab === 'personalisasi'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-3"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div class="settings-card">
                        <p class="settings-card-title">Kustomisasi Profil</p>
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px 40px;">
                            <div class="field-group" style="margin-bottom:0;">
                                <label class="field-label">Gender</label>
                                <select name="gender" class="field-input">
                                    <option value="" {{ !$user->gender ? 'selected' : '' }}>Pilih Gender</option>
                                    <option value="Laki-laki" {{ $user->gender === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ $user->gender === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="field-group" style="margin-bottom:0;">
                                <label class="field-label">Lokasi</label>
                                <input class="field-input" type="text" name="location_name" value="{{ $user->location_name }}" placeholder="Jakarta, Indonesia">
                            </div>
                            <div class="field-group" style="margin-bottom:0;">
                                <label class="field-label">Bahasa</label>
                                <select name="language" class="field-input">
                                    <option value="id" {{ ($user->language ?? 'id') === 'id' ? 'selected' : '' }}>🇮🇩 Bahasa Indonesia</option>
                                    <option value="en" {{ ($user->language ?? 'id') === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                                </select>
                            </div>
                        </div>
                        <p style="font-size:11px;color:#9ca3af;font-weight:600;margin-top:16px;padding:10px 14px;background:rgba(243,244,246,0.6);border-radius:10px;" class="dark:bg-gray-800/30">
                            <i class="fas fa-info-circle" style="margin-right:6px;color:#0F766E;"></i>
                            Dukungan bahasa Inggris penuh akan hadir di pembaruan berikutnya.
                        </p>
                    </div>

                    <div class="settings-card">
                        <p class="settings-card-title">Tampilan</p>
                        <div style="margin-bottom:28px;">
                            <p style="font-size:13px;font-weight:800;color:#374151;margin-bottom:14px;" class="dark:text-gray-200">Tema Halaman</p>
                            <div class="theme-control" style="max-width:320px;">
                                <button type="button" class="theme-btn" :class="theme === 'auto' ? 'active' : ''" @click="setTheme('auto')">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i> Otomatis
                                </button>
                                <button type="button" class="theme-btn" :class="theme === 'light' ? 'active' : ''" @click="setTheme('light')">
                                    <i class="fa-solid fa-sun"></i> Terang
                                </button>
                                <button type="button" class="theme-btn" :class="theme === 'dark' ? 'active' : ''" @click="setTheme('dark')">
                                    <i class="fa-solid fa-moon"></i> Gelap
                                </button>
                            </div>
                            <input type="hidden" name="theme" :value="theme">
                        </div>

                        <div class="toggle-row">
                            <div>
                                <p class="toggle-label">Mode Senyap</p>
                                <p class="toggle-sublabel">Nonaktifkan semua suara notifikasi</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="silent_mode" value="1" {{ $user->silent_mode ? 'checked' : '' }}>
                                <div class="toggle-track"><div class="toggle-thumb"></div></div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- ─────────── TAB: NOTIFIKASI ─────────── --}}
                <div x-show="activeTab === 'notifikasi'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-3"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;">

                        {{-- Notifikasi Azan --}}
                        <div class="settings-card" style="margin-bottom:0;">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                                <div style="width:36px;height:36px;background:rgba(15,118,110,0.1);color:#0F766E;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;">
                                    <i class="fa-solid fa-mosque"></i>
                                </div>
                                <h3 style="font-size:15px;font-weight:800;color:#1f2937;" class="dark:text-gray-100">Notifikasi Waktu Sholat</h3>
                            </div>
                            <div class="toggle-row">
                                <div>
                                    <span class="toggle-label">Notifikasi Azan</span>
                                    <p class="toggle-sublabel">Pengingat visual saat waktu sholat tiba</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="azan_notification" value="1" {{ $user->azan_notification ? 'checked' : '' }}>
                                    <div class="toggle-track"><div class="toggle-thumb"></div></div>
                                </label>
                            </div>
                            <div class="toggle-row">
                                <div>
                                    <span class="toggle-label">Mode Senyap</span>
                                    <p class="toggle-sublabel">Diatur di tab Personalisasi</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" {{ $user->silent_mode ? 'checked' : '' }} disabled>
                                    <div class="toggle-track" style="opacity:0.5;"><div class="toggle-thumb"></div></div>
                                </label>
                            </div>
                        </div>

                        {{-- Daily Reminder --}}
                        <div class="settings-card" style="margin-bottom:0;">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                                <div style="width:36px;height:36px;background:rgba(245,158,11,0.1);color:#d97706;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;">
                                    <i class="fa-solid fa-clock"></i>
                                </div>
                                <div>
                                    <h3 style="font-size:15px;font-weight:800;color:#1f2937;" class="dark:text-gray-100">Daily Reminder</h3>
                                    <p style="font-size:11px;color:#9ca3af;font-weight:500;">Jam pengingat ibadah harian</p>
                                </div>
                            </div>
                            <div class="notif-row">
                                <div>
                                    <span class="notif-row-label">🌙 Tahajud</span>
                                </div>
                                <input class="notif-time-input" type="time" name="tahajud_time" value="{{ $user->tahajud_time ?? '03:30' }}">
                            </div>
                            <div class="notif-row">
                                <div>
                                    <span class="notif-row-label">☀️ Dhuha</span>
                                </div>
                                <input class="notif-time-input" type="time" name="duha_time" value="{{ $user->duha_time ?? '08:00' }}">
                            </div>
                            <div class="notif-row">
                                <div>
                                    <span class="notif-row-label">📖 Tilawah</span>
                                </div>
                                <input class="notif-time-input" type="time" name="tilawah_time" value="{{ $user->tilawah_time ?? '18:30' }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─────────── TAB: DATA & PRIVASI ─────────── --}}
                <div x-show="activeTab === 'privasi'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-3"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;margin-bottom:20px;">

                        {{-- Backup Data --}}
                        <div class="settings-card" style="margin-bottom:0;display:flex;flex-direction:column;">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                                <div style="width:42px;height:42px;background:linear-gradient(135deg,rgba(15,118,110,0.15),rgba(13,148,136,0.1));color:#0F766E;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;border:1px solid rgba(15,118,110,0.2);">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <div>
                                    <h3 style="font-size:14px;font-weight:800;color:#1f2937;" class="dark:text-gray-100">Backup & Ekspor CSV</h3>
                                    <p style="font-size:10px;color:#0F766E;font-weight:700;margin-top:1px;">Data tersimpan aman</p>
                                </div>
                            </div>
                            <p style="font-size:12px;font-weight:600;color:#9ca3af;line-height:1.6;margin-bottom:20px;flex:1;">
                                Unduh semua data ibadah (shalat, tilawah, dzikir) dalam format CSV yang bisa dibuka di Excel.
                            </p>
                            <a href="{{ route('settings.export-csv') }}"
                               style="width:100%;padding:11px;background:#0F766E;color:white;border:none;border-radius:12px;font-family:'Poppins',sans-serif;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;transition:all 0.2s;box-shadow:0 4px 12px rgba(15,118,110,0.25);display:block;text-align:center;text-decoration:none;"
                               onmouseover="this.style.background='#0c5e58'" onmouseout="this.style.background='#0F766E'">
                                <i class="fas fa-download" style="margin-right:6px;"></i> Download Data CSV
                            </a>
                            <p style="font-size:10px;text-align:center;margin-top:10px;color:#9ca3af;font-weight:700;letter-spacing:0.05em;">
                                Termasuk data shalat, tilawah & dzikir
                            </p>
                        </div>

                        {{-- Keamanan --}}
                        <div class="settings-card" style="margin-bottom:0;">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                                <div style="width:42px;height:42px;background:linear-gradient(135deg,rgba(99,102,241,0.15),rgba(139,92,246,0.1));color:#6366f1;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;border:1px solid rgba(99,102,241,0.2);">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <div>
                                    <h3 style="font-size:14px;font-weight:800;color:#1f2937;" class="dark:text-gray-100">Keamanan Akun</h3>
                                    <p style="font-size:10px;color:#6366f1;font-weight:700;margin-top:1px;">Proteksi berlapis</p>
                                </div>
                            </div>
                            <div class="toggle-row">
                                <div>
                                    <p class="toggle-label">Password Aktif</p>
                                    <p class="toggle-sublabel">Akun terlindungi dengan password</p>
                                </div>
                                <span style="font-size:11px;background:rgba(15,118,110,0.1);color:#0F766E;padding:4px 12px;border-radius:99px;font-weight:800;">Aktif</span>
                            </div>
                            <div style="margin-top:16px;">
                                <button type="button" @click="showPasswordModal = true; activeTab = 'akun'"
                                    style="width:100%;padding:11px;background:transparent;border:2px solid #0F766E;color:#0F766E;border-radius:12px;font-family:'Poppins',sans-serif;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;transition:all 0.2s;"
                                    onmouseover="this.style.background='rgba(15,118,110,0.05)'" onmouseout="this.style.background='transparent'">
                                    <i class="fas fa-key" style="margin-right:6px;"></i> Ganti Password
                                </button>
                            </div>
                        </div>

                        {{-- Laporan PDF --}}
                        <div class="settings-card" style="margin-bottom:0;display:flex;flex-direction:column;">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                                <div style="width:42px;height:42px;background:linear-gradient(135deg,rgba(245,158,11,0.15),rgba(217,119,6,0.1));color:#d97706;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;border:1px solid rgba(245,158,11,0.2);">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <div>
                                    <h3 style="font-size:14px;font-weight:800;color:#1f2937;" class="dark:text-gray-100">Laporan PDF</h3>
                                    <p style="font-size:10px;color:#d97706;font-weight:700;margin-top:1px;">Ringkasan ibadah</p>
                                </div>
                            </div>
                            <p style="font-size:12px;font-weight:600;color:#9ca3af;line-height:1.6;margin-bottom:20px;flex:1;">
                                Buat laporan ringkasan ibadah dalam format PDF siap cetak. Pilih periode di tab Laporan.
                            </p>
                            <button type="button" @click="activeTab = 'laporan'"
                                style="width:100%;padding:11px;background:transparent;border:2px solid #e5e7eb;color:#6b7280;border-radius:12px;font-family:'Poppins',sans-serif;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;transition:all 0.2s;"
                                onmouseover="this.style.borderColor='#d97706';this.style.color='#d97706'" onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#6b7280'">
                                <i class="fas fa-arrow-right" style="margin-right:6px;"></i> Buat Laporan
                            </button>
                        </div>
                    </div>

                    {{-- Danger Zone --}}
                    <div class="danger-card">
                        <div>
                            <p style="font-size:14px;font-weight:800;color:#991b1b;margin-bottom:4px;">Hapus Akun</p>
                            <p style="font-size:12px;font-weight:600;color:#b91c1c;opacity:0.7;line-height:1.5;">
                                Tindakan ini tidak dapat dibatalkan. Semua data akan hilang secara <b>permanen</b>.
                            </p>
                        </div>
                        <button type="button" @click="showDeleteModal = true"
                            style="padding:11px 24px;background:#dc2626;color:white;border:none;border-radius:12px;font-family:'Poppins',sans-serif;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;box-shadow:0 4px 12px rgba(220,38,38,0.3);white-space:nowrap;transition:all 0.2s;flex-shrink:0;">
                            <i class="fas fa-trash" style="margin-right:6px;"></i> Hapus Akun
                        </button>
                    </div>
                </div>

                {{-- ─────────── TAB: LAPORAN ─────────── --}}
                <div x-show="activeTab === 'laporan'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-3"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    {{-- Intro --}}
                    <div class="settings-card" style="background:linear-gradient(135deg,rgba(15,118,110,0.08),rgba(13,148,136,0.06));border-color:rgba(15,118,110,0.2);margin-bottom:20px;">
                        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                            <div style="width:52px;height:52px;background:#0F766E;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:22px;color:white;flex-shrink:0;box-shadow:0 4px 15px rgba(15,118,110,0.3);">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div style="flex:1;">
                                <h2 style="font-size:18px;font-weight:900;color:#1f2937;margin-bottom:4px;" class="dark:text-gray-100">Laporan Ibadah PDF</h2>
                                <p style="font-size:13px;color:#6b7280;font-weight:600;line-height:1.5;">
                                    Buat laporan komprehensif perjalanan ibadahmu — Shalat, Tilawah, Dzikir, dan Streak. Buka di tab baru lalu simpan sebagai PDF menggunakan browser.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Pilih Periode --}}
                    <div class="settings-card">
                        <p class="settings-card-title">Pilih Rentang Waktu Laporan</p>

                        <div class="period-grid">
                            @php
                                $periods = [
                                    'hari_ini'  => ['Hari Ini',       'fa-sun',          '#f59e0b'],
                                    'bulan_ini' => ['Bulan Ini',      'fa-calendar',     '#0F766E'],
                                    '3_bulan'   => ['3 Bulan',        'fa-calendar-days','#6366f1'],
                                    '6_bulan'   => ['6 Bulan',        'fa-calendar-days','#8b5cf6'],
                                    'tahun_ini' => ['Tahun Ini',      'fa-calendar-week','#0ea5e9'],
                                    '3_tahun'   => ['3 Tahun',        'fa-layer-group',  '#ec4899'],
                                    '5_tahun'   => ['5 Tahun',        'fa-layer-group',  '#f97316'],
                                    'semua'     => ['Semua Data',     'fa-infinity',     '#dc2626'],
                                ];
                            @endphp

                            @foreach($periods as $key => [$label, $icon, $color])
                            <button type="button"
                                    class="period-btn"
                                    :class="selectedPeriod === '{{ $key }}' ? 'selected' : ''"
                                    @click="selectedPeriod = '{{ $key }}'">
                                <i class="fas {{ $icon }}" style="color: {{ $color }};"></i>
                                <span>{{ $label }}</span>
                            </button>
                            @endforeach
                        </div>

                        {{-- Preview info --}}
                        <div style="padding:16px;background:rgba(243,244,246,0.6);border-radius:14px;margin-bottom:24px;" class="dark:bg-gray-800/30">
                            <p style="font-size:12px;font-weight:600;color:#6b7280;" class="dark:text-gray-400">
                                <i class="fas fa-info-circle" style="color:#0F766E;margin-right:6px;"></i>
                                Laporan akan mencakup: <strong>Shalat Wajib & Sunnah</strong>, <strong>Tilawah Al-Quran</strong>, <strong>Dzikir</strong>, dan <strong>Streak Ibadah</strong> pada periode yang dipilih.
                            </p>
                        </div>

                        {{-- Konten yang dimasukkan --}}
                        <p class="settings-card-title" style="margin-bottom:16px;">Konten Laporan</p>
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;margin-bottom:24px;">
                            @foreach([
                                ['fa-mosque','Shalat Wajib','Subuh, Dzuhur, Asar, Maghrib, Isya'],
                                ['fa-moon','Shalat Sunnah','Tahajud, Dhuha, Witir'],
                                ['fa-book-open','Tilawah Al-Quran','Log harian & statistik'],
                                ['fa-hand-holding-heart','Dzikir','Pagi, Petang, Setelah Shalat'],
                                ['fa-fire','Streak & Konsistensi','Hari aktif ibadah'],
                            ] as [$ic, $title, $desc])
                            <div style="padding:14px;background:rgba(255,255,255,0.6);border:1px solid #e5e7eb;border-radius:14px;display:flex;gap:10px;align-items:flex-start;" class="dark:bg-gray-800/30 dark:border-gray-700">
                                <i class="fas {{ $ic }}" style="color:#0F766E;margin-top:2px;width:16px;flex-shrink:0;"></i>
                                <div>
                                    <div style="font-size:12px;font-weight:800;color:#1f2937;" class="dark:text-gray-200">{{ $title }}</div>
                                    <div style="font-size:11px;color:#9ca3af;font-weight:500;">{{ $desc }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Tombol Generate --}}
                        <button type="button" @click="openReport()"
                            style="width:100%;padding:16px;background:#0F766E;color:white;border:none;border-radius:16px;font-family:'Poppins',sans-serif;font-size:14px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;transition:all 0.2s;box-shadow:0 6px 20px rgba(15,118,110,0.35);display:flex;align-items:center;justify-content:center;gap:10px;"
                            onmouseover="this.style.background='#0c5e58';this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.background='#0F766E';this.style.transform='translateY(0)'">
                            <i class="fas fa-file-pdf"></i>
                            Buat & Buka Laporan PDF
                            <i class="fas fa-external-link-alt" style="font-size:11px;opacity:0.7;"></i>
                        </button>
                        <p style="text-align:center;font-size:11px;color:#9ca3af;margin-top:10px;font-weight:600;">
                            Akan terbuka di tab baru · Gunakan Ctrl+P untuk menyimpan sebagai PDF
                        </p>
                    </div>

                </div>

            </div>{{-- .settings-content --}}

            {{-- ── STICKY FOOTER (hanya untuk form tab akun, personalisasi, notifikasi) ── --}}
            <div class="settings-footer" x-show="activeTab !== 'laporan'">
                <span style="font-size:12px;font-weight:600;color:#9ca3af;"
                      x-text="'Tab aktif: ' + {'akun':'Akun & Profil','personalisasi':'Personalisasi','notifikasi':'Notifikasi','privasi':'Data & Privasi','laporan':'Laporan'}[activeTab]">
                </span>
                <button type="submit" id="saveSettingsBtn" class="btn-save" x-show="activeTab !== 'privasi' && activeTab !== 'laporan'">
                    <i class="fa-solid fa-floppy-disk" style="margin-right:8px;"></i>
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </main>
</div>
@endsection
