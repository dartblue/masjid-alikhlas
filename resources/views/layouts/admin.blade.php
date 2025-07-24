<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') | Admin Masjid</title>
    <link rel="icon" type="image/x-icon" href="/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        body {
            background-color: #f9fbfd;
        }
        .sidebar {
            width: 240px;
            background: white;
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            padding: 1.5rem 1rem;
            border-right: 1px solid #ddd;
        }
        .sidebar .logo {
            font-weight: bold;
            font-size: 24px;
            color: #08b2e3;
            margin-bottom: 2rem;
            text-align: center;
        }
        .sidebar a {
            display: block;
            padding: 0.75rem 1rem;
            color: #333;
            border-radius: 8px;
            text-decoration: none;
            margin-bottom: 0.5rem;
        }
        .sidebar a.active,
        .sidebar a:hover {
            background: #e0f3ff;
            color: #08b2e3;
        }
        .navbar-top {
            margin-left: 240px;
            background: white;
            padding: 1rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .content-wrapper {
            margin-left: 240px;
            padding: 2rem;
        }
        .nav-link.active {
            background-color: #e0f3ff;
            color: #08b2e3 !important;
            font-weight: 600;
        }
        .nav-link:hover {
            background-color: #e0f3ff;
            color: #08b2e3 !important;
        }
        .nav-item .nav-link {
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }
        .nav-item .nav-link.active {
            background-color: #e0f3ff;
            color: #08b2e3;
            font-weight: 600;
        }
        .submenu {
            display: none;
        }
        .submenu.show {
            display: block;
        }


    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">Masjid Admin</div>

        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class='bx bx-home-alt'></i> Dashboard
        </a>

        @php
            $kasActive = request()->routeIs('kas.penerimaan') || request()->routeIs('kas.pengeluaran') || request()->routeIs('kas.laporan');
        @endphp

        <div class="nav-item">
            <a href="#"
            class="nav-link d-flex justify-content-between align-items-center {{ $kasActive ? 'active' : '' }}"
            onclick="toggleDropdown(this)">
                <span>
                    <i class="bx bx-wallet"></i> Kas Masjid
                </span>
                <i class="bx dropdown-arrow {{ $kasActive ? 'bx-chevron-down' : 'bx-chevron-right' }}"></i>
            </a>

            <div class="submenu ps-3 {{ $kasActive ? 'show' : '' }}">
                <a class="nav-link {{ request()->routeIs('kas.penerimaan') ? 'active' : '' }}" href="{{ route('kas.penerimaan') }}">
                    <i class="bx bx-down-arrow-alt"></i> Penerimaan
                </a>
                <a class="nav-link {{ request()->routeIs('kas.pengeluaran') ? 'active' : '' }}" href="{{ route('kas.pengeluaran') }}">
                    <i class="bx bx-up-arrow-alt"></i> Pengeluaran
                </a>
                <a class="nav-link {{ request()->routeIs('kas.laporan') ? 'active' : '' }}" href="{{ route('kas.laporan') }}">
                    <i class="bx bx-file"></i> Laporan Keuangan
                </a>
            </div>
        </div>


        <a href="{{ route('admin.artikel.index') }}" class="{{ request()->routeIs('admin.artikel.index') ? 'active' : '' }}">
            <i class='bx bx-news'></i> Artikel
        </a>
        <a href="{{ route('admin.kegiatan.index') }}" class="{{ request()->routeIs('admin.kegiatan.index') ? 'active' : '' }}">
            <i class='bx bx-clipboard'></i> Kegiatan
        </a>

        <!-- Logout -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class='bx bx-log-out'></i> Logout
        </a>
    </div>

    <!-- Navbar -->
    <div class="navbar-top">
        <div>👋 Selamat datang, Admin</div>
        <div>
            <input type="text" class="form-control form-control-sm" placeholder="Cari sesuatu...">
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleDropdown(trigger) {
            event.preventDefault();

            const submenu = trigger.nextElementSibling;
            const arrow = trigger.querySelector('.dropdown-arrow');

            const isShown = submenu.classList.contains('show');

            // Tutup semua dulu
            document.querySelectorAll('.submenu').forEach(el => el.classList.remove('show'));
            document.querySelectorAll('.dropdown-arrow').forEach(icon => {
                icon.classList.remove('bx-chevron-down');
                icon.classList.add('bx-chevron-right');
            });

            if (!isShown) {
                submenu.classList.add('show');
                arrow.classList.remove('bx-chevron-right');
                arrow.classList.add('bx-chevron-down');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
