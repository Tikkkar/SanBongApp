<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Đặt Sân Bóng Đá</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .footer { background-color: #1a1a1a; color: white; padding: 20px 0; margin-top: auto; }
        main { min-height: 80vh; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="bi bi-dribbble"></i> Đặt Sân Bóng Đá
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Trang Chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/pitches') }}">Danh Sách Sân</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/tournaments') }}">Giải Đấu</a></li>
                </ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng Nhập</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-light text-success px-3 ms-2 fw-bold" href="{{ route('register') }}">Đăng Ký</a></li>
                    @else
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item"><a class="nav-link fw-bold text-warning" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ url('/bookings') }}">Lịch Đặt Của Tôi</a></li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item" type="submit">Đăng Xuất</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container my-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto py-3">
        <div class="container text-center">
            <p class="mb-1">© 2026 Hệ thống Quản lý và Đặt Sân Bóng Đá trực tuyến.</p>
            <p class="mb-0"><i class="bi bi-telephone-fill me-2"></i>Hotline: 0336186131</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
