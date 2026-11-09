<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Toko Maksum - Modern POS')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-green: #10b981;
            --primary-green-dark: #059669;
            --primary-green-light: #ecfdf5;
            --primary-green-border: #a7f3d0;
            --primary-green-subtle: #d1fae5;
            --dark-slate: #0f172a;
            --muted-slate: #64748b;
            --bg-canvas: #f6f9f6;
            --card-radius: 16px;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-canvas);
            color: #1e293b;
            min-height: 100vh;
        }

        /* Navbar Styling */
        .navbar-maksum {
            background: #ffffff;
            border-bottom: 1px solid rgba(16, 185, 129, 0.15);
            box-shadow: 0 2px 12px rgba(16, 185, 129, 0.05);
        }

        .brand-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 10px;
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        }

        .nav-link-custom {
            color: var(--muted-slate);
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 10px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.92rem;
        }

        .nav-link-custom:hover {
            color: var(--primary-green-dark);
            background: var(--primary-green-light);
        }

        .nav-link-custom.active {
            color: var(--primary-green-dark);
            background: var(--primary-green-subtle);
        }

        /* Custom UI Elements */
        .card-custom {
            background: #ffffff;
            border: 1px solid rgba(16, 185, 129, 0.12);
            border-radius: var(--card-radius);
            box-shadow: 0 4px 20px -4px rgba(16, 185, 129, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-custom-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -4px rgba(16, 185, 129, 0.12);
            border-color: var(--primary-green-border);
        }

        .btn-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 20px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .btn-green:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
            transform: translateY(-1px);
        }

        .btn-green-subtle {
            background: var(--primary-green-light);
            color: var(--primary-green-dark);
            border: 1px solid var(--primary-green-border);
            font-weight: 600;
            border-radius: 10px;
            padding: 8px 16px;
            transition: all 0.2s ease;
        }

        .btn-green-subtle:hover {
            background: var(--primary-green-subtle);
            color: #047857;
            border-color: #6ee7b7;
        }

        .badge-role-admin {
            background: #fef3c7;
            color: #b45309;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
        }

        .badge-role-kasir {
            background: var(--primary-green-light);
            color: var(--primary-green-dark);
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
        }

        /* Modern Tables */
        .table-maksum {
            margin-bottom: 0;
        }

        .table-maksum thead th {
            background: #f8fafc;
            color: var(--muted-slate);
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding: 14px 16px;
        }

        .table-maksum tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.93rem;
        }

        .table-maksum tbody tr:last-child td {
            border-bottom: none;
        }

        .table-maksum tbody tr:hover td {
            background-color: #fbfdfb;
        }
    </style>
    @stack('styles')
</head>
<body>

    @auth
    <!-- Navbar Header -->
    <nav class="navbar navbar-expand-lg navbar-maksum sticky-top py-2 px-3 px-md-4">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ Auth::user()->role === 'admin' ? route('dashboard') : route('kasir.index') }}">
                <div class="brand-badge">
                    <i class="bi bi-shop"></i>
                </div>
                <div>
                    <span class="fw-bold text-dark fs-5 mb-0 d-block lh-1">Toko Maksum</span>
                    <small class="text-muted fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.3px;">POS & INVENTARIS</small>
                </div>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Links -->
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav me-auto ms-lg-4 mb-2 mb-lg-0 gap-1">
                    @if(Auth::user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-grid-fill"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-custom {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                                <i class="bi bi-box-seam-fill"></i> Data Produk
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('kasir.*') ? 'active' : '' }}" href="{{ route('kasir.index') }}">
                            <i class="bi bi-cart-check-fill"></i> Kasir POS
                        </a>
                    </li>
                </ul>

                <!-- User profile & Logout -->
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2 bg-light px-3 py-1 rounded-pill border">
                        <i class="bi bi-person-circle text-success fs-5"></i>
                        <span class="fw-semibold text-dark small">{{ Auth::user()->name }}</span>
                        @if(Auth::user()->role === 'admin')
                            <span class="badge-role-admin">Admin</span>
                        @else
                            <span class="badge-role-kasir">Kasir</span>
                        @endif
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1 fw-semibold">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <footer class="text-center py-4 text-muted small border-top bg-white mt-auto">
        <div class="container">
            <p class="mb-0 fw-medium">
                &copy; {{ date('Y') }} <strong>Toko Maksum</strong> — Sistem Kasir Modern Point of Sale
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
