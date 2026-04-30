<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Muwashi Mandi') — Livestock Marketplace</title>
    <meta name="description" content="@yield('meta_description', 'Buy and sell livestock — cows, goats, buffaloes, bulls, and sheep at the best prices.')">
    @yield('meta')
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #16a34a;
            --primary-dark: #15803d;
            --primary-light: #bbf7d0;
            --secondary: #0d9488;
            --accent: #f59e0b;
            --bg: #f0fdf4;
            --bg-card: #ffffff;
            --text: #1e293b;
            --text-light: #64748b;
            --border: #e2e8f0;
            --danger: #ef4444;
            --success: #22c55e;
            --radius: 12px;
            --shadow: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -4px rgba(0,0,0,0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a { color: var(--primary); text-decoration: none; transition: color 0.2s; }
        a:hover { color: var(--primary-dark); }

        /* ── Navbar ── */
        .navbar {
            background: linear-gradient(135deg, #15803d 0%, #0d9488 100%);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .navbar-brand:hover { color: #fff; }
        .navbar-brand i { color: var(--accent); }
        .nav-links { display: flex; align-items: center; gap: 0.5rem; }
        .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: #fff; }
        .nav-link.active { background: rgba(255,255,255,0.2); color: #fff; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); color: #fff; transform: translateY(-1px); }
        .btn-secondary { background: var(--secondary); color: #fff; }
        .btn-secondary:hover { background: #0f766e; color: #fff; }
        .btn-accent { background: var(--accent); color: #fff; }
        .btn-accent:hover { background: #d97706; color: #fff; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #dc2626; color: #fff; }
        .btn-outline { background: transparent; border: 2px solid var(--primary); color: var(--primary); }
        .btn-outline:hover { background: var(--primary); color: #fff; }
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.8rem; }
        .btn-white { background: #fff; color: var(--primary); }
        .btn-white:hover { background: var(--primary-light); color: var(--primary-dark); }

        /* ── Container ── */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
        main { flex: 1; padding: 2rem 0; }

        /* ── Cards ── */
        .card {
            background: var(--bg-card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
        .card-body { padding: 1.2rem; }

        /* ── Forms ── */
        .form-group { margin-bottom: 1.2rem; }
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: var(--text);
            font-size: 0.9rem;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }
        .form-textarea { resize: vertical; min-height: 100px; }
        .form-error { color: var(--danger); font-size: 0.8rem; margin-top: 0.3rem; }

        /* ── Alerts ── */
        .alert {
            padding: 1rem 1.2rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-info { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }

        /* ── Grid ── */
        .grid-ads {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        /* ── Ad Card ── */
        .ad-card-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #e2e8f0;
        }
        .ad-card-badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-cow { background: #dbeafe; color: #1e40af; }
        .badge-goat { background: #fef3c7; color: #92400e; }
        .badge-buffalo { background: #e0e7ff; color: #3730a3; }
        .badge-bull { background: #fee2e2; color: #991b1b; }
        .badge-sheep { background: #f3e8ff; color: #6b21a8; }
        .badge-other { background: #f1f5f9; color: #475569; }

        .ad-price {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--primary);
        }
        .ad-location { color: var(--text-light); font-size: 0.85rem; }

        /* ── Status Badges ── */
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-payment_pending { background: #fee2e2; color: #991b1b; border: 1px dashed #ef4444; }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-sold { background: #e0e7ff; color: #3730a3; }
        .status-expired { background: #f1f5f9; color: #475569; }

        /* ── Featured & Boosted ── */
        .badge-featured { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #fff; font-weight: 700; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3); }
        .badge-boosted { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; font-weight: 700; }
        .card-featured { border: 2px solid #fbbf24; background: #fffcf0; }

        /* ── Floating Chat Button ── */
        .floating-chat {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 60px;
            height: 60px;
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(21, 128, 61, 0.4);
            text-decoration: none;
            transition: transform 0.2s, background 0.2s;
            z-index: 1000;
        }
        .floating-chat:hover {
            transform: scale(1.1);
            background: #16a34a;
            color: #fff;
        }

        /* ── Pagination ── */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.3rem;
            margin-top: 2rem;
            list-style: none;
        }
        .pagination li a, .pagination li span {
            padding: 0.5rem 0.9rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--text);
            background: #fff;
            border: 1px solid var(--border);
        }
        .pagination li.active span { background: var(--primary); color: #fff; border-color: var(--primary); }
        .pagination li a:hover { background: var(--primary-light); }

        /* ── Footer ── */
        .footer {
            background: #1e293b;
            color: rgba(255,255,255,0.7);
            padding: 2rem;
            text-align: center;
            font-size: 0.9rem;
            margin-top: auto;
        }
        .footer a { color: var(--accent); }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-light);
        }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; color: var(--border); }

        /* ── Responsive ── */
        .hamburger {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .side-drawer {
            position: fixed;
            top: 0;
            left: -300px;
            width: 280px;
            height: 100vh;
            background: #fff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: left 0.3s ease;
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
        }
        .side-drawer.open {
            left: 0;
        }
        .drawer-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .drawer-overlay.open {
            display: block;
            opacity: 1;
        }

        .drawer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }
        .drawer-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-light);
            cursor: pointer;
        }
        .drawer-links {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .drawer-link {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text);
            padding: 0.8rem;
            border-radius: 8px;
            transition: background 0.2s;
        }
        .drawer-link:hover, .drawer-link.active {
            background: var(--bg);
            color: var(--primary);
        }
        .drawer-link i { width: 24px; text-align: center; }

        @media (max-width: 768px) {
            .navbar { padding: 0 1rem; }
            .nav-links { display: none; }
            .hamburger { display: block; }
            .grid-ads { grid-template-columns: repeat(auto-fill, minmax(100%, 1fr)); }
            .card-body { padding: 1rem; }
            .container { padding: 0 1rem; }
            main { padding: 1rem 0; }
            h1 { font-size: 1.3rem !important; }
            h2 { font-size: 1.2rem !important; }
            .form-group { margin-bottom: 1rem; }
            .btn { width: 100%; justify-content: center; }
            .btn-sm { width: auto; }
            .flex-between { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
            .ad-price { font-size: 1.1rem; }
        }

        /* ── Table ── */
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.8rem 1rem; text-align: left; border-bottom: 1px solid var(--border); }
        th { background: #f8fafc; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; color: var(--text-light); }
        tr:hover { background: #f0fdf4; }

        /* ── Misc ── */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .mt-3 { margin-top: 1.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mb-3 { margin-bottom: 1.5rem; }
        .flex { display: flex; }
        .flex-between { display: flex; justify-content: space-between; align-items: center; }
        .gap-1 { gap: 0.5rem; }
        .gap-2 { gap: 1rem; }
    </style>
    @stack('styles')
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar">
        <div style="display:flex; align-items:center; gap:1rem;">
            <button class="hamburger" id="hamburgerBtn">
                <i class="fas fa-bars"></i>
            </button>
            <a href="{{ route('home') }}" class="navbar-brand">
                <img src="{{ asset('favicon.png') }}" alt="Muwashi Mandi Logo" style="height: 28px; width: 28px; border-radius: 4px;"> Muwashi Mandi
            </a>
        </div>
        
        <div class="nav-links">
            <a href="{{ route('ads.index') }}" class="nav-link {{ request()->routeIs('ads.index') ? 'active' : '' }}">
                <i class="fas fa-search"></i> {{ __('Ads') }}
            </a>
            @auth
                <a href="{{ route('chats.index') }}" class="nav-link {{ request()->routeIs('chats.*') ? 'active' : '' }}" style="position:relative;">
                    <i class="fas fa-comments"></i> {{ __('Chats') }}
                    <span id="nav-unread-badge" class="badge-unread" style="display:none; position:absolute; top:2px; right:2px; background:var(--danger); color:white; font-size:0.7rem; padding:1px 5px; border-radius:10px; border:2px solid var(--primary);">0</span>
                </a>
                <a href="{{ route('ads.create') }}" class="nav-link {{ request()->routeIs('ads.create') ? 'active' : '' }}">
                    <i class="fas fa-plus"></i> {{ __('Post Ad') }}
                </a>
                <a href="{{ route('ads.mine') }}" class="nav-link {{ request()->routeIs('ads.mine') ? 'active' : '' }}">
                    <i class="fas fa-list"></i> {{ __('My Ads') }}
                </a>
                <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i> {{ __('Profile') }}
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-link" style="color: var(--accent);">
                        <i class="fas fa-shield-alt"></i> Admin
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="nav-link" style="background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.85);font-family:inherit;font-size:0.9rem;font-weight:500;">
                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-white btn-sm" style="width:auto;">{{ __('Login') }}</a>
                <a href="{{ route('register') }}" class="btn btn-accent btn-sm" style="width:auto;">{{ __('Register') }}</a>
            @endauth

            {{-- Language Switcher --}}
            <div style="margin-left: 1rem; padding-left: 1rem; border-left: 1px solid rgba(255,255,255,0.2); display: flex; gap: 0.5rem;">
                <a href="{{ route('lang.switch', 'en') }}" class="nav-link" style="padding: 0.2rem 0.5rem; font-size: 0.75rem; background: {{ app()->getLocale() == 'en' ? 'rgba(255,255,255,0.2)' : 'transparent' }};">EN</a>
                <a href="{{ route('lang.switch', 'ur') }}" class="nav-link" style="padding: 0.2rem 0.5rem; font-size: 0.75rem; background: {{ app()->getLocale() == 'ur' ? 'rgba(255,255,255,0.2)' : 'transparent' }};">اردو</a>
            </div>
        </div>
    </nav>

    {{-- Mobile Drawer --}}
    <div class="drawer-overlay" id="drawerOverlay"></div>
    <div class="side-drawer" id="sideDrawer">
        <div class="drawer-header">
            <a href="{{ route('home') }}" class="navbar-brand" style="color:var(--primary); font-size:1.3rem;">
                <img src="{{ asset('favicon.png') }}" alt="Muwashi Mandi Logo" style="height: 28px; width: 28px; border-radius: 4px;"> Muwashi Mandi
            </a>
            <button class="drawer-close" id="drawerCloseBtn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="drawer-links">
            <a href="{{ route('ads.index') }}" class="drawer-link {{ request()->routeIs('ads.index') ? 'active' : '' }}">
                <i class="fas fa-search"></i> {{ __('Ads') }}
            </a>
            @auth
                <a href="{{ route('chats.index') }}" class="drawer-link {{ request()->routeIs('chats.*') ? 'active' : '' }}" style="display:flex; justify-content:space-between; align-items:center;">
                    <span><i class="fas fa-comments"></i> {{ __('Chats') }}</span>
                    <span id="drawer-unread-badge" class="status-badge status-rejected" style="display:none; font-size:0.7rem; padding:0.1rem 0.4rem;">0</span>
                </a>
                <a href="{{ route('ads.create') }}" class="drawer-link {{ request()->routeIs('ads.create') ? 'active' : '' }}">
                    <i class="fas fa-plus"></i> {{ __('Post Ad') }}
                </a>
                <a href="{{ route('ads.mine') }}" class="drawer-link {{ request()->routeIs('ads.mine') ? 'active' : '' }}">
                    <i class="fas fa-list"></i> {{ __('My Ads') }}
                </a>
                <a href="{{ route('profile.show') }}" class="drawer-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i> {{ __('Profile') }}
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="drawer-link" style="color: var(--accent);">
                        <i class="fas fa-shield-alt"></i> Admin Dashboard
                    </a>
                @endif
                
                {{-- Mobile Lang Switcher --}}
                <div style="display:flex; gap:0.5rem; margin-top:1rem; padding:0 0.8rem;">
                    <a href="{{ route('lang.switch', 'en') }}" class="btn {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-outline' }} btn-sm" style="flex:1; justify-content:center;">English</a>
                    <a href="{{ route('lang.switch', 'ur') }}" class="btn {{ app()->getLocale() == 'ur' ? 'btn-primary' : 'btn-outline' }} btn-sm" style="flex:1; justify-content:center;">اردو</a>
                </div>

                <form action="{{ route('logout') }}" method="POST" style="margin-top:auto;">
                    @csrf
                    <button type="submit" class="drawer-link" style="width:100%; background:none; border:none; text-align:left; color:var(--danger); cursor:pointer;">
                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                    </button>
                </form>
            @else
                <div style="display:flex; flex-direction:column; gap:1rem; margin-top:1rem;">
                    <a href="{{ route('login') }}" class="btn btn-outline" style="width:100%; justify-content:center;">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="btn btn-primary" style="width:100%; justify-content:center;">{{ __('Register') }}</a>
                </div>
                {{-- Mobile Lang Switcher --}}
                <div style="display:flex; gap:0.5rem; margin-top:1rem;">
                    <a href="{{ route('lang.switch', 'en') }}" class="btn {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-outline' }} btn-sm" style="flex:1; justify-content:center;">English</a>
                    <a href="{{ route('lang.switch', 'ur') }}" class="btn {{ app()->getLocale() == 'ur' ? 'btn-primary' : 'btn-outline' }} btn-sm" style="flex:1; justify-content:center;">اردو</a>
                </div>
            @endauth
        </div>
    </div>

    {{-- Flash Messages --}}
    <main>
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info"><i class="fas fa-info-circle"></i> {{ session('info') }}</div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- Floating Chat Button --}}
    @auth
        @if(!auth()->user()->isAdmin())
            <a href="{{ route('chats.index') }}" class="floating-chat" title="Chat with Support">
                <i class="fas fa-headset"></i>
            </a>
        @endif
    @endauth

    {{-- Footer --}}
    <footer class="footer">
        <p>&copy; {{ date('Y') }} <a href="{{ route('home') }}">Muwashi Mandi</a> — {{ __('Pakistan\'s #1 Livestock Marketplace') ?? 'Pakistan\'s #1 Livestock Marketplace' }}</p>
        <p style="font-size:0.8rem; margin-top:0.5rem; opacity:0.8; max-width:800px; margin-left:auto; margin-right:auto;">
            {{ __('Disclaimer Footer') ?? 'Disclaimer: Muwashi Mandi is a platform connecting buyers and sellers. Never make advance payments without inspecting the animal in person. We are not responsible for any fraud or financial loss.' }}
        </p>
    </footer>

    <script>
        // CSRF token for AJAX
        window.csrfToken = '{{ csrf_token() }}';

        // SweetAlert Delete Confirmation
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('form.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const title = this.dataset.confirmTitle || 'Are you sure?';
                    const text = this.dataset.confirmText || 'You won\'t be able to revert this!';
                    
                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Mobile Drawer Logic
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const drawerCloseBtn = document.getElementById('drawerCloseBtn');
            const sideDrawer = document.getElementById('sideDrawer');
            const drawerOverlay = document.getElementById('drawerOverlay');

            function toggleDrawer() {
                sideDrawer.classList.toggle('open');
                drawerOverlay.classList.toggle('open');
                if (sideDrawer.classList.contains('open')) {
                    document.body.style.overflow = 'hidden'; // Prevent background scrolling
                } else {
                    document.body.style.overflow = '';
                }
            }

            hamburgerBtn.addEventListener('click', toggleDrawer);
            drawerCloseBtn.addEventListener('click', toggleDrawer);
            drawerOverlay.addEventListener('click', toggleDrawer);

            @auth
                // Global Unread Counter Polling
                function updateUnreadCount() {
                    fetch('{{ route('chats.unread') }}', {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        const navBadge = document.getElementById('nav-unread-badge');
                        const drawerBadge = document.getElementById('drawer-unread-badge');
                        
                        if (data.count > 0) {
                            const countText = data.count > 9 ? '9+' : data.count;
                            if (navBadge) {
                                navBadge.textContent = countText;
                                navBadge.style.display = 'block';
                            }
                            if (drawerBadge) {
                                drawerBadge.textContent = data.count;
                                drawerBadge.style.display = 'block';
                            }
                        } else {
                            if (navBadge) navBadge.style.display = 'none';
                            if (drawerBadge) drawerBadge.style.display = 'none';
                        }
                    });
                }
                
                updateUnreadCount();
                setInterval(updateUnreadCount, 15000); // Every 15 seconds
            @endauth
        });
    </script>
    @stack('scripts')
</body>
</html>
