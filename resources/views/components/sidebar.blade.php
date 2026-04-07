<div class="sidebar" id="sidebar">
    <div class="logo">
        <h2>MahabBa</h2>
        <div class="logo-sub">Daily Routine Muslim</div>
    </div>
    <div class="menu">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{ route('prayers.index') }}" class="{{ request()->routeIs('prayers.*') || request()->routeIs('shalat-sunnah.*') ? 'active' : '' }}">
            <i class="fas fa-hands-praying"></i> Salat Wajib
        </a>
        <a href="{{ route('quran.index') }}" class="{{ request()->routeIs('quran.*') ? 'active' : '' }}">
            <i class="fas fa-book-open"></i> Al Qur'an
        </a>
        <a href="{{ route('shalat-sunnah.halaman-sunnah') }}" class="{{ request()->routeIs('shalat-sunnah.*') ? 'active' : '' }}">
           <i class="fas fa-star-and-crescent"></i> Salat Sunnah
        </a>
        <a href="{{ route('dzikir.index') }}" class="{{ request()->routeIs('dzikir.*') ? 'active' : '' }}">
            <i class="fas fa-mosque"></i> Dzikir
        </a>
        <a href="{{ route('hadith.index') }}" class="{{ request()->routeIs('hadith.*') ? 'active' : '' }}">
            <i class="fas fa-book-reader"></i> One Day One Hadis
        </a>
        <a href="{{ route('tracker.index') }}" class="{{ request()->routeIs('tracker.*') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Tracker
        </a>
        <a href="{{ route('zakat.index') }}" class="{{ request()->routeIs('zakat.*') ? 'active' : '' }}">
            <i class="fa-solid fa-scale-balanced"></i> Zakat
        </a>
        <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> Settings
        </a>
        <a href="{{ route('profile.index') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i> Profile
        </a>
        
        <!-- Logout Button (Scrollable) -->
        <div class="logout-btn" style="margin-top: 20px; border-top: 1px solid #bdc3c7; padding-top: 10px;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>
</div>
