<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petugas - Sistem Informasi Perpustakaan</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0d6efd 0%, #0a4296 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
            width: 100%;
            max-width: 440px;
        }
        .login-header {
            background-color: #ffffff;
            padding: 2.2rem 2rem 1.5rem;
            text-align: center;
        }
        .icon-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #e7f1ff;
            color: #0d6efd;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }
        .btn-login {
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container p-3">
    <div class="card login-card mx-auto">
        <div class="login-header">
            <div class="icon-circle">
                <i class="bi bi-book-half"></i>
            </div>
            <h4 class="fw-bold mb-1 text-dark">Perpustakaan Sekolah</h4>
            <p class="text-muted small mb-0">Silakan login sebagai Petugas / Admin</p>
        </div>

        <div class="card-body p-4 pt-2 bg-white">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show small" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', 'admin@perpus.com') }}" 
                               placeholder="nama@sekolah.sch.id" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label small fw-semibold">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-login shadow-sm mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                </button>
            </form>

            <div class="bg-light p-3 rounded-3 text-center border">
                <p class="small text-muted mb-1"><strong>Default Akun Demo Petugas:</strong></p>
                <p class="small mb-0 font-monospace text-primary">Email: <b>admin@perpus.com</b> | Pass: <b>admin123</b></p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
