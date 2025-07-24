<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Masjid Al-Ikhlas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="/logo.png">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        body{
            background-color: rgba(245, 245, 245, 1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg px-4">
    <a class="navbar-brand" href="{{ url('/') }}">
        <img src="/logo.png" alt="Logo" height="40" class="me-2">
        MASJID AL-IKHLAS
    </a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
        <li class="{{ Request::is('/') ? 'active' : '' }}"><a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">BERANDA</a></li>
        <li class="{{ Request::is('pengumuman*') ? 'active' : '' }}"><a class="nav-link {{ Request::is('pengumuman*') ? 'active' : '' }}" href="{{ url('/pengumuman') }}">PENGUMUMAN</a></li>
        <li class="{{ Request::is('kegiatan*') ? 'active' : '' }}"><a class="nav-link {{ Request::is('kegiatan*') ? 'active' : '' }}" href="{{ url('/kegiatan') }}">KEGIATAN</a></li>
        <li class="{{ Request::is('artikel*') ? 'active' : '' }}"><a class="nav-link {{ Request::is('artikel*') ? 'active' : '' }}" href="{{ url('/artikel') }}">ARTIKEL</a></li>
        </ul>
    </div>
    </nav>

    <div class="container my-4">
        @yield('content')
    </div>

    <footer class="footer-masjid">
        <div class="container">
            <div class="row text-white">
            <!-- KIRI -->
            <div class="col-md-3 text-center">
                <h5>MASJID AL-IKHLAS</h5>
                <img src="/logo.png" alt="Logo Masjid" class="footer-logo">
                <p>
                Keandra Park Cluster Canna Kota Cirebon<br>
                Email: masjid.alikhlas@gmail.com
                </p>
            </div>

            <div class="col-md-3"></div>

            <!-- KANAN -->
            <div class="col-md-3">
                <h5>KEGIATAN MASJID</h5>
                <ul>
                <li>Pendaftaran Mualaf</li>
                <li>Akad Nikah</li>
                <li>Donor Darah</li>
                </ul>
            </div>

            <!-- KANAN LAIN -->
            <div class="col-md-3">
                <h5>MENU</h5>
                <ul>
                <li>Beranda</li>
                <li>Kegiatan</li>
                <li>Artikel</li>
                </ul>
            </div>
            </div>
        </div>

        <div class="footer-bottom">
            © {{ date('Y') }} - Masjid Al-Ikhlas | Powered by Dartblue
        </div>
    </footer>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
