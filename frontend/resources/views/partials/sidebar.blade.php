{{-- Sidebar Navigation Partial --}}
{{-- Include this on every page: @include('partials.sidebar') --}}

<style>
    /* ===== APP LAYOUT ===== */
    .app-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        min-height: 100vh;
    }

    /* ===== SIDEBAR ===== */
    .app-sidebar {
        background: var(--bg-card, #1A1A1A);
        padding: 1.5rem;
        border-right: 1px solid rgba(255,255,255,0.05);
        display: flex;
        flex-direction: column;
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        z-index: 1000;
    }

    .sidebar-logo {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        font-size: 1.25rem;
        margin-bottom: 2rem;
        text-decoration: none;
        color: var(--text-white, #FFFFFF);
    }

    .sidebar-logo-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #E85A20 0%, #FF8C42 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: white;
        font-size: 1.1rem;
    }

    .sidebar-nav {
        list-style: none;
        flex: 1;
        padding: 0;
        margin: 0;
    }

    .sidebar-nav-item {
        margin-bottom: 0.35rem;
    }

    .sidebar-nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.7rem 1rem;
        border-radius: 10px;
        text-decoration: none;
        color: var(--text-gray, #A0A0A0);
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .sidebar-nav-link:hover {
        background: rgba(232, 90, 32, 0.1);
        color: var(--primary-orange, #E85A20);
    }

    .sidebar-nav-link.active {
        background: var(--primary-orange, #E85A20);
        color: white;
    }

    .sidebar-nav-icon {
        font-size: 1.2rem;
        width: 24px;
        text-align: center;
    }

    .sidebar-user {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: rgba(255,255,255,0.03);
        border-radius: 12px;
        margin-top: auto;
    }

    .sidebar-user-avatar {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, var(--primary-orange, #E85A20), #FF8C42);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        color: white;
        flex-shrink: 0;
    }

    .sidebar-user-info h4 {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-white, #FFFFFF);
        margin: 0;
    }

    .sidebar-user-info p {
        font-size: 0.7rem;
        color: var(--text-gray, #A0A0A0);
        margin: 0;
    }

    /* ===== HAMBURGER BUTTON ===== */
    .sidebar-hamburger {
        display: none;
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 1100;
        width: 44px;
        height: 44px;
        background: var(--bg-card, #1A1A1A);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        cursor: pointer;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 5px;
        padding: 10px;
        transition: all 0.3s ease;
    }

    .sidebar-hamburger:hover {
        background: var(--bg-card-hover, #252525);
        border-color: var(--primary-orange, #E85A20);
    }

    .sidebar-hamburger span {
        display: block;
        width: 22px;
        height: 2px;
        background: var(--text-white, #FFFFFF);
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .sidebar-hamburger.active span:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }
    .sidebar-hamburger.active span:nth-child(2) {
        opacity: 0;
    }
    .sidebar-hamburger.active span:nth-child(3) {
        transform: rotate(-45deg) translate(5px, -5px);
    }

    /* ===== OVERLAY ===== */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 999;
        backdrop-filter: blur(2px);
    }

    .sidebar-overlay.active {
        display: block;
    }

    /* ===== MOBILE TOP BAR (shows page title + hamburger area) ===== */
    .mobile-topbar {
        display: none;
        align-items: center;
        padding: 0.75rem 1rem 0.75rem 4rem;
        background: var(--bg-card, #1A1A1A);
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .mobile-topbar-title {
        font-weight: 600;
        font-size: 1rem;
        color: var(--text-white, #FFFFFF);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .app-layout {
            grid-template-columns: 1fr;
        }

        .app-sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 260px;
            height: 100vh;
            transition: left 0.3s ease;
            box-shadow: none;
        }

        .app-sidebar.open {
            left: 0;
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.5);
        }

        .sidebar-hamburger {
            display: flex;
        }

        .mobile-topbar {
            display: flex;
        }
    }

    @media (max-width: 640px) {
        .app-sidebar {
            width: 100%;
            left: -100%;
        }

        .app-sidebar.open {
            left: 0;
        }
    }
</style>

{{-- Hamburger Button (mobile/tablet) --}}
<button class="sidebar-hamburger" id="sidebarHamburger" aria-label="Toggle menu">
    <span></span>
    <span></span>
    <span></span>
</button>

{{-- Overlay (mobile/tablet) --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- Sidebar --}}
<aside class="app-sidebar" id="appSidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-logo">
        <div class="sidebar-logo-icon">C</div>
        <span>CITRA</span>
    </a>

    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="{{ route('dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="sidebar-nav-icon">🏠</span>
                Dashboard
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('practice') }}" class="sidebar-nav-link {{ request()->routeIs('practice') ? 'active' : '' }}">
                <span class="sidebar-nav-icon">🎭</span>
                Mode Latihan
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('leaderboard') }}" class="sidebar-nav-link {{ request()->routeIs('leaderboard') ? 'active' : '' }}">
                <span class="sidebar-nav-icon">🏆</span>
                Leaderboard
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('tutorial') }}" class="sidebar-nav-link {{ request()->routeIs('tutorial') ? 'active' : '' }}">
                <span class="sidebar-nav-icon">📚</span>
                Tutorial
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('history') }}" class="sidebar-nav-link {{ request()->routeIs('history') ? 'active' : '' }}">
                <span class="sidebar-nav-icon">📜</span>
                Riwayat
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('profile') }}" class="sidebar-nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                <span class="sidebar-nav-icon">👤</span>
                Profil
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('settings') }}" class="sidebar-nav-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                <span class="sidebar-nav-icon">⚙️</span>
                Pengaturan
            </a>
        </li>
    </ul>

    <div class="sidebar-user">
        <div class="sidebar-user-avatar">
            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
        </div>
        <div class="sidebar-user-info">
            <h4>{{ Auth::user()->name ?? 'Pengguna' }}</h4>
            <p>Level: {{ Auth::user()->level ?? 'Pemula' }}</p>
        </div>
    </div>
</aside>

<script>
    // Sidebar toggle for mobile/tablet
    (function() {
        const hamburger = document.getElementById('sidebarHamburger');
        const sidebar = document.getElementById('appSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
            hamburger.classList.add('active');
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            hamburger.classList.remove('active');
        }

        hamburger.addEventListener('click', function() {
            if (sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        overlay.addEventListener('click', closeSidebar);

        // Close sidebar on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSidebar();
        });

        // Close sidebar when clicking a nav link (mobile)
        sidebar.querySelectorAll('.sidebar-nav-link').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 1024) {
                    closeSidebar();
                }
            });
        });
    })();
</script>
