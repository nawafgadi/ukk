<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Toko Maksum POS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-green: #10b981;
            --primary-green-dark: #059669;
            --primary-green-light: #ecfdf5;
            --primary-green-subtle: #d1fae5;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #e6f4ea 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 40px -10px rgba(16, 185, 129, 0.15), 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid rgba(16, 185, 129, 0.15);
            overflow: hidden;
            width: 100%;
            max-width: 440px;
        }

        .brand-icon-wrapper {
            width: 58px;
            height: 58px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }

        .input-group-text {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: #64748b;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary-green);
            color: var(--primary-green);
        }

        .btn-submit {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.28);
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-1px);
            box-shadow: 0 8px 22px rgba(16, 185, 129, 0.35);
            color: white;
        }

        .demo-chip {
            background: var(--primary-green-light);
            border: 1px solid var(--primary-green-subtle);
            color: var(--primary-green-dark);
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: left;
            flex: 1;
        }

        .demo-chip:hover {
            background: var(--primary-green-subtle);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="p-4 p-sm-5">
            <!-- Header Logo -->
            <div class="text-center mb-4">
                <div class="brand-icon-wrapper mb-3">
                    <i class="bi bi-shop"></i>
                </div>
                <h3 class="fw-bold text-dark mb-1">Toko Maksum</h3>
                <p class="text-muted small">Point of Sale & Manajemen Kasir Modern</p>
            </div>

            <!-- Error Notification -->
            @if (session('error'))
                <div class="alert alert-danger d-flex align-items-center gap-2 rounded-3 py-2 px-3 small mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark small">Email Pengguna</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="nama@tokomaksum.com"
                               value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark small">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" id="password" name="password"
                               class="form-control"
                               placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-submit mb-4">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Sistem
                </button>
            </form>

            <!-- Quick Demo Helper for Evaluators / UKK -->
            <div class="border-top pt-3">
                <small class="text-muted d-block mb-2 fw-semibold text-center" style="font-size: 0.76rem; letter-spacing: 0.5px;">
                    AKSES CEPAT PENGUJIAN (KLIK UNTUK ISI OTOMATIS)
                </small>
                <div class="d-flex gap-2">
                    <div class="demo-chip" onclick="fillDemo('admin@restokita.com', 'password123')">
                        <div class="fw-bold"><i class="bi bi-shield-lock-fill"></i> Akun Admin</div>
                        <div class="text-muted" style="font-size: 0.72rem;">admin@restokita.com</div>
                    </div>
                    <div class="demo-chip" onclick="fillDemo('kasir@restokita.com', 'password123')">
                        <div class="fw-bold"><i class="bi bi-person-badge-fill"></i> Akun Kasir</div>
                        <div class="text-muted" style="font-size: 0.72rem;">kasir@restokita.com</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function fillDemo(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
